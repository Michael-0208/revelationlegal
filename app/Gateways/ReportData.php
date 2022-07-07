<?php

namespace App\Gateways;

use App\Http\Controllers\CommonController;
use App\Models\Answer;
use App\Models\Question;
use App\Models\TblQuestionTest;
use App\Models\Page;
use App\Models\TblPageTest;
use App\Models\Respondent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ReportData
{
    private $tempData = array();
    /**
     * 
     * Returns the data of Demographic Report 
     * 
     * @param int $survey_id    survey id
     * @param array $filter_ary    filter variables
     * @param float $min_rates  minimum rates
     * @param string $metric    metric values for demographic report
     * @return array 
     */
    public function get_demographic_data($survey_id, $filter_ary = array(), $min_rates, $metric)
    {
        // echo $survey_id;die;
        $respondents = $this->get_resp_data($survey_id, $filter_ary, 1);
        // echo"<pre>";print_r($respondents);die;
        $data['survey'] = CommonController::get_survey($survey_id);
        $data['respondents'] = $respondents;
        $data['sent'] = $respondents->count();
        // if($survey_id != 54){
            // echo $survey_id;die;
            $parent = Question::join('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->where('tblquestion.survey_id', $survey_id)
            ->where('tblpage.question_id_parent', 0)
            ->select('tblquestion.*')
            ->get();
       /*  }else{
           
            // die($survey_id);
            // $parent = Question::join('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            // ->where('tblquestion.survey_id', $survey_id)
            // ->where('tblpage.question_id_parent', 0)
            // ->select('tblquestion.*')
            // ->get();
            $parent = TblQuestionTest::join('tblpageTest', 'tblpageTest.page_id', 'tblquestionTest.page_id')
            ->where('tblquestionTest.survey_id', $survey_id)
            ->where('tblpageTest.question_id_parent', 0)
            ->select('tblquestionTest.*')
            ->get();
        } */
        
        $tmp_legal = [];
        $tmp_support = [];
        foreach ($parent as $row) {
            if (strpos($row->question_desc, 'Legal ') !== false) {
                $tmp_legal = $this->legelSupportData($survey_id, $row->question_id);
            } else if (strpos($row->question_desc, 'Support ') !== false) {
                $tmp_support = $this->legelSupportData($survey_id, $row->question_id);
            }
        } 

        $participated = 0;
        $completed = 0;
        $respondentsComp = 0;
        $participatedHours = 0;
        $nonparticipatedComp = 0;

        $tmpPosition = array();
        $tmpDepartment = array();
        $tmpGroup = array();
        $tmpLocation = array();
        $tmpCategory = array();

        foreach ($respondents as $respondent){
            $respondent->resp_total_compensation = $respondent->resp_compensation * (1 + $respondent->resp_benefit_pct);
            if ($respondent->survey_completed == 1){
                $completed++;
                $respondentsComp += $respondent->resp_total_compensation;
            } else if ($respondent->last_dt && $respondent->survey_completed == 0){
                $participated++;
                $respondentsComp += $respondent->resp_total_compensation;
                if(array_key_exists($respondent->resp_id, $tmp_support)){
                    $participatedHours += $tmp_support[$respondent->resp_id]['answer_value'];
                }
                if(array_key_exists($respondent->resp_id, $tmp_legal)){
                    $participatedHours += $tmp_legal[$respondent->resp_id]['answer_value'];
                }
            } else {
                $nonparticipatedComp += $respondent->resp_total_compensation;
            }

            if(!array_key_exists($respondent->cust_3, $tmpPosition)) {
                $tmpPosition[$respondent->cust_3] = $respondent->cust_3;
            }
            if(!array_key_exists($respondent->cust_4, $tmpDepartment)) {
                $tmpDepartment[$respondent->cust_4] = $respondent->cust_4;
            }
            if(!array_key_exists($respondent->cust_2, $tmpGroup)){
                $tmpGroup[$respondent->cust_2] = $respondent->cust_2;
            }
            if(!array_key_exists($respondent->cust_6, $tmpLocation)){
                $tmpLocation[$respondent->cust_6] = $respondent->cust_6;
            }
            if(!array_key_exists($respondent->cust_5, $tmpCategory)){
                $tmpCategory[$respondent->cust_5] = $respondent->cust_5;
            }
        }
        sort($tmpDepartment);
        sort($tmpPosition);
        sort($tmpGroup);
        sort($tmpLocation);
        sort($tmpCategory);

        $data['position'] = $tmpPosition;
        $data['department'] = $tmpDepartment;
        $data['group'] = $tmpGroup;
        $data['location'] = $tmpLocation;
        $data['category'] = $tmpCategory;

        $resps = $this->get_resp_data($survey_id, $filter_ary, 1);
        $totalHours= 0;
        foreach ($resps as $resp){
            $totalHours += $resp->support_hours + $resp->legal_hours;
        }

        $data['respondentsComp']=number_format(round($respondentsComp));
        $data['participated'] = $participated;
        $data['completed'] = $completed;
        $data['totalHours'] = number_format($totalHours);

        if ($participated == 0) {
            $participatedHours = 0;
        } else {
            $participatedHours = $participatedHours / $participated;
        }
        $data['avgAnnualHours'] = number_format(round($participatedHours));
        $data['nonparticipatedComp'] = number_format(round($nonparticipatedComp));

        $data['metric_data'] = $this->get_demographic_metric($survey_id, $metric, array(), $min_rates);
        
        return $data;
    }
    /**
     * 
     * Returns the data of Demographic report 
     * 
     * @param int $survey_id    survey id
     * @param string $metric    metric values for demographic report
     * @param array $filter_ary     filter variables for respondents
     * @param float $min_rates  minimum rates 
     * @return array 
     */
    public function get_demographic_metric ($survey_id, $metric, $filter_ary = array(), $min_rates) {
        $data = array();
        $resps = $this->get_resp_data($survey_id, $filter_ary, 1);
        $data['category'] = array();
        $data['location'] = array();
        $data['department'] = array();
        $data['group'] = array();
        $data['position'] = array();
        $invited = 0;
        switch ($metric) {
            case 'responseratecomplete':
                $tmpAry = array(
                    'field' => '',
                    'invite_num' => 1,
                    'complete_num' => 0
                );
                foreach ($resps as $resp) {
                    if(!array_key_exists($resp->cust_5, $data['category'])) {
                        $data['category'][$resp->cust_5] = $tmpAry;
                        $data['category'][$resp->cust_5]['field'] = $resp->cust_5;
                        if ($resp->survey_completed == 1) {
                            $data['category'][$resp->cust_5]['complete_num'] = 1;
                        }
                    } else {
                        $data['category'][$resp->cust_5]['invite_num'] += 1;
                        if ($resp->survey_completed == 1) {
                            $data['category'][$resp->cust_5]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_6, $data['location'])) {
                        $data['location'][$resp->cust_6] = $tmpAry;
                        $data['location'][$resp->cust_6]['field'] = $resp->cust_6;
                        if ($resp->survey_completed == 1) {
                            $data['location'][$resp->cust_6]['complete_num'] = 1;
                        }
                    } else {
                        $data['location'][$resp->cust_6]['invite_num'] += 1;
                        if ($resp->survey_completed == 1) {
                            $data['location'][$resp->cust_6]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_4, $data['department'])) {
                        $data['department'][$resp->cust_4] = $tmpAry;
                        $data['department'][$resp->cust_4]['field'] = $resp->cust_4;
                        if ($resp->survey_completed == 1) {
                            $data['department'][$resp->cust_4]['complete_num'] = 1;
                        }
                    } else {
                        $data['department'][$resp->cust_4]['invite_num'] += 1;
                        if ($resp->survey_completed == 1) {
                            $data['department'][$resp->cust_4]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_2, $data['group'])) {
                        $data['group'][$resp->cust_2] = $tmpAry;
                        $data['group'][$resp->cust_2]['field'] = $resp->cust_2;
                        if ($resp->survey_completed == 1) {
                            $data['group'][$resp->cust_2]['complete_num'] = 1;
                        }
                    } else {
                        $data['group'][$resp->cust_2]['invite_num'] += 1;
                        if ($resp->survey_completed == 1) {
                            $data['group'][$resp->cust_2]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_3, $data['position'])) {
                        $data['position'][$resp->cust_3] = $tmpAry;
                        $data['position'][$resp->cust_3]['field'] = $resp->cust_3;
                        if ($resp->survey_completed == 1) {
                            $data['position'][$resp->cust_3]['complete_num'] = 1;
                        }
                    } else {
                        $data['position'][$resp->cust_3]['invite_num'] += 1;
                        if ($resp->survey_completed == 1) {
                            $data['position'][$resp->cust_3]['complete_num'] += 1;
                        }
                    }
                }
                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                foreach ($data['category'] as $item) {
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        $item['percent'] = round($item['complete_num'] / $item['invite_num'] * 100);
                        array_push($tmpAry, $item);
                    }
                }
                $keys = array_column($tmpAry, 'percent');
                array_multisort($keys, SORT_ASC, $tmpAry);
                if ($tmp['invite_num'] == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $tmp['invite_num'] * 100);
                }
                array_push($tmpAry, $tmp);
                $data['category'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                foreach ($data['department'] as $item) {
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        $item['percent'] = round($item['complete_num'] / $item['invite_num'] * 100);
                        array_push($tmpAry, $item);
                    }
                }
                $keys = array_column($tmpAry, 'percent');
                array_multisort($keys, SORT_ASC, $tmpAry);
                if ($tmp['invite_num'] == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $tmp['invite_num'] * 100);
                }
                array_push($tmpAry, $tmp);
                $data['department'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                foreach ($data['location'] as $item) {
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        $item['percent'] = round($item['complete_num'] / $item['invite_num'] * 100);
                        array_push($tmpAry, $item);
                    }
                }
                $keys = array_column($tmpAry, 'percent');
                array_multisort($keys, SORT_ASC, $tmpAry);
                if ($tmp['invite_num'] == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $tmp['invite_num'] * 100);
                }
                array_push($tmpAry, $tmp);
                $data['location'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                foreach ($data['position'] as $item) {
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        $item['percent'] = round($item['complete_num'] / $item['invite_num'] * 100);
                        array_push($tmpAry, $item);
                    }
                }
                $keys = array_column($tmpAry, 'percent');
                array_multisort($keys, SORT_ASC, $tmpAry);
                if ($tmp['invite_num'] == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $tmp['invite_num'] * 100);
                }
                array_push($tmpAry, $tmp);
                $data['position'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                foreach ($data['group'] as $item) {
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        $item['percent'] = round($item['complete_num'] / $item['invite_num'] * 100);
                        array_push($tmpAry, $item);
                    }
                }
                $keys = array_column($tmpAry, 'percent');
                array_multisort($keys, SORT_ASC, $tmpAry);
                if ($tmp['invite_num'] == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $tmp['invite_num'] * 100);
                }
                array_push($tmpAry, $tmp);
                $data['group'] = $tmpAry;

                break;

            case 'responseratestarted':
                $tmpAry = array(
                    'field' => '',
                    'invite_num' => 1,
                    'complete_num' => 0
                );
                foreach ($resps as $resp) {
                    if(!array_key_exists($resp->cust_5, $data['category'])) {
                        $data['category'][$resp->cust_5] = $tmpAry;
                        $data['category'][$resp->cust_5]['field'] = $resp->cust_5;
                        if ($resp->survey_completed == 1 || ($resp->invitation_sent == 1 && $resp->survey_completed == 0)) {
                            $data['category'][$resp->cust_5]['complete_num'] = 1;
                        }
                    } else {
                        $data['category'][$resp->cust_5]['invite_num'] += 1;
                        if ($resp->survey_completed == 1 || ($resp->invitation_sent == 1 && $resp->survey_completed == 0)) {
                            $data['category'][$resp->cust_5]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_6, $data['location'])) {
                        $data['location'][$resp->cust_6] = $tmpAry;
                        $data['location'][$resp->cust_6]['field'] = $resp->cust_6;
                        if ($resp->survey_completed == 1 || ($resp->invitation_sent == 1 && $resp->survey_completed == 0)) {
                            $data['location'][$resp->cust_6]['complete_num'] = 1;
                        }
                    } else {
                        $data['location'][$resp->cust_6]['invite_num'] += 1;
                        if ($resp->survey_completed == 1 || ($resp->invitation_sent == 1 && $resp->survey_completed == 0)) {
                            $data['location'][$resp->cust_6]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_4, $data['department'])) {
                        $data['department'][$resp->cust_4] = $tmpAry;
                        $data['department'][$resp->cust_4]['field'] = $resp->cust_4;
                        if ($resp->survey_completed == 1 || ($resp->invitation_sent == 1 && $resp->survey_completed == 0)) {
                            $data['department'][$resp->cust_4]['complete_num'] = 1;
                        }
                    } else {
                        $data['department'][$resp->cust_4]['invite_num'] += 1;
                        if ($resp->survey_completed == 1 || ($resp->invitation_sent == 1 && $resp->survey_completed == 0)) {
                            $data['department'][$resp->cust_4]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_2, $data['group'])) {
                        $data['group'][$resp->cust_2] = $tmpAry;
                        $data['group'][$resp->cust_2]['field'] = $resp->cust_2;
                        if ($resp->survey_completed == 1 || ($resp->invitation_sent == 1 && $resp->survey_completed == 0)) {
                            $data['group'][$resp->cust_2]['complete_num'] = 1;
                        }
                    } else {
                        $data['group'][$resp->cust_2]['invite_num'] += 1;
                        if ($resp->survey_completed == 1 || ($resp->invitation_sent == 1 && $resp->survey_completed == 0)) {
                            $data['group'][$resp->cust_2]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_3, $data['position'])) {
                        $data['position'][$resp->cust_3] = $tmpAry;
                        $data['position'][$resp->cust_3]['field'] = $resp->cust_3;
                        if ($resp->survey_completed == 1 || ($resp->invitation_sent == 1 && $resp->survey_completed == 0)) {
                            $data['position'][$resp->cust_3]['complete_num'] = 1;
                        }
                    } else {
                        $data['position'][$resp->cust_3]['invite_num'] += 1;
                        if ($resp->survey_completed == 1 || ($resp->invitation_sent == 1 && $resp->survey_completed == 0)) {
                            $data['position'][$resp->cust_3]['complete_num'] += 1;
                        }
                    }
                }
                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                foreach ($data['category'] as $item) {
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        $item['percent'] = round($item['complete_num'] / $item['invite_num'] * 100);
                        array_push($tmpAry, $item);
                    }
                }
                $keys = array_column($tmpAry, 'percent');
                array_multisort($keys, SORT_ASC, $tmpAry);
                if ($tmp['invite_num'] == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $tmp['invite_num'] * 100);
                }
                array_push($tmpAry, $tmp);
                $data['category'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                foreach ($data['department'] as $item) {
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        $item['percent'] = round($item['complete_num'] / $item['invite_num'] * 100);
                        array_push($tmpAry, $item);
                    }
                }
                $keys = array_column($tmpAry, 'percent');
                array_multisort($keys, SORT_ASC, $tmpAry);
                if ($tmp['invite_num'] == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $tmp['invite_num'] * 100);
                }
                array_push($tmpAry, $tmp);
                $data['department'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                foreach ($data['location'] as $item) {
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        $item['percent'] = round($item['complete_num'] / $item['invite_num'] * 100);
                        array_push($tmpAry, $item);
                    }
                }
                $keys = array_column($tmpAry, 'percent');
                array_multisort($keys, SORT_ASC, $tmpAry);
                if ($tmp['invite_num'] == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $tmp['invite_num'] * 100);
                }
                array_push($tmpAry, $tmp);
                $data['location'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                foreach ($data['position'] as $item) {
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        $item['percent'] = round($item['complete_num'] / $item['invite_num'] * 100);
                        array_push($tmpAry, $item);
                    }
                }
                $keys = array_column($tmpAry, 'percent');
                array_multisort($keys, SORT_ASC, $tmpAry);
                if ($tmp['invite_num'] == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $tmp['invite_num'] * 100);
                }
                array_push($tmpAry, $tmp);
                $data['position'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                foreach ($data['group'] as $item) {
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        $item['percent'] = round($item['complete_num'] / $item['invite_num'] * 100);
                        array_push($tmpAry, $item);
                    }
                }
                $keys = array_column($tmpAry, 'percent');
                array_multisort($keys, SORT_ASC, $tmpAry);
                if ($tmp['invite_num'] == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $tmp['invite_num'] * 100);
                }
                array_push($tmpAry, $tmp);
                $data['group'] = $tmpAry;
                break;

            case 'invites':
                $tmpAry = array(
                    'field' => '',
                    'invite_num' => 1,
                    'complete_num' => 1
                );
                foreach ($resps as $resp) {
                    if(!array_key_exists($resp->cust_5, $data['category'])) {
                        $data['category'][$resp->cust_5] = $tmpAry;
                        $data['category'][$resp->cust_5]['field'] = $resp->cust_5;
                    } else {
                        $data['category'][$resp->cust_5]['invite_num'] += 1;
                        $data['category'][$resp->cust_5]['complete_num'] = round($data['category'][$resp->cust_5]['invite_num'] * 1.125);
                    }

                    if(!array_key_exists($resp->cust_6, $data['location'])) {
                        $data['location'][$resp->cust_6] = $tmpAry;
                        $data['location'][$resp->cust_6]['field'] = $resp->cust_6;
                    } else {
                        $data['location'][$resp->cust_6]['invite_num'] += 1;
                        $data['location'][$resp->cust_6]['complete_num'] = round($data['location'][$resp->cust_6]['invite_num'] * 1.125);
                    }

                    if(!array_key_exists($resp->cust_4, $data['department'])) {
                        $data['department'][$resp->cust_4] = $tmpAry;
                        $data['department'][$resp->cust_4]['field'] = $resp->cust_4;
                    } else {
                        $data['department'][$resp->cust_4]['invite_num'] += 1;
                        $data['department'][$resp->cust_4]['complete_num'] = round($data['department'][$resp->cust_4]['invite_num'] * 1.125);
                    }

                    if(!array_key_exists($resp->cust_2, $data['group'])) {
                        $data['group'][$resp->cust_2] = $tmpAry;
                        $data['group'][$resp->cust_2]['field'] = $resp->cust_2;
                    } else {
                        $data['group'][$resp->cust_2]['invite_num'] += 1;
                        $data['group'][$resp->cust_2]['complete_num'] = round($data['group'][$resp->cust_2]['invite_num'] * 1.125);
                    }

                    if(!array_key_exists($resp->cust_3, $data['position'])) {
                        $data['position'][$resp->cust_3] = $tmpAry;
                        $data['position'][$resp->cust_3]['field'] = $resp->cust_3;
                    } else {
                        $data['position'][$resp->cust_3]['invite_num'] += 1;
                        $data['position'][$resp->cust_3]['complete_num'] = round($data['position'][$resp->cust_3]['invite_num'] * 1.125);
                    }
                }
                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['category'], 'invite_num');
                array_multisort($keys, SORT_DESC, $data['category']);
                foreach ($data['category'] as $item) {
                    if ($begin == 1) {
                        $completed = $item['complete_num'];
                        $begin = 0;
                    }
                    $item['completed'] = $completed;
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        $item['percent'] = round($item['invite_num'] / $completed * 100);
                        array_push($tmpAry, $item);
                    }
                }
                if ($completed == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['invite_num'] / $completed * 100);
                }
                array_push($tmpAry, $tmp);
                $data['category'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['department'], 'invite_num');
                array_multisort($keys, SORT_DESC, $data['department']);
                foreach ($data['department'] as $item) {
                    if ($begin == 1) {
                        $completed = $item['complete_num'];
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        $item['percent'] = round($item['invite_num'] / $completed * 100);
                        array_push($tmpAry, $item);
                    }
                }
                if ($completed == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['invite_num'] / $completed * 100);
                }
                array_push($tmpAry, $tmp);
                $data['department'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $begin = 1;
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $keys = array_column($data['location'], 'invite_num');
                array_multisort($keys, SORT_DESC, $data['location']);
                foreach ($data['location'] as $item) {
                    if ($begin == 1) {
                        $completed = $item['complete_num'];
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        $item['percent'] = round($item['invite_num'] / $completed * 100);
                        array_push($tmpAry, $item);
                    }
                }
                if ($completed == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['invite_num'] / $completed * 100);
                }
                array_push($tmpAry, $tmp);
                $data['location'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $begin = 1;
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $keys = array_column($data['position'], 'invite_num');
                array_multisort($keys, SORT_DESC, $data['position']);
                foreach ($data['position'] as $item) {
                    if ($begin == 1) {
                        $completed = $item['complete_num'];
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        $item['percent'] = round($item['invite_num'] / $completed * 100);
                        array_push($tmpAry, $item);
                    }
                }
                if ($tmp['invite_num'] == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['invite_num'] / $completed * 100);
                }
                array_push($tmpAry, $tmp);
                $data['position'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $begin = 1;
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $keys = array_column($data['group'], 'invite_num');
                array_multisort($keys, SORT_DESC, $data['group']);
                foreach ($data['group'] as $item) {
                    if ($begin == 1) {
                        $completed = $item['complete_num'];
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        $item['percent'] = round($item['invite_num'] / $completed * 100);
                        array_push($tmpAry, $item);
                    }
                }
                if ($completed == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['invite_num'] / $completed * 100);
                }
                array_push($tmpAry, $tmp);
                $data['group'] = $tmpAry;
                break;

            case 'surveycomplete':
                $tmpAry = array(
                    'field' => '',
                    'invite_num' => 1,
                    'complete_num' => 0
                );
                foreach ($resps as $resp) {
                    if(!array_key_exists($resp->cust_5, $data['category'])) {
                        $data['category'][$resp->cust_5] = $tmpAry;
                        $data['category'][$resp->cust_5]['field'] = $resp->cust_5;
                        if ($resp->survey_completed == 1) {
                            $data['category'][$resp->cust_5]['complete_num'] = 1;
                        }
                    } else {
                        $data['category'][$resp->cust_5]['invite_num'] += 1;
                        if ($resp->survey_completed == 1) {
                            $data['category'][$resp->cust_5]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_6, $data['location'])) {
                        $data['location'][$resp->cust_6] = $tmpAry;
                        $data['location'][$resp->cust_6]['field'] = $resp->cust_6;
                        if ($resp->survey_completed == 1) {
                            $data['location'][$resp->cust_6]['complete_num'] = 1;
                        }
                    } else {
                        $data['location'][$resp->cust_6]['invite_num'] += 1;
                        if ($resp->survey_completed == 1) {
                            $data['location'][$resp->cust_6]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_4, $data['department'])) {
                        $data['department'][$resp->cust_4] = $tmpAry;
                        $data['department'][$resp->cust_4]['field'] = $resp->cust_4;
                        if ($resp->survey_completed == 1) {
                            $data['department'][$resp->cust_4]['complete_num'] = 1;
                        }
                    } else {
                        $data['department'][$resp->cust_4]['invite_num'] += 1;
                        if ($resp->survey_completed == 1) {
                            $data['department'][$resp->cust_4]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_2, $data['group'])) {
                        $data['group'][$resp->cust_2] = $tmpAry;
                        $data['group'][$resp->cust_2]['field'] = $resp->cust_2;
                        if ($resp->survey_completed == 1) {
                            $data['group'][$resp->cust_2]['complete_num'] = 1;
                        }
                    } else {
                        $data['group'][$resp->cust_2]['invite_num'] += 1;
                        if ($resp->survey_completed == 1) {
                            $data['group'][$resp->cust_2]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_3, $data['position'])) {
                        $data['position'][$resp->cust_3] = $tmpAry;
                        $data['position'][$resp->cust_3]['field'] = $resp->cust_3;
                        if ($resp->survey_completed == 1) {
                            $data['position'][$resp->cust_3]['complete_num'] = 1;
                        }
                    } else {
                        $data['position'][$resp->cust_3]['invite_num'] += 1;
                        if ($resp->survey_completed == 1) {
                            $data['position'][$resp->cust_3]['complete_num'] += 1;
                        }
                    }
                }
                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['category'], 'complete_num');
                array_multisort($keys, SORT_DESC, $data['category']);
                foreach ($data['category'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['invite_num'] * 1.125);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            $item['percent'] = round($item['complete_num'] / $invited * 100);
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['category'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['department'], 'complete_num');
                array_multisort($keys, SORT_DESC, $data['department']);
                foreach ($data['department'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['invite_num'] * 1.125);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            $item['percent'] = round($item['complete_num'] / $invited * 100);
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['department'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['location'], 'complete_num');
                array_multisort($keys, SORT_DESC, $data['location']);
                foreach ($data['location'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['invite_num'] * 1.125);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            $item['percent'] = round($item['complete_num'] / $invited * 100);
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['location'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['position'], 'complete_num');
                array_multisort($keys, SORT_DESC, $data['position']);
                foreach ($data['position'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['invite_num'] * 1.125);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            $item['percent'] = round($item['complete_num'] / $invited * 100);
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['position'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['group'], 'complete_num');
                array_multisort($keys, SORT_DESC, $data['group']);
                foreach ($data['group'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['invite_num'] * 1.125);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            $item['percent'] = round($item['complete_num'] / $invited * 100);
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['group'] = $tmpAry;

                break;

            case 'surveynotfinished':
                $tmpAry = array(
                    'field' => '',
                    'invite_num' => 1,
                    'complete_num' => 0
                );
                foreach ($resps as $resp) {
                    if(!array_key_exists($resp->cust_5, $data['category'])) {
                        $data['category'][$resp->cust_5] = $tmpAry;
                        $data['category'][$resp->cust_5]['field'] = $resp->cust_5;
                        if ($resp->survey_completed == 0 && $resp->start_dt != '' && $resp->last_dt == '') {
                            $data['category'][$resp->cust_5]['complete_num'] = 1;
                        }
                    } else {
                        $data['category'][$resp->cust_5]['invite_num'] += 1;
                        if ($resp->survey_completed == 0 && $resp->start_dt != '' && $resp->last_dt == '') {
                            $data['category'][$resp->cust_5]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_6, $data['location'])) {
                        $data['location'][$resp->cust_6] = $tmpAry;
                        $data['location'][$resp->cust_6]['field'] = $resp->cust_6;
                        if ($resp->survey_completed == 0 && $resp->start_dt != '' && $resp->last_dt == '') {
                            $data['location'][$resp->cust_6]['complete_num'] = 1;
                        }
                    } else {
                        $data['location'][$resp->cust_6]['invite_num'] += 1;
                        if ($resp->survey_completed == 0 && $resp->start_dt != '' && $resp->last_dt == '') {
                            $data['location'][$resp->cust_6]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_4, $data['department'])) {
                        $data['department'][$resp->cust_4] = $tmpAry;
                        $data['department'][$resp->cust_4]['field'] = $resp->cust_4;
                        if ($resp->survey_completed == 0 && $resp->start_dt != '' && $resp->last_dt == '') {
                            $data['department'][$resp->cust_4]['complete_num'] = 1;
                        }
                    } else {
                        $data['department'][$resp->cust_4]['invite_num'] += 1;
                        if ($resp->survey_completed == 0 && $resp->start_dt != '' && $resp->last_dt == '') {
                            $data['department'][$resp->cust_4]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_2, $data['group'])) {
                        $data['group'][$resp->cust_2] = $tmpAry;
                        $data['group'][$resp->cust_2]['field'] = $resp->cust_2;
                        if ($resp->survey_completed == 0 && $resp->start_dt != '' && $resp->last_dt == '') {
                            $data['group'][$resp->cust_2]['complete_num'] = 1;
                        }
                    } else {
                        $data['group'][$resp->cust_2]['invite_num'] += 1;
                        if ($resp->survey_completed == 0 && $resp->start_dt != '' && $resp->last_dt == '') {
                            $data['group'][$resp->cust_2]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_3, $data['position'])) {
                        $data['position'][$resp->cust_3] = $tmpAry;
                        $data['position'][$resp->cust_3]['field'] = $resp->cust_3;
                        if ($resp->survey_completed == 0 && $resp->start_dt != '' && $resp->last_dt == '') {
                            $data['position'][$resp->cust_3]['complete_num'] = 1;
                        }
                    } else {
                        $data['position'][$resp->cust_3]['invite_num'] += 1;
                        if ($resp->survey_completed == 0 && $resp->start_dt != '' && $resp->last_dt == '') {
                            $data['position'][$resp->cust_3]['complete_num'] += 1;
                        }
                    }
                }
                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['category'], 'complete_num');
                array_multisort($keys, SORT_DESC, $data['category']);
                foreach ($data['category'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['complete_num'] * 1.125);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['complete_num'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['category'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['department'], 'complete_num');
                array_multisort($keys, SORT_DESC, $data['department']);
                foreach ($data['department'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['complete_num'] * 1.125);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['complete_num'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['department'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['location'], 'complete_num');
                array_multisort($keys, SORT_DESC, $data['location']);
                foreach ($data['location'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['complete_num'] * 1.125);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['complete_num'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['location'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['position'], 'complete_num');
                array_multisort($keys, SORT_DESC, $data['position']);
                foreach ($data['position'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['complete_num'] * 1.125);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['complete_num'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['position'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['group'], 'complete_num');
                array_multisort($keys, SORT_DESC, $data['group']);
                foreach ($data['group'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['complete_num'] * 1.125);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['complete_num'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['group'] = $tmpAry;

                break;

            case 'surveynotstarted':
                $tmpAry = array(
                    'field' => '',
                    'invite_num' => 1,
                    'complete_num' => 0
                );
                foreach ($resps as $resp) {
                    if(!array_key_exists($resp->cust_5, $data['category'])) {
                        $data['category'][$resp->cust_5] = $tmpAry;
                        $data['category'][$resp->cust_5]['field'] = $resp->cust_5;
                        if ($resp->survey_completed == 0 && $resp->start_dt == '') {
                            $data['category'][$resp->cust_5]['complete_num'] = 1;
                        }
                    } else {
                        $data['category'][$resp->cust_5]['invite_num'] += 1;
                        if ($resp->survey_completed == 0 && $resp->start_dt == '') {
                            $data['category'][$resp->cust_5]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_6, $data['location'])) {
                        $data['location'][$resp->cust_6] = $tmpAry;
                        $data['location'][$resp->cust_6]['field'] = $resp->cust_6;
                        if ($resp->survey_completed == 0 && $resp->start_dt == '') {
                            $data['location'][$resp->cust_6]['complete_num'] = 1;
                        }
                    } else {
                        $data['location'][$resp->cust_6]['invite_num'] += 1;
                        if ($resp->survey_completed == 0 && $resp->start_dt == '') {
                            $data['location'][$resp->cust_6]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_4, $data['department'])) {
                        $data['department'][$resp->cust_4] = $tmpAry;
                        $data['department'][$resp->cust_4]['field'] = $resp->cust_4;
                        if ($resp->survey_completed == 0 && $resp->start_dt == '') {
                            $data['department'][$resp->cust_4]['complete_num'] = 1;
                        }
                    } else {
                        $data['department'][$resp->cust_4]['invite_num'] += 1;
                        if ($resp->survey_completed == 0 && $resp->start_dt == '') {
                            $data['department'][$resp->cust_4]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_2, $data['group'])) {
                        $data['group'][$resp->cust_2] = $tmpAry;
                        $data['group'][$resp->cust_2]['field'] = $resp->cust_2;
                        if ($resp->survey_completed == 0 && $resp->start_dt == '') {
                            $data['group'][$resp->cust_2]['complete_num'] = 1;
                        }
                    } else {
                        $data['group'][$resp->cust_2]['invite_num'] += 1;
                        if ($resp->survey_completed == 0 && $resp->start_dt == '') {
                            $data['group'][$resp->cust_2]['complete_num'] += 1;
                        }
                    }

                    if(!array_key_exists($resp->cust_3, $data['position'])) {
                        $data['position'][$resp->cust_3] = $tmpAry;
                        $data['position'][$resp->cust_3]['field'] = $resp->cust_3;
                        if ($resp->survey_completed == 0 && $resp->start_dt == '') {
                            $data['position'][$resp->cust_3]['complete_num'] = 1;
                        }
                    } else {
                        $data['position'][$resp->cust_3]['invite_num'] += 1;
                        if ($resp->survey_completed == 0 && $resp->start_dt == '') {
                            $data['position'][$resp->cust_3]['complete_num'] += 1;
                        }
                    }
                }
                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['category'], 'complete_num');
                array_multisort($keys, SORT_DESC, $data['category']);
                foreach ($data['category'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['complete_num'] * 1.125);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['complete_num'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['category'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['department'], 'complete_num');
                array_multisort($keys, SORT_DESC, $data['department']);
                foreach ($data['department'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['complete_num'] * 1.125);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['complete_num'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['department'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['location'], 'complete_num');
                array_multisort($keys, SORT_DESC, $data['location']);
                foreach ($data['location'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['complete_num'] * 1.125);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['complete_num'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['location'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['position'], 'complete_num');
                array_multisort($keys, SORT_DESC, $data['position']);
                foreach ($data['position'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['complete_num'] * 1.125);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['complete_num'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['position'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['field'] = '';
                $tmp['invite_num'] = 0;
                $tmp['complete_num'] = 0;
                $begin = 1;
                $keys = array_column($data['group'], 'complete_num');
                array_multisort($keys, SORT_DESC, $data['group']);
                foreach ($data['group'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['complete_num'] * 1.125);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['complete_num'] += $item['complete_num'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['complete_num'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['complete_num'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['group'] = $tmpAry;

                break;

            case 'costtofirminvite':
                $tmpAry = array(
                    'field' => '',
                    'invite_num' => 1,
                    'cost' => 0
                );
                foreach ($resps as $resp) {
                    if(!array_key_exists($resp->cust_5, $data['category'])) {
                        $data['category'][$resp->cust_5] = $tmpAry;
                        $data['category'][$resp->cust_5]['field'] = $resp->cust_5;
                        $data['category'][$resp->cust_5]['cost'] = $resp->resp_compensation;
                    } else {
                        $data['category'][$resp->cust_5]['invite_num'] += 1;
                        $data['category'][$resp->cust_5]['cost'] += $resp->resp_compensation;
                    }

                    if(!array_key_exists($resp->cust_6, $data['location'])) {
                        $data['location'][$resp->cust_6] = $tmpAry;
                        $data['location'][$resp->cust_6]['field'] = $resp->cust_6;
                        $data['location'][$resp->cust_6]['cost'] = $resp->resp_compensation;
                    } else {
                        $data['location'][$resp->cust_6]['invite_num'] += 1;
                        $data['location'][$resp->cust_6]['cost'] += $resp->resp_compensation;
                    }

                    if(!array_key_exists($resp->cust_4, $data['department'])) {
                        $data['department'][$resp->cust_4] = $tmpAry;
                        $data['department'][$resp->cust_4]['field'] = $resp->cust_4;
                        $data['department'][$resp->cust_4]['cost'] = $resp->resp_compensation;
                    } else {
                        $data['department'][$resp->cust_4]['invite_num'] += 1;
                        $data['department'][$resp->cust_4]['cost'] += $resp->resp_compensation;
                    }

                    if(!array_key_exists($resp->cust_2, $data['group'])) {
                        $data['group'][$resp->cust_2] = $tmpAry;
                        $data['group'][$resp->cust_2]['field'] = $resp->cust_2;
                        $data['group'][$resp->cust_2]['cost'] = $resp->resp_compensation;
                    } else {
                        $data['group'][$resp->cust_2]['invite_num'] += 1;
                        $data['group'][$resp->cust_2]['cost'] += $resp->resp_compensation;
                    }

                    if(!array_key_exists($resp->cust_3, $data['position'])) {
                        $data['position'][$resp->cust_3] = $tmpAry;
                        $data['position'][$resp->cust_3]['field'] = $resp->cust_3;
                        $data['position'][$resp->cust_3]['cost'] = $resp->resp_compensation;
                    } else {
                        $data['position'][$resp->cust_3]['invite_num'] += 1;
                        $data['position'][$resp->cust_3]['cost'] += $resp->resp_compensation;
                    }
                }
                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['category'], 'cost');
                array_multisort($keys, SORT_DESC, $data['category']);
                foreach ($data['category'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['cost'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['category'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['department'], 'cost');
                array_multisort($keys, SORT_DESC, $data['department']);
                foreach ($data['department'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['cost'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['department'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['location'], 'cost');
                array_multisort($keys, SORT_DESC, $data['location']);
                foreach ($data['location'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['cost'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['location'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['position'], 'cost');
                array_multisort($keys, SORT_DESC, $data['position']);
                foreach ($data['position'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['cost'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['position'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['group'], 'cost');
                array_multisort($keys, SORT_DESC, $data['group']);
                foreach ($data['group'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['cost'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['group'] = $tmpAry;

                break;

            case 'costtofirmparticipants':
                $tmpAry = array(
                    'field' => '',
                    'invite_num' => 1,
                    'cost' => 0
                );
                foreach ($resps as $resp) {
                    if(!array_key_exists($resp->cust_5, $data['category'])) {
                        $data['category'][$resp->cust_5] = $tmpAry;
                        $data['category'][$resp->cust_5]['field'] = $resp->cust_5;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['category'][$resp->cust_5]['cost'] = $resp->resp_compensation;
                        }
                    } else {
                        $data['category'][$resp->cust_5]['invite_num'] += 1;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['category'][$resp->cust_5]['cost'] += $resp->resp_compensation;
                        }
                    }

                    if(!array_key_exists($resp->cust_6, $data['location'])) {
                        $data['location'][$resp->cust_6] = $tmpAry;
                        $data['location'][$resp->cust_6]['field'] = $resp->cust_6;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['location'][$resp->cust_6]['cost'] = $resp->resp_compensation;
                        }
                    } else {
                        $data['location'][$resp->cust_6]['invite_num'] += 1;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['location'][$resp->cust_6]['cost'] += $resp->resp_compensation;
                        }
                    }

                    if(!array_key_exists($resp->cust_4, $data['department'])) {
                        $data['department'][$resp->cust_4] = $tmpAry;
                        $data['department'][$resp->cust_4]['field'] = $resp->cust_4;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['department'][$resp->cust_4]['cost'] = $resp->resp_compensation;
                        }
                    } else {
                        $data['department'][$resp->cust_4]['invite_num'] += 1;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['department'][$resp->cust_4]['cost'] += $resp->resp_compensation;
                        }
                    }

                    if(!array_key_exists($resp->cust_2, $data['group'])) {
                        $data['group'][$resp->cust_2] = $tmpAry;
                        $data['group'][$resp->cust_2]['field'] = $resp->cust_2;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['group'][$resp->cust_2]['cost'] = $resp->resp_compensation;
                        }
                    } else {
                        $data['group'][$resp->cust_2]['invite_num'] += 1;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['group'][$resp->cust_2]['cost'] += $resp->resp_compensation;
                        }
                    }

                    if(!array_key_exists($resp->cust_3, $data['position'])) {
                        $data['position'][$resp->cust_3] = $tmpAry;
                        $data['position'][$resp->cust_3]['field'] = $resp->cust_3;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['position'][$resp->cust_3]['cost'] = $resp->resp_compensation;
                        }
                    } else {
                        $data['position'][$resp->cust_3]['invite_num'] += 1;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['position'][$resp->cust_3]['cost'] += $resp->resp_compensation;
                        }
                    }
                }
                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['category'], 'cost');
                array_multisort($keys, SORT_DESC, $data['category']);
                foreach ($data['category'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['cost'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['category'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['department'], 'cost');
                array_multisort($keys, SORT_DESC, $data['department']);
                foreach ($data['department'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['cost'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['department'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['location'], 'cost');
                array_multisort($keys, SORT_DESC, $data['location']);
                foreach ($data['location'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['cost'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['location'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['position'], 'cost');
                array_multisort($keys, SORT_DESC, $data['position']);
                foreach ($data['position'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['cost'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['position'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['group'], 'cost');
                array_multisort($keys, SORT_DESC, $data['group']);
                foreach ($data['group'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['cost'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['group'] = $tmpAry;

                break;

            case 'percentinvite':
                $tmpAry = array(
                    'field' => '',
                    'invite_num' => 1,
                    'cost' => 0
                );
                $total_cost = 0;
                foreach ($resps as $resp) {
                    if(!array_key_exists($resp->cust_5, $data['category'])) {
                        $data['category'][$resp->cust_5] = $tmpAry;
                        $data['category'][$resp->cust_5]['field'] = $resp->cust_5;
                        $data['category'][$resp->cust_5]['cost'] = $resp->resp_compensation;
                    } else {
                        $data['category'][$resp->cust_5]['invite_num'] += 1;
                        $data['category'][$resp->cust_5]['cost'] += $resp->resp_compensation;
                    }

                    if(!array_key_exists($resp->cust_6, $data['location'])) {
                        $data['location'][$resp->cust_6] = $tmpAry;
                        $data['location'][$resp->cust_6]['field'] = $resp->cust_6;
                        $data['location'][$resp->cust_6]['cost'] = $resp->resp_compensation;
                    } else {
                        $data['location'][$resp->cust_6]['invite_num'] += 1;
                        $data['location'][$resp->cust_6]['cost'] += $resp->resp_compensation;
                    }

                    if(!array_key_exists($resp->cust_4, $data['department'])) {
                        $data['department'][$resp->cust_4] = $tmpAry;
                        $data['department'][$resp->cust_4]['field'] = $resp->cust_4;
                        $data['department'][$resp->cust_4]['cost'] = $resp->resp_compensation;
                    } else {
                        $data['department'][$resp->cust_4]['invite_num'] += 1;
                        $data['department'][$resp->cust_4]['cost'] += $resp->resp_compensation;
                    }

                    if(!array_key_exists($resp->cust_2, $data['group'])) {
                        $data['group'][$resp->cust_2] = $tmpAry;
                        $data['group'][$resp->cust_2]['field'] = $resp->cust_2;
                        $data['group'][$resp->cust_2]['cost'] = $resp->resp_compensation;
                    } else {
                        $data['group'][$resp->cust_2]['invite_num'] += 1;
                        $data['group'][$resp->cust_2]['cost'] += $resp->resp_compensation;
                    }

                    if(!array_key_exists($resp->cust_3, $data['position'])) {
                        $data['position'][$resp->cust_3] = $tmpAry;
                        $data['position'][$resp->cust_3]['field'] = $resp->cust_3;
                        $data['position'][$resp->cust_3]['cost'] = $resp->resp_compensation;
                    } else {
                        $data['position'][$resp->cust_3]['invite_num'] += 1;
                        $data['position'][$resp->cust_3]['cost'] += $resp->resp_compensation;
                    }

                    $total_cost += $resp->resp_compensation;
                }
                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['category'], 'cost');
                array_multisort($keys, SORT_DESC, $data['category']);
                foreach ($data['category'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($total_cost == 0) {
                            $item['percentVal'] = 0;
                        } else {
                            $item['percentVal'] = round($item['cost'] / $total_cost * 100);
                        }
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['cost'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($total_cost == 0) {
                    $tmp['percentVal'] = 0;
                } else {
                    $tmp['percentVal'] = round($tmp['cost'] / $total_cost * 100);
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['category'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['department'], 'cost');
                array_multisort($keys, SORT_DESC, $data['department']);
                foreach ($data['department'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($total_cost == 0) {
                            $item['percentVal'] = 0;
                        } else {
                            $item['percentVal'] = round($item['cost'] / $total_cost * 100);
                        }
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['cost'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($total_cost == 0) {
                    $tmp['percentVal'] = 0;
                } else {
                    $tmp['percentVal'] = round($tmp['cost'] / $total_cost * 100);
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['department'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['location'], 'cost');
                array_multisort($keys, SORT_DESC, $data['location']);
                foreach ($data['location'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($total_cost == 0) {
                            $item['percentVal'] = 0;
                        } else {
                            $item['percentVal'] = round($item['cost'] / $total_cost * 100);
                        }
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['cost'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($total_cost == 0) {
                    $tmp['percentVal'] = 0;
                } else {
                    $tmp['percentVal'] = round($tmp['cost'] / $total_cost * 100);
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['location'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['position'], 'cost');
                array_multisort($keys, SORT_DESC, $data['position']);
                foreach ($data['position'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($total_cost == 0) {
                            $item['percentVal'] = 0;
                        } else {
                            $item['percentVal'] = round($item['cost'] / $total_cost * 100);
                        }
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['cost'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($total_cost == 0) {
                    $tmp['percentVal'] = 0;
                } else {
                    $tmp['percentVal'] = round($tmp['cost'] / $total_cost * 100);
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['position'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['group'], 'cost');
                array_multisort($keys, SORT_DESC, $data['group']);
                foreach ($data['group'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($total_cost == 0) {
                            $item['percentVal'] = 0;
                        } else {
                            $item['percentVal'] = round($item['cost'] / $total_cost * 100);
                        }
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['cost'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($total_cost == 0) {
                    $tmp['percentVal'] = 0;
                } else {
                    $tmp['percentVal'] = round($tmp['cost'] / $total_cost * 100);
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['group'] = $tmpAry;

                break;

            case 'percentparticipants':
                $tmpAry = array(
                    'field' => '',
                    'invite_num' => 1,
                    'cost' => 0
                );
                $total_cost = 0;
                foreach ($resps as $resp) {
                    if(!array_key_exists($resp->cust_5, $data['category'])) {
                        $data['category'][$resp->cust_5] = $tmpAry;
                        $data['category'][$resp->cust_5]['field'] = $resp->cust_5;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['category'][$resp->cust_5]['cost'] = $resp->resp_compensation;
                        }
                    } else {
                        $data['category'][$resp->cust_5]['invite_num'] += 1;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['category'][$resp->cust_5]['cost'] += $resp->resp_compensation;
                        }
                    }

                    if(!array_key_exists($resp->cust_6, $data['location'])) {
                        $data['location'][$resp->cust_6] = $tmpAry;
                        $data['location'][$resp->cust_6]['field'] = $resp->cust_6;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['location'][$resp->cust_6]['cost'] = $resp->resp_compensation;
                        }
                    } else {
                        $data['location'][$resp->cust_6]['invite_num'] += 1;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['location'][$resp->cust_6]['cost'] += $resp->resp_compensation;
                        }
                    }

                    if(!array_key_exists($resp->cust_4, $data['department'])) {
                        $data['department'][$resp->cust_4] = $tmpAry;
                        $data['department'][$resp->cust_4]['field'] = $resp->cust_4;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['department'][$resp->cust_4]['cost'] = $resp->resp_compensation;
                        }
                    } else {
                        $data['department'][$resp->cust_4]['invite_num'] += 1;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['department'][$resp->cust_4]['cost'] += $resp->resp_compensation;
                        }
                    }

                    if(!array_key_exists($resp->cust_2, $data['group'])) {
                        $data['group'][$resp->cust_2] = $tmpAry;
                        $data['group'][$resp->cust_2]['field'] = $resp->cust_2;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['group'][$resp->cust_2]['cost'] = $resp->resp_compensation;
                        }
                    } else {
                        $data['group'][$resp->cust_2]['invite_num'] += 1;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['group'][$resp->cust_2]['cost'] += $resp->resp_compensation;
                        }
                    }

                    if(!array_key_exists($resp->cust_3, $data['position'])) {
                        $data['position'][$resp->cust_3] = $tmpAry;
                        $data['position'][$resp->cust_3]['field'] = $resp->cust_3;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['position'][$resp->cust_3]['cost'] = $resp->resp_compensation;
                        }
                    } else {
                        $data['position'][$resp->cust_3]['invite_num'] += 1;
                        if ($resp->start_dt != '' && $resp->last_dt != '') {
                            $data['position'][$resp->cust_3]['cost'] += $resp->resp_compensation;
                        }
                    }

                    $total_cost += $resp->resp_compensation;
                }

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['category'], 'cost');
                array_multisort($keys, SORT_DESC, $data['category']);
                foreach ($data['category'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($total_cost == 0) {
                            $item['percentVal'] = 0;
                        } else {
                            $item['percentVal'] = round($item['cost'] / $total_cost * 100);
                        }
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            if ($invited == 0) {
                                $item['percent'] = 0;
                            } else {
                                $item['percent'] = round($item['cost'] / $invited * 100);
                            }
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($total_cost == 0) {
                    $tmp['percentVal'] = 0;
                } else {
                    $tmp['percentVal'] = round($tmp['cost'] / $total_cost * 100);
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['category'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['department'], 'cost');
                array_multisort($keys, SORT_DESC, $data['department']);
                foreach ($data['department'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($total_cost == 0) {
                            $item['percentVal'] = 0;
                        } else {
                            $item['percentVal'] = round($item['cost'] / $total_cost * 100);
                        }
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            $item['percent'] = round($item['cost'] / $invited * 100);
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($total_cost == 0) {
                    $tmp['percentVal'] = 0;
                } else {
                    $tmp['percentVal'] = round($tmp['cost'] / $total_cost * 100);
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['department'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['location'], 'cost');
                array_multisort($keys, SORT_DESC, $data['location']);
                foreach ($data['location'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($total_cost == 0) {
                            $item['percentVal'] = 0;
                        } else {
                            $item['percentVal'] = round($item['cost'] / $total_cost * 100);
                        }
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            $item['percent'] = round($item['cost'] / $invited * 100);
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($total_cost == 0) {
                    $tmp['percentVal'] = 0;
                } else {
                    $tmp['percentVal'] = round($tmp['cost'] / $total_cost * 100);
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['location'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['position'], 'cost');
                array_multisort($keys, SORT_DESC, $data['position']);
                foreach ($data['position'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($total_cost == 0) {
                            $item['percentVal'] = 0;
                        } else {
                            $item['percentVal'] = round($item['cost'] / $total_cost * 100);
                        }
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            $item['percent'] = round($item['cost'] / $invited * 100);
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($total_cost == 0) {
                    $tmp['percentVal'] = 0;
                } else {
                    $tmp['percentVal'] = round($tmp['cost'] / $total_cost * 100);
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['position'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['cost'] = 0;
                $begin = 1;
                $keys = array_column($data['group'], 'cost');
                array_multisort($keys, SORT_DESC, $data['group']);
                foreach ($data['group'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['cost'] * 1.3);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['cost'] += $item['cost'];
                    } else {
                        if ($total_cost == 0) {
                            $item['percentVal'] = 0;
                        } else {
                            $item['percentVal'] = round($item['cost'] / $total_cost * 100);
                        }
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            $item['percent'] = round($item['cost'] / $invited * 100);
                        }
                        array_push($tmpAry, $item);
                    }
                }
                if ($total_cost == 0) {
                    $tmp['percentVal'] = 0;
                } else {
                    $tmp['percentVal'] = round($tmp['cost'] / $total_cost * 100);
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['cost'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $data['group'] = $tmpAry;

                break;

            case 'avgannualhours':
                $tmpAry = array(
                    'field' => '',
                    'invite_num' => 1,
                    'hours' => 0
                );
                $total_hours = 0;
                foreach ($resps as $resp) {
                    if(!array_key_exists($resp->cust_5, $data['category'])) {
                        $data['category'][$resp->cust_5] = $tmpAry;
                        $data['category'][$resp->cust_5]['field'] = $resp->cust_5;
                        if ($resp->start_dt != '') {
                            $data['category'][$resp->cust_5]['hours'] = $resp->legal_hours + $resp->support_hours;
                        }
                    } else {
                        $data['category'][$resp->cust_5]['invite_num'] += 1;
                        if ($resp->start_dt != '') {
                            $data['category'][$resp->cust_5]['hours'] += $resp->legal_hours + $resp->support_hours;
                        }
                    }

                    if(!array_key_exists($resp->cust_6, $data['location'])) {
                        $data['location'][$resp->cust_6] = $tmpAry;
                        $data['location'][$resp->cust_6]['field'] = $resp->cust_6;
                        if ($resp->start_dt != '') {
                            $data['location'][$resp->cust_6]['hours'] = $resp->legal_hours + $resp->support_hours;
                        }
                    } else {
                        $data['location'][$resp->cust_6]['invite_num'] += 1;
                        if ($resp->start_dt != '') {
                            $data['location'][$resp->cust_6]['hours'] += $resp->legal_hours + $resp->support_hours;
                        }
                    }

                    if(!array_key_exists($resp->cust_4, $data['department'])) {
                        $data['department'][$resp->cust_4] = $tmpAry;
                        $data['department'][$resp->cust_4]['field'] = $resp->cust_4;
                        if ($resp->start_dt != '') {
                            $data['department'][$resp->cust_4]['hours'] = $resp->legal_hours + $resp->support_hours;
                        }
                    } else {
                        $data['department'][$resp->cust_4]['invite_num'] += 1;
                        if ($resp->start_dt != '') {
                            $data['department'][$resp->cust_4]['hours'] += $resp->legal_hours + $resp->support_hours;
                        }
                    }

                    if(!array_key_exists($resp->cust_2, $data['group'])) {
                        $data['group'][$resp->cust_2] = $tmpAry;
                        $data['group'][$resp->cust_2]['field'] = $resp->cust_2;
                        if ($resp->start_dt != '') {
                            $data['group'][$resp->cust_2]['hours'] = $resp->legal_hours + $resp->support_hours;
                        }
                    } else {
                        $data['group'][$resp->cust_2]['invite_num'] += 1;
                        if ($resp->start_dt != '') {
                            $data['group'][$resp->cust_2]['hours'] += $resp->legal_hours + $resp->support_hours;
                        }
                    }

                    if(!array_key_exists($resp->cust_3, $data['position'])) {
                        $data['position'][$resp->cust_3] = $tmpAry;
                        $data['position'][$resp->cust_3]['field'] = $resp->cust_3;
                        if ($resp->start_dt != '') {
                            $data['position'][$resp->cust_3]['hours'] = $resp->legal_hours + $resp->support_hours;
                        }
                    } else {
                        $data['position'][$resp->cust_3]['invite_num'] += 1;
                        if ($resp->start_dt != '') {
                            $data['position'][$resp->cust_3]['hours'] += $resp->legal_hours + $resp->support_hours;
                        }
                    }

                    $total_hours += $resp->legal_hours + $resp->support_hours;
                }

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['hours'] = 0;
                $begin = 1;
                $keys = array_column($data['category'], 'hours');
                array_multisort($keys, SORT_DESC, $data['category']);
                foreach ($data['category'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['hours'] * 1.3 / $item['invite_num']);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['hours'] += $item['hours'];
                    } else {
                        $item['hours'] = round($item['hours'] / $item['invite_num']);
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            $item['percent'] = round($item['hours'] / $invited * 100);
                        }
                        array_push($tmpAry, $item);
                    }
                }
                $keys = array_column($tmpAry, 'hours');
                array_multisort($keys, SORT_DESC, $tmpAry);
                if ($tmp['invite_num'] == 0) {
                    $tmp['hours'] = 0;
                } else {
                    $tmp['hours'] = round($tmp['hours'] / $tmp['invite_num']);
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['hours'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $abegin = 1;
                foreach ($tmpAry as $key => $item) {
                    if ($abegin == 1) {
                        $flag_hours = round($item['hours'] * 1.3);
                        $abegin = 0;
                    }
                    $tmpAry[$key]['hours'] = number_format($tmpAry[$key]['hours']);
                    if ($flag_hours == 0) {
                        $tmpAry[$key]['percent'] = 0;
                    } else {                        
                        $tmpAry[$key]['percent'] = round($item['hours'] / $flag_hours * 100);
                    }
                }
                $data['category'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['hours'] = 0;
                $begin = 1;
                $keys = array_column($data['department'], 'hours');
                array_multisort($keys, SORT_DESC, $data['department']);
                foreach ($data['department'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['hours'] * 1.3 / $item['invite_num']);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['hours'] += $item['hours'];
                    } else {
                        if ($item['invite_num'] == 0) {
                            $item['hours'] = 0;
                        } else {
                            $item['hours'] = round($item['hours'] / $item['invite_num']);
                        }
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            $item['percent'] = round($item['hours'] / $invited * 100);
                        }
                        array_push($tmpAry, $item);
                    }
                }
                $keys = array_column($tmpAry, 'hours');
                array_multisort($keys, SORT_DESC, $tmpAry);
                if ($tmp['invite_num'] == 0) {
                    $tmp['hours'] = 0;
                } else {
                    $tmp['hours'] = round($tmp['hours'] / $tmp['invite_num']);
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['hours'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $abegin = 1;
                foreach ($tmpAry as $key => $item) {
                    if ($abegin == 1) {
                        $flag_hours = round($item['hours'] * 1.3);
                        $abegin = 0;
                    }
                    $tmpAry[$key]['hours'] = number_format($tmpAry[$key]['hours']);
                    if ($flag_hours == 0) {
                        $tmpAry[$key]['percent'] = 0;
                    } else {                        
                        $tmpAry[$key]['percent'] = round($item['hours'] / $flag_hours * 100);
                    }
                }
                $data['department'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['hours'] = 0;
                $begin = 1;
                $keys = array_column($data['location'], 'hours');
                array_multisort($keys, SORT_DESC, $data['location']);
                foreach ($data['location'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['hours'] * 1.3 / $item['invite_num']);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['hours'] += $item['hours'];
                    } else {
                        if ($item['invite_num'] == 0) {
                            $item['hours'] = 0;
                        } else {
                            $item['hours'] = round($item['hours'] / $item['invite_num']);
                        }
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            $item['percent'] = round($item['hours'] / $invited * 100);
                        }
                        array_push($tmpAry, $item);
                    }
                }
                $keys = array_column($tmpAry, 'hours');
                array_multisort($keys, SORT_DESC, $tmpAry);
                if ($tmp['invite_num'] == 0) {
                    $tmp['hours'] = 0;
                } else {
                    $tmp['hours'] = round($tmp['hours'] / $tmp['invite_num']);
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['hours'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $abegin = 1;
                foreach ($tmpAry as $key => $item) {
                    if ($abegin == 1) {
                        $flag_hours = round($item['hours'] * 1.3);
                        $abegin = 0;
                    }
                    $tmpAry[$key]['hours'] = number_format($tmpAry[$key]['hours']);
                    if ($flag_hours == 0) {
                        $tmpAry[$key]['percent'] = 0;
                    } else {                        
                        $tmpAry[$key]['percent'] = round($item['hours'] / $flag_hours * 100);
                    }
                }
                $data['location'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['hours'] = 0;
                $begin = 1;
                $keys = array_column($data['position'], 'hours');
                array_multisort($keys, SORT_DESC, $data['position']);
                foreach ($data['position'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['hours'] * 1.3 / $item['invite_num']);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['hours'] += $item['hours'];
                    } else {
                        if ($item['invite_num'] == 0) {
                            $item['hours'] = 0;
                        } else {
                            $item['hours'] = round($item['hours'] / $item['invite_num']);
                        }
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            $item['percent'] = round($item['hours'] / $invited * 100);
                        }
                        array_push($tmpAry, $item);
                    }
                }
                $keys = array_column($tmpAry, 'hours');
                array_multisort($keys, SORT_DESC, $tmpAry);
                if ($tmp['invite_num'] == 0) {
                    $tmp['hours'] = 0;
                } else {
                    $tmp['hours'] = round($tmp['hours'] / $tmp['invite_num']);
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['hours'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $abegin = 1;
                foreach ($tmpAry as $key => $item) {
                    if ($abegin == 1) {
                        $flag_hours = round($item['hours'] * 1.3);
                        $abegin = 0;
                    }
                    $tmpAry[$key]['hours'] = number_format($tmpAry[$key]['hours']);
                    if ($flag_hours == 0) {
                        $tmpAry[$key]['percent'] = 0;
                    } else {                        
                        $tmpAry[$key]['percent'] = round($item['hours'] / $flag_hours * 100);
                    }
                }
                $data['position'] = $tmpAry;

                $tmpAry = array();
                $tmp = array();
                $tmp['invite_num'] = 0;
                $tmp['hours'] = 0;
                $begin = 1;
                $keys = array_column($data['group'], 'hours');
                array_multisort($keys, SORT_DESC, $data['group']);
                foreach ($data['group'] as $item) {
                    if ($begin == 1) {
                        $invited = round($item['hours'] * 1.3 / $item['invite_num']);
                        $begin = 0;
                    }
                    if ($item['invite_num'] < $min_rates) {
                        $tmp['field'] = 'xothers';
                        $tmp['invite_num'] += $item['invite_num'];
                        $tmp['hours'] += $item['hours'];
                    } else {
                        if ($item['invite_num'] == 0) {
                            $item['hours'] = 0;
                        } else {
                            $item['hours'] = round($item['hours'] / $item['invite_num']);
                        }
                        if ($invited == 0) {
                            $item['percent'] = 0;
                        } else {
                            $item['percent'] = round($item['hours'] / $invited * 100);
                        }
                        array_push($tmpAry, $item);
                    }
                }
                $keys = array_column($tmpAry, 'hours');
                array_multisort($keys, SORT_DESC, $tmpAry);
                if ($tmp['invite_num'] == 0) {
                    $tmp['hours'] = 0;
                } else {
                    $tmp['hours'] = round($tmp['hours'] / $tmp['invite_num']);
                }
                if ($invited == 0) {
                    $tmp['percent'] = 0;
                } else {
                    $tmp['percent'] = round($tmp['hours'] / $invited * 100);
                }
                array_push($tmpAry, $tmp);
                $abegin = 1;
                foreach ($tmpAry as $key => $item) {
                    if ($abegin == 1) {
                        $flag_hours = round($item['hours'] * 1.3);
                        $abegin = 0;
                    }
                    $tmpAry[$key]['hours'] = number_format($tmpAry[$key]['hours']);
                    if ($flag_hours == 0) {
                        $tmpAry[$key]['percent'] = 0;
                    } else {                        
                        $tmpAry[$key]['percent'] = round($item['hours'] / $flag_hours * 100);
                    }
                }
                $data['group'] = $tmpAry;

                break;

            default:
                # code...
                break;
        }
        
        return $data;
    }
    /**
     * 
     * Returns the data of respondents
     * 
     * @param int $survey_id    survey id
     * @param array $filter_ary     filter variables for respondents
     * @param int $demographic_usage    flag variable to separate the demographic report
     * @param string $search_key    search key variable for respondents
     * @return \App\Models\Respondent 
     */
    
    
    
    public function get_resp_data($survey_id, $filter_ary = array(), $demographic_usage = 0, $search_key = "")
    {
        
        // This query will tell us if there is a legal taxonomy or not. There should always be a support taxonomy but on some projects, there will not
        // be a legal. We need to know that so we then know how to get our data.
        $parent = static::getSurveyTaxonomy($survey_id);
        $tmp_legal = [];
        $tmp_support = [];
        
        // dd($parent,'after');
        foreach ($parent as $row) {
            if (strpos($row->question_desc, 'Legal ') !== false) {
                $tmp_legal = $this->legelSupportData($survey_id, $row->question_id);
            } else if (strpos($row->question_desc, 'Support ') !== false || strpos($row->question_desc, 'Shared ') !== false ) {
                $tmp_support = $this->legelSupportData($survey_id, $row->question_id);
            }
        }
        // dd($tmp_support,'after');
        $permissionData = new PermissionData();
        $allowed_locationAry = $permissionData->getAllowedLocationsByUser($survey_id, Auth::user());
        $allowed_deptAry = $permissionData->getAllowedDepartmentsByUser($survey_id, Auth::user());
        
        if ($demographic_usage == 0) {
            if (!empty($filter_ary)) {
                $resps = Respondent::where('survey_id', $survey_id)->whereIn('survey_completed',[1,2]);
                
                $resps->when($filter_ary['position'], function ($q) use ($filter_ary) {
                    $q->whereIn('cust_3', $filter_ary['position']);
                });
                $resps->when($filter_ary['department'], function ($q) use ($filter_ary) {
                    $q->whereIn('cust_4', $filter_ary['department']);
                });
                $resps->when($filter_ary['group'], function ($q) use ($filter_ary) {
                    $q->whereIn('cust_2', $filter_ary['group']);
                });
                $resps->when($filter_ary['location'], function ($q) use ($filter_ary) {
                    $q->whereIn('cust_6', $filter_ary['location']);
                });
                if(isset($filter_ary['category'])){
                    $resps->when($filter_ary['category'], function ($q) use ($filter_ary) {
                        $q->whereIn('cust_5', $filter_ary['category']);
                    });
                }
                  
                $resps->when($search_key, function ($q) use ($search_key) {
                    $q->where('resp_first', 'LIKE', "%$search_key%")
                      ->orWhere('resp_last', 'LIKE', "%$search_key%");
                });


                $resps = $resps->whereIn('cust_4', $allowed_deptAry)
                        ->whereIn('cust_6', $allowed_locationAry)
                        ->orderBy('resp_last', 'asc')
                        ->get();
            } else {
                if($survey_id != 54){
                    $resps = Respondent::where('survey_id', '=', $survey_id)
                    ->whereIn('survey_completed', [1,2])
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
                }else{
                    $resps = Respondent::where('survey_id', '=', $survey_id)
                    ->whereIn('survey_completed',[1,2])
                    ->where(function($q) use ($search_key) {
                        if ($search_key != '') {
                            $q->where('resp_first', 'LIKE', "%$search_key%")
                                ->orWhere('resp_last', 'LIKE', "%$search_key%");
                        }
                    })
                    ->orderBy('resp_last', 'asc')
                    ->get();
                }
               
               
            }

        } else {
            if (!empty($filter_ary)) {
                $resps = Respondent::where('survey_id', $survey_id)->whereIn('survey_completed',[1,2]);
               
                $resps->when($filter_ary['position'], function ($q) use ($filter_ary) {
                    $q->whereIn('cust_3', $filter_ary['position']);
                });
                $resps->when($filter_ary['department'], function ($q) use ($filter_ary) {
                    $q->whereIn('cust_4', $filter_ary['department']);
                });
                $resps->when($filter_ary['group'], function ($q) use ($filter_ary) {
                    $q->whereIn('cust_2', $filter_ary['group']);
                });
                $resps->when($filter_ary['location'], function ($q) use ($filter_ary) {
                    $q->whereIn('cust_6', $filter_ary['location']);
                });
                $resps->when($filter_ary['category'], function ($q) use ($filter_ary) {
                    $q->whereIn('cust_5', $filter_ary['category']);
                });
                $resps->when($search_key, function ($q) use ($search_key) {
                    $q->where('resp_first', 'LIKE', "%$search_key%")
                      ->orWhere('resp_last', 'LIKE', "%$search_key%");
                });

                $resps = $resps->whereIn('cust_4', $allowed_deptAry)
                                ->whereIn('cust_6', $allowed_locationAry)
                                ->orderBy('resp_last', 'asc')
                                ->get();
            } else {
                /* $resps = Respondent::where('survey_id', '=', $survey_id)->where('survey_completed',1)
                    ->where(function($q) use ($search_key) {
                        if ($search_key != '') {
                            $q->where('resp_first', 'LIKE', "%$search_key%")
                                ->orWhere('resp_last', 'LIKE', "%$search_key%");
                        }
                    })
                    ->whereIn('cust_4', $allowed_deptAry)
                    ->whereIn('cust_6', $allowed_locationAry)
                    ->orderBy('resp_last', 'asc')
                    ->get(); */
                if($survey_id != 54){
                    $resps = Respondent::where('survey_id', '=', $survey_id)
                    ->whereIn('survey_completed',[1,2])
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
                }else{
                    $resps = Respondent::where('survey_id', '=', $survey_id)
                    ->whereIn('survey_completed',[1,2])
                    ->where(function($q) use ($search_key) {
                        if ($search_key != '') {
                            $q->where('resp_first', 'LIKE', "%$search_key%")
                                ->orWhere('resp_last', 'LIKE', "%$search_key%");
                        }
                    })
                    ->orderBy('resp_last', 'asc')
                    ->get();
                }
            }
        }
        
       

        foreach ($resps as $resp){
            $tmp_support_hours  = 0;
            $tmp_legal_hours    = 0;
            $resp->legal_hours   = 0;
            $resp->support_hours = 0;
            if(array_key_exists($resp->resp_id, $tmp_support)){
                $resp->support_hours = $tmp_support[$resp->resp_id]['answer_value'];
                $tmp_support_hours = $resp->support_hours;
            }
            if(array_key_exists($resp->resp_id, $tmp_legal)){
                $resp->legal_hours = $tmp_legal[$resp->resp_id]['answer_value'];
                $tmp_legal_hours = $resp->legal_hours;
            }
            if ($tmp_support_hours != 0 || $tmp_legal_hours != 0) {
                $resp->hourly_rate = round($resp->resp_compensation * (1 + $resp->resp_benefit_pct) / ($tmp_support_hours + $tmp_legal_hours));
            } else {
                $resp->hourly_rate = 0;
            }
            $resp->total_hours = $tmp_support_hours + $tmp_legal_hours;
        }
        
        return $resps;
        

    }

    public function GetUniqueValues($surveyId,$column){
        return Respondent::select($column)->where('survey_id',$surveyId)->groupBy($column)->pluck($column)->toArray();
    }
    /**
     * 
     * Returns the data of answers by respondents
     * 
     * @param int $survey_id    survey id
     * @param int $question_id    question id
     * @return array 
     */
    public function legelSupportData($survey_id, $question_id)
    {
        $tmp_legal=[];
        // if($survey_id != 54){
        $legal_question = Question::where('question_id', $question_id)
        ->where('survey_id', '=', $survey_id)
        ->firstOrFail();
        $legal_answers = Answer::where('question_id', '=', $legal_question->question_id)->get();
        
       

        foreach ( $legal_answers as  $answer) {
            $tmp_legal[$answer->resp_id] = $answer;
        }

        return $tmp_legal;
    }
    /**
     * 
     * Returns the data of individual report in time and cost by questions
     * 
     * @param int $resp_id  respondent id
     * @param int $survey_id    survey id
     * @param \App\Models\Respondent $resp  respondent
     * @return array 
     */
    public function get_resp_timecost($resp_id, $survey_id, $resp)
    {
        $allData = [];

        $allData['detail_table'] = $this->getRespReportData($resp_id, $survey_id);
        $tableData = $this->getRespAnswerData($resp_id, $survey_id, $resp);
        $allData['tableData'] = $this->getRespAnswerDataByDepth($tableData);
        
        return $allData;
    }
    /**
     * 
     * Returns the data of table by question depth
     * 
     * @param int $resp_id    respondent id
     * @param int $survey_id    survey id
     * @param \App\Models\Respondent $resp  respondent
     * @param int $depth    taxonomy depth
     * @return array 
     */
    public function getRespTableByDeep ($resp_id, $survey_id, $resp, $depth) {
        $tableData = $this->getRespAnswerData($resp_id, $survey_id, $resp);
        $res['tableData'] = $this->getRespAnswerDataByDepth($tableData, $depth);
        
        return $res;
    }
    /**
     * 
     * Returns the data of report answer (time & cost) by individual respondents
     * 
     * @param int $resp_id    respondent id
     * @param int $survey_id    survey id
     * @return array 
     */
    public function getRespReportData ($resp_id, $survey_id) {
        // if($survey_id != 54){
            $parentQuery = Answer::leftJoin('tblquestion', 'tblquestion.question_id', 'tblanswer.question_id')
            ->leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->leftJoin('tblrespondent', 'tblrespondent.resp_id', 'tblanswer.resp_id')
            ->where('tblanswer.answer_value', '>', 0)
            ->where('tblanswer.resp_id', $resp_id)
            ->where('tblrespondent.survey_id', $survey_id)
            ->where('tblpage.question_id_parent', 0)
            ->select('tblquestion.question_desc',
                'tblquestion.question_id',
                'tblquestion.lead_codes',
                'tblpage.question_id_parent',
                'tblanswer.answer_value')
            ->get();
        // }else{
           /*  $parentQuery = Answer::leftJoin('tblquestionTest', 'tblquestionTest.question_id', 'tblanswer.question_id')
            ->leftJoin('tblpageTest', 'tblpageTest.page_id', 'tblquestionTest.page_id')
            ->leftJoin('tblrespondent', 'tblrespondent.resp_id', 'tblanswer.resp_id')
            ->where('tblanswer.answer_value', '>', 0)
            ->where('tblanswer.resp_id', $resp_id)
            ->where('tblrespondent.survey_id', $survey_id)
            ->where('tblpageTest.question_id_parent', 0)
            ->select('tblquestionTest.question_desc',
                'tblquestionTest.question_id',
                'tblquestionTest.lead_codes',
                'tblpageTest.question_id_parent',
                'tblanswer.answer_value')
            ->get(); */
        // }
        

        $parentIdAry = array();
        $parentAry   = array();
        foreach ($parentQuery as $tmp) {
            $parentIdAry[] = $tmp->question_id;
            $parentAry[$tmp->question_id] = $tmp;
        }
        // if($survey_id != 54){
            $child1Query = Answer::leftJoin('tblquestion', 'tblquestion.question_id', 'tblanswer.question_id')
            ->leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->leftJoin('tblrespondent', 'tblrespondent.resp_id', 'tblanswer.resp_id')
            ->where('tblanswer.answer_value', '>', 0)
            ->where('tblanswer.resp_id', $resp_id)
            ->where('tblrespondent.survey_id', $survey_id)
            ->whereIn('tblpage.question_id_parent', $parentIdAry)
            ->select('tblquestion.question_desc',
                'tblquestion.question_id',
                'tblquestion.lead_codes',
                'tblpage.question_id_parent',
                'tblanswer.answer_value')
            ->get();
        // }else{
      /*       $child1Query = Answer::leftJoin('tblquestionTest', 'tblquestionTest.question_id', 'tblanswer.question_id')
            ->leftJoin('tblpageTest', 'tblpageTest.page_id', 'tblquestionTest.page_id')
            ->leftJoin('tblrespondent', 'tblrespondent.resp_id', 'tblanswer.resp_id')
            ->where('tblanswer.answer_value', '>', 0)
            ->where('tblanswer.resp_id', $resp_id)
            ->where('tblrespondent.survey_id', $survey_id)
            ->whereIn('tblpageTest.question_id_parent', $parentIdAry)
            ->select('tblquestionTest.question_desc',
                'tblquestionTest.question_id',
                'tblquestionTest.lead_codes',
                'tblpageTest.question_id_parent',
                'tblanswer.answer_value')
            ->get();
        } */
        

        $child1IdAry = array();
        $child1Ary   = array();
        foreach ($child1Query as $tmp) {
            $child1IdAry[] = $tmp->question_id;
            $tmp->hours = round($parentAry[$tmp->question_id_parent]->answer_value * $tmp->answer_value / 100);
            $tmp->parent = $parentAry[$tmp->question_id_parent]->question_desc;
            $child1Ary[$tmp->question_id] = $tmp;
        }

        $data['child1Data'] = $child1Query;
        // if($survey_id != 54){
            $query = Answer::leftJoin('tblquestion', 'tblquestion.question_id', 'tblanswer.question_id')
            ->leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->leftJoin('tblrespondent', 'tblrespondent.resp_id', 'tblanswer.resp_id')
            ->where('tblanswer.answer_value', '>', 0)
            ->where('tblanswer.resp_id', $resp_id)
            ->where('tblrespondent.survey_id', $survey_id)
            ->whereIn('tblpage.question_id_parent', $child1IdAry)
            ->select('tblquestion.question_desc',
                'tblquestion.question_id',
                'tblpage.question_id_parent',
                'tblanswer.answer_value',
                'tblquestion.lead_codes',
                'tblquestion.question_proximity_factor AS prox_factor')
            ->get();
      /*   }else{
            $query = Answer::leftJoin('tblquestionTest', 'tblquestionTest.question_id', 'tblanswer.question_id')
            ->leftJoin('tblpageTest', 'tblpageTest.page_id', 'tblquestionTest.page_id')
            ->leftJoin('tblrespondent', 'tblrespondent.resp_id', 'tblanswer.resp_id')
            ->where('tblanswer.answer_value', '>', 0)
            ->where('tblanswer.resp_id', $resp_id)
            ->where('tblrespondent.survey_id', $survey_id)
            ->whereIn('tblpageTest.question_id_parent', $child1IdAry)
            ->select('tblquestionTest.question_desc',
                'tblquestionTest.question_id',
                'tblpageTest.question_id_parent',
                'tblanswer.answer_value',
                'tblquestionTest.lead_codes',
                'tblquestionTest.question_proximity_factor AS prox_factor')
            ->get();
        } */
      

        foreach ($query as $key => $element) {
            $query[$key]->hours = round($child1Ary[$element->question_id_parent]->hours * $element->answer_value / 100);
            $query[$key]->parent = $child1Ary[$element->question_id_parent]->question_desc;
            $query[$key]->grandParent = $parentAry[$child1Ary[$element->question_id_parent]->question_id_parent]->question_desc;
        }

        $data['categoryData'] = $query;

        return $data;
    }
    /**
     * 
     * Returns the data of respondents answer data
     * 
     * @param int $resp_id    respondent id
     * @param int $survey_id    survey id
     * @param \App\Models\Respondent $resp  respondents
     * @return array 
     */
    public function getRespAnswerData ($resp_id, $survey_id, $resp) {
        // if($survey_id != 54){
            $db_val = Answer::leftJoin('tblquestion', 'tblquestion.question_id', 'tblanswer.question_id')
            ->leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->where('tblquestion.survey_id', $survey_id)
            ->where('tblanswer.resp_id', $resp_id)
            ->where('tblanswer.answer_value', '>', 0)
            ->select('tblanswer.question_id AS id',
                'tblanswer.answer_value AS val',
                'tblquestion.lead_codes',
                'tblquestion.question_desc AS question',
                'tblpage.question_id_parent AS pid',
                'tblquestion.question_proximity_factor AS prox_factor')
            ->orderBy('tblpage.question_id_parent', 'ASC')
            ->orderBy('tblquestion.question_desc', 'ASC')
            ->get();
     /*    }else{
            $db_val = Answer::leftJoin('tblquestionTest', 'tblquestionTest.question_id', 'tblanswer.question_id')
            ->leftJoin('tblpageTest', 'tblpageTest.page_id', 'tblquestionTest.page_id')
            ->where('tblquestionTest.survey_id', $survey_id)
            ->where('tblanswer.resp_id', $resp_id)
            ->where('tblanswer.answer_value', '>', 0)
            ->select('tblanswer.question_id AS id',
                'tblanswer.answer_value AS val',
                'tblquestionTest.lead_codes',
                'tblquestionTest.question_desc AS question',
                'tblpageTest.question_id_parent AS pid',
                'tblquestionTest.question_proximity_factor AS prox_factor')
            ->orderBy('tblpageTest.question_id_parent', 'ASC')
            ->orderBy('tblquestionTest.question_desc', 'ASC')
            ->get();
        } */
        

        $tmpData = $resp->toArray();
        $total_compensation = $tmpData['resp_compensation'] * (1 + $tmpData['resp_benefit_pct']);
        $total_hours = 0;
        $return_val = array ();
        foreach ($db_val as $item) {
            if ($item->pid == 0) {
                $total_hours += $item->val;
            }
            $return_val[$item->id] = $item;
        }

        $return = array();
        foreach ($return_val as $item) {
            if ($item->pid == 0) {
                $return_val[$item->id]->percent_hour = round($item->val / $total_hours * 100);
                $return_val[$item->id]->total_hours = $total_hours;
                $return_val[$item->id]->hours = $item->val;
                $return_val[$item->id]->cost = round($total_compensation * $item->val / $total_hours);
                $return_val[$item->id]->top_parent = $return_val[$item->id]->question;
            } else {
                $pid = $item->pid;
                $return_val[$item->id]->hours = $return_val[$item->id]->val;
                do {
                    if ($pid != 0) {
                        $return_val[$item->id]->hours *= $return_val[$pid]->val / 100;
                        $return_val[$item->id]->top_parent = $return_val[$pid]->question;
                        $pid = $return_val[$pid]->pid;
                    }
                } while ($pid != 0);
                $return_val[$item->id]->total_hours = $total_hours;
                $return_val[$item->id]->hours = round($return_val[$item->id]->hours);
                $return_val[$item->id]->percent_hour = round($return_val[$item->id]->hours / $total_hours * 100);
                $return_val[$item->id]->cost = round($total_compensation * $return_val[$item->id]->hours / $total_hours);
            }
            array_push($return, $return_val[$item->id]);
        }

        return $return;
    }

    /**
     * 
     * Returns the data of respondent answers by the taxonomy depth or return the depth of current taxonomy data
     * 
     * @param \App\Models\Question $data    question data
     * @param int $depth    taxonomy depth
     * @param boolean $get_depth    flag variable to get depth of questions
     * @return array 
     */
    public function getRespAnswerDataByDepth ($data, $depth = 10, $get_depth = false) {
        $res = array();
        $questionDepth = -1;
        $pidAry = [0];

        while ($questionDepth < $depth) {
            $tmpAry = array();
            foreach ($data as $item) {
                if (in_array($item->pid, $pidAry)) {
                    $res[] = $item;
                    $tmpAry[] = $item;
                }
            }

            if (empty($tmpAry)) {
                break;
            }

            $pidAry = array();
            foreach ($tmpAry as $e) {
                $pidAry[] = $e->id;
            }
            $questionDepth++;
        }

        if ($get_depth !== false) {
            return $questionDepth;
        }

        return $res;
    }
    /**
     * 
     * Returns the data of activity by survey and question description
     * 
     * @param int $survey_id    survey id
     * @param string $type  question description
     * @return \App\Models\Question 
     */
    public function activitiesData($survey_id, $type)
    {
        $question_id =  Question::join('tblpage', 'tblpage.page_id', '=', 'tblquestion.page_id')
                        ->where('tblquestion.survey_id',$survey_id)
                        ->where('tblpage.question_id_parent',0)
                        ->whereRaw('LOWER(`question_desc`) LIKE ? ',[trim(strtolower($type)).'%'])
                        ->orderBy('tblquestion.question_desc')
                        ->first();
        return $question_id;
    }
    /**
     * 
     * Returns the data of activities with the page and answer data
     * 
     * @param int $resp_id    respondent id
     * @param int $survey_id    survey id
     * @param int $question_id    question id
     * @return \App\Models\Page 
     */
    public function activitiesPageData($resp_id, $survey_id, $question_id)
    {
        $pagedata =  Page::leftjoin('tblquestion','tblquestion.page_id','=','tblpage.page_id')
                        ->leftjoin('tblanswer','tblanswer.question_id', '=', 'tblquestion.question_id')
                        ->where('tblanswer.resp_id', '=', $resp_id)
                        ->where('tblquestion.survey_id' ,'=', $survey_id)
                        ->where('tblanswer.answer_value','>',0)
                        ->whereIn('tblpage.question_id_parent', $question_id)
                        // ->orderBy('tblquestion.question_desc')
                        ->get()->toArray();

        return $pagedata;
    }
    /**
     * 
     * Returns the array of question ids
     * 
     * @param array $array    question array=
     * @return array
     */
    public function getArrayOfQuestionId($array)
    {
        $question_id = [];
        foreach ($array as $value) {
            $question_id[] = $value['question_id'];
        }
        return $question_id;

    }
    /**
     * 
     * Returns the data of report of activities by respondents
     * 
     * @param int $resp_id    respondent id
     * @param int $survey_id    survey id
     * @param int $question_id    question id
     * @return array 
     */
    public function generateReport($resp_id, $survey_id, $question_id)
    {
        $child1_legal = $this->activitiesPageData($resp_id,$survey_id,$question_id);
        $q_id_child1 = $this->getArrayOfQuestionId($child1_legal);

        $this->tempData[]=$child1_legal;
        // dump($legalData);
        if (empty($q_id_child1)) {
            return;
        }
        return $this->generateReport($resp_id, $survey_id, $q_id_child1);
    }
    /**
     * 
     * Returns the data of filter variables
     * 
     * @param \App\Models\Respondent $respondents    respondents
     * @return array
     */
    public function getRespondentData($respondents)
    {
       // dd($respondents[0]);
        // map columns in the respondents to a key on the $data array
        $data['position'] = $respondents->pluck('cust_3')->unique()->sort()->toArray();
        $data['department'] = $respondents->pluck('cust_4')->unique()->sort()->toArray();
        $data['group'] = $respondents->pluck('cust_2')->unique()->sort()->toArray();
        $data['location'] =  $respondents->pluck('cust_6')->unique()->sort()->toArray();
        $data['category'] =  $respondents->pluck('cust_5')->unique()->sort()->toArray();

        $data['survey_status'] = [];
        //dd($data['location']);
        /* array_filter($data[4'position']);
        array_filter($data['department']);
        array_filter($data['group']);
        array_filter($data['location']);
        array_filter($data['category']);
        array_filter($data['survey_status']); */


        return $data;
    }
    /**
     * 
     * Returns the data of answers of appropriate question
     * 
     * @param \App\Models\Question $question    question
     * @return \App\Models\Answer 
     */
    public function getQuestionAnswers($question)
    {
        return Answer::with('respondent')->whereHas('respondent', function($q){
            $q->where('survey_completed', 1);
        })->where('question_id', $question->question_id)->get();

    }
    /**
     * 
     * Returns the data of activity by custom fields like position, department, and group
     * 
     * @param \App\Models\Question $question    question
     * @return array
     */
    public function getCostPerEmployee($question)
    {
        $answers = $this->getQuestionAnswers($question);

        $results = [];

        // calculate some values foreach respondent in the answers
        foreach($answers as $answer)
        {
            $respondent = $answer->respondent;
            $page = $answer->question->page;

            // add the columns if missing
            if(!isset($results[$respondent->cust_1]))
                $results[$respondent->cust_1] = ['total_hours' => 0, 'total_cost' => 0.00, 'total_employees' => 0];

            if(!isset($results[$respondent->cust_2]))
                $results[$respondent->cust_2] = ['total_hours' => 0, 'total_cost' => 0.00, 'total_employees' => 0];

            if(!isset($results[$respondent->cust_3]))
                $results[$respondent->cust_3] = ['total_hours' => 0, 'total_cost' => 0.00, 'total_employees' => 0];

            if(!isset($results[$respondent->cust_4]))
                $results[$respondent->cust_4] = ['total_hours' => 0, 'total_cost' => 0.00, 'total_employees' => 0];

            if(!isset($results[$respondent->cust_5]))
                $results[$respondent->cust_5] = ['total_hours' => 0, 'total_cost' => 0.00, 'total_employees' => 0];

            if(!isset($results[$respondent->cust_6]))
                $results[$respondent->cust_6] = ['total_hours' => 0, 'total_cost' => 0.00, 'total_employees' => 0];

            // update the columns for this respondent
            $results[$respondent->cust_1]['total_hours'] += $answer->answer_value;
            $results[$respondent->cust_1]['total_cost'] += ($answer->answer_value ? $respondent->resp_compensation * (1 + $respondent->resp_benefit_pct) : 0); // if there are no errors here than there shouldn't be a cost of employee count so avoid summing those values and set it to 0 instead
            $results[$respondent->cust_1]['total_employees'] = ($answer->answer_value ? $results[$respondent->cust_1]['total_employees'] + 1: 0);

            $results[$respondent->cust_2]['total_hours'] += $answer->answer_value;
            $results[$respondent->cust_2]['total_cost'] += ($answer->answer_value ? $respondent->resp_compensation * (1 + $respondent->resp_benefit_pct) : 0);
            $results[$respondent->cust_2]['total_employees'] = ($answer->answer_value ? $results[$respondent->cust_2]['total_employees'] + 1 : 0);

            $results[$respondent->cust_3]['total_hours'] += $answer->answer_value;
            $results[$respondent->cust_3]['total_cost'] += ($answer->answer_value ? $respondent->resp_compensation * (1 + $respondent->resp_benefit_pct) : 0);
            $results[$respondent->cust_3]['total_employees'] = ($answer->answer_value ? $results[$respondent->cust_3]['total_employees'] + 1 : 0);

            $results[$respondent->cust_4]['total_hours'] += $answer->answer_value;
            $results[$respondent->cust_4]['total_cost'] += ($answer->answer_value ? $respondent->resp_compensation * (1 + $respondent->resp_benefit_pct) : 0);
            $results[$respondent->cust_4]['total_employees'] = ($answer->answer_value ? $results[$respondent->cust_4]['total_employees'] + 1 : 0);

            $results[$respondent->cust_5]['total_hours'] += $answer->answer_value;
            $results[$respondent->cust_5]['total_cost'] += ($answer->answer_value ? $respondent->resp_compensation * (1 + $respondent->resp_benefit_pct) : 0);
            $results[$respondent->cust_5]['total_employees'] = ($answer->answer_value ? $results[$respondent->cust_5]['total_employees'] + 1 : 0);

            $results[$respondent->cust_6]['total_hours'] += $answer->answer_value;
            $results[$respondent->cust_6]['total_cost'] += ($answer->answer_value ? $respondent->resp_compensation * (1 + $respondent->resp_benefit_pct) : 0);
            $results[$respondent->cust_6]['total_employees'] = ($answer->answer_value ?  $results[$respondent->cust_6]['total_employees'] + 1 : 0);
        }

        return $results;
    }
    /**
     * 
     * Returns the data of a child taxonomy of one for the demographic report
     * 
     * @param int $survey_id    survey id
     * @param int $parent_id    parent question id
     * @param float $parent_hours    parent question answer hours
     * @param float $parent_cost    parent question cost
     * @param float $total_cost    total cost
     * @param array $filter_ary    filter variables
     * @return array 
     */
    public function get_child_service_data ($survey_id, $parent_id, $parent_hours, $parent_cost, $total_cost, $filter_ary = array()) {
        $answers = Question::leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->where('tblquestion.survey_id', $survey_id)
            ->where('tblpage.question_id_parent', $parent_id)
            ->select('tblquestion.question_id',
                'tblquestion.question_desc',
                'tblquestion.lead_codes',
                'tblpage.question_id_parent')
            ->distinct('tblquestion.question_id')
            ->get();
        // dd(($answers));
        if (count($answers) == 0) {
            return false;
        }

        $returnAry = array();

        $answer_questionAry = $this->getQuestionDataByResp($survey_id);

        $resps = $this->get_resp_data($survey_id, $filter_ary);

        $respAry = array();
        // dd($answers);
        foreach ($answers as $answer) {
            if(!array_key_exists($answer->question_id, $returnAry)){
                $returnAry[$answer->question_id] = array();
                $returnAry[$answer->question_id]['question_id'] = $answer->question_id;
                $returnAry[$answer->question_id]['lead_code'] = $answer->lead_codes;
                $returnAry[$answer->question_id]['question_desc'] = $answer->question_desc;
                $returnAry[$answer->question_id]['hours'] = 0;
                $returnAry[$answer->question_id]['cost'] = 0;
                foreach ($resps as $resp) {
                    // dd($resp);
                    if (array_key_exists($resp->resp_id, $answer_questionAry) && array_key_exists($answer->question_id, $answer_questionAry[$resp->resp_id])) {
                        $resp_question_hours = $this->getQuestionHoursFromArray($resp->resp_id, $answer->question_id, $answer_questionAry);
                        // dd($resp_question_hours);
                        $returnAry[$answer->question_id]['hours'] += $resp_question_hours;
                        $returnAry[$answer->question_id]['cost'] = $returnAry[$answer->question_id]['cost'] +($resp->hourly_rate * $resp_question_hours);

                    //    dd($resp_question_hours ,$returnAry );
                        if (!in_array($resp->resp_id, $respAry)) {
                            array_push($respAry, $resp->resp_id);
                        }
                    }
                }
            }
        }
        // dd($returnAry);
        foreach ($returnAry as $key => $e) {

            // dd($e['cost']);

            if ($e['hours'] > 0) {
                $returnAry[$key]['avg_hourly_cost'] = round($e['cost'] / $e['hours']);
            } else {
                $returnAry[$key]['avg_hourly_cost'] = 0;
            }
            if ($total_cost != 0) {
                $returnAry[$key]['total_cost_pct'] = round(100 * $e['cost'] / $total_cost, 1);
            }
            if ($parent_cost != 0) {
                $returnAry[$key]['selection_cost_pct'] = round(100 * $e['cost'] / $parent_cost);
            }
            $returnAry[$key]['hours'] = round($e['hours']);
            $returnAry[$key]['cost'] = round($e['cost']);
            $returnAry[$key]['resp_num'] = count($respAry);
        }

        $keys = array_column($returnAry, 'cost');
        array_multisort($keys, SORT_DESC, $returnAry);
        // dd($returnAry);
        return $returnAry;
    }
    /**
     * 
     * Returns the data of a child taxonomy of one for the nc demographic report
     * 
     * @param int $survey_id    survey id
     * @param int $parent_id    parent question id
     * @param float $parent_hours    parent question answer hours
     * @param float $parent_cost    parent question cost
     * @param float $total_cost    total cost
     * @param array $filter_ary    filter variables
     * @return array 
     */
    public function get_nc_child_service_data ($survey_id, $parent_id, $parent_hours, $parent_cost, $total_hours, $filter_ary = array()) {
        $answers = Question::leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->where('tblquestion.survey_id', $survey_id)
            ->where('tblpage.question_id_parent', $parent_id)
            ->select('tblquestion.question_id',
                'tblquestion.question_desc',
                'tblquestion.lead_codes',
                'tblpage.question_id_parent')
            ->distinct('tblquestion.question_id')
            ->get();

        if (count($answers) == 0) {
            return false;
        }

        $returnAry = array();

        $answer_questionAry = $this->getQuestionDataByResp($survey_id);

        $resps = $this->get_resp_data($survey_id, $filter_ary);

        $respAry = array();

        foreach ($answers as $answer) {
            if(!array_key_exists($answer->question_id, $returnAry)){
                $returnAry[$answer->question_id] = array();
                $returnAry[$answer->question_id]['question_id'] = $answer->question_id;
                $returnAry[$answer->question_id]['lead_codes'] = $answer->lead_codes;
                $returnAry[$answer->question_id]['question_desc'] = $answer->question_desc;
                $returnAry[$answer->question_id]['hours'] = 0;
                foreach ($resps as $resp) {
                    if (array_key_exists($resp->resp_id, $answer_questionAry) && array_key_exists($answer->question_id, $answer_questionAry[$resp->resp_id])) {
                        $resp_question_hours = $this->getQuestionHoursFromArray($resp->resp_id, $answer->question_id, $answer_questionAry);
                        $returnAry[$answer->question_id]['hours'] += $resp_question_hours;
                        if (!in_array($resp->resp_id, $respAry)) {
                            array_push($respAry, $resp->resp_id);
                        }
                    }
                }
            }
        }

        foreach ($returnAry as $key => $e) {
            $returnAry[$key]['total_hours_pct'] = round(100 * $e['hours'] / $total_hours, 1);
            $returnAry[$key]['selection_hours_pct'] = round(100 * $e['hours'] / $parent_hours);
            $returnAry[$key]['hours'] = round($e['hours']);
            $returnAry[$key]['resp_num'] = count($respAry);
        }

        $keys = array_column($returnAry, 'hours');
        array_multisort($keys, SORT_DESC, $returnAry);

        return $returnAry;
    }
    /**
     * 
     * Returns the data of question data by respondents
     * 
     * @param int $survey_id    survey id
     * @return array 
     */
    public function getQuestionDataByResp ($survey_id) {

        // query has to be changed
        

        $answers = Answer::leftJoin('tblquestion', 'tblquestion.question_id', 'tblanswer.question_id')
            ->leftJoin('tblrespondent', 'tblrespondent.resp_id', 'tblanswer.resp_id')
            ->leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->where('tblquestion.survey_id', $survey_id)
            ->where('tblanswer.answer_value', '>', 0)
            ->where('tblrespondent.survey_completed',1)
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
                $returnAry[$answer->resp_id][$answer->question_id]['prox_factor'] = $answer->question_proximity_factor;
            } else {
                $returnAry[$answer->resp_id][$answer->question_id] = array();
                $returnAry[$answer->resp_id][$answer->question_id]['parent_id'] = $answer->question_id_parent;
                $returnAry[$answer->resp_id][$answer->question_id]['answer_value'] = $answer->answer_value;
                $returnAry[$answer->resp_id][$answer->question_id]['prox_factor'] = $answer->question_proximity_factor;
            }
        }

        return $returnAry;
    }
    /**
     * 
     * Returns the hours array of question object array
     * 
     * @param int $resp_id    respondet id
     * @param int $question_id    question id
     * @param array $questionAry    question data array by respodents and questions
     * @return integer  
     * divided / 100
     */
    public function getQuestionHoursFromArray ($resp_id, $question_id, $questionAry = array()) {

        // dd($questionAry); 
        if(isset($questionAry[$resp_id][$question_id]['parent_id'])){
            if ($questionAry[$resp_id][$question_id]['parent_id'] == 0) {
                return $questionAry[$resp_id][$question_id]['answer_value'];
            } else {
                return $this->getQuestionHoursFromArray($resp_id, $questionAry[$resp_id][$question_id]['parent_id'], $questionAry) * $questionAry[$resp_id][$question_id]['answer_value'] / 100;
            }
        }
       
    }
    /**
     * 
     * Returns the data of questions by the taxonomy
     * 
     * @param \App\Models\Survey $survey    survey
     * @param array $filter    filter variables
     * @param array $ids    parent question ids
     * @param array $results    return value
     * @return array 
     */
    public function getQuestionsByArea($survey, $filter = [], $ids = [0], $results = [])
    {
        // dd($filter);
        $qAndAs = DB::table('tblquestion')
            ->select('question_desc AS question', 'answer_value AS answer', 'tblquestion.question_id', 'tblpage.page_id', 'tblpage.question_id_parent AS question_parent_id', 'tblrespondent.*')
            ->leftJoin('tblanswer', 'tblanswer.question_id', '=', 'tblquestion.question_id')
            ->leftJoin('tblpage', 'tblpage.page_id', '=', 'tblquestion.page_id')
            ->rightJoin('tblrespondent', 'tblrespondent.resp_id', '=', 'tblanswer.resp_id')
            ->where('tblrespondent.survey_completed',1)
            ->where('tblquestion.survey_id', $survey->survey_id)
            ->whereIn('question_id_parent', $ids)
            ->when(!empty($filter), function($q) use ($filter) {
                foreach($filter as $column => $value)
                {
                    $q->where($column, $value);
                }
            })
            // ->where('tblanswer.resp_id', 1515) // uncomment this line and enter a resp id to only show the results for this one respondent. compare these results to the individual report numbers for the same respondent
            ->get()->toArray();
            // dd($qAndAs);
        unset($filter['tblquestion.question_desc']); // want to make sure we are not filtering child questions based on the parent taxonomy filter

        if(count($qAndAs) <= 0) {
            return $results;
        }
            // echo "NOWWWWW <br>";    
           

        // terminal case, no more results at this level
            
        // dd($results);
        $this->adjustHours($qAndAs, count($results) ? $results[count($results) - 1] : []);

        $results[] = $qAndAs;
        
        return $this->getQuestionsByArea($survey, $filter, array_map(function($q){ return $q->question_id; }, $qAndAs), $results);
    }
    /**
     * 
     * Returns the data of child questions hours by parents
     * 
     * @param \App\Models\Question $childQuestions    child questions
     * @param array $parentQuestions    parent questions
     * @return array 
     */
    public function adjustHours($childQuestions, $parentQuestions) // converts the answer value as a percentage to an actual number of hours
    {
        
        $parents = [];
        
        if(count($parentQuestions) < 1) // this is the first level, there are no parent questions (i.e. question_id_parent is 0)
        {
            // $hoursTotal = 0;
            foreach($childQuestions as $child)
            {
                $percentage = $child->answer / 100;
                $child->resp_compensation = ($child->resp_compensation * (1 + $child->resp_benefit_pct));
                $child->percentage = 1;
            }
            return $childQuestions;
           /*  //  dd($childQuestions);
            return $childQuestions; */
        }
        
        $total_hours_per_employee = [];

        // move the parent questions into an easy to access table so we don't have to traverse the list each time we need to find a specific parent
        foreach($parentQuestions as $parent)
        {
            $parents[$parent->question_id . '-' . $parent->resp_id] = $parent;

            if(!isset($total_hours_per_employee[$parent->resp_id]))
                $total_hours_per_employee[$parent->resp_id] = 0;

            $total_hours_per_employee[$parent->resp_id] += $parent->answer;
        }

        // adjust each of the child hours (answer)
        foreach($childQuestions as $child)
        {
            $key = $child->question_parent_id . '-' . $child->resp_id;
            if(isset($parents[$key]))
            {
                $parent = $parents[$key];
                $percentage = $child->answer / 100;
                $child->answer = $percentage * $parent->answer; 
                $child->resp_compensation = ($child->resp_compensation * (1 + $child->resp_benefit_pct));
                // $child->resp_compensation = ($child->resp_compensation + $child->resp_compensation * $child->resp_benefit_pct);
                $child->percentage = $child->answer / $total_hours_per_employee[$child->resp_id];
            }
        }

        return $childQuestions;
    }
    /**
     * 
     * Returns the data of respondents by questions
     * 
     * @param int $survey_id    survey id
     * @param int $question_id    question id
     * @param array $filter_ary    filter variable
     * @return array 
     */
    public function getRespListofQuestion ($survey_id, $question_id, $filter_ary = array()) {
        $resps = $this->get_resp_data($survey_id, $filter_ary);
        $answer_questionAry = $this->getQuestionDataByResp($survey_id);
        
        $returnAry = array();
        
        foreach ($resps as $resp) {
            if (array_key_exists($resp->resp_id, $answer_questionAry) && array_key_exists($question_id, $answer_questionAry[$resp->resp_id])) {
                $resp_question_hours = $this->getQuestionHoursFromArray($resp->resp_id, $question_id, $answer_questionAry);
                array_push($returnAry, [
                    'name' => $resp->resp_last . ', ' . $resp->resp_first,
                    'employee_id' => $resp->cust_1,
                    'employee_category' => $resp->cust_5,
                    'department' => $resp->cust_4,
                    'position' => $resp->cust_3,
                    'location' => $resp->cust_6,
                    'hours' => $resp_question_hours,
                ]);
            }
        }



        $keys = array_column($returnAry, 'name');
        array_multisort($keys, SORT_ASC, $returnAry);
        //dd($returnAry);
        return $returnAry;
    }
    /**
     * 
     * Returns the data of survey top parent taxonomy 
     * 
     * @param int $survey_id    survey id
     * @return  \Illuminate\Support\Facades\DB
     */
    public static function getSurveyTaxonomy($survey_id)
    {   
        // dd($survey_id,'getSurveyTaxonomy');
        // if($survey_id != 54){
            return DB::select(DB::raw('SELECT q.* FROM tblquestion q INNER JOIN tblpage p ON q.page_id = p.page_id WHERE q.survey_id = ' . $survey_id . ' AND p.question_id_parent = 0'));
       /*  }else{
            // dd('gotcha');
            return DB::select(DB::raw('SELECT q.* FROM tblquestionTest q INNER JOIN tblpageTest p ON q.page_id = p.page_id WHERE q.survey_id = ' . $survey_id . ' AND p.question_id_parent = 0'));

            // dd($data);
        } */
        
    }
    /**
     * 
     * Returns the data of irregular report
     * 
     * @param int $survey_id    survey id
     * @param int $count    maximum options
     * @param float $max_percent    maximum percent for irregular
     * @param int $max_hr    maximum hours for irregular
     * @param int $min_hr    minimum hours for irregular
     * @param int $depth    taxonomy depth
     * @return array 
     */
    public function getIrregularList ($survey_id, $count, $max_percent, $max_hr, $min_hr, $depth = 1) {

        $result = array();

        $questionAry = $this->getQuestionAry($survey_id);
        $headerAry = $this->getQuestionByPid([0], $questionAry);
        $classifications = $this->getQuestionByPid($headerAry, $questionAry);
        $substantives = $this->getQuestionByPid($classifications, $questionAry);
        if ($depth == 2) {
            $classifications = $substantives;
        } else if ($depth == 3) {
            $childrens = $this->getQuestionByPid($substantives, $questionAry);
            $classifications = $childrens;
        }

        $resps = $this->get_resp_data($survey_id);
        // Options Data
        $resp_countoptionAry = $this->getDetailIrregularList($survey_id, ['max_options' => $count], 'options');
        // Participant Time
        $resp_parttimeAry = $this->getDetailIrregularList($survey_id, ['max_percent' => $max_percent], 'classification');
        
        foreach ($resps as $resp) {
            $total_hours = $resp->legal_hours + $resp->support_hours;
            // Count Options
            if (array_key_exists($resp->resp_id, $resp_countoptionAry)) {
                if (!array_key_exists($resp->resp_id, $result)) {
                    $result[$resp->resp_id] = array (
                        'resp_id' => $resp->resp_id,
                        'full_name' => $resp->resp_last . ", " . $resp->resp_first,
                        'count' => 1,
                        'participated_time' => 0,
                        'exaggerated_time' => 0
                    );
                } else {
                    $result[$resp->resp_id]['count'] = 1;
                }
            }
            // Participant Time
            if (array_key_exists($resp->resp_id, $resp_parttimeAry)) {
                if (!array_key_exists($resp->resp_id, $result)) {
                    $result[$resp->resp_id] = array (
                        'resp_id' => $resp->resp_id,
                        'full_name' => $resp->resp_last . ", " . $resp->resp_first,
                        'count' => 0,
                        'participated_time' => 1,
                        'exaggerated_time' => 0
                    );
                } else {
                    $result[$resp->resp_id]['participated_time'] = 1;
                }
            }
            // Exaggerated Hours
            $week_hours = $total_hours / 52;
            if ($week_hours > $max_hr || $week_hours < $min_hr) {
                if (!array_key_exists($resp->resp_id, $result)) {
                    $result[$resp->resp_id] = array (
                        'resp_id' => $resp->resp_id,
                        'full_name' => $resp->resp_last . ", " . $resp->resp_first,
                        'count' => 0,
                        'participated_time' => 0,
                        'exaggerated_time' => 1
                    );
                } else {
                    $result[$resp->resp_id]['exaggerated_time'] = 1;
                }
            }
        }
        
        return $result;

    }
    /**
     * 
     * Returns the data of irregular data by report kinds
     * 
     * @param int $survey_id    survey id
     * @param array $params    criteria variables of irregular data
     * @param array $sort    irregular report kinds
     * @return array 
     */
    public function getDetailIrregularList ($survey_id, $params = array(), $sort) {
        $result = array ();

        if ($sort == 'classification') {
            $percent = $params['max_percent'] * 100;

            $questionAry = $this->getQuestionAry($survey_id);
            $questionIds = $this->getFinalQuestionNodes($questionAry);

            $permissionData = new PermissionData();
            $allowed_locationAry = $permissionData->getAllowedLocationsByUser($survey_id, Auth::user());
            $allowed_deptAry = $permissionData->getAllowedDepartmentsByUser($survey_id, Auth::user());
            
            $queryData = Answer::leftJoin('tblrespondent', 'tblrespondent.resp_id', 'tblanswer.resp_id')
                ->leftJoin('tblquestion', 'tblquestion.question_id', 'tblanswer.question_id')
                ->leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
                ->whereIn('tblanswer.question_id', $questionIds)
                ->where('tblanswer.answer_value', '>', 0)
                ->where('tblrespondent.survey_completed', 1)
                ->whereIn('tblrespondent.cust_4', $allowed_deptAry)
                ->whereIn('tblrespondent.cust_6', $allowed_locationAry)
                ->select('tblrespondent.resp_id',
                    'tblrespondent.resp_first',
                    'tblrespondent.resp_last',
                    'tblquestion.question_desc',
                    'tblanswer.answer_value',
                    'tblquestion.question_id')
                ->orderBy('tblrespondent.resp_id', 'asc')
                ->get();

            $questionDataAry = $this->getQuestionDataByResp($survey_id);
            $totalHoursAry   = $this->getTotalHoursAryByResp($survey_id);

            $headerAry = $this->getQuestionByPid([0], $questionAry);
            $classifications = $this->getQuestionByPid($headerAry, $questionAry);

            foreach ($queryData as $row) {
                $hours = $this->getQuestionHoursFromArray($row->resp_id, $row->question_id, $questionDataAry);
                $tmpPercent = $totalHoursAry[$row->resp_id] > 0 ? round($hours * 100 / $totalHoursAry[$row->resp_id]) : 0;
                if ($tmpPercent > $percent) {
                    $row->answer_value = $tmpPercent;
                    $classificationData = $this->getClassification($row->question_id, $questionAry, $classifications);
                    if ($classificationData) {
                        $row->classification = $classificationData['question_desc'];
                    }
                    $result[$row->resp_id] = $row;
                }
            }
        } else if ($sort == 'options') {
            $limit_options = $params['max_options'];

            $questionAry = $this->getQuestionAry($survey_id);
            $headerAry = $this->getQuestionByPid([0], $questionAry);
            $classifications = $this->getQuestionByPid($headerAry, $questionAry);
            $substantives = $this->getQuestionByPid($classifications, $questionAry);

            $respCountAry = $this->getCountAryByResp($survey_id, $classifications);

            $resps = $this->get_resp_data($survey_id);

            foreach ($resps as $resp) {
                if (array_key_exists($resp->resp_id, $respCountAry)) {
                    foreach ($respCountAry[$resp->resp_id] as $c) {
                        if ($c > $limit_options) {
                            $resp->count_num = $c;
                            array_push($result, $resp);
                        }
                    }
                }
            }

        } else if ($sort == 'deviation') {
            $max_hrs = $params['max_hrs'];
            $min_hrs = $params['min_hrs'];

            $resps = $this->get_resp_data($survey_id);

            foreach ($resps as $row) {
                $row->legal_hours = $row->legal_hours ? $row->legal_hours : 0;
                $row->support_hours = $row->support_hours ? $row->support_hours : 0;
                $total_hours = $row->legal_hours + $row->support_hours;
                $weekHours = round($total_hours / 52);
                $row->week_hours = $weekHours;
                if ($weekHours < $min_hrs || $weekHours > $max_hrs) {
                    array_push($result, $row);
                }
            }
        }
        
        return $result;
    }
    /**
     * 
     * Returns the data of question by the parent
     * 
     * @param int $survey_id    survey id
     * @param int $pid    parent question id
     * @return \App\Models\Question 
     */
    public static function getQuestionAry ($survey_id, $pid = 0) {
        if ($pid === 0) {
            $query = Question::leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
                ->where('tblquestion.survey_id', $survey_id)
                ->select('tblquestion.question_id',
                    'tblquestion.question_desc',
                    'tblquestion.question_proximity_factor',
                    'tblpage.question_id_parent AS pid')
                ->orderBy('tblquestion.question_sortorder_id', 'asc')
                ->get();
        } else {
            $query = Question::leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
                ->where('tblquestion.survey_id', $survey_id)
                ->where('tblpage.question_id_parent', $pid)
                ->select('tblquestion.question_id',
                    'tblquestion.question_desc',
                    'tblpage.question_id_parent AS pid')
                ->get();
        }
        
        return $query;
    }
    /**
     * 
     * Returns the data of childs by parent ids
     * 
     * @param array $pids    parent ids
     * @param \App\Models\Question $questionAry    questions
     * @return array 
     */
    public static function getQuestionByPid ($pids = array(), $questionAry) {
        $res = array ();

        foreach ($questionAry as $item) {
            foreach ($pids as $pid) {
                if ($item->pid == $pid) {
                    $res[] = $item->question_id;
                }
            }
        }

        return $res;
    }
    /**
     * 
     * Returns the data of final nodes questions
     * 
     * @param \App\Models\Question $questionAry     questions
     * @return array 
     */
    public function getFinalQuestionNodes ($questionAry) {
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
    }
    /**
     * 
     * Returns the classifications of the provided questions
     * 
     * @param int $question_id    question id
     * @param \App\Models\Question $questionAry    questions
     * @param array $classifcations    classifications
     * @return array 
     */
    public function getClassification ($question_id, $questionAry, $classifications) {
        foreach ($questionAry as $row) {
            if ($row->question_id == $question_id) {
                if (in_array($row->pid, $classifications)) {
                    foreach ($questionAry as $res) {
                        if ($res->question_id == $row->pid) {
                            return $res;
                        }
                    }
                } else {
                    return $this->getClassification($row->pid, $questionAry, $classifications);
                }
            }
        }
    }
    /**
     * 
     * Returns the data of question counts by respondents
     * 
     * @param int $survey_id    survey id
     * @param array $questionIds    question ids
     * @return array 
     */
    public function getCountAryByResp ($survey_id, $questionIds) {
        $res = array ();

        $query = Answer::leftJoin('tblquestion', 'tblquestion.question_id', 'tblanswer.question_id')
            ->leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->whereIn('tblanswer.question_id', $questionIds)
            ->where('tblquestion.survey_id', $survey_id)
            ->select('tblanswer.resp_id',
                'tblpage.question_id_parent',
                DB::raw('IFNULL(COUNT(tblanswer.question_id), 0) AS count_num'))
            ->groupBy('tblanswer.resp_id')
            ->groupBy('tblpage.question_id_parent')
            ->get();
        
        foreach ($query as $row) {
            if (!array_key_exists($row->resp_id, $res)) {
                $res[$row->resp_id] = array ($row->count);
            } else {
                array_push($res[$row->resp_id], $row->count_num);
            }
        }

        return $res;
    }
    /**
     * 
     * Returns the data of participated time by respondents
     * 
     * @param int $survey_id    survey id
     * @param array $questionIds    question ids
     * @return array 
     */
    public function getPartTimeAryByResp ($survey_id, $questionIds) {
        $res = array ();
        $questionData = $this->getQuestionDataByResp($survey_id);
        foreach ($questionData as $respId => $row) {
            foreach ($row as $questionId => $e) {
                if (in_array($questionId, $questionIds) && $respId) {
                    if (!array_key_exists($respId, $res)) {
                        $res[$respId] = array ( array(
                            'resp_id' => $respId,
                            'question_id' => $questionId,
                            'hours' => $this->getQuestionHoursFromArray($respId, $questionId, $questionData)));
                    } else {
                        array_push($res[$respId], array (
                            'resp_id' => $respId,
                            'question_id' => $questionId,
                            'hours' => $this->getQuestionHoursFromArray($respId, $questionId, $questionData)
                        ));
                    }
                }
            }
        }

        return $res;
    }
    /**
     * 
     * Returns the data of full irregular data by respodent, survey, taxonomy depth, and irregular report kinds
     * 
     * @param \Illuminate\Http\Request $request    ajax request
     * @return array 
     */
    public function getFullIrregularDataByResp ($request) {
        
        $survey_id  = $request->input('survey_id');
        $sort       = $request->input('sort');
        $resp_id    = $request->input('resp_id');
        $depth      = $request->input('depth');

        $questionAry = $this->getQuestionAry($survey_id);
        $headerAry = $this->getQuestionByPid([0], $questionAry);
        $classifications = $this->getQuestionByPid($headerAry, $questionAry);
        if ($depth == 2) {
            $substantives = $this->getQuestionByPid($classifications, $questionAry);
            $classifications = $substantives;
        }

        $questionDataAry = $this->getQuestionDataByResp($survey_id);

        if ($sort == 'irregular') {
            $queryData  = Answer::leftJoin('tblquestion', 'tblquestion.question_id', 'tblanswer.question_id')
                ->leftJoin('tblrespondent', 'tblrespondent.resp_id', 'tblanswer.resp_id')
                ->whereIn('tblanswer.question_id', $classifications)
                ->where('tblanswer.resp_id', $resp_id)
                ->where('tblquestion.survey_id', $survey_id)
                ->get();

            $legalData   = $this->legelSupportData($survey_id,"Legal Services");
            $supportData = $this->legelSupportData($survey_id,"Support Activities");
            $countAry    = $this->getCountAryByResp($survey_id, $classifications);

            foreach ($queryData as $row) {
                $row->legal_hours   = $legalData[$resp_id]['answer_value'];
                $row->support_hours = $supportData[$resp_id]['answer_value'];
                $row->count_options = $countAry[$resp_id];
                if ($row->answer_value > 0) {
                    $row->total_hours   = $this->getQuestionHoursFromArray($resp_id, $row->question_id, $questionDataAry);
                    $row->hours_percent = round(100 * $row->total_hours / ($row->legal_hours + $row->support_hours));
                } else {
                    $row->total_hours   = 0;
                    $row->hours_percent = 0;
                }
                $row->weekly_hours = round($row->total_hours / 52, 4);
            }

            $result = $queryData;
        } else if ($sort == 'classification') {
            $questionAry = $this->getQuestionAry($survey_id);
            $questionIds = $this->getFinalQuestionNodes($questionAry);

            $queryData = Answer::leftJoin('tblquestion', 'tblquestion.question_id', 'tblanswer.question_id')
                ->leftJoin('tblrespondent', 'tblrespondent.resp_id', 'tblanswer.resp_id')
                ->whereIn('tblanswer.question_id', $questionIds)
                ->where('tblanswer.resp_id', $resp_id)
                ->where('tblquestion.survey_id', $survey_id)
                ->select('tblanswer.resp_id',
                    'tblrespondent.resp_first',
                    'tblrespondent.resp_last',
                    'tblquestion.question_desc',
                    'tblanswer.answer_value',
                    'tblanswer.question_id',
                    'tblquestion.survey_id')
                ->get();
            $totalHoursAry = $this->getTotalHoursAryByResp($survey_id);
            $questionDataAry = $this->getQuestionDataByResp($survey_id);

            $headerAry = $this->getQuestionByPid([0], $questionAry);
            $classifications = $this->getQuestionByPid($headerAry, $questionAry);
            foreach ($queryData as $row) {
                $total_hours = $totalHoursAry[$row->resp_id];
                $row->total_hours = $total_hours;
                $row->hours_value = 0;
                $classificationData = $this->getClassification($row->question_id, $questionAry, $classifications);
                if ($classificationData) {
                    $row->classification = $classificationData['question_desc'];
                }
                if ($row->answer_value > 0) {
                    $hours = $this->getQuestionHoursFromArray($row->resp_id, $row->question_id, $questionDataAry);
                    $row->hours_value = $hours;
                    $row->answer_value = round($hours * 100 / $total_hours);
                }
                $result[] = $row;
            }
        } else if ($sort == 'deviation') {
            $queryData = Answer::leftJoin('tblquestion', 'tblquestion.question_id', 'tblanswer.question_id')
                ->leftJoin('tblrespondent', 'tblrespondent.resp_id', 'tblanswer.resp_id')
                ->whereIn('tblanswer.question_id', $classifications)
                ->where('tblanswer.resp_id', $resp_id)
                ->where('tblquestion.survey_id', $survey_id)
                ->select('tblanswer.resp_id',
                    'tblrespondent.resp_first',
                    'tblrespondent.resp_last',
                    'tblrespondent.cust_3',
                    'tblanswer.question_id',
                    'tblquestion.question_desc',
                    'tblquestion.survey_id',
                    'tblanswer.answer_value')
                ->get();
            $hoursAry = $this->getTotalHoursAryByResp($survey_id);
            $questionDataAry = $this->getQuestionDataByResp($survey_id);
            foreach ($queryData as $row) {
                $row->total_hours = $hoursAry[$resp_id];
                if ($row->answer_value > 0) {
                    $row->answer_value = $this->getQuestionHoursFromArray($resp_id, $row->question_id, $questionDataAry);
                }
                $row->hours_percent = round($row->answer_value * 100 / $row->total_hours);
                $row->weekly_hours = round($row->answer_value / 52, 4);
            }
            $result = $queryData;
        }

        return $result;
    }
    /**
     * 
     * Returns the data of total hours array by respondents
     * 
     * @param int $survey_id    survey id
     * @return array 
     */
    public function getTotalHoursAryByResp ($survey_id) {
        $res = array ();

        $resp_data = $this->get_resp_data($survey_id);

        foreach ($resp_data as $row) {
            $res[$row->resp_id] = $row->legal_hours + $row->support_hours;
        }

        return $res;
    }
    /**
     * 
     * Check if the question is substantive level or not
     * 
     * @param int $question_id    question id
     * @param array $classifications    classification level ids
     * @param \App\Models\Question $data    questions
     * @return boolean 
     */
    public function checkSubstantiveParent ($question_id, $classifications, $data) {
        foreach ($data as $row) {
            if ($row->id == $question_id) {
                if (in_array($row->pid, $classifications)) {
                    return $row;
                } else {
                    if ($row->pid == 0) {
                        return false;
                    }
                    return $this->checkSubstantiveParent($row->pid, $classifications, $data);
                }
            }
        }
    }
    /**
     * 
     * Returns the data of proximity categories
     * 
     * @param int $survey_id    survey id
     * @param \App\Models\Question $data    questions
     * @return array 
     */
    public function getProxCategoryData ($survey_id, $data) {
        $res = array ();
        $res['high'] = array ();
        $res['med'] = array ();
        $res['low'] = array ();

        $questionAry = $this->getQuestionAry($survey_id);
        $headerAry = $this->getQuestionByPid([0], $questionAry);
        $classifications = $this->getQuestionByPid($headerAry, $questionAry);

        foreach ($data as $row) {
            if ($this->checkChildExist($row->id, $data) === false) {
                $substantive = $this->checkSubstantiveParent($row->id, $classifications, $data);
                if ($substantive !== false && !empty($substantive)) {
                    switch ($row->prox_factor) {
                        case 1:
                            if (!array_key_exists($substantive->id, $res['low'])) {
                                $res['low'][$substantive->id] = array ();
                                $res['low'][$substantive->id]['question_id'] = $substantive->id;
                                $res['low'][$substantive->id]['question_desc'] = $substantive->question;
                                $res['low'][$substantive->id]['percent'] = $row->percent_hour;
                                $res['low'][$substantive->id]['hours'] = $row->hours;
                                $res['low'][$substantive->id]['top_parent'] = $row->top_parent;
                                $res['low'][$substantive->id]['parent'] = $this->getQuestionName($row->pid, $data);
                                $res['low'][$substantive->id]['prox_factor'] = 1;
                            } else {
                                $res['low'][$substantive->id]['percent'] += $row->percent_hour;
                                $res['low'][$substantive->id]['hours'] += $row->hours;
                            }  
                            break;
                        
                        case 2:
                            if (!array_key_exists($substantive->id, $res['med'])) {
                                $res['med'][$substantive->id] = array ();
                                $res['med'][$substantive->id]['question_id'] = $substantive->id;
                                $res['med'][$substantive->id]['question_desc'] = $substantive->question;
                                $res['med'][$substantive->id]['percent'] = $row->percent_hour;
                                $res['med'][$substantive->id]['hours'] = $row->hours;
                                $res['med'][$substantive->id]['top_parent'] = $row->top_parent;
                                $res['med'][$substantive->id]['parent'] = $this->getQuestionName($row->pid, $data);
                                $res['med'][$substantive->id]['prox_factor'] = 2;
                            } else {
                                $res['med'][$substantive->id]['percent'] += $row->percent_hour;
                                $res['med'][$substantive->id]['hours'] += $row->hours;
                            }  
                            break;
                        
                        case 3:
                            if (!array_key_exists($substantive->id, $res['high'])) {
                                $res['high'][$substantive->id] = array ();
                                $res['high'][$substantive->id]['question_id'] = $substantive->id;
                                $res['high'][$substantive->id]['question_desc'] = $substantive->question;
                                $res['high'][$substantive->id]['percent'] = $row->percent_hour;
                                $res['high'][$substantive->id]['hours'] = $row->hours;
                                $res['high'][$substantive->id]['top_parent'] = $row->top_parent;
                                $res['high'][$substantive->id]['parent'] = $this->getQuestionName($row->pid, $data);
                                $res['high'][$substantive->id]['prox_factor'] = 3;
                            } else {
                                $res['high'][$substantive->id]['percent'] += $row->percent_hour;
                                $res['high'][$substantive->id]['hours'] += $row->hours;
                            }  
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                }
            }            
        }
        
        $keys = array_column($res['high'], 'hours');
        array_multisort($keys, SORT_DESC, $res['high']);
        
        $keys = array_column($res['med'], 'hours');
        array_multisort($keys, SORT_DESC, $res['med']);
        
        $keys = array_column($res['low'], 'hours');
        array_multisort($keys, SORT_DESC, $res['low']);

        $result = array_merge($res['low'], $res['med']);
        $result = array_merge($result, $res['high']);

        return $result;
    }
    /**
     * 
     * Check if the question is child of the provided question
     * 
     * @param int $question_id    parent question id
     * @param \App\Models\Question $data    question
     * @return boolean 
     */
    private function checkChildExist ($question_id, $data) {
        foreach ($data as $row) {
            if ($row->pid == $question_id) {
                return true;
            }
        }
        return false;
    }
    /**
     * 
     * Returns the name of question
     * 
     * @param int $id    question id
     * @param \App\Models\Question $data    question
     * @return string 
     */
    public function getQuestionName ($id, $data) {
        foreach ($data as $row) {
            if ($row->id == $id)
                return $row->question;
        }
    }
    /**
     * 
     * Returns the data of filter variables
     * 
     * @param \App\Models\Respondent $resps    respondents
     * @param array $data    filter variables
     * @return array 
     */
    public function getRespFilterDataAry ($resps, $data) {
        $tmpPosition = array();
        $tmpDepartment = array();
        $tmpGroup = array();
        $tmpLocation = array();
        $tmpCategory = array();

        $data['total_cost'] = 0;
        $data['total_hours'] = 0;

        $data['legal_hours'] = 0;
        $data['support_hours'] = 0;

        $data['legal_cost'] = 0;
        $data['support_cost'] = 0;

        //dd($resps);

        foreach ($resps as $resp) {
            if(!array_key_exists($resp->cust_3, $tmpPosition)) {
                $tmpPosition[$resp->cust_3] = $resp->cust_3;
            }
            if(!array_key_exists($resp->cust_4, $tmpDepartment)) {
                $tmpDepartment[$resp->cust_4] = $resp->cust_4;
            }
            if(!array_key_exists($resp->cust_2, $tmpGroup)){
                $tmpGroup[$resp->cust_2] = $resp->cust_2;
            }
            if(!array_key_exists($resp->cust_6, $tmpLocation)){
                $tmpLocation[$resp->cust_6] = $resp->cust_6;
            }
            if(!array_key_exists($resp->cust_5, $tmpCategory)){
                $tmpCategory[$resp->cust_5] = $resp->cust_5;
            }
            
            $compensation = $resp->resp_compensation + $resp->resp_compensation * $resp->resp_benefit_pct;
            $data['total_cost'] += $compensation;
            $data['total_hours'] += $resp->support_hours + $resp->legal_hours;

            $data['legal_hours'] += $resp->legal_hours;
            $data['support_hours'] += $resp->support_hours;

            $data['legal_cost'] += $resp->legal_hours * $resp->hourly_rate;
            $data['support_cost'] += $resp->support_hours * $resp->hourly_rate;
        }

        sort($tmpDepartment);
        sort($tmpPosition);
        sort($tmpGroup);
        sort($tmpLocation);
        sort($tmpCategory);

        $data['position'] = $tmpPosition;
        $data['department'] = $tmpDepartment;
        $data['group'] = $tmpGroup;
        $data['location'] = $tmpLocation;
        $data['category'] = $tmpCategory;
        $data['survey_status'] = [];

        return $data;
    }
    /**
     * 
     * Returns the data of filter variables by taxonomy branches
     * 
     * @param $survey_id    survey id
     * @param array $data    filter variables
     * @return array 
     */
    public function getTaxonomyFilterDataAry ($survey_id, $data) {
        $questionAry = $this->getQuestionAry($survey_id);
        $data['branch'] = $this->getQuestionByPid([0], $questionAry);
        $data['classification'] = $this->getQuestionByPid($data['branch'], $questionAry);
        $data['substantive'] = $this->getQuestionByPid($data['classification'], $questionAry);
        $data['process'] = $this->getQuestionByPid($data['substantive'], $questionAry);

        return $data;
    }
    /**
     * 
     * Returns the array question data with the question id as a key
     * 
     * @param int $survey_id    survey id
     * @return array 
     */
    public function getQuestionAryByQuestionId ($survey_id) {
        $res = array ();

        $query = Question::where('survey_id', $survey_id)
            ->get();

        foreach ($query as $row) {
            $res[$row->question_id] = $row;
        }

        return $res;
    }
    /**
     * 
     * Returns the array of questions with parents
     * 
     * @param \App\Models\Question $questionAry    questions
     * @return array 
     */
   
   
   /*  public function getAryOfQuestionWithParents ($questionAry) {
        $res = array ();

        foreach ($questionAry as $row) {
            $res[$row->question_id] = array ();
            array_push($res[$row->question_id], $row->question_id);
            $pid = $row->pid;
           /*  if($pid != 0){
               
            } *

            while ($pid != 0) {
                //dd($pid)
                array_push($res[$row->question_id], $pid);
                foreach ($questionAry as $tmp) {
                    if ($tmp->question_id == $pid) {
                        $pid = $tmp->pid;
                    }
                }
            }
        }

        return $res;
    } */

    public function getAryOfQuestionWithParents ($questionAry) {
        //ini_set('memory_limit','-1');
        $res = array ();
        $questionAry = $questionAry->toArray();
      
        foreach ($questionAry as $row) {
            
            $res[$row['question_id']] = array(); //array create 
            array_push($res[$row['question_id']], $row['question_id']);
            $pid = $row['pid'];

            while ($pid != 0) {
                array_push($res[$row['question_id']], $pid);

                

                foreach ($questionAry as $tmp) {
                    if ($tmp['question_id'] == $pid) {
                        $pid = $tmp['pid'];
                    }
                }
            }
        }
       // dd($res);
        return $res;
    }

    /**
     * 
     * Returns the description of question
     * 
     * @param int $question_id    question id
     * @param \App\Models\Question $questionAry    questions
     * @return string 
     */
    public function getQuestionDesc ($question_id, $questionAry) {
        foreach ($questionAry as $row) {
            if ($row->question_id == $question_id) {
                return $row->question_desc;
            }
        }
        return '';
    }
    /**
     * 
     * Returns the parent of question
     * 
     * @param int $question_id    question id
     * @param \App\Models\Question $questionAry    questions
     * @return \App\Models\Question 
     */
    public function getParentOfQuestion ($question_id, $questionAry) {
        foreach ($questionAry as $row) {
            if ($row->question_id == $question_id) {
                $pid = $row->pid;
            }
        }

        foreach ($questionAry as $row) {
            if ($pid == $row->question_id) {
                return $row;
            }
        }
    }
    /**
     * 
     * Add the excel copyright and title
     * 
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet    Spreadsheet
     * @param int $row    number of rows
     * @param int $lastRow  last row
     * @param string $survey_name   survey name
     * @return \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet 
     */
    public function addExcelCopyright ($sheet, $row, $lastRow, $survey_name) {
        $row++;

        $sheet->getRowDimension('1')->setRowHeight(40);
        $sheet->getRowDimension($row)->setRowHeight(40);
        $sheet->setCellValue('A1', "$survey_name");
        $sheet->getStyle('A1')->getFont()->setSize(20);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);
        $sheet->mergeCells("A1:{$lastRow}1");
        
        $drawing = new Drawing();
        $drawing->setName('Logo Image');
        $drawing->setDescription('Revelation Legal');
        $drawing->setPath('imgs/logo-new-small_rev.png');
        $drawing->setCoordinates("A$row");
        $drawing->setOffsetX(10);
        $drawing->setHeight(60);
        $drawing->setWorksheet($sheet);

        $sheet->getStyle("B$row")->getAlignment()->setWrapText(true);
        $sheet->getStyle("B$row")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue("B$row", url('/') . "\n © ofPartner LLC, All Rights Reserved. Report Generated " . date('m/d/Y'));        
        $sheet->getStyle("A$row:{$lastRow}$row")->getBorders()->getOutline()->setBorderStyle(Border::BORDER_NONE);
        $sheet->mergeCells("B$row:{$lastRow}$row");
        
       // dd($sheet);
        
        return $sheet;
    }
    /**
     * 
     * Returns the answer values by respondents
     * 
     * @param int $survey_id    survey id
     * @return array
     */
    public function getAnswerByRespondent ($survey_id) {
        //dd($survey_id);
        $res = Answer::join('tblrespondent', 'tblrespondent.resp_id', '=','tblanswer.resp_id')->where('tblrespondent.survey_id', $survey_id)->get();
        //dd($res);
        $return = array();

        foreach ($res as $item) {
            if (!array_key_exists($item->resp_id, $return)) {
                $return[$item->resp_id] = array(
                    $item->question_id => $item->answer_value
                );
            } else {
                $return[$item->resp_id][$item->question_id] = $item->answer_value;
            }
        }
        //dd($return);
        return $return;
    }
}
