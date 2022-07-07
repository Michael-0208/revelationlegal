<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\Respondent;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Gateways\SurveyData;
use App\Models\Answer;
use App\Models\Question;
use App\Models\TblQuestionTest;
use App\Models\RespondentLocation;
use App\Models\HelpManager;
use App\Models\SupportLocation;
use App\Models\SurveyProgress;
use Illuminate\Support\Facades\Mail;

class SurveyController extends Controller
{
    /**
     * 
     * Dashboard index
     * 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function dashboard()
    {
        if (!Auth::user()) {
            return view('auth.login');
        }

        // user must have the "Survey" permission to view this page, but that permission is not necessarily tied to any particular survey - because we don't know which survey the user will choose
        /* if (!Auth::user()->permissions->where('name', 'Survey')){
            return abot(403);
        } */
           

        // Log the last login time
        Auth::user()->last_login = Carbon::now();
        Auth::user()->save();
        
        return view('dashboard');
    }
    /**
     * 
     * Survey index
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function survey_index(Request $request)
    {

        if (!Auth::user()) {
            return view('auth.login');
        }
        $survey_id = $request->id;
        $survey = Survey::where('survey_id', $survey_id)->firstOrFail();
        CommonController::validateUser($survey->survey_id, 'surveyProfile');
        $respondents = Respondent::where('survey_id', '=', $survey_id)->get();
        $total_resp = 0;
        $completed_resp = 0;
        $sent = 0;
        foreach ($respondents as $respondent) {
            if ($respondent->last_dt) {
                $total_resp++;
            }
            if ($respondent->survey_completed == 1 || $respondent->survey_completed == 2) {
                $completed_resp++;
            }

            $sent++;
        }
        $percent_total = $sent > 0 ? round(($total_resp / $sent) * 100) : 0;
        $percent_completed = $total_resp > 0 ? round(($completed_resp / $total_resp) * 100) : 0;
        $survey_active = 'Active';
        if ($survey->survey_active == 0) {
            $survey_active = "Inactive";
        }

        $data = [];
        $data['invitations_sent'] = $sent;
        $data['percent_total'] = $percent_total;
        $data['percent_completed'] = $percent_completed;
        $data['survey_active'] = $survey_active;
        $data['total_resp'] = $total_resp;
        $data['completed_resp'] = $completed_resp;
        $data['survey'] = $survey;
        $data['helpContent'] = HelpManager::find(1);
       
        (($data['helpContent']->helpImages) != '') ? $HelpImages = unserialize($data['helpContent']->helpImages) : $HelpImages = [];
        //  dd( $HelpImages);
        if(count($HelpImages) > 0){
            foreach ($HelpImages as $key => $image) {
                
                $data['helpContent']->course = str_replace('['.$key.']', '<img src="'.url('/helpcontent').'/'.$image.'">' ,$data['helpContent']->course ); 
              
            }
        }else{
            $data['helpContent']->course = str_replace("[help_image_1]",'', $data['helpContent']->course );
            $data['helpContent']->course = str_replace("[help_image_2]",'', $data['helpContent']->course );
        }
        // dd($data['helpContent']->course);
        

        $survey_data = Survey::where('survey_id', $survey_id)->firstOrFail();
        return view('surveys.surveyindex', ['data' => $data, 'survey' => $survey_data]);
    }
    /**
     * 
     * Toggle the survey activated
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function toggle_active (Request $request) {
        $params = $request->all();

        $updated = Survey::find($params['survey_id']);
        $updated->survey_active = $params['survey_active'];
        if ($updated->save()) {
            return 200;
        } else {
            return 404;
        }
    }
    /**
     * 
     * Update the survey data
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Survey $survey
     * @return DB
     */
    public function saveFieldLabels(Request $request, Survey $survey)
    {
        $updated = \DB::table('tblsurvey')
            ->where('survey_id', $survey->survey_id)
            ->update($request->except(['_token']));

        return $updated;
    } 

    /**
     * Get the data related to the specifed page, including the previous page id, next page id, and page questions
     *
     * @param Request $request
     * @param SurveyData $survey_gateway
     * @return array
     */
    public function getQuestionData(Request $request, Question $question)
    {
        // dd(';tes');
        $respondent_id = $request->input('respondent_id');

        $survey_gateway = new SurveyData($respondent_id);
        
      
        return $survey_gateway->getCurrentQuestion($question->question_id);
      /*   }else{
            return $survey_gateway->getCurrentQuestion($questionTest->question_id);
        } */
       
    }

    /**
     * Save the answers to this question and return the next set of questions
     *
     * @param Request $request
     * @return View
     */
    public function saveQuestions(Request $request)
    {
        $params = $request->all();
        $current_question = $params['currentQuestionId'];

        foreach ($params['questions'] as $question) {
            // determine if the respondent already answered this question
            $existing = Answer::where([['resp_id', $params['respondent_id']], ['question_id', $question['id']]])->first();

            // if answer is 0 and there's already an existing answer - delete it
            if (!$question['answer'] && $existing) {
                $existing->delete();
                continue;
            }

            // if the answer is 0 and there is no existing answer, just skip to the next question to avoid creating a 0 valued answer
            elseif (!$question['answer'])
                continue;

            // if the respondent already has an answer, than update it
            if ($existing) {
                $existing->answer_value = $question['answer'];
                $existing->save();
            }
            // else create a new answer for this respondent
            else {
                $existing = Answer::create([
                    'resp_id' => (int) $params['respondent_id'],
                    'question_id' => (int) $question['id'],
                    'answer_value' => (int) $question['answer']
                ]);
            }
        }

        $survey_gateway = new SurveyData($params['respondent_id']);


        // get the next set of questions
        $next_question = $survey_gateway->getNextQuestion($current_question);
        $data = [
            'next_question' =>  $next_question ? $next_question['question_id'] : null,
        ];

        // update last_dt of respondent
        $respondent = Respondent::where('resp_id', $params['respondent_id'])
            ->where('survey_id', $params['survey_id'])
            ->first();
        $respondent->last_dt = now();
        if (array_key_exists('branchLength', $params) && ($params['branchLength'] == $params['branchIndex'] + 1)) {
            $respondent->survey_completed = 1;
        }
        $respondent->update();

        return $data;
    }
    /**
     * 
     * Invitation Settings
     * 
     * @param Request $request
     * @return void
     */
    public function saveLocations(Request $request)
    {
        $data = $request->all();

        $support_locations = SupportLocation::where('survey_id', $data['survey_id'])->get()->pluck('id');

        RespondentLocation::where('resp_id', $data['respondent_id'])
            ->whereIn('support_location_id', $support_locations)
            ->delete();

        foreach ($data['locations'] as $location) {

            if ($location['answer'])
                RespondentLocation::create([
                    'resp_id' => $data['respondent_id'],
                    'support_location_id' => $location['support_location_id'],
                    'resp_pct' => $location['answer'],
                ]);
        }

        $respondent = Respondent::where('resp_id', $data['respondent_id'])
            ->where('survey_id', $data['survey_id'])
            ->first();
        
        if (is_null($respondent->start_dt)) {
            $respondent->start_dt = now();
            $respondent->last_dt = now();
        } else {
            $respondent->last_dt = now();
        }

        $respondent->update();
    }
    /**
     * 
     * Reset question answers
     * 
     * @param int $survey_id    survey id
     * @param string $status
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function resetQuestion(Request $request)
    {
        $survey_id = $request->survey_id;
        $respondent_id = $request->respondent_id;
        $question_id = $request->question_id;

        $survey_gateway = new SurveyData($respondent_id);
        $survey_gateway->resetQuestions($survey_id, $question_id);

        // update last_dt of respondent
        $respondent = Respondent::where('resp_id', $respondent_id)
        ->where('survey_id', $survey_id)
        ->first();        
        $respondent->last_dt = now();
        $respondent->update();

        return [
            'success' => true,
        ];
    }
    /**
     * 
     * Reset location answers
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function resetLocations(Request $request)
    {
        $data = $request->all();

        $support_locations = SupportLocation::where('survey_id', $data['survey_id'])->get()->pluck('id');

        RespondentLocation::where('resp_id', $data['respondent_id'])
            ->whereIn('support_location_id', $support_locations)
            ->delete();
        
        // update last_dt of respondent
        $respondent = Respondent::where('resp_id', $data['respondent_id'])
            ->where('survey_id', $data['survey_id'])
            ->first();
        $respondent->last_dt = now();
        $respondent->update();

        return [
            'success' => true,
        ];
    }

    /**
     * Get the root questions legal and / or support depending on user selection
     * 
     * @param \Illuminate\Http\Request
     * @param \Illuminate\Http\Response
     */
    public function getSurveyBranches(Request $request)
    {
        $data = $request->all();
        $survey_gateway = new SurveyData($data['respondent_id']);
        return $survey_gateway->getRootQuestion($data['survey_id']);
    }

    /**
     * Delete the answers for this respondent associated with the legal branch
     * 
     * @param \Illuminate\Http\Request
     * @param \Illuminate\Http\Response
     */
    public function deleteLegalAnswers(Request $request)
    {
        $survey_id = $request->survey_id;
        $gateway = new SurveyData($request->respondent_id);
        // update last_dt of respondent
        $respondent = Respondent::where('resp_id', $request->respondent_id)
            ->where('survey_id', $survey_id)
            ->first();
        $respondent->last_dt = now();
        $respondent->update();

        return  $gateway->deleteLegalAnswers(Survey::find($survey_id));

        return [
            'status' => 'success'
        ];
    }
    /**
     * 
     * Create survey view
     * 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function createSurvey()
    {
        return view('surveys.create');
    }
    /**
     * 
     * Return the file of the participatn guide
     * 
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function participantGuide(){
        return response()->file('Participants_Guide.pdf');
    }
    /**
     * 
     * Return the table html for print
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function printSurvey(Request $request)
    {
        $survey_id = $request->survey_id;
        $respondent_id = $request->respondent_id;
        $survey_gateway = new SurveyData($respondent_id);

        // open table
        $summary_table = '<table class="table"><tbody>';

        // find the root / branch questions
        $questions = $survey_gateway->getRespondentAnswers($survey_id, 0);

        // add the heading for the root questions
        $summary_table .= "<tr style='background-color: #E5E5E5; color: #248ABC; font-size: x-large;'><td>Branch</td><td align='right'>Hours</td></tr>";

        // add a row for each root question
        foreach ($questions as $question) {
            $question->depth = 0;
            $style = $question->answer_value ? "font-weight: 900;" : "";
            $summary_table .= "<tr style='$style'><td style=''>$question->question_desc</td><td align='right'>$question->answer_value</td></tr>";
        }

        // iterate over the questions in this survey display child questions and descandants
        while (($question = $questions->pop())) {

            $child_questions = $survey_gateway->getRespondentAnswers($survey_id, $question->question_id);

            if (!$child_questions->count())
                continue;

            $depth = $question->depth;

            $summary_table .= "<tr style='background-color: #E5E5E5; color: #248ABC; font-size: x-large;'><td>$question->question_desc</td><td align='right'>Percentage</td></tr>";

            foreach ($child_questions as $child) {

                if ($child->answer_value)
                    $questions->push($child);

                // if ($question->question_id_parent === 0)
                //     $child->depth = 0;
                // else
                $child->depth =  $depth + 3;

                if ($depth > 0)
                    $indent = '|' . str_repeat('-', $depth);
                else
                    $indent = '';

                $percentage = $child->answer_value ? $child->answer_value : 0;
                $style = $child->answer_value ? "font-weight: 900;" : "";
                $summary_table .= "<tr style='$style'><td>$indent $child->question_desc</td><td align='right'>$percentage%</td></tr>";
                
               

                


            }
        }

        $summary_table .= '</tbody></table>';
        Respondent::where('resp_id',$respondent_id)->update(['survey_completed'=>1]);
        $respondent_details = Respondent::find($respondent_id);

        Mail::send([], [], function ($message) use ($respondent_details) {
            $message->from('support@revelationlegal.com', 'support@revelationlegal.com');
            $message->to($respondent_details->resp_email); //$respondent_details->resp_email caisonlee28@gmail.com (for dynamic respondent_details)
            $message->subject('Your Questionnaire');
            
            $body ='<html>

            <body>
                <table align="center" width="600" style="font-family: sans-serif;">
                    <tr>
                        <td style="text-align: center;"><img width="180"
                                src="http://127.0.0.1:8000/public/imgs/revel-i-logo.png" style="max-width:180px;"></td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin-top: 20px;">
                                Hi '.$respondent_details->resp_first.', <br>
                            <p style="font-family: sans-serif;font-size: 15px;margin: 10px 0;">
                            Thank you for completing your questionnaire.  Within the next 48 hours, you should receive your results along with instructions on how to interpret the results.
                            </p>
                            <p style="font-family: sans-serif;font-size: 15px;margin: 0 0 10px;">
                            If for any reason you do not receive your materials, contact us at support@revelationlegal.com so we can promptly resolve the issue.
            
                            </p>
                            <p style="font-family: sans-serif;font-size: 15px;margin:0 0 10px;">
                            Thank you.
                            </p>
                            <p style="font-family: sans-serif;font-size: 12px;margin: 25px 0 0 0;">Notice: This communication may
                                contain privileged or other confidential information. If you have received it in error, please
                                advise the sender by reply email and immediately delete the message and any attachments without
                                copying or disclosing the contents.
                            </p>
                        </td>
                    </tr>
                </table>
            </body>
            
            </html>';
                
            $message->setBody($body, 'text/html');
            $message->attach( url('/') . '/public/REV_LEGAL-i_GUIDE.pdf');
            /* $message->attach( url('/') . '/public/respondentPDF/'.$resp['filename']);
            $message->attach( url('/') . '/public/REV_LEGAL-i_GUIDE.pdf'); *

            //return 1;
            // $message->attach('https://www.orimi.com/pdf-test.pdf');*/
        });
        return $summary_table;
    }
    /**
     * 
     * Save survey progress in the database
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function setSurveyProgress (Request $request) {
        $params = $request->all();

        $check_query = SurveyProgress::where('survey_id', $params['survey_id'])
            ->where('resp_id', $params['respondent_id']);
        
        if (count($check_query->get()) > 0) {
            $check_query->update([
                'data' => $params['req']
            ]);
        } else {
            $survey_progress = new SurveyProgress();
            $survey_progress->survey_id = $params['survey_id'];
            $survey_progress->resp_id = $params['respondent_id'];
            $survey_progress->data = $params['req'];
    
            $survey_progress->save();
        }
        
        return 200;
    }
}
