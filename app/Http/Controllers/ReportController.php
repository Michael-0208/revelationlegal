<?php

namespace App\Http\Controllers;

use App\Models\Respondent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Gateways\ReportData;
use App\Models\Survey;
use DateTime;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    /**
     * @var \App\Gateways\ReportData
     */
    protected $reportData;
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request, ReportData $reportData)
    {
        $this->middleware(['auth:sanctum', 'verified']);
        $this->reportData = $reportData;
        $this->request = $request;
    }
    /**
     * Validate the user is logged in and assigned to the survey
     * 
     * @param int $survey_id    survey id
     * @return void
     */
    protected function validateUser($survey_id)
    {
        if (!Auth::user()) {
            return redirect('login');
        }
        /* if (!Auth::user()->is_admin && Auth::user()->survey_id != $survey_id) {
            abort(403, 'Unauthorized Action!');
        } */
    }

   

    public function SendReport(Request $request){
        //dd($request);
        $resp = Respondent::find($request->input('resp_id'));


        $file = $request->file('RespondentPDF');
        $TimeStamp = Carbon::now()->timestamp;
        $destinationPath = public_path('/').'/respondentPDF/';
        $FileName = $file->getClientOriginalName().$resp->resp_first.$TimeStamp.'.pdf';
        //dd($destinationPath);
        $fileMoved = $file->move($destinationPath,$FileName);
       
        $resp['filename'] = $FileName;
       // dd($resp['filename']);
        Mail::send([], [], function ($message) use ($resp) {
            $message->from('support@revelationlegal.com', 'support@revelationlegal.com');
            $message->to($resp->resp_email); //$resp->resp_email caisonlee28@gmail.com (for dynamic respondent)
            $message->subject('Your Survey Report');
            
            $body ='<html>
                    <body>
                    <table align="center" width="600" style="font-family: sans-serif;">
                    <tr>
                    <td style="text-align: center;"><img width="300px" src="http://127.0.0.1:8000/public/imgs/revel-i-logo.png"></td></tr> 
                    <tr><td>
                    <p style="margin-top: 20px;">
                    Hi '.$resp->resp_first.', <br>
                    <p style="margin: 10px 0;">Welcome to your future of increased productivity and effectiveness!</p>

                    <p style="margin: 10px 0;">The attached custom report provides a detailed job analysis based on the information you provided when you completed our online questionnaire.  Be sure to review the accompanying guide that will help you interpret the results and identify opportunities for improvement.</p>

                     <p style="margin: 10px 0;">And remember, understanding how you work is the gateway to innovation and a better quality of life. RevelationLegal-i is the key that unlocks it.</p>   

                    <p style="margin: 10px 0;">If you have questions or need assistance, contact us at support@revelationlegal.com.</p> 

                    <p style="font-size:9px; ">Notice: This communication may contain privileged or other confidential information. If you have received it in error, please advise the sender by reply email and immediately delete the message and any attachments without copying or disclosing the contents.</p> 
                    <br><br>
                    Thanks, <br>
                    Support RevlationLegal 
                    </p>
                    </td></tr>
                    </table>
                    </body> 
                </html>';
               
            $message->setBody($body, 'text/html');
            $message->attach( url('/') . '/public/respondentPDF/'.$resp['filename']);
            $message->attach( url('/') . '/public/REV_LEGAL-i_GUIDE.pdf');
            
            return 1;
           // $message->attach('https://www.orimi.com/pdf-test.pdf');
        });


        $respUpdate = Respondent::where('resp_id',$request->input('resp_id'))->update(['survey_completed'=>2]);



      /*   if(!empty($_POST['data'])){
            $data = base64_decode($_POST['data']);
            

            print_r($data);
            exit;
            
            $fileName = 'SendReport';
        
            file_put_contents( "/public/".$fileName.".pdf", $data );
            echo "done";
        } else {
            echo "No Data Sent";
        } */
    }


    /**
     * 
     * Individual Report
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function individual_report($survey_id)
    {
        CommonController::validateUser($survey_id, 'surveyReports');
        $attrs = CommonController::get_survey_attributes($survey_id);

        $resps = $this->reportData->get_resp_data($survey_id);
        // dd($resps);
        $data['resps'] = $resps;
        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $survey = (object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']
        ];
        $data['survey'] = $survey;

        $tmpPosition = array();
        $tmpDepartment = array();
        $tmpGroup = array();
        $tmpLocation = array();
        $tmpCategory = array();
        foreach ($resps as $resp) {
            if (!array_key_exists($resp->cust_3, $tmpPosition)) {
                $tmpPosition[$resp->cust_3] = $resp->cust_3;
            }
            if (!array_key_exists($resp->cust_4, $tmpDepartment)) {
                $tmpDepartment[$resp->cust_4] = $resp->cust_4;
            }
            if (!array_key_exists($resp->cust_2, $tmpGroup)) {
                $tmpGroup[$resp->cust_2] = $resp->cust_2;
            }
            if (!array_key_exists($resp->cust_6, $tmpLocation)) {
                $tmpLocation[$resp->cust_6] = $resp->cust_6;
            }
            if (!array_key_exists($resp->cust_5, $tmpCategory)) {
                $tmpCategory[$resp->cust_5] = $resp->cust_5;
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
        $data['survey_status'] = [];
        // dd($)

        return view('reports.reports.individual', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Compilation Report
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function compilation_report($survey_id)
    {
        CommonController::validateUser($survey_id, 'surveyReports');
        $resps = $this->reportData->get_resp_data($survey_id);
        // dd($resps);
        // if($survey_id != 54){
        $parents = DB::table('tblquestion')
        ->leftJoin('tblpage', 'tblquestion.page_id', 'tblpage.page_id')
        ->where('tblquestion.survey_id', $survey_id)
        ->where('tblpage.question_id_parent', 0)
        ->get();
       
        foreach ($parents as $item) {
            if ($item->question_desc == 'Legal Services' || $item->question_desc == 'Legal Activities') {
                $data['legal_id'] = $item->question_id;
                $data['legal_label'] = $item->question_desc;
            } else {
                $data['support_id'] = $item->question_id;
                $data['support_label'] = $item->question_desc;
            }
        }

        // dd($data);
        
        $data['resps'] = $resps;
        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        
        $survey = (object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']
        ];
        
        $data['survey'] = $survey;
        $data['total_cost'] = 0;
        $data['total_hours'] = 0;

        $data['legal_hours'] = 0;
        $data['support_hours'] = 0;

        $data['legal_cost'] = 0;
        $data['support_cost'] = 0;

        $tmpPosition = array();
        $tmpDepartment = array();
        $tmpGroup = array();
        $tmpLocation = array();
        $tmpCategory = array();

        foreach ($resps as $resp) {
            if (!array_key_exists($resp->cust_3, $tmpPosition)) {
                $tmpPosition[$resp->cust_3] = $resp->cust_3;
            }
            if (!array_key_exists($resp->cust_4, $tmpDepartment)) {
                $tmpDepartment[$resp->cust_4] = $resp->cust_4;
            }
            if (!array_key_exists($resp->cust_2, $tmpGroup)) {
                $tmpGroup[$resp->cust_2] = $resp->cust_2;
            }
            if (!array_key_exists($resp->cust_6, $tmpLocation)) {
                $tmpLocation[$resp->cust_6] = $resp->cust_6;
            }
            if (!array_key_exists($resp->cust_5, $tmpCategory)) {
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
        // dd($data['support_cost']);
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

        if ($data['total_hours'] == 0) {
            $data['hourly_rate'] = 0;
        } else {
            $data['hourly_rate'] = round($data['total_cost'] / $data['total_hours']);
        }
        $data['total_cost'] = round($data['total_cost']);
        $data['total_hours'] = round($data['total_hours']);

        $data['respondents_num'] = number_format($resps->count());

        // dd($data);
        return view('reports.reports.compilation', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Validation Report
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function validation_report($survey_id)
    {
        CommonController::validateUser($survey_id, 'surveyReports');

        $data = array();        

        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $survey = (object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']
        ];
        $data['survey'] = $survey;

        return view('reports.reports.validation', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Get the data of irregular list
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIrregularList () {
        // Initialize the parameter to get irregular list
        $survey_id = $this->request->input('survey_id');
        $count = $this->request->input('count');
        $max_percent = $this->request->input('max_percent');
        $min_hr = $this->request->input('min_hr');
        $max_hr = $this->request->input('max_hr');
        $depth = $this->request->input('depth');

        $respondents = Respondent::where('survey_id', $survey_id)
            ->where('survey_completed', 1)
            ->get()
            ->count();

        $response = $this->reportData->getIrregularList($survey_id, $count, $max_percent, $max_hr, $min_hr, $depth);

        if (!empty($response)) {
            $data['status'] = 200;
            $data['res'] = $response;
            $data['resp_num'] = $respondents;
            $data['irregular_num'] = count($response);
        } else {
            $data['status'] = 400;
        }

        return json_encode($data);
    }
    /**
     * 
     * Get the irregular data of classifcations
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClassificationIrregular () {
        $survey_id = $this->request->input('survey_id');
        $params['max_percent'] = $this->request->input('max_percent');

        $response = $this->reportData->getDetailIrregularList($survey_id, $params, 'classification');

        if (!empty($response)) {
            $data['status'] = 200;
            $data['data'] = $response;
        } else {
            $data['status'] = 400;
        }

        return json_encode($data);
    }
    /**
     * 
     * Get the irregular data of options
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOptionsIrregular () {
        $survey_id = $this->request->input('survey_id');
        $params['max_options'] = $this->request->input('max_options');

        $response = $this->reportData->getDetailIrregularList($survey_id, $params, 'options');

        if (!empty($response)) {
            $data['status'] = 200;
            $data['data'] = $response;
        } else {
            $data['status'] = 400;
        }

        return json_encode($data);
    }
    /**
     * 
     * Get the irregular data of deviation
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeviationIrregular () {
        $survey_id = $this->request->input('survey_id');
        $params['max_hrs'] = $this->request->input('max_hrs');
        $params['min_hrs'] = $this->request->input('min_hrs');

        $response = $this->reportData->getDetailIrregularList($survey_id, $params, 'deviation');

        if (!empty($response)) {
            $data['status'] = 200;
            $data['data'] = $response;
        } else {
            $data['status'] = 400;
        }

        return json_encode($data);
    }
/**
     * 
     * Get the full irregular data of classifcations
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFullDataOfIrregular () {
        $request = $this->request;

        $res = array ();

        $data = $this->reportData->getFullIrregularDataByResp($request);

        if (!empty($data)) {
            $res['status'] = 200;
            $res['data'] = $data;
        } else {
            $res['status'] = 400;
        }

        return json_encode($res);
    }
    /**
     * 
     * Get the url of the exported excel file
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportExcelSummaryData () {
        $survey_id = $this->request->input('survey_id');
        $sort    = $this->request->input('sort');
        $columns = $this->request->input('columns');
        $data    = $this->request->input('data');
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $colStr = 'A';
        foreach ($columns as $col) {
            $sheet->setCellValue($colStr . '1', $col);
            ++$colStr;
        }
        $colStr = chr(ord($colStr) - 1);
        $spreadsheet
            ->getActiveSheet()
            ->getStyle("A1:{$colStr}1")
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('c9c9c7');

        $spreadsheet
            ->getActiveSheet()
            ->getStyle("A1:{$colStr}1")
            ->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center');

        $rows = 2;
        $colStr = 'A';
        foreach ($data as $row) {
            $sheet->setCellValue($colStr . $rows, $row);
            ++$colStr;
        }

        $spreadsheet->getActiveSheet()->getStyle("A1:$colStr$rows")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        for ($tmpStr = 'B'; $tmpStr < $colStr; ++$tmpStr) {
            $spreadsheet->getActiveSheet()->getColumnDimension($tmpStr)->setWidth(30);
        }

        $surveyData = Survey::where('survey_id', $survey_id)->firstOrFail();
        $sheet = $this->reportData->addExcelCopyright($sheet, 2, $tmpStr, "Irregular Summary Data({$surveyData->survey_name})");

        $fileName = "irregular_{$sort}_summary_data()_" . date('m_d_Y') . ".xlsx";
        $writer = new Xlsx($spreadsheet);
        
        $writer->save($fileName);        
        header("Content-Type: application/json");
        $data['url'] = url('/') . '/' . $fileName;
        return json_encode($data);
    }
    /**
     * 
     * Get the url of the exported excel file
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportExcelFullData () {
        $survey_id = $this->request->input('survey_id');
        $sort    = $this->request->input('sort');
        $columns = $this->request->input('columns');
        $data    = $this->request->input('data');
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $colStr = 'A';
        foreach ($columns as $col) {
            $sheet->setCellValue($colStr . '2', $col);
            ++$colStr;
        }
        $colStr = chr(ord($colStr) - 1);
        $spreadsheet
            ->getActiveSheet()
            ->getStyle("A2:{$colStr}2")
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('c9c9c7');

        $spreadsheet
            ->getActiveSheet()
            ->getStyle("A2:{$colStr}2")
            ->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center');

        $rows = 3;
        foreach ($data as $row) {
            $colStr = 'A';
            foreach ($row as $item) {
                $sheet->setCellValue($colStr . $rows, $item);
                ++$colStr;
            }
            $rows++;
        }
        
        $spreadsheet->getActiveSheet()->getStyle("A2:$colStr$rows")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        for ($tmpStr = 'B'; $tmpStr < $colStr; ++$tmpStr) {
            $spreadsheet->getActiveSheet()->getColumnDimension($tmpStr)->setWidth(30);
        }

        $surveyData = Survey::where('survey_id', $survey_id)->firstOrFail();
        $sheet = $this->reportData->addExcelCopyright($sheet, $rows, $tmpStr, "Irregular Full Data({$surveyData->survey_name})");

        $fileName = "irregular_{$sort}_full_data()_" . date('m_d_Y') . ".xlsx";
        $writer = new Xlsx($spreadsheet);
        
        $writer->save($fileName);        
        header("Content-Type: application/json");
        $data['url'] = url('/') . '/' . $fileName;
        return json_encode($data);
    }
    /**
     * 
     * Get the data of child service for compilation report
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompilationChildServiceData () {

        if (!\Auth::check())
            abort(403);

        $survey_id = $this->request->input('survey_id');
        $parent_hours = $this->request->input('parent_hours');
        $parent_cost = $this->request->input('parent_cost');
        $total_cost = $this->request->input('total_cost');
        $this->validateUser($survey_id);
        $filter_ary = [
            'position' => json_decode($this->request->input('position')),
            'department' => json_decode($this->request->input('department')),
            'group' => json_decode($this->request->input('group')),
            'location' => json_decode($this->request->input('location')),
            'category' => json_decode($this->request->input('category')),
        ];
        $parent_id = $this->request->input('parent_id');

        if ($parent_hours == 0) {
            return json_encode(['status' => 400]);
        }

        $services = $this->reportData->get_child_service_data($survey_id, $parent_id, $parent_hours, $parent_cost, $total_cost, $filter_ary);

        if ($services === false) {
            return json_encode(['status' => 400]);
        }

        return json_encode($services);
    }
    /**
     * 
     * Get the respondents list
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetailCompilationRespsList()
    {

        if (!\Auth::check())
            abort(403);

        $survey_id = $this->request->input('survey_id');
        $question_id = $this->request->input('question_id');
        $this->validateUser($survey_id);
        $filter_ary = [
            'position' => json_decode($this->request->input('position')),
            'department' => json_decode($this->request->input('department')),
            'group' => json_decode($this->request->input('group')),
            'location' => json_decode($this->request->input('location')),
            'category' => json_decode($this->request->input('category')),
        ];

        $data = $this->reportData->getRespListofQuestion($survey_id, $question_id, $filter_ary);
        // dd($data);
        return json_encode($data);
    }
    /**
     * 
     * Get the url of the exported excel file
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportCompilationExcelData ()
    {
        if (!\Auth::check())
            abort(403);

        $excelData  = $this->request->input('excelData');
        $surveyName = (string) $this->request->input('survey_name');
        $label      = $this->request->input('label');
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A2', 'Full Name');
        $sheet->setCellValue('B2', 'Employee ID');
        $sheet->setCellValue('C2', 'Employee Category');
        $sheet->setCellValue('D2', 'Department');
        $sheet->setCellValue('E2', 'Position');
        $sheet->setCellValue('F2', 'Location');
        $sheet->setCellValue('G2', 'Hours');

        $spreadsheet
            ->getActiveSheet()
            ->getStyle("A2:G2")
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('c9c9c7');

        $spreadsheet
            ->getActiveSheet()
            ->getStyle("A2:G2")
            ->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center');

        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(50);
        $sheet->getColumnDimension('E')->setWidth(40);
        $sheet->getColumnDimension('F')->setWidth(20);
        
        $total_hours = 0;
        $i = 4;

        foreach ($excelData as $row) {
            $sheet->setCellValue("A$i", $row['name']);
            $sheet->setCellValue("B$i", $row['employee_id']);
            $sheet->setCellValue("C$i", $row['employee_category']);
            $sheet->setCellValue("D$i", $row['department']);
            $sheet->setCellValue("E$i", $row['position']);
            $sheet->setCellValue("F$i", $row['location']);
            $sheet->setCellValue("G$i", $row['hours']);
            $total_hours += $row['hours'];
            $i++;
        }
        
        $sheet->setCellValue('A3', 'Grand Total');
        $sheet->setCellValue('G3', $total_hours);
        $sheet->mergeCells("A3:F3");
        
        // Table Caption and Copyright
        $sheet = $this->reportData->addExcelCopyright($sheet, $i, "G", "Compilation Report Zoom({$surveyName}) - {$label}");

        $data['filename'] = "Compilation Report Zoom({$surveyName}) - {$label}.xlsx";
        $filename = "Compilation Report.xlsx";
  
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);      

        header("Content-Type: application/json");
        $data['url'] = url('/') . '/' . $filename;
        return json_encode($data);
    }
    /**
     * 
     * Get the respondents list
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRespondentList()
    {
        $survey_id = $this->request->input('survey_id');
        $search_key = "";
        if ($this->request->input('search_key')) {
            $search_key = $this->request->input('search_key');
        }
        $filter_ary = [
            'position' => json_decode($this->request->input('position')),
            'department' => json_decode($this->request->input('department')),
            'group' => json_decode($this->request->input('group')),
            'location' => json_decode($this->request->input('location')),
            'category' => json_decode($this->request->input('category')),
        ];

        $data = $this->reportData->get_resp_data($survey_id, $filter_ary, 0, $search_key);

        return response()->json($data);
    }
    /**
     * 
     * Get the respondents data
     * 
     * @param int $resp_id  respondent id
     * @param int $survey_id    survey id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRespondentData($resp_id, $survey_id)
    {

        if (!\Auth::check())
            abort(403);

        $data = [];
        if($survey_id != 54){
            $answers = DB::table('tblquestion')
            ->rightJoin('tblanswer', 'tblquestion.question_id', '=', 'tblanswer.question_id')
            ->where('tblquestion.page_id', '!=', null)
            ->where('tblanswer.resp_id', '=', $resp_id)
            ->get();
        }else{
            // dd('asd');
            $answers = DB::table('tblquestionTest')
            ->rightJoin('tblanswer', 'tblquestionTest.question_id', '=', 'tblanswer.question_id')
            ->where('tblquestionTest.page_id', '!=', null)
            ->where('tblanswer.resp_id', '=', $resp_id)
            ->get();
        }
        
            

        $resp = Respondent::where('resp_id', '=', $resp_id)->firstOrFail();
        $last_dt = new DateTime($resp->last_dt);
        $resp->last_dt = $last_dt->format('Y-m-d H:i e');

        $data['answers'] = $answers;
        $data['resp'] = $resp;

        $survey = Survey::where('survey_id', $survey_id)->firstOrFail();

        $data['resp_report_data'] = $this->reportData->get_resp_timecost($resp_id, $survey_id, $resp);
        // dd($data['resp_report_data']);
        return json_encode($data);
    }
    /**
     * 
     * Demographic report
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function demographic_report($survey_id)
    {
        // dd($survey_id);
        $this->validateUser($survey_id);
        $data = $this->reportData->get_demographic_data($survey_id, array(), 10, 'responseratecomplete');
         $survey = Survey::where('survey_id', $survey_id)->firstOrFail();
        // echo"<pre>";print_r($data);die;
        return view('reports.reports.demographic', compact('data', 'survey'));
    }
    /**
     * 
     * Get the data for the demographic report
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDemographicData()
    {

        if (!\Auth::check())
            abort(403);

        $survey_id = $this->request->input('survey_id');
        $metric = explode('_', $this->request->input('metric'));
        $min_rates = json_decode($this->request->input('min_rates'));
        $filter_ary = [
            'position' => json_decode($this->request->input('position')),
            'department' => json_decode($this->request->input('department')),
            'group' => json_decode($this->request->input('group')),
            'location' => json_decode($this->request->input('location')),
            'category' => json_decode($this->request->input('category')),
        ];

        $data['stat'] = $this->reportData->get_demographic_data($survey_id, $filter_ary, $min_rates, $metric[0]);

        $data['metric'] = $this->reportData->get_demographic_metric($survey_id, $metric[0], $filter_ary, $min_rates);

        return json_encode($data);
    }
    /**
     * 
     * Get the data for the demographic report with the depth of taxonomy
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBranchUpdate()
    {

        if (!\Auth::check())
            abort(403);

        $survey_id = $this->request->input('survey_id');

        $resp_id = $this->request->input('resp_id');
        $depth = $this->request->input('depth');

        $resp = Respondent::where('resp_id', '=', $resp_id)->firstOrFail();

        $res = $this->reportData->getRespTableByDeep($resp_id, $survey_id, $resp, $depth);

        return json_encode($res);
    }
}
