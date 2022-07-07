<?php

namespace App\Http\Controllers;

use App\Gateways\ReportData;
use App\Gateways\TaxonomyData;
use App\Models\Answer;
use App\Models\Respondent;
use App\Models\Setting;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RespondentController extends Controller
{
    public function __construct()
    {
        $this->reportData = new ReportData();
        $this->taxonomyData = new TaxonomyData();
    }
    /**
     * 
     * Get the data for the demographic report
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Survey $survey
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(Request $request, Survey $survey)
    {

        $this->validateUser($survey);

        $respondents = Respondent::where('survey_id', $survey->survey_id)->get();

        foreach ($respondents as $resp) {
            $resp->hashed_survey = base64_encode($resp->survey_id);
            $resp->hashed_code = base64_encode($resp->resp_access_code);
        }

        $data['survey'] = $survey; // for the reports layout

        $labels = static::getLabels($survey->survey_id);

        return view('respondents.index', compact('respondents', 'survey', 'data', 'labels'));
    }
    /**
     * 
     * Download the respondents csv example file
     * 
     * @param \App\Models\Survey
     * @return \Illuminate\Http\Response
     */
    public function downloadExampleCsv(Survey $survey)
    {
        $f = fopen('example-respondents.csv', 'w');

        $columns =  [
            'access code',
            'respondent email address',
            'respondent first name',
            'respondnet last name',
            'rental square feet',
            'alternate text (0 or 1) ?',
        ];

        $columns = array_merge($columns, array_values((array) self::getLabels($survey->survey_id)));

        $columns[] = 'fte ?';
        $columns[] = 'compensation as a regular decimal number';
        $columns[] = 'benifits percentage (decimal number between 0.0 and 1.0)';

        fputcsv($f, $columns);

        fclose($f);

        $headers = array(
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-cache, must-revalidate'
        );

        return Response::download('example-respondents.csv', 'example-respondents.csv', $headers);
    }
    /**
     * 
     * Download the respondents csv file
     * 
     * @param \App\Models\Survey
     * @return \Illuminate\Http\Response
     */
    public function downloadRespondents(Survey $survey)
    {
        $respondents = Respondent::where('survey_id', $survey->survey_id)->get();

        $spreadsheet = new Spreadsheet();        
        $sheet = $spreadsheet->getActiveSheet();

        $columns = array (
            [
                'field' => 'COMPL',
                'width' => 10,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'FIRST NAME',
                'width' => 20,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'LAST NAME',
                'width' => 20,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'EMAIL',
                'width' => 40,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'Employee ID',
                'width' => 20,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'Group',
                'width' => 25,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'Title',
                'width' => 40,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'Department',
                'width' => 40,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'Category',
                'width' => 20,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'Location',
                'width' => 20,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'FTE',
                'width' => 20,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'BONUS',
                'width' => 20,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'SALARY',
                'width' => 20,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'START DT',
                'width' => 30,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'LAST DT',
                'width' => 30,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'RENTABLE SQ FT',
                'width' => 25,
                'color' => '#E8E8E8'
            ],
        );
        $colStr = 'A';
        foreach ($columns as $col) {
            $sheet->setCellValue($colStr . '2', $col['field']);
            $sheet->getColumnDimension($colStr)->setWidth($col['width']);
            ++$colStr;
        }
        $colStr = chr(ord($colStr) - 1);
        $sheet->getStyle("A2:{$colStr}2")
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('E8E8E8');

        $rows = 3;
       // dd($respondents[2]);
        foreach ($respondents as $resp) {
           
            
            if ($resp->survey_completed == 1) {
                $completed = 'C';
                $completed_color = 'E2EFDA';
            } else if ($resp->last_dt) {
                $completed = 'S';
                $completed_color = 'FFF3CB';
            }else{
                $completed = 'N';
                $completed_color = "E0EAF6";
            }
            
            $sheet->setCellValue('A' . $rows, $completed);
            $sheet->getStyle("A{$rows}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB($completed_color);
            $sheet->setCellValue('B' . $rows, $resp->resp_first);
            $sheet->setCellValue('C' . $rows, $resp->resp_last);
            $sheet->setCellValue('D' . $rows, $resp->resp_email);
            $sheet->setCellValue('E' . $rows, $resp->cust_1);
            $sheet->setCellValue('F' . $rows, $resp->cust_2);
            $sheet->setCellValue('G' . $rows, $resp->cust_3);
            $sheet->setCellValue('H' . $rows, $resp->cust_4);
            $sheet->setCellValue('I' . $rows, $resp->cust_5);
            $sheet->setCellValue('J' . $rows, $resp->cust_6);
            $sheet->setCellValue('K' . $rows, $resp->fte ? $resp->fte : 0.00);
            $sheet->setCellValue('L' . $rows, $resp->resp_bonus ? $resp->resp_bonus : 0.0);
            $sheet->setCellValue('M' . $rows, $resp->resp_total_compensation ? $resp->resp_total_compensation : 0.00); 
            $sheet->setCellValue('N' . $rows, $resp->start_dt ? $resp->start_dt : '');
            $sheet->setCellValue('O' . $rows, $resp->last_dt ? $resp->last_dt : '');
            $sheet->setCellValue('P' . $rows, $resp->rentable_square_feet ? $resp->rentable_square_feet : 0.0); 
            $rows++;          
        }

        $sheet->getColumnDimension('A')->setWidth(25);
        $surveyData = Survey::where('survey_id', $survey->survey_id)->firstOrFail();
        $sheet = $this->reportData->addExcelCopyright($sheet, $rows, $colStr, "Participants({$surveyData->survey_name})");

        $fileName = "Participants({$surveyData->survey_name})_" . date('m_d_Y') . ".xlsx";
        $writer = new Xlsx($spreadsheet);
        
        $writer->save($fileName);          

        $headers = array(
            'Content-Type' => 'text/xlsx',
            'Cache-Control' => 'no-cache, must-revalidate'
        );

        return Response::download($fileName, $fileName, $headers);
    }
    /**
     * 
     * Download the respondents all data excel file
     * 
     * @param \App\Models\Survey
     * @return \Illuminate\Http\Response
     */
    public function downloadParticipantAllData(Survey $survey)
    {
        
        
        $respondents = Respondent::where('survey_id', $survey->survey_id)
            ->orderBy("resp_last", "asc")
            ->get();
            //dd($respondents);
        $spreadsheet = new Spreadsheet();        
        $sheet = $spreadsheet->getActiveSheet();

        $columns = array (
            [
                'field' => 'COMPL',
                'width' => 10,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'FIRST NAME',
                'width' => 20,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'LAST NAME',
                'width' => 20,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'EMAIL',
                'width' => 40,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'Employee ID',
                'width' => 20,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'Group',
                'width' => 25,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'Title',
                'width' => 40,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'Department',
                'width' => 40,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'Category',
                'width' => 20,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'Location',
                'width' => 20,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'FTE',
                'width' => 20,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'BONUS',
                'width' => 20,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'SALARY',
                'width' => 20,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'START DT',
                'width' => 30,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'LAST DT',
                'width' => 30,
                'color' => '#E8E8E8'
            ],
            [
                'field' => 'RENTABLE SQ FT',
                'width' => 25,
                'color' => '#E8E8E8'
            ],
        );
        $colStr = 'A';
        foreach ($columns as $col) {
            $sheet->setCellValue($colStr . '2', $col['field']);
            $sheet->getColumnDimension($colStr)->setWidth($col['width']);
            ++$colStr;
        }
        $colStr = chr(ord($colStr) - 1);
        $sheet->getStyle("A2:{$colStr}2")
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('E8E8E8');

        $questions = $this->taxonomyData->getOrderedQuestionData($survey->survey_id);
       //dd($questions);
        $pid = 0;
        $question_color = "BDD7EE";
        foreach ($questions as $question) {
            if ($pid != $question['pid']) {
                if ($question_color == "BDD7EE") {
                    $question_color = "FFF3CB";
                } else {
                    $question_color = "BDD7EE";
                }
                $pid = $question['pid'];
            }
            $colStr++;
            if ($question['full_code'] != '') {
                $quesiton_desc = "[" . $question['full_code'] . "] {$question['name']}";
            } else {
                $quesiton_desc = $question['name'];
            }
            $sheet->setCellValue($colStr . '2', $quesiton_desc);
            $sheet->getStyle("{$colStr}2")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB($question_color);
            $sheet->getColumnDimension($colStr)->setAutoSize(true);
            //dd($sheet);
        }
       // dd($sheet);
        $respAnswerData = $this->reportData->getAnswerByRespondent($survey->survey_id);
        //dd($respAnswerData);
        $rows = 3;
        ini_set('memory_limit', '1024M');
        foreach ($respondents as $resp) {
            $completed = 'N';
            $completed_color = "E0EAF6";
            if ($resp->survey_completed == 1) {
                $completed = 'C';
                $completed_color = 'E2EFDA';
            } else if ($resp->last_dt) {
                $completed = 'S';
                $completed_color = 'FFF3CB';
            }
            $sheet->setCellValue('A' . $rows, $completed);
            $sheet->getStyle("A{$rows}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB($completed_color);
            $sheet->setCellValue('B' . $rows, $resp->resp_first);
            $sheet->setCellValue('C' . $rows, $resp->resp_last);
            $sheet->setCellValue('D' . $rows, $resp->resp_email);
            $sheet->setCellValue('E' . $rows, $resp->cust_1);
            $sheet->setCellValue('F' . $rows, $resp->cust_2);
            $sheet->setCellValue('G' . $rows, $resp->cust_3);
            $sheet->setCellValue('H' . $rows, $resp->cust_4);
            $sheet->setCellValue('I' . $rows, $resp->cust_5);
            $sheet->setCellValue('J' . $rows, $resp->cust_6);
            $sheet->setCellValue('K' . $rows, $resp->resp_benefit_pct ? $resp->resp_benefit_pct : 0.00);
            $sheet->setCellValue('L' . $rows, $resp->resp_bonus ? $resp->resp_bonus : 0.0);
            $sheet->setCellValue('M' . $rows, $resp->resp_total_compensation ? $resp->resp_total_compensation : 0.00); 
            $sheet->setCellValue('N' . $rows, $resp->start_dt ? $resp->start_dt : '');
            $sheet->setCellValue('O' . $rows, $resp->last_dt ? $resp->last_dt : '');
            $sheet->setCellValue('P' . $rows, $resp->rentable_square_feet ? $resp->rentable_square_feet : 0.0);
            
            $col = 'Q'; 
            if (!array_key_exists($resp->resp_id, $respAnswerData)) {
                foreach ($questions as $q) {
                    $col++;
                    $sheet->setCellValue($col . $rows, 0);
                }
            } else {
                $answerData = $respAnswerData[$resp->resp_id];
                foreach ($questions as $q) {
                    $col++;
                    if (!array_key_exists($q['id'], $answerData)) {
                        $sheet->setCellValue($col . $rows, 0);
                    } else {
                        $sheet->setCellValue($col . $rows, $answerData[$q['id']]);
                    }
                }
            }
            $rows++;          
        } 

        $sheet->getColumnDimension('A')->setWidth(25);
        $surveyData = Survey::where('survey_id', $survey->survey_id)->firstOrFail();
        $sheet = $this->reportData->addExcelCopyright($sheet, $rows, $colStr, "Participants All Data({$surveyData->survey_name})");

        $fileName = "Participants_All_Data({$surveyData->survey_name}).xlsx";
        $writer = new Xlsx($spreadsheet);
        
        $writer->save($fileName);        

        $headers = array(
            'Content-Type' => 'text/xlsx',
            'Cache-Control' => 'no-cache, must-revalidate'
        );
        
       return Response::download($fileName, $fileName, $headers);
    }
    /**
     * 
     * Download the respondents csv file
     * 
     * @param \App\Models\Survey $survey
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function uploadRespondents(Survey $survey, Request $request)
    {
        $data = $request->all();

        $this->validateUser($survey);

        if ($request->hasFile('file_respondent_upload')) {
            $file = $request->file('file_respondent_upload');
            if ($file->getClientOriginalExtension() == 'csv') {
                $f = fopen($file->getRealPath(), 'r');

                while (($filedata = fgetcsv($f, 1000, ",")) !== false) {
                    // check if the respondent already exists based on email or first / last name
                    // $respondent = Respondent::where('resp_email', $filedata[1])->orWhere(function($q) use ($filedata) {
                    //     $q->where('resp_first', $filedata[2])->where('resp_last', $filedata[3]);
                    // })->first();

                    $respondent = Respondent::where(function ($q) use ($filedata) {
                        $q->where('resp_first', $filedata[2])->where('resp_last', $filedata[3]);
                    })->first();

                    // if respondent does not exist create a new one
                    if (!$respondent)
                        $respondent = new Respondent();


                    if (isset($filedata[0]))
                        $respondent->resp_access_code = $filedata[0];
                    else {
                        // generate new access code
                    }

                    $respondent->resp_email = $filedata[1];
                    $respondent->resp_first = $filedata[2];
                    $respondent->resp_last = $filedata[3];
                    $respondent->rentable_square_feet = $filedata[4];
                    // $respondent->alternate_text = $filedata[5];
                    $respondent->cust_1 = $filedata[6];
                    $respondent->cust_2 = $filedata[7];
                    $respondent->cust_3 = $filedata[8];
                    $respondent->cust_4 = $filedata[9];
                    $respondent->cust_5 = $filedata[10];
                    $respondent->cust_6 = $filedata[11];
                    // $respondent->fte = $filedata[12];
                    $respondent->resp_compensation = $filedata[13];
                    $respondent->resp_benefit_pct = $filedata[14];
                    $respondent->survey_id = (int) $survey->survey_id;

                    $respondent->save();
                }

                fclose($f);
            }
        }

        return redirect()->action([RespondentController::class, 'index'], $survey);
    }
    /**
     * 
     * Validate the user to access to the respondents
     * 
     * @param \App\Models\Survey $survey
     * @param \App\Models\Respondent $respondent
     * @return \App\Models\Respondent
     */
    public function getRespondent(Survey $survey, Respondent $respondent)
    {
        $this->validateUser($survey);
       //

        $respondent['resp_compensation'] = implode(',',array_map("strrev", array_reverse(str_split(strrev((int)$respondent['resp_compensation']), 3))));
        //dd($respondent['resp_compensation']);
        return $respondent;
    }
    /**
     * 
     * Insert respondent
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveRespondent(Request $request)
    {
        $data = $request->all();

        $survey = Survey::find($data['survey_id']);
        $this->validateUser($survey);
        //dd($survey);
        if (isset($data['participant_id'])) {
            $respondent = Respondent::find($data['participant_id']);


        } else {
            $respondent = new Respondent();
        }

        $compensationArray = explode(',',$data['resp_compensation']);

        if(count($compensationArray) > 1){
            $data['resp_compensation'] = (float)implode('',explode(',',$data['resp_compensation']));
        }else{
            $data['resp_compensation'] = (float)$data['resp_compensation'];
        }

        if(count(explode('.',$data['resp_benefit_pct'])) > 1){
            $data['resp_benefit_pct'] = (float)(explode('%',$data['resp_benefit_pct'])[0]);
            $data['resp_benefit_pct'] = $data['resp_benefit_pct'];
        }else{
            $data['resp_benefit_pct'] = (float)(explode('%',$data['resp_benefit_pct'])[0]);
            $data['resp_benefit_pct'] = $data['resp_benefit_pct']/100;
        }
        
        //dd($data['resp_benefit_pct']);

        $respondent->resp_access_code = $data['resp_access_code'];
        $respondent->resp_first = $data['resp_first'];
        $respondent->resp_last = $data['resp_last'];
        $respondent->resp_email = $data['resp_email'];
        $respondent->cust_1 = $data['cust_1'];
        $respondent->cust_2 = $data['cust_2'];
        $respondent->cust_3 = $data['cust_3'];
        $respondent->cust_4 = $data['cust_4'];
        $respondent->cust_5 = $data['cust_5'];
        $respondent->cust_6 = $data['cust_6'];
        $respondent->rentable_square_feet = $data['rentable_square_feet'];
        $respondent->resp_compensation = $data['resp_compensation'];
        $respondent->resp_benefit_pct = $data['resp_benefit_pct'];
        $respondent->survey_id = (int) $survey->survey_id;

       // $respondent->alternate_text = $data['alternate_text'];
        $respondent->fte = $data['fte'];

        $respondent->save();

        return redirect()->action([RespondentController::class, 'index'], $survey);
    }
    /**
     * 
     * Delete the respondent
     * 
     * @param \App\Models\Survey $survey
     * @param \App\Models\Respondent $respondent
     * @return \App\Models\Respondent
     */
    function removeRespondent(Survey $survey, Respondent $respondent)
    {
        $this->validateUser($survey);
        $respondent->delete();
        return $respondent;
    }
    /**
     * 
     * Get the survey data
     * 
     * @param int $survey_id 
     * @param \Illuminate\Http\Request $request
     * @return \DB
     */
    public static function getLabels($survey_id)
    {
        $survey_data = \DB::table('tblsurvey')
            ->select(
                'cust_1_label AS cust_1',
                'cust_2_label AS cust_2',
                'cust_3_label AS cust_3',
                'cust_4_label AS cust_4',
                'cust_5_label AS cust_5',
                'cust_6_label AS cust_6',
            )
            ->where('survey_id', $survey_id)
            ->first();

        return $survey_data;
    }

    private function validateUser($survey)
    {
        CommonController::validateUser($survey->survey_id, 'surveyRespondents');
    }
    /**
     * 
     * Reset the respondents data
     * 
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function resetRespondents(Request $request)
    {
        $resp_ids = $request->resp_ids;
        //dd($resp_ids);
        foreach ($resp_ids as $resp_id) {
            $respondent = Respondent::find($resp_id);
            Answer::where('resp_id', $resp_id)->delete();
            $respondent->last_dt = null;
            $respondent->start_dt = null;
            $respondent->last_page_id = null;
            $respondent->survey_completed = 0;
            $respondent->save();
        }
    }
    /**
     * 
     * Delete the respondents
     * 
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function deleteRespondents(Request $request)
    {
        $resp_ids = $request->resp_ids;

        foreach ($resp_ids as $resp_id) {
            $respondent = Respondent::find($resp_id);
            Answer::where('resp_id', $respondent->id)->delete();
            $respondent->delete();
        }
    }
    /**
     * 
     * Survey Landing Index
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function surveyLanding(Request $request)
    {
        $survey_id = base64_decode($request->sv);
        $access_code = base64_decode($request->ac);

        $respondent = Respondent::where('resp_access_code', $access_code)->first();
        $survey = Survey::find($survey_id);
        session()->put('survey', $survey);
        session()->put('respondent', $respondent);
        session()->put('survey_hash', $request->sv);
        session()->put('code_hash', $request->ac);
        session()->save();
        
        $settings = Setting::where('survey_id', $survey_id)->first();
        
        if($settings == null){
            return redirect()->back()->withErrors(['message'=>'First Fill up settings details']);
        }

        if (!$settings->show_splash_page){
            return redirect(action([RespondentController::class, 'surveyQuestionnaire']));
        }
           

        return view('survey.landing', compact('survey', 'settings'));
    }
    /**
     * 
     * Survey Questionnaire
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function surveyQuestionnaire(Request $request)
    {
        if (!session()->has('survey_hash') || !session('code_hash') || !session()->has('respondent') || !session('survey'))
            return redirect(route('survey.error'))->with('error', 'It appears you do not have permission to access this survey. If this is in error, please try the survey link sent to you.');

        $settings = Setting::where('survey_id', session('survey')->survey_id)->first();
        $survey = Survey::where('survey_id', session('survey')->survey_id)->first();
        
        return view('survey.questions', compact('settings', 'survey'));
    }
    /**
     * 
     * Survey error page
     * 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function surveyError()
    {
        return view('survey.error');
    }
    /**
     * 
     * Generate the acccess code for respondent
     * 
     * @return string
     */
    public function generateAccessCode()
    {
        $chars = '1A2BC3DE43G5HI6JK7LM8NO9PQ0RPT7VWXYZ';
        $result = '';
        for ($p = 0; $p < 6; $p++) {
            $result .= ($p % 2) ? $chars[mt_rand(19, 35)] : $chars[mt_rand(0, 18)];
        }
        return $result;
    }
    /**
     * 
     * Validate the respondents access code from the database
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function validateAccessCode(Request $request)
    {
        $code = $request->input('code');
        return (['count' => Respondent::where('resp_access_code', $code)->count()]);
    }
}
