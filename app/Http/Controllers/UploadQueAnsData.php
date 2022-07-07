<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadQueAnsData extends Controller
{   
    //upload Questions
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


    //upload answers
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
