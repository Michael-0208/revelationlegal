<?php

namespace App\Gateways;

use App\Models\Question;

class AnalysisData {
    /**
     * 
     * Returns the data for At-A-Glance Report Data
     * 
     * @param int $survey_id    survey id
     * @param array $resps  respodents data array
     * @param int $depth    taxonomy question depth
     * @param float $min_percent    minimum percent to display data
     * @param int $option   number for cust variable in respondents
     * @return array 
     */
    public function getAtaGlanceData ($survey_id, $resps, $depth, $min_percent, $option) {
        $reportData = new ReportData();
        // Array of Question Data by Respondents
        $questionAry = $reportData->getQuestionDataByResp($survey_id);
        $questionDetailData = $this->getQuestionData($survey_id);
        // Question IDs to filter respondents with depth of taxonomy
        $questionIds = $this->getQuestionIdsByDepth ($survey_id, $depth);
        // Question Array
        $questionDataAry = $reportData->getQuestionAry($survey_id);
        $questionDescAry = $this->getQuestionOneDescription($questionDataAry);
        
        $data = array ();
        $totalAry = array ();
        $total_hours = 0;
        $total_cost = 0;

        foreach ($resps as $resp) {
            switch ($option) {
                case 0: // Position
                    $option_field = $resp->cust_3;
                    break;
                
                case 1: // Department
                    $option_field = $resp->cust_4;
                    break;
                
                case 2: // Group
                    $option_field = $resp->cust_2;
                    break;
                
                case 3: // Location
                    $option_field = $resp->cust_6;
                    break;
                
                case 4: // Category
                    $option_field = $resp->cust_5;
                    break;
                
                case 5: // Participant
                    $option_field = $resp->resp_last . ', ' . $resp->resp_first;
                    break;
                
                default:
                    $option_field = $resp->cust_6;
                    break;
            }

            if (array_key_exists($resp->resp_id, $questionAry)) {
                foreach ($questionAry[$resp->resp_id] as $question_id => $row) {
                    if (in_array($question_id, $questionIds)) {
                        $hours = $reportData->getQuestionHoursFromArray($resp->resp_id, $question_id, $questionAry);

                        array_push($data, [
                            'option' => $option_field,
                            'question_desc' => $questionDescAry[$question_id],
                            'question_id' => $question_id,
                            'hours' => $hours,
                            'cost' => $resp->hourly_rate * $hours
                        ]);
                        
                        if (!array_key_exists($option_field, $totalAry)) {
                            $totalAry[$option_field] = array (
                                'hours' => $hours,
                                'cost' => $hours * $resp->hourly_rate
                            );
                        } else {
                            $totalAry[$option_field]['hours'] += $hours;
                            $totalAry[$option_field]['cost'] += $hours * $resp->hourly_rate;
                        }
                    }
                }
                $total_hours += $resp->legal_hours + $resp->support_hours;
                $total_cost += $resp->resp_compensation + $resp->resp_compensation * $resp->resp_benefit_pct;
            }
        }
        
        $res = array ();
        foreach ($data as $row) {
            if (!array_key_exists($row['option'], $res)) {
                $res[$row['option']] = array (
                    $row['question_id'] => array (
                        'question_id' => $row['question_id'],
                        'question_desc' => $row['question_desc'],
                        'hours' => $row['hours'],
                        'cost' => $row['cost']
                    )
                );
            } else {
                if (!array_key_exists($row['question_id'], $res[$row['option']])) {
                    $res[$row['option']][$row['question_id']] = array (
                        'question_id' => $row['question_id'],
                        'question_desc' => $row['question_desc'],
                        'hours' => $row['hours'],
                        'cost' => $row['cost']
                    );
                } else {
                    $res[$row['option']][$row['question_id']]['hours'] += $row['hours'];
                    $res[$row['option']][$row['question_id']]['cost'] += $row['cost'];
                }
            }
        }
        
        $returnAry = array ();
        foreach ($res as $optionSelected => $row) {
            $tmpAry = array ();
            foreach ($row as $item) {
                $percent = round(100 * $item['hours'] / $totalAry[$optionSelected]['hours']);
                if ($percent > $min_percent) {
                    $item['option'] = $optionSelected;
                    $item['percent'] = $percent;
                    $item['hours'] = round($item['hours']);
                    $tmpAry[] = $item;
                }
            }
            if (!empty($tmpAry)) {
                $keys = array_column($tmpAry, 'question_desc');
                array_multisort($keys, SORT_ASC, $tmpAry);
                $tmpFlag = 1;
                foreach ($tmpAry as $e) {
                    if ($tmpFlag == 1) {
                        $e['rowspan'] = count($tmpAry);
                        $tmpFlag = 0;
                    } else {
                        $e['rowspan'] = 0;
                    }
                    array_push($returnAry, $e);
                }
            }
        }
        
        $keys1 = array_column($returnAry, 'option');
        $keys2 = array_column($returnAry, 'rowspan');
        array_multisort($keys1, SORT_ASC, $keys2, SORT_DESC, $returnAry);

        $return_data = array ();
        $return_data['rows'] = $returnAry;
        $return_data['grand_total_hours'] = $total_hours;
        $return_data['grand_total_cost'] = round($total_cost);

        return $return_data;
    }
    /**
     * 
     * Returns the question ids by the depth of taxonomy
     * 
     * @param int $survey_id    survey id
     * @param int $depth    taxonomy question depth
     * @return array 
     */
    public function getQuestionIdsByDepth ($survey_id, $depth) {
        $reportData = new ReportData();
        $questions  = $reportData->getQuestionAry($survey_id);

        // Legal | Support
        $parents    = $reportData->getQuestionByPid([0], $questions);
        if ($depth == 0) {  
            return $parents;
        }
        // Classifications
        $classifications = $reportData->getQuestionByPid($parents, $questions);
        if ($depth == 1) {
            return $classifications;
        }
        // Substantive Areas
        $substantives = $reportData->getQuestionByPid($classifications, $questions);
        if ($depth == 2) {
            return $substantives;
        }
        // Processes
        $processes = $reportData->getQuestionByPid($substantives, $questions);
        if ($depth == 3) {
            return $processes;
        }
        // Activities
        $activities = $reportData->getQuestionByPid($processes, $questions);
        if ($depth == 4) {
            return $activities;
        }
    }
    /**
     * 
     * Returns the data of questions with the survey id
     * 
     * @param int $survey_id    survey id
     * @return array 
     */
    public function getQuestionData ($survey_id) {
        $rows = Question::where('survey_id', $survey_id)->get();

        $res = array ();
        foreach ($rows as $row) {
            $res[$row->question_id] = $row;
        }

        return $res;
    }
    /**
     * 
     * Returns the data for Comparative Glance Report Data
     * 
     * @param int $survey_id    survey id
     * @param array $resps  respodents data array
     * @param int $depth    taxonomy question depth
     * @param float $min_percent    minimum percent to display data
     * @param int $option_primary   number for the first display rule cust variable in respondents
     * @param int $option_secondary   number for the second display rule cust variable in respondents
     * @return array 
     */
    public function getComparativeGlanceData ($survey_id, $resps, $depth, $min_percent, $option_primary, $option_secondary) {
        $reportData = new ReportData();
        // Array of Question Data by Respondents
        $questionAry = $reportData->getQuestionDataByResp($survey_id);
        $questionDetailData = $this->getQuestionData($survey_id);
        // Question IDs to filter respondents with depth of taxonomy
        $questionIds = $this->getQuestionIdsByDepth ($survey_id, $depth);
        // Question Array
        $questionDataAry = $reportData->getQuestionAry($survey_id);
        $questionDescAry = $this->getQuestionOneDescription($questionDataAry);
        
        $data = array ();
        $totalAry = array ();
        $total_hours = 0;
        $total_cost = 0;

        foreach ($resps as $resp) {
            switch ($option_primary) {
                case 0: // Position
                    $option_field = $resp->cust_3;
                    break;
                
                case 1: // Department
                    $option_field = $resp->cust_4;
                    break;
                
                case 2: // Group
                    $option_field = $resp->cust_2;
                    break;
                
                case 3: // Location
                    $option_field = $resp->cust_6;
                    break;
                
                case 4: // Category
                    $option_field = $resp->cust_5;
                    break;
                
                case 5: // Participant
                    $option_field = $resp->resp_last . ', ' . $resp->resp_first;
                    break;
                
                default:
                    $option_field = $resp->cust_6;
                    break;
            }

            switch ($option_secondary) {
                case 0: // Position
                    $option_field_secondary = $resp->cust_3;
                    break;
                
                case 1: // Department
                    $option_field_secondary = $resp->cust_4;
                    break;
                
                case 2: // Group
                    $option_field_secondary = $resp->cust_2;
                    break;
                
                case 3: // Location
                    $option_field_secondary = $resp->cust_6;
                    break;
                
                case 4: // Category
                    $option_field_secondary = $resp->cust_5;
                    break;
                
                case 5: // Participant
                    $option_field_secondary = $resp->resp_last . ', ' . $resp->resp_first;
                    break;
                
                default:
                    $option_field_secondary = $resp->cust_6;
                    break;
            }

            if (array_key_exists($resp->resp_id, $questionAry)) {
                foreach ($questionAry[$resp->resp_id] as $question_id => $row) {
                    if (in_array($question_id, $questionIds)) {
                        $hours = $reportData->getQuestionHoursFromArray($resp->resp_id, $question_id, $questionAry);

                        array_push($data, [
                            'option' => $option_field,
                            'sub_option' => $option_field_secondary,
                            'question_desc' => $questionDescAry[$question_id],
                            'question_id' => $question_id,
                            'hours' => $hours,
                            'cost' => $resp->hourly_rate * $hours
                        ]);
                        // $total_hours = $resp->legal_hours + $resp->support_hours;
                        if (!array_key_exists($option_field, $totalAry)) {
                            $totalAry[$option_field] = array (
                                $option_field_secondary => array (
                                    'hours' => $hours,
                                    'cost' => $hours * $resp->hourly_rate
                                )
                            );
                        } else {
                            if (!array_key_exists($option_field_secondary, $totalAry[$option_field])) {
                                $totalAry[$option_field][$option_field_secondary] = array (
                                    'hours' => $hours,
                                    'cost' => $hours * $resp->hourly_rate
                                );
                            } else {
                                $totalAry[$option_field][$option_field_secondary]['hours'] += $hours;
                                $totalAry[$option_field][$option_field_secondary]['cost'] += $hours * $resp->hourly_rate;
                            }
                        }
                    }
                }
                $total_hours += $resp->legal_hours + $resp->support_hours;
                $total_cost += $resp->resp_compensation + $resp->resp_compensation * $resp->resp_benefit_pct;
            }
        }
        
        $res = array ();
        foreach ($data as $row) {
            if (!array_key_exists($row['option'], $res)) {
                $res[$row['option']] = array (
                    $row['sub_option'] => array (
                        $row['question_id'] => array (
                            'sub_option' => $row['sub_option'],
                            'question_id' => $row['question_id'],
                            'question_desc' => $row['question_desc'],
                            'hours' => $row['hours'],
                            'cost' => $row['cost']
                        )
                    )
                );
            } else {
                if (!array_key_exists($row['sub_option'], $res[$row['option']])) {
                    $res[$row['option']][$row['sub_option']] = array (
                        $row['question_id'] => array (
                            'sub_option' => $row['sub_option'],
                            'question_id' => $row['question_id'],
                            'question_desc' => $row['question_desc'],
                            'hours' => $row['hours'],
                            'cost' => $row['cost']
                        )
                    );
                } else {
                    if (!array_key_exists($row['question_id'], $res[$row['option']][$row['sub_option']])) {
                        $res[$row['option']][$row['sub_option']][$row['question_id']] = array (
                            'sub_option' => $row['sub_option'],
                            'question_id' => $row['question_id'],
                            'question_desc' => $row['question_desc'],
                            'hours' => $row['hours'],
                            'cost' => $row['cost']
                        );
                    } else {
                        $res[$row['option']][$row['sub_option']][$row['question_id']]['hours'] += $row['hours'];
                        $res[$row['option']][$row['sub_option']][$row['question_id']]['cost'] += $row['cost'];
                    }
                }
            }
        }
        
        $returnAry = array ();
        foreach ($res as $optionSelected => $row) {
            $tmpAry = array ();
            foreach ($row as $optionSelectedSecondary => $item) {
                $tmpArySecondary = array ();
                foreach ($item as $e) {
                    $percent = round(100 * $e['hours'] / $totalAry[$optionSelected][$optionSelectedSecondary]['hours']);
                    if ($percent > $min_percent) {
                        $e['option'] = $optionSelected;
                        $e['percent'] = $percent;
                        $e['hours'] = round($e['hours']);
                        $tmpArySecondary[] = $e;
                    }
                }
                if (!empty($tmpArySecondary)) {
                    // $keys = array_column($tmpArySecondary, 'question_desc');
                    // array_multisort($keys, SORT_ASC, $tmpArySecondary);
                    $tmpFlagSecondary = 1;
                    foreach ($tmpArySecondary as $f) {
                        if ($tmpFlagSecondary == 1) {
                            $f['rowspan_secondary'] = count($tmpArySecondary);
                            $tmpFlagSecondary = 0;
                        } else {
                            $f['rowspan_secondary'] = 0;
                        }
                        $tmpAry[] = $f;
                    }
                }
            }
            if (!empty($tmpAry)) {
                $tmpFlag = 1;
                $ary_key1 = array_column($tmpAry, 'sub_option');
                $ary_key2 = array_column($tmpAry, 'rowspan_secondary');
                $ary_key3 = array_column($tmpAry, 'question_desc');
                array_multisort($ary_key1, SORT_ASC, $ary_key2, SORT_DESC, $ary_key3, SORT_ASC, $tmpAry);
                foreach ($tmpAry as $e) {
                    if ($tmpFlag == 1) {
                        $e['rowspan'] = count($tmpAry);
                        $tmpFlag = 0;
                    } else {
                        $e['rowspan'] = 0;
                    }
                    array_push($returnAry, $e);
                }
            }
        }
        
        $keys1 = array_column($returnAry, 'option');
        $keys2 = array_column($returnAry, 'rowspan');
        $keys3 = array_column($returnAry, 'sub_option');
        $keys4 = array_column($returnAry, 'rowspan_secondary');
        array_multisort($keys1, SORT_ASC, $keys2, SORT_DESC, $keys3, SORT_ASC, $keys4, SORT_DESC, $returnAry);
        
        $return_data = array ();
        $return_data['rows'] = $returnAry;
        $return_data['grand_total_hours'] = $total_hours;
        $return_data['grand_total_cost'] = round($total_cost);

        return $return_data;
    }
    /**
     * 
     * Returns the array of string value for question of hierarchical taxonomy texts combined
     * 
     * @param array $questionAry    questions objects array
     * @return array 
     */
    public function getQuestionOneDescription ($questionAry) {
        $res = array ();
        foreach ($questionAry as $row) {
            $res[$row->question_id] = $this->getCombinedStringByQuesitonId($row->question_id, $questionAry);
        }
        return $res;
    }
    /**
     * 
     * Returns the string of question with hierarchical taxonomy texts combined
     * 
     * @param int $question_id    question id
     * @param array $questionAry  questions objects array
     * @return string 
     */
    public function getCombinedStringByQuesitonId ($question_id, $questionAry) {
        foreach ($questionAry as $row) {
            if ($row->question_id == $question_id) {
                if ($row->pid == 0) {
                    return $row->question_desc;
                } else {
                    return $this->getCombinedStringByQuesitonId($row->pid, $questionAry) . ".." . $row->question_desc;
                }
            }
        }
    }
}