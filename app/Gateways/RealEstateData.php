<?php

namespace App\Gateways;

use App\Models\Answer;
use App\Models\Location;
use App\Models\Question;
use App\Models\Respondent;
use DateTime;
use Illuminate\Support\Facades\Auth;

class RealEstateData {
    /**
     * 
     * Returns the data of respondents with the question answers data
     * 
     * @param int $survey_id    survey id
     * @param array $filter_ary    filters for cust variable in respondents
     * @param string $search_key    search string
     * @param array $filter_taxonomy    filter for question data
     * @return array 
     */
    public function get_resp_data($survey_id, $filter_ary = array(), $search_key = "", $filter_taxonomy = array ())
    {
        $parent = ReportData::getSurveyTaxonomy($survey_id);

        $reportData = new ReportData();
        $tmp_legal = [];
        $tmp_support = [];

        foreach ($parent as $row) {
            if (strpos($row->question_desc, 'Legal ') !== false) {
                $tmp_legal = $reportData->legelSupportData($survey_id, $row->question_id);
            } else if (strpos($row->question_desc, 'Support ') !== false || strpos($row->question_desc, 'Shared ') !== false ) {
                $tmp_support = $reportData->legelSupportData($survey_id, $row->question_id);
            }
        }

        $questionDataAry = $this->getQuestionDataByResp($survey_id);
        $permissionData = new PermissionData();
        $allowed_locationAry = $permissionData->getAllowedLocationsByUser($survey_id, Auth::user());
        $allowed_deptAry = $permissionData->getAllowedDepartmentsByUser($survey_id, Auth::user());

        if (!empty($filter_ary)) {
            $resps = Respondent::where('survey_id', $survey_id)
                ->where('survey_completed', 1)
                ->where(function($q) use ($search_key) {
                    if ($search_key != '') {
                        $q->where('resp_first', 'LIKE', "%$search_key%")
                            ->orWhere('resp_last', 'LIKE', "%$search_key%");
                    }
                })
                ->where(function($q) use ($filter_ary) {
                    if (array_key_exists('position', $filter_ary)) {
                        if (!empty($filter_ary['position'])) {
                            $q->whereIn('cust_3', $filter_ary['position']);
                        } else {
                            $q->where('cust_3', 'Nothing123!@#');
                        }
                    }
                    if (array_key_exists('department', $filter_ary)) {
                        if (!empty($filter_ary['department'])) {
                            $q->whereIn('cust_4', $filter_ary['department']);
                        } else {
                            $q->where('cust_4', 'Nothing123!@#');
                        }                        
                    }
                    if (array_key_exists('group', $filter_ary)) {
                        if (!empty($filter_ary['group'])) {
                            $q->whereIn('cust_2', $filter_ary['group']);
                        } else {
                            $q->where('cust_2', 'Nothing123!@#');
                        }
                    }
                    if (array_key_exists('location', $filter_ary)) {
                        if (!empty($filter_ary['location'])) {
                            $q->whereIn('cust_6', $filter_ary['location']);
                        } else {
                            $q->where('cust_6', 'Nothing123!@#');
                        }
                    }
                    if (array_key_exists('category', $filter_ary)) {
                        if (!empty($filter_ary['category'])) {
                            $q->whereIn('cust_5', $filter_ary['category']);
                        } else {
                            $q->where('cust_5', 'Nothing123!@#');
                        }
                    }
                    return $q;
                })
                ->whereIn('cust_4', $allowed_deptAry)
                ->whereIn('cust_6', $allowed_locationAry)
                ->orderBy('resp_last', 'asc')
                ->get();
        } else {
            $resps = Respondent::where('survey_id', '=', $survey_id)
                ->where('survey_completed', '=', 1)
                ->where(function($q) use ($search_key) {
                    if ($search_key != '') {
                        $q->where('resp_first', 'LIKE', "%$search_key%")
                            ->orWhere('resp_last', 'LIKE', "%$search_key%");
                    }
                })
                ->whereIn('cust_4', $allowed_deptAry)
                ->whereIn('cust_6', $allowed_locationAry)
                ->orderBy('resp_last', 'asc')
                ->get();
        }

        $questionAry = $this->getQuestionAry($survey_id);
        $children    = $this->getFinalQuestionNodes($questionAry);
        $pidInfoAry  = $reportData->getAryOfQuestionWithParents($questionAry);
        $rsfRatesAry = $this->getRSFratesAry($survey_id);

        foreach ($resps as $resp){
            $tmp_support_hours = 0;
            $tmp_legal_hours = 0;
            $resp->legal_hours   = 0;
            $resp->support_hours = 0;
            if(array_key_exists($resp->resp_id, $tmp_support)){
                $resp->support_hours = $tmp_support[$resp->resp_id]['answer_value'] ? $tmp_support[$resp->resp_id]['answer_value'] : 0;
                $tmp_support_hours = $resp->support_hours;
            }
            if(array_key_exists($resp->resp_id, $tmp_legal)){
                $resp->legal_hours = $tmp_legal[$resp->resp_id]['answer_value'];
                $tmp_legal_hours = $resp->legal_hours;
            }
            if ($tmp_support_hours != 0 || $tmp_legal_hours != 0) {
                $resp->total_hours = $tmp_support_hours + $tmp_legal_hours;
                $resp->hourly_rate = round($resp->resp_compensation * (1 + $resp->resp_benefit_pct) / ($tmp_support_hours + $tmp_legal_hours));
            } else {
                $resp->hourly_rate = 0;
            }
            // Set RSF information
            if (array_key_exists($resp->cust_6, $rsfRatesAry)) {
                $resp->rsf_rate = $rsfRatesAry[$resp->cust_6]['location_Current'];
                $resp->rsf_rate_adjacent = $rsfRatesAry[$resp->cust_6]['location_Adjacent'];
                $resp->rsf_rate_regional = $rsfRatesAry[$resp->cust_6]['location_Regional'];
                $resp->rsf_rate_other = $rsfRatesAry[$resp->cust_6]['location_OTHER'];
            } else {
                $resp->rsf_rate = 0;    
                $resp->rsf_rate_adjacent = 0;    
                $resp->rsf_rate_regional = 0;    
                $resp->rsf_rate_other = 0;    
            }
            $resp->rsf_cost = round($resp->rsf_rate * $resp->rentable_square_feet);
            $resp->rsf_cost_adjacent = round($resp->rsf_rate_adjacent * $resp->rentable_square_feet);
            $resp->rsf_cost_regional = round($resp->rsf_rate_regional * $resp->rentable_square_feet);
            $resp->rsf_cost_other = round($resp->rsf_rate_other * $resp->rentable_square_feet);
            // Calculate the hours via Proximity Factors
            $resp->prox_low_hours    = 0;
            $resp->prox_medium_hours = 0;
            $resp->prox_high_hours   = 0;
            $resp->prox_virtual_hours = 10;

            if (!empty($filter_taxonomy)) {
                $allowed_questions = array_merge($filter_taxonomy['classification'], $filter_taxonomy['substantive'], $filter_taxonomy['process']);
            }

            if (array_key_exists($resp->resp_id, $questionDataAry)) {
                
                foreach ($questionDataAry[$resp->resp_id] as $question_id => $row) {
                   /*  echo "<pre>";
                        print_r($question_id);
                        echo "</pre>"; 
                        echo "<pre>";
                        print_r($row);
                        echo "</pre>"; */
                    if (!empty($filter_taxonomy)) {
                        if (in_array($question_id, $children)) {
                            if (count(array_intersect($pidInfoAry[$question_id], $allowed_questions)) > 0) {
                            
                                $qHours = $reportData->getQuestionHoursFromArray($resp->resp_id, $question_id, $questionDataAry);
                                
                                if ($row['proximity_factor'] == 1) {
                                    $resp->prox_low_hours = $resp->prox_low_hours + $qHours;
                                } else if ($row['proximity_factor'] == 2) {
                                    $resp->prox_medium_hours = $resp->prox_medium_hours + $qHours;
                                } else if ($row['proximity_factor'] == 3) {
                                    $resp->prox_high_hours = $resp->prox_high_hours + $qHours;
                                } else if ($row['proximity_factor'] == 4) {
                                    $resp->prox_virtual_hours = $resp->prox_virtual_hours + $qHours;
                                }
                            }
                        }
                    } else {
                        if (in_array($question_id, $children)) {
                            $qHours = $reportData->getQuestionHoursFromArray($resp->resp_id, $question_id, $questionDataAry);
                        
                            if ($row['proximity_factor'] == 1) {
                                $resp->prox_low_hours = $resp->prox_low_hours + $qHours;
                            } else if ($row['proximity_factor'] == 2) {
                                $resp->prox_medium_hours = $resp->prox_medium_hours + $qHours;
                            } else if ($row['proximity_factor'] == 3) {
                                $resp->prox_high_hours = $resp->prox_high_hours + $qHours;
                            } else if ($row['proximity_factor'] == 4) {
                                $resp->prox_virtual_hours = $resp->prox_virtual_hours + $qHours;
                            }
                        }
                    }
                }
                // exit;
                // dd();
            }
        }
        return $resps;
    }
    /**
     * 
     * Returns the question answers data by respondents
     * 
     * @param int $survey_id    survey id
     * @return array 
     */
    public function getQuestionDataByResp ($survey_id) {
       // ini_set('memory_limit', '-1');
        // query has to be changed
        //dd($survey_id);
        /* $answers = cache()->rememberForever('answers'.$survey_id, function() use ($survey_id){
            return Answer::leftJoin('tblquestion', 'tblquestion.question_id', 'tblanswer.question_id')
            ->leftJoin('tblrespondent', 'tblrespondent.resp_id', 'tblanswer.resp_id')
            ->leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->where('tblquestion.survey_id', $survey_id)
            // ->where('tblanswer.answer_value', '>', 0)
            ->orderBy('tblrespondent.resp_id', 'asc')
            ->orderBy('tblpage.question_id_parent', 'asc')
            ->select('tblrespondent.resp_id',
                'tblquestion.question_id',
                'tblquestion.question_proximity_factor',
                'tblpage.question_id_parent',
                'tblanswer.answer_value')
            ->get();
        }); */
            

        $answers = Answer::leftJoin('tblquestion', 'tblquestion.question_id', 'tblanswer.question_id')
            ->leftJoin('tblrespondent', 'tblrespondent.resp_id', 'tblanswer.resp_id')
            ->leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->where('tblquestion.survey_id', $survey_id)
            ->where('tblanswer.answer_value', '>', 0)
            ->orderBy('tblrespondent.resp_id', 'asc')
            ->orderBy('tblpage.question_id_parent', 'asc')
            ->select('tblrespondent.resp_id',
                'tblquestion.question_id',
                'tblquestion.question_proximity_factor',
                'tblpage.question_id_parent',
                'tblanswer.answer_value')
            ->get();
        //dd($answers);
        $returnAry = array();
        foreach ($answers as $answer) {
            if(!array_key_exists($answer->resp_id, $returnAry)) {
                $returnAry[$answer->resp_id] = array();
                $returnAry[$answer->resp_id][$answer->question_id] = array();
                $returnAry[$answer->resp_id][$answer->question_id]['parent_id'] = $answer->question_id_parent;
                $returnAry[$answer->resp_id][$answer->question_id]['answer_value'] = $answer->answer_value;
                $returnAry[$answer->resp_id][$answer->question_id]['proximity_factor'] = $answer->question_proximity_factor;
            } else {
                $returnAry[$answer->resp_id][$answer->question_id] = array();
                $returnAry[$answer->resp_id][$answer->question_id]['parent_id'] = $answer->question_id_parent;
                $returnAry[$answer->resp_id][$answer->question_id]['answer_value'] = $answer->answer_value;
                $returnAry[$answer->resp_id][$answer->question_id]['proximity_factor'] = $answer->question_proximity_factor;
            }
        }

        return $returnAry;
    }
    /**
     * 
     * Returns the data of questions regarding to the survey
     * 
     * @param int $survey_id    survey id
     * @return \App\Models\Question 
     */
    public function getQuestionAry ($survey_id) {
 
        /* $query = cache()->rememberForever('questionarry'.$survey_id, function() use ($survey_id){
        
            return Question::leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
                    ->where('tblquestion.survey_id', $survey_id)
                    ->select('tblquestion.question_id',
                        'tblquestion.question_desc',
                        'tblpage.question_id_parent AS pid')
                    ->get();

        }); */

        $query =  Question::leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
                    ->where('tblquestion.survey_id', $survey_id)
                    ->select('tblquestion.question_id',
                        'tblquestion.question_desc',
                        'tblpage.question_id_parent AS pid')
                    ->get();
       // dd($query);
        return $query;
    }
    /**
     * 
     * Returns the answers data of final nodes of taxonomy
     * 
     * @param \App\Models\Question $questionAry 
     * @return array 
     */
    /* public function getFinalQuestionNodes ($questionAry) {
        $res = array ();

        foreach ($questionAry as $row) {
            $flag = 0;
            foreach ($questionAry as $child) {
                if ($child->pid == $row->question_id) {
                    $flag = 1;
                    break;
                }
            }
            if ($flag == 0) {
                $res[] = $row->question_id;
            }
        }

        return $res;
    } */

    public function getFinalQuestionNodes ($questionAry) {
        $res = array ();
      
        $pids = $questionAry->pluck('pid')->toArray() ;
        // $questionAry = $questionAry->toArray();
        // dd($questionAry);

        foreach ($questionAry as $row) {
            $flag = 0;
           
            foreach ($questionAry as $child) {
             
                if ($child['pid'] == $row['question_id']) {
                    $flag = 1;
                    break;
                }
            }
            
            if ($flag == 0) {
                $res[] = $row['question_id'];
            }
           // dd($res);
        }
        // dd();
        // dd($res); 
        return $res;
    }
    /**
     * 
     * Returns the data of RSF by location
     * 
     * @param int $survey_id    survey id
     * @return array 
     */
    public function getRSFratesAry ($survey_id) {
        $query = Location::where('survey_id', $survey_id)->get();

        $res = array (); 

        foreach ($query as $row) {
            $res[$row->location] = array ();
            $res[$row->location]['location_OTHER'] = $row->location_OTHER;
            $res[$row->location]['location_Regional'] = $row->location_Regional;
            $res[$row->location]['location_Adjacent'] = $row->location_Adjacent;
            $res[$row->location]['location_Current'] = $row->location_Current;
        }

        return $res;
    }
    /**
     * 
     * Returns the data of individual respondents RSF rate and cost
     * 
     * @param int $resp_id    respondent id
     * @param int $survey_id    survey id
     * @return array 
     */
    public function getIndividualRespData ($resp_id, $survey_id) {
        $resp = Respondent::where('survey_id', $survey_id)
            ->where('resp_id', $resp_id)
            ->firstOrFail();

        $last_dt = new DateTime($resp->last_dt);
        $resp->last_dt = $last_dt->format('Y-m-d H:i e');
        
        $rsfRatesAry = $this->getRSFratesAry($survey_id);
        // Set RSF information
        if (array_key_exists($resp->cust_6, $rsfRatesAry)) {
            $resp->rsf_rate = $rsfRatesAry[$resp->cust_6]['location_Current'];
        } else {
            $resp->rsf_rate = 0;    
        }
        $resp->rsf_cost = round($resp->rsf_rate * $resp->rentable_square_feet);

        return $resp;
    }
    /**
     * 
     * Returns the data of Opportunity Detail Report
     * 
     * @param int $survey_id    survey id
     * @param \App\Models\Respondent $resps    respondents data
     * @param int $depth    taxonomy depth
     * @param int $prox    Proximity Factor 
     * @return array 
     */
    public function getOpportunityDetailData ($survey_id, $resps, $depth, $prox) {
        $res = array ();

        $reportData = new ReportData();
        $analysisData = new AnalysisData();
        // Array of Question Data by Respondents
        $questionAry = $reportData->getQuestionDataByResp($survey_id);
        // Question IDs to filter respondents with depth of taxonomy
        $questionIds = $analysisData->getQuestionIdsByDepth ($survey_id, $depth);
        // Question Array
        $questionDataAry = $reportData->getQuestionAry($survey_id);
        $questionDescAry = $analysisData->getQuestionOneDescription($questionDataAry);
        $questionProxAry = $this->getQuestionProximity($questionDataAry);
        // RSF rating 
        $rsfRatesAry = $this->getRSFratesAry($survey_id);

        $children = $this->getFinalQuestionNodes($questionDataAry);
        $pidInfoAry = $reportData->getAryOfQuestionWithParents($questionDataAry);
        
        $data = array ();
        $total_hours = 0;
        $total_rsf_cost = array (
            'current' => 0,
            'adjacent' => 0,
            'regional' => 0,
            'other' => 0,
            'variance_adjacent' => 0,
            'variance_regional' => 0,
            'variance_other' => 0,
        );

        if (empty($questionIds)) {
            return 404;
        }

        foreach ($resps as $resp) {
            if (array_key_exists($resp->resp_id, $questionAry)) {
                foreach ($questionAry[$resp->resp_id] as $question_id => $row) {
                    if (in_array($question_id, $children)) {
                        $parentQID = array_intersect($pidInfoAry[$question_id], $questionIds);
                        $curID = current($parentQID);
                        if (count($parentQID) > 0) {
                            if ($prox == 'all' || $prox == $row['prox_factor']) {
                                $hours = $reportData->getQuestionHoursFromArray($resp->resp_id, $question_id, $questionAry);
                                $rsf = $resp->total_hours > 0 ? $resp->rentable_square_feet * $hours / $resp->total_hours : 0;
                                
                                if (array_key_exists($resp->cust_6, $rsfRatesAry)) {
                                    $locationCurrent = $rsfRatesAry[$resp->cust_6]['location_Current'];
                                    $locationAdjacent = $rsfRatesAry[$resp->cust_6]['location_Adjacent'];
                                    $locationRegional = $rsfRatesAry[$resp->cust_6]['location_Regional'];
                                    $locationOther = $rsfRatesAry[$resp->cust_6]['location_OTHER'];
                                } else {
                                    $locationCurrent = 0;
                                    $locationAdjacent = 0;
                                    $locationRegional = 0;
                                    $locationOther = 0;
                                }

                                $rsf_cost_current = $locationCurrent * $rsf;
                                if ($row['prox_factor'] == 2 || $row['prox_factor'] == 1) {
                                    $rsf_cost_adjacent = $locationAdjacent * $rsf;
                                    if ($row['prox_factor'] == 1) {
                                        $rsf_cost_regional = $locationRegional * $rsf;
                                        $rsf_cost_other = $locationOther * $rsf;
                                    } else {
                                        $rsf_cost_regional = 0;
                                        $rsf_cost_other = 0;
                                    }
                                } else {
                                    $rsf_cost_adjacent = 0;
                                    $rsf_cost_regional = 0;
                                    $rsf_cost_other = 0;
                                }

                                $variance_adjacent = 0;
                                $variance_regional = 0;
                                $variance_other = 0;
                                if ($row['prox_factor'] == 2 || $row['prox_factor'] == 1) {
                                    $variance_adjacent = $rsf_cost_current - $locationAdjacent * $rsf;
                                    if ($row['prox_factor'] == 1) {
                                        $variance_regional = $rsf_cost_current - $locationRegional * $rsf;
                                        $variance_other = $rsf_cost_current - $locationOther * $rsf;
                                    }
                                }

                                if (!array_key_exists($curID, $data)) {
                                    $data[$curID] = array (
                                        'question_desc' => $questionDescAry[$curID],
                                        'proximity_factor' => $questionProxAry[$curID],
                                        'hours' => $hours,
                                        'rsf' => $rsf,
                                        'rsf_cost_current' => $rsf_cost_current,
                                        'rsf_cost_adjacent' => $rsf_cost_adjacent,
                                        'rsf_cost_regional' => $rsf_cost_regional,
                                        'rsf_cost_other' => $rsf_cost_other,
                                        'variance_adjacent' => $variance_adjacent,
                                        'variance_regional' => $variance_regional,
                                        'variance_other' => $variance_other,
                                    );
                                } else {
                                    $data[$curID]['hours'] += $hours;
                                    $data[$curID]['rsf'] += $rsf;
                                    $data[$curID]['rsf_cost_current'] += $rsf_cost_current;
                                    $data[$curID]['rsf_cost_adjacent'] += $rsf_cost_adjacent;
                                    $data[$curID]['rsf_cost_regional'] += $rsf_cost_regional;
                                    $data[$curID]['rsf_cost_other'] += $rsf_cost_other;
                                    $data[$curID]['variance_adjacent'] += $variance_adjacent;
                                    $data[$curID]['variance_regional'] += $variance_regional;
                                    $data[$curID]['variance_other'] += $variance_other;
                                }
        
                                $total_hours += $hours;
                                $total_rsf_cost['current'] += $rsf_cost_current;
                                $total_rsf_cost['adjacent'] += $rsf_cost_adjacent;
                                $total_rsf_cost['regional'] += $rsf_cost_regional;
                                $total_rsf_cost['other'] += $rsf_cost_other;
                                $total_rsf_cost['variance_adjacent'] += $variance_adjacent;
                                $total_rsf_cost['variance_regional'] += $variance_regional;
                                $total_rsf_cost['variance_other'] += $variance_other;
                            }
                        }
                    }
                }
            }
        }

        $keys = array_column($data, 'question_desc');
        array_multisort($keys, SORT_ASC, $data);

        /**
         * Additional Calculation 
         * Adjacent: Medium & Low
         * Regional & Other: Low
         */
        foreach ($data as $question_id => $row) {
            switch ($row['proximity_factor']) {
                case 3:
                    $total_rsf_cost['adjacent'] -= $row['rsf_cost_adjacent'];
                    $total_rsf_cost['regional'] -= $row['rsf_cost_regional'];
                    $total_rsf_cost['other'] -= $row['rsf_cost_other'];
                    $total_rsf_cost['variance_adjacent'] -= $row['variance_adjacent'];
                    $total_rsf_cost['variance_regional'] -= $row['variance_regional'];
                    $total_rsf_cost['variance_other'] -= $row['variance_other'];
                    $data[$question_id]['rsf_cost_adjacent'] = 0;
                    $data[$question_id]['rsf_cost_regional'] = 0;
                    $data[$question_id]['rsf_cost_other'] = 0;
                    $data[$question_id]['variance_adjacent'] = 0;
                    $data[$question_id]['variance_regional'] = 0;
                    $data[$question_id]['variance_other'] = 0;
                    break;
                    
                case 2:
                    $total_rsf_cost['regional'] -= $row['rsf_cost_regional'];
                    $total_rsf_cost['other'] -= $row['rsf_cost_other'];
                    $total_rsf_cost['variance_regional'] -= $row['variance_regional'];
                    $total_rsf_cost['variance_other'] -= $row['variance_other'];
                    $data[$question_id]['rsf_cost_regional'] = 0;
                    $data[$question_id]['rsf_cost_other'] = 0;
                    $data[$question_id]['variance_regional'] = 0;
                    $data[$question_id]['variance_other'] = 0;
                    break;
                
                default:
                    # code...
                    break;
            }
        }

        $res['rows'] = $data;
        $res['total_hours'] = $total_hours;
        $res['total_rsf_cost'] = $total_rsf_cost;

        return $res;
    }
    /**
     * 
     * Returns the array of questions proximity query with the question id as a key
     * 
     * @param \App\Models\Question $questionAry    questions 
     * @return array 
     */
    public function getQuestionProximity ($questionAry) {
        $res = array ();
        foreach ($questionAry as $row) {
            $res[$row->question_id] = $row->question_proximity_factor;
        }
        return $res;
    }
    /**
     * 
     * Returns the data of Activity by Location Report
     * 
     * @param \App\Models\Respondent $resps    respondents
     * @param array $prox Proximity factors
     * @return array 
     */
    public function getActivityByLocation ($resps, $prox) {
        $res = array ();
        
        $data = array ();
        $res['total_hours'] = 0;
        $res['total_rsf'] = 0;
        $include_resps = array ();
        $total_rsf_cost = array (
            'current' => 0,
            'adjacent' => 0,
            'regional' => 0,
            'other' => 0,
        );

        foreach ($resps as $resp) {
            $hours = 0;
            $rsf = 0;

            if (in_array('all', $prox)) {
                $hours = $resp->total_hours;
                $rsf = $resp->rentable_square_feet;
            } else {
                if ($resp->total_hours != 0) {
                    if (in_array(1, $prox)) {
                        $hours += $resp->prox_low_hours;
                        $rsf += $rsf * $resp->prox_low_hours / $resp->total_hours;
                    }
                    if (in_array(2, $prox)) {
                        $hours = $resp->prox_med_hours;
                        $rsf = $rsf * $resp->prox_med_hours / $resp->total_hours;
                    }
                    if (in_array(3, $prox)) {
                        $hours = $resp->prox_high_hours;
                        $rsf = $rsf * $resp->prox_high_hours / $resp->total_hours;
                    }
                }
            }

            if (!array_key_exists($resp->cust_6, $data)) {
                $data[$resp->cust_6] = array ();
                $data[$resp->cust_6]['hours'] = $hours;
                $data[$resp->cust_6]['rsf'] = $rsf;
                $data[$resp->cust_6]['rsf_cost_current'] = 0;
                $data[$resp->cust_6]['rsf_cost_adjacent'] = 0;
                $data[$resp->cust_6]['rsf_cost_regional'] = 0;
                $data[$resp->cust_6]['rsf_cost_other'] = 0;
                $include_resps[$resp->cust_6] = array ($resp);
            } else {
                $data[$resp->cust_6]['hours'] += $hours;
                $data[$resp->cust_6]['rsf'] += $rsf;
                $data[$resp->cust_6]['rsf_cost_current'] += $resp->rsf_cost;
                $data[$resp->cust_6]['rsf_cost_adjacent'] += $resp->rsf_cost_adjacent;
                $data[$resp->cust_6]['rsf_cost_regional'] += $resp->rsf_cost_regional;
                $data[$resp->cust_6]['rsf_cost_other'] += $resp->rsf_cost_other;
                array_push($include_resps[$resp->cust_6], $resp);
            }

            $res['total_hours'] += $hours;
            $res['total_rsf'] += $rsf;
            $total_rsf_cost['current'] += $resp->rsf_cost;
            $total_rsf_cost['adjacent'] += $resp->rsf_cost_adjacent;
            $total_rsf_cost['regional'] += $resp->rsf_cost_regional;
            $total_rsf_cost['other'] += $resp->rsf_cost_other;
        }

        foreach ($data as $location => $row) {
            $data[$location]['percent'] = $res['total_hours'] > 0 ? round(100 * $row['hours'] / $res['total_hours'], 1) : 0;
            $data[$location]['rsf_percent'] = $res['total_rsf'] > 0 ? round(100 * $row['rsf'] / $res['total_rsf'], 1) : 0;
            $data[$location]['rsf_cost_current_percent'] = $total_rsf_cost['current'] > 0 ? round(100 * $row['rsf_cost_current'] / $total_rsf_cost['current'], 1) : 0;
            $data[$location]['rsf_cost_adjacent_percent'] = $total_rsf_cost['adjacent'] > 0 ? round(100 * $row['rsf_cost_adjacent'] / $total_rsf_cost['adjacent'], 1) : 0;
            $data[$location]['rsf_cost_regional_percent'] = $total_rsf_cost['regional'] > 0 ? round(100 * $row['rsf_cost_regional'] / $total_rsf_cost['regional'], 1) : 0;
            $data[$location]['rsf_cost_other_percent'] = $total_rsf_cost['other'] > 0 ? round(100 * $row['rsf_cost_other'] / $total_rsf_cost['other'], 1) : 0;
        }

        ksort($data);

        $res['rows'] = $data;
        $res['total_rsf_cost'] = $total_rsf_cost;
        $res['resps'] = $include_resps;

        return $res;
    }
    /**
     * 
     * Returns the data of Opportunity Summary Data Report
     * 
     * @param int $survey_id    survey id
     * @param \App\Models\Respondent $resps    respondents
     * @return array 
     */
    public function getOpportunitySummaryData ($survey_id, $resps) {
        $prop = array (
                'rsf' => 0,
                'rsf_cost_current' => 0,
                'rsf_cost_adjacent' => 0,
                'rsf_cost_regional' => 0,
                'rsf_cost_other' => 0,
            );

        $res = array (
            'high' => $prop,
            'med' => $prop,
            'low' => $prop,
            'virtual' => $prop,
        );

        $rsfRatesAry = $this->getRSFratesAry($survey_id);
        // dd($resps[0]);
        foreach ($resps as $resp) {
            if ($resp->total_hours > 0) {
                $other_rsf = $resp->rentable_square_feet * $resp->prox_virtual_hours / $resp->total_hours;
                $low_rsf = $resp->rentable_square_feet * $resp->prox_low_hours / $resp->total_hours;
                $med_rsf = $resp->rentable_square_feet * $resp->prox_medium_hours / $resp->total_hours;
                $high_rsf = $resp->rentable_square_feet * $resp->prox_high_hours / $resp->total_hours;
            } else {
                // echo "FROM";
                $other_rsf = 0;
                $low_rsf = 0;
                $med_rsf = 0;
                $high_rsf = 0;
            }

            /* echo "<pre>";
            print_r($other_rsf);
            echo "</pre>"; */
            if (array_key_exists($resp->cust_6, $rsfRatesAry)) {
                $locationCurrent = $rsfRatesAry[$resp->cust_6]['location_Current'];
                $locationAdjacent = $rsfRatesAry[$resp->cust_6]['location_Adjacent'];
                $locationRegional = $rsfRatesAry[$resp->cust_6]['location_Regional'];
                $locationOther = $rsfRatesAry[$resp->cust_6]['location_OTHER'];
            } else {
                $locationCurrent = 0;
                $locationAdjacent = 0;
                $locationRegional = 0;
                $locationOther = 0;
            }
            $res['low']['rsf'] += $low_rsf;
            $res['low']['rsf_cost_current'] += $low_rsf * $locationCurrent;
            $res['low']['rsf_cost_adjacent'] += $low_rsf * $locationAdjacent;
            $res['low']['rsf_cost_regional'] += $low_rsf * $locationRegional;
            $res['low']['rsf_cost_other'] += $low_rsf * $locationOther;

            $res['virtual']['rsf'] += $other_rsf;
            $res['virtual']['rsf_cost_current'] += $other_rsf * $locationCurrent;
            $res['virtual']['rsf_cost_adjacent'] += $other_rsf * $locationAdjacent;
            $res['virtual']['rsf_cost_regional'] += $other_rsf * $locationRegional;
            $res['virtual']['rsf_cost_other'] += $other_rsf * $locationOther;
            
            $res['med']['rsf'] += $med_rsf;
            $res['med']['rsf_cost_current'] += $med_rsf * $locationCurrent;
            $res['med']['rsf_cost_adjacent'] += $med_rsf * $locationAdjacent;
            
            $res['high']['rsf'] += $high_rsf;
            $res['high']['rsf_cost_current'] += $high_rsf * $locationCurrent;
        }
        
        return $res;
    }
    /**
     * 
     * Returns the data of Activity Cost By Location Report
     * 
     * @param int $survey_id    survey id
     * @param \App\Models\Respondent $resps    respondents
     * @param int $depth taxonomy data
     * @param integeer $prox Proximity factor
     * @return array 
     */
    public function getActivityCostByLocationData ($survey_id, $resps, $depth, $prox) {
        $reportData = new ReportData();
        $analysisData = new AnalysisData();
        // Array of Question Data by Respondents
        $questionAry = $reportData->getQuestionDataByResp($survey_id);
        $questionDetailData = $analysisData->getQuestionData($survey_id);
        // Question IDs to filter respondents with depth of taxonomy
        $questionIds = $analysisData->getQuestionIdsByDepth ($survey_id, $depth);
        // Question Array
        $questionDataAry = $reportData->getQuestionAry($survey_id);
        $questionDescAry = $analysisData->getQuestionOneDescription($questionDataAry);
        // $questionProxAry = $this->getQuestionProximity($questionDataAry);
        // RSF rating 
        $rsfRatesAry = $this->getRSFratesAry($survey_id);

        if (empty($questionIds))
            return 404;

        $data = array ();

        foreach ($resps as $resp) {
            if (array_key_exists($resp->resp_id, $questionAry)) {
                foreach ($questionAry[$resp->resp_id] as $question_id => $row) {
                    if (in_array($question_id, $questionIds)) {
                        if ($prox == 'all' || $prox == $row['prox_factor']) {
                            $hours = $reportData->getQuestionHoursFromArray($resp->resp_id, $question_id, $questionAry);
                            $rsf = $resp->total_hours > 0 ? $resp->rentable_square_feet * $hours / $resp->total_hours : 0;
                            $employee_cost = $hours * $resp->hourly_rate;

                            if (array_key_exists($resp->cust_6, $rsfRatesAry)) {
                                $rsf_cost_current = $rsfRatesAry[$resp->cust_6]['location_Current'] * $rsf;
                            } else {
                                $rsf_cost_current = 0 * $rsf;
                            }

                            if (!array_key_exists($resp->cust_6, $data)) {
                                $data[$resp->cust_6] = array ();
                                $data[$resp->cust_6]['total_hours'] = $hours;
                                $data[$resp->cust_6]['total_rsf'] = $rsf;
                                $data[$resp->cust_6]['total_cost_current'] = $rsf_cost_current;
                                $data[$resp->cust_6]['rows'] = array ();
                            } else {
                                $data[$resp->cust_6]['total_hours'] += $hours;
                                $data[$resp->cust_6]['total_rsf'] += $rsf;
                                $data[$resp->cust_6]['total_cost_current'] += $rsf_cost_current;
                            }
                            
                            if (!array_key_exists($question_id, $data[$resp->cust_6]['rows'])) {
                                $data[$resp->cust_6]['rows'][$question_id] = array (
                                    'location' => $resp->cust_6,
                                    'question_desc' => $questionDescAry[$question_id],
                                    'employee_cost_sum' => $employee_cost,
                                    'employee_cost' => $employee_cost,
                                    'hours' => $hours,
                                    'rsf' => $rsf,
                                    'rsf_cost_current' => $rsf_cost_current,
                                    'resps' => array ($resp->resp_id),
                                    'resp_num' => 1,
                                );
                            } else {
                                if (!in_array($resp->resp_id, $data[$resp->cust_6]['rows'][$question_id]['resps'])) {
                                    array_push($data[$resp->cust_6]['rows'][$question_id]['resps'], $resp->resp_id);
                                }
                                $data[$resp->cust_6]['rows'][$question_id]['resp_num'] = count($data[$resp->cust_6]['rows'][$question_id]['resps']);                                
                                $data[$resp->cust_6]['rows'][$question_id]['employee_cost_sum'] += $employee_cost;
                                $data[$resp->cust_6]['rows'][$question_id]['employee_cost'] = $data[$resp->cust_6]['rows'][$question_id]['resp_num'] > 0 ? $data[$resp->cust_6]['rows'][$question_id]['employee_cost_sum'] / $data[$resp->cust_6]['rows'][$question_id]['resp_num'] : 0;
                                $data[$resp->cust_6]['rows'][$question_id]['hours'] += $hours;
                                $data[$resp->cust_6]['rows'][$question_id]['rsf'] += $rsf;
                                $data[$resp->cust_6]['rows'][$question_id]['rsf_cost_current'] += $rsf_cost_current;
                            }
                        }
                    }
                }
            }
        }

        foreach ($data as $location => $item) {
            $employee_cost_sum = 0;
            $resps = 0;
            foreach ($item['rows'] as $row) {
                $employee_cost_sum += $row['employee_cost_sum'];
                $resps += count($row['resps']);
            }
            $item['total_employee_cost_num'] = $employee_cost_sum;
            $item['resps'] = $resps;
            $item['total_employee_cost'] = $resps > 0 ?  $employee_cost_sum / $resps : 0;
            $keys = array_column($item['rows'], 'question_desc');
            array_multisort($keys, SORT_ASC, $item['rows']);
            $data[$location] = $item;
        }
        
        ksort($data);
        
        return $data;
    }
    /**
     * 
     * Returns the data of Proximity by Activity Report
     * 
     * @param int $survey_id    survey id
     * @param \App\Models\Respondent $resps   respondents
     * @param int $depth    taxonomy depth
     * @return array 
     */
    public function getProximitybyActivityData ($survey_id, $resps, $depth) {
        $reportData = new ReportData();
        $analysisData = new AnalysisData();
        // Array of Question Data by Respondents
        $questionAry = $reportData->getQuestionDataByResp($survey_id);
        $questionDetailData = $analysisData->getQuestionData($survey_id);
        // Question IDs to filter respondents with depth of taxonomy
        $questionIds = $analysisData->getQuestionIdsByDepth ($survey_id, $depth);
        // Question Array
        $questionDataAry = $reportData->getQuestionAry($survey_id);
        $questionDescAry = $analysisData->getQuestionOneDescription($questionDataAry);
        // $questionProxAry = $this->getQuestionProximity($questionDataAry);
        // RSF rating 
        $rsfRatesAry = $this->getRSFratesAry($survey_id);

        $children = $this->getFinalQuestionNodes($questionDataAry);
        $pidInfoAry  = $reportData->getAryOfQuestionWithParents($questionDataAry);
        
        if (empty($questionIds))
            return 404;

        $data = array ();
        
        foreach ($resps as $resp) {
            if (array_key_exists($resp->resp_id, $questionAry)) {
                foreach ($questionAry[$resp->resp_id] as $question_id => $row) {
                    if (in_array($question_id, $children)) {
                        $parentQID = array_intersect($pidInfoAry[$question_id], $questionIds);
                        if (count($parentQID) > 0) {
                            $curID = current($parentQID);
                            $qHours = $reportData->getQuestionHoursFromArray($resp->resp_id, $question_id, $questionAry);
                            $qRSF = $resp->rentable_square_feet * $qHours / $resp->total_hours;
                            $low_hours = 0;
                            $med_hours = 0;
                            $high_hours = 0;
                            $virtual_hours = 10;
                            $low_rsf = 0;
                            $med_rsf = 0;
                            $high_rsf = 0;
                            $virtual_rsf = 10;

                            switch ($row['prox_factor']) {
                                case 1:
                                    $low_hours = $qHours;
                                    $low_rsf = $qRSF;
                                    break;
                                
                                case 2:
                                    $med_hours = $qHours;
                                    $med_rsf = $qRSF;
                                    break;
                                
                                case 3:
                                    $high_hours = $qHours;
                                    $high_rsf = $qRSF;
                                    break;
                                case 4:
                                    $virtual_hours = $qHours;
                                    $virtual_rsf = $qRSF;
                                    break;
                                
                                default:
                                    # code...
                                    break;
                            }

                            if (!array_key_exists($curID, $data)) {
                                $data[$curID] = array (
                                    'question_desc' => $questionDescAry[$curID],
                                    'low_hours' => $low_hours,
                                    'med_hours' => $med_hours,
                                    'virtual_hours' => $virtual_hours,
                                    'high_hours' => $high_hours,
                                    'low_rsf' => $low_rsf,
                                    'med_rsf' => $med_rsf,
                                    'high_rsf' => $high_rsf,
                                    'virtual_rsf' => $virtual_rsf,
                                );
                            } else {
                                $data[$curID]['low_hours'] += $low_hours;
                                $data[$curID]['med_hours'] += $med_hours;
                                $data[$curID]['high_hours'] += $high_hours;
                                $data[$curID]['low_rsf'] += $low_rsf;
                                $data[$curID]['med_rsf'] += $med_rsf;
                                $data[$curID]['high_rsf'] += $high_rsf;
                                $data[$curID]['virtual_rsf'] += $virtual_rsf;
                            }
                        }
                    }
                }
            }
        }

        array_multisort(array_column($data, 'question_desc'), SORT_ASC, 
                    array_column($data, 'high_hours'), SORT_DESC, 
                    $data);

        return $data;
    }
}