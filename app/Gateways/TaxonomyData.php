<?php

namespace App\Gateways;

use App\Gateways\ReportData as GatewaysReportData;
use App\Models\Answer;
use App\Models\Page;
use App\Models\Question;
/* use App\Models\TblPageTest;
use App\Models\TblQuestionTest; */
use Illuminate\Support\Facades\DB;

class TaxonomyData {
    /**
     * 
     * Returns the data of questions by survey
     * 
     * @param int $survey_id    survey id
     * @return array 
     */
    public function getQuestionsBySurvey ($survey_id) {
     
        $res = Question::leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
        ->where('tblquestion.survey_id', $survey_id)
        ->select('tblquestion.question_id AS id',
                'tblpage.question_id_parent AS pid',
                'tblquestion.question_desc AS name',
                'tblquestion.question_sortorder_id AS sort_id',
                'tblquestion.question_desc_alt',
                'tblquestion.question_extra',
                'tblquestion.question_extra_alt',
                'tblquestion.question_code',
                'tblquestion.question_UTBMS',
                'tblquestion.question_proximity_factor',
                'tblquestion.lead_codes',
                'tblquestion.question_enabled')
        ->orderBy('tblpage.question_id_parent', 'asc')
        ->get();
        // dd($res[0]);
    
        $firstchildAry = array();
        $dataAry = array();
        foreach ($res as $e) {
            if (!array_key_exists($e->pid, $firstchildAry)) {
                $firstchildAry[$e->pid] = $e->id;
            }
            $dataAry[$e->id]['question_id'] = $e->question_id;
            $dataAry[$e->id]['pid'] = $e->pid;
            $dataAry[$e->id]['question_code'] = $e->question_code;
        }
        
        $nextsiblingAry = array();
        foreach ($res as $key => $e) {
            if (isset($res[$key + 1])) {
                if ($e->pid == $res[$key + 1]->pid) {
                    $nextsiblingAry[$e->id] = $res[$key + 1]->id;
                } else {
                    $nextsiblingAry[$e->id] = '';
                }
            } else {
                $nextsiblingAry[$e->id] = '';
            }
            $code = $this->getPartialCode($e->id, $dataAry);
            $res[$key]->str_code = $e->question_code;
            $res[$key]->data_id = $e->id;
            $res[$key]->data_parent = $e->pid;
            $res[$key]->data_first_child = isset($firstchildAry[$e->id]) ? $firstchildAry[$e->id] : "";
            $res[$key]->data_next_sibling = $nextsiblingAry[$e->id];
        }
        
        // dd($res[17]);
        return $res;
    }
    /**
     * 
     * Update the question row
     * 
     * @param int $survey_id    survey id
     * @param int $question_id    question id
     * @param array $data   update row data
     * @param \Illuminate\Http\Request $req    request updated
     * @return \App\Models\Question 
     */
    public function updateQuestion ($survey_id, $question_id, $data = array()) {
        // dd($data);
        // echo "not";
        $row = Question::where('survey_id', $survey_id)
        ->where('question_id', $question_id)
        ->update($data);
        
        // dd($row);
        return $row;
    }
    /**
     * 
     * Remove the rows
     * 
     * @param int $survey_id    survey id
     * @param int $question_id    question id
     * @return int
     */
    public function removeQuestion ($survey_id, $question_id) {
        $answer_check = Answer::leftJoin('tblquestion', 'tblquestion.question_id', 'tblanswer.question_id')
            ->where('tblquestion.question_id', $question_id)
            ->where('tblquestion.survey_id', $survey_id)
            ->get();
        
        $page_check = Page::where('question_id_parent', $question_id)
            ->get();

        if (count($answer_check) > 0) {
            return 400;
        } else {
            $remove = Question::where('question_id', $question_id)
                ->where('survey_id', $survey_id)
                ->delete();

            if (count($page_check) > 0) {
                $removePage = Page::where('survey_id', $survey_id)
                    ->where('question_id_parent', $question_id)
                    ->delete();
            }

            if (!$remove) {
                return 400;
            } else {
                return 200;
            }
        }
    }
    /**
     * 
     * Insert a new row of question
     * 
     * @param int $question_id    question id
     * @param array $data   data inserted
     * @param int $question_id_parent   parent question id
     * @return \App\Models\Question
     */
    public function insertQuestion ($survey_id, $data = array(), $question_id_parent) {
        $pageData = Page::where('survey_id', $survey_id)
            ->where('question_id_parent', $question_id_parent)
            ->first();
        //dd($pageData);
            
        
        $pageSeq = Question::leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->where('tblquestion.survey_id', $survey_id)
            ->where('tblpage.question_id_parent', $question_id_parent)
            ->orderBy('tblquestion.question_seq', 'desc')
            ->get();
         

        $data['question_id'] = Question::max('question_id') + 1;
        //dd($pageData);
        if($pageData != NULL){
            $data['page_id'] = $pageData->page_id;
        }else{
            $data['page_id'] = 1;
        }
        
        if (count($pageSeq) == 0) {
            $data['question_seq'] = 1;
        } else {
            $data['question_seq'] = $pageSeq[0]->question_seq + 1;
        }
        $data['question_id_original'] = Question::max('question_id_original') + 1;
        
        $insertQuestionTable = Question::create($data);

        if ($insertQuestionTable) {
            $data['status'] = 200;
            $data['question_id_parent'] = $question_id_parent;
            $data['updatedData'] = $this->getQuestionsBySurvey($survey_id);
            return $data;
        }

        return false;
    }
    /**
     * 
     * Returns the data of excel export
     * 
     * @param int $survey_id    survey id
     * @return array 
     */
    public function getExcelExportData ($survey_id) {
        $res = Question::leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->where('tblquestion.survey_id', $survey_id)
            ->select('tblquestion.question_id AS id',
                    'tblpage.question_id_parent AS pid',
                    'tblquestion.question_desc AS name',
                    'tblquestion.question_sortorder_id AS sort_id',
                    'tblquestion.question_code',
                    'tblquestion.question_extra',
                    'tblquestion.lead_codes',
                    'tblquestion.question_proximity_factor')
               
            // ->orderBy('tblquestion.question_sortorder_id', 'asc')
            ->orderBy('tblpage.question_id_parent', 'asc')
            ->orderBy('tblquestion.question_sortorder_id', 'asc')
            ->get();
       
        $returnAry = array();
        foreach ($res as $e) {
            $question_proximity_factor = '';
            switch ($e->question_proximity_factor) {
                case 1:
                    $question_proximity_factor = 'Low';
                    break;
                
                case 2:
                    $question_proximity_factor = 'Medium';
                    break;
                
                case 3:
                    $question_proximity_factor = 'High';
                    break;

                case 4:
                    $question_proximity_factor = 'Virtual';
                    break;
                
                default:
                    # code...
                    break;
            }
            $returnAry[$e->id] = array(
                'id' => $e->id,
                'pid' => $e->pid,
                'name' => $e->name,
                'question_code' => $e->question_code,
                'question_extra' => $e->question_extra,
                'question_proximity_factor' => $question_proximity_factor,
                'lead_codes' =>$e->lead_codes,
            );
        }

       

        if($survey_id != 61){
            foreach ($returnAry as $key => $t) {
                $code_data = $this->getCode($t['id'], $returnAry);
                $returnAry[$key]['b_code'] = $code_data['b_code'];
                $returnAry[$key]['c_code'] = $code_data['c_code'];
                $returnAry[$key]['s_code'] = $code_data['s_code'];
                $returnAry[$key]['p_code'] = $code_data['p_code'];
                $returnAry[$key]['a_code'] = $code_data['a_code'];
                $returnAry[$key]['activity']  = $code_data['activity'];
                $returnAry[$key]['full_code'] = $code_data['code'];
                $returnAry[$key]['order_id']  = $returnAry[$key]['pid'] . $code_data['code'];
            }
            
            $resultAry = $this->getOrderedQuestionIdAry($survey_id, $returnAry);
        }else{
            foreach ($returnAry as $key => $t) {
                $code_data = $this->getCode($t['id'], $returnAry);
                $returnAry[$key]['b_code'] = $code_data['b_code'];
                $returnAry[$key]['c_code'] = $code_data['c_code'];
                $returnAry[$key]['s_code'] = $code_data['s_code'];
                $returnAry[$key]['p_code'] = $code_data['p_code'];
                $returnAry[$key]['a_code'] = $code_data['a_code'];
                $returnAry[$key]['activity']  = $code_data['activity'];
                $returnAry[$key]['full_code'] = $code_data['code'];
                $returnAry[$key]['order_id']  = $returnAry[$key]['pid'] . $code_data['code'];
            }
            
            $resultAry = $this->getOrderedQuestionIdAry($survey_id, $returnAry);
        }
        
        // $key1 = array_column($returnAry, 'order_id');
        // array_multisort($key1, SORT_STRING, $returnAry);
        // dd($returnAry);
        return $resultAry;
    }
    /**
     * 
     * Returns the data of questions ordered 
     * 
     * @param int $survey_id    survey id
     * @param array $data    questions data
     * @return array 
     */
    private function getOrderedQuestionIdAry ($survey_id, $data) {
        $questionAry = GatewaysReportData::getQuestionAry($survey_id);
        $res = array ();
        foreach ($data as $row)  {
            if ($row['full_code'] == '') {
                $row['depth'] = 0;
            } else {
                $row['depth'] = count(explode('.', $row['full_code']));
            }
            $row['childs_num'] = count($this->getAllChildQuestionsByPid($row['id'], $questionAry));
            array_push($res, $row);
        }
        // 
        $key1 = array_column($res, 'depth');
        //array_multisort($key1, SORT_ASC, $res);
        // dd($res);
        $i = 1;
        foreach ($res as $key => $row) {
            $res[$key]['order_num'] = $i;
            $i++;
        }

        $result = array ();
        foreach ($res as $row) {
            if ($row['pid'] == 0) {
                $result[] = $row;
                if ($row['order_num'] == 2) {
                    // $key1 = array_column($result, 'name');
                    //array_multisort($key1, SORT_STRING, $result);
                }
            } else {
                if (!$this->checkElementInArray($row['id'], $result)) {
                    $childs = $this->getChildQuestionByPid($row['pid'], $res);
                    $key    = $this->getKeyOfArray($row['pid'], $result);
                    // $key1   = array_column($childs, 'name');
                   // array_multisort($key1, SORT_STRING, $childs);
                    array_splice($result, $key + 1, 0, $childs);
                }
            }
        }
        // dd($result);
        return $result;
    }
    /**
     * 
     * Returns the code of questions
     * 
     * @param int $question_id    question id
     * @param array $data    question data
     * @return array
     */
    private function getCode ($question_id, $data) {
        $ret = array(
            'b_code' => '',
            'c_code' => '',
            's_code' => '',
            'p_code' => '',
            'a_code' => '',
            'activity' => '',
        );

        $this_code = $this->getPartialCode($question_id, $data);

        switch ($this_code['depth']) {
            case 1:
                $ret['c_code'] = $data[$question_id]['name'];
                break;
            
            case 2:
                $ret['c_code'] = $this_code['code'];
                $ret['s_code'] = $data[$question_id]['name'];
                break;
            
            case 3:
                $ret['s_code'] = $this_code['code'];
                $ret['p_code'] = $data[$question_id]['name'];
                break;
            
            case 4:
                $ret['p_code'] = $this_code['code'];
                $ret['a_code'] = $data[$question_id]['name'];
                break;
                
            case 5:
                $ret['a_code'] = $this_code['code'];
                $ret['activity'] = $data[$question_id]['name'];
                break;
            
            default:
                # code...
                break;
        }
        $ret['code'] = $this_code['code'];

        return $ret;
    }
    /**
     * 
     * Returns the partial code of questions
     * 
     * @param int $survey_id    survey id
     * @param array $data   questions data
     * @return array
     */
    private function getPartialCode ($question_id, $data) {
        $depth = 1;
        $code = "";
        $pid = $data[$question_id]['pid'];
        $code = $data[$question_id]['question_code'];

        while ($pid != 0) {
            if ($data[$pid]['question_code'] != '') {
                $code = $code;
            }
            $pid = $data[$pid]['pid'];
            $depth++;
        }

        return array(
            'depth' => $depth,
            'code' => $code,
        );
    }
    /**
     * 
     * Returns the all child questions of parent provided
     * 
     * @param int $pid    parent question id
     * @param \App\Models\Question $questionAry    questions
     * @return array 
     */
    public function getAllChildQuestionsByPid ($pid, $questionAry) {
        $res = array ();

        $childs = GatewaysReportData::getQuestionByPid([$pid], $questionAry);
        if (count($childs) != 0) {
            foreach ($childs as $child) {
                array_push($res, $child);
            }
            $child1 = GatewaysReportData::getQuestionByPid($childs, $questionAry);
            if (count($child1) != 0) {
                foreach ($child1 as $child) {
                    array_push($res, $child);
                }
                $child2 = GatewaysReportData::getQuestionByPid($child1, $questionAry);
                if (count($child2) != 0) {
                    foreach ($child2 as $child) {
                        array_push($res, $child);
                    }
                    $child3 = GatewaysReportData::getQuestionByPid($child2, $questionAry);
                    if (count($child3) != 0) {
                        foreach ($child3 as $child) {
                            array_push($res, $child);
                        }
                    } else {
                        return $res;
                    }
                } else {
                    return $res;
                }
            } else {
                return $res;
            }
        } else {
            return $res;
        }

        return $res;
    }
    /**
     * 
     * Returns the 1th child questions of parent provided
     * 
     * @param int $pid    parent question id
     * @param array $data   questions data
     * @return array 
     */
    private function getChildQuestionByPid ($pid, $data) {
        $res = array ();

        foreach ($data as $row) {
            if ($row['pid'] == $pid) {
                $res[] = $row;
            }
        }

        return $res;
    }
    /**
     * 
     * Returns the key of array object
     * 
     * @param int $id    array id
     * @param array $res    response array
     * @return int
     */
    private function getKeyOfArray ($id, $res) {
        foreach ($res as $key => $e) {
            if ($id == $e['id']) {
                return $key;
            }
        }
        return null;
    }
    /**
     * 
     * Check if the element is in the array or not
     * 
     * @param int $id    id
     * @param array $array
     * @return boolean 
     */
    private function checkElementInArray ($id, $array) {
        foreach ($array as $row) {
            if ($id == $row['id']) {
                return true;
            }
        }
        return false;
    }
    /**
     * 
     * Returns the sorted question data
     * 
     * @param int $survey_id    survey id
     * @return array
     */
    public function getOrderedQuestionData($survey_id) {            
        $res = Question::leftJoin('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->where('tblquestion.survey_id', $survey_id)
            ->select('tblquestion.question_id AS id',
                    'tblpage.question_id_parent AS pid',
                    'tblquestion.question_desc AS name',
                    'tblquestion.question_code',
                    'tblquestion.question_extra')
            ->orderBy('tblquestion.page_id', 'asc')
            ->orderBy('tblquestion.question_desc', 'asc')
            ->get();

        $returnAry = array();
        foreach ($res as $e) {
            $returnAry[$e->id] = array(
                'id' => $e->id,
                'pid' => $e->pid,
                'name' => $e->name,
                'question_code' => $e->question_code,
                'question_extra' => $e->question_extra,
            );
        }

        foreach ($returnAry as $key => $t) {
            $code_data = $this->getCode($t['id'], $returnAry);
            $returnAry[$key]['activity']  = $code_data['activity'];
            $returnAry[$key]['full_code'] = $code_data['code'];
            $returnAry[$key]['order_id']  = $returnAry[$key]['pid'] . $code_data['code'];
        }

        return $returnAry;
    }
}
