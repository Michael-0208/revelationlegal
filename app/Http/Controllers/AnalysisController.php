<?php

namespace App\Http\Controllers;

use App\Gateways\AnalysisData;
use App\Gateways\ReportData;
use App\Models\Survey;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AnalysisController extends Controller
{
    /**
     * @var \App\Gateways\AnalysisData
     */
    protected $analysisData;
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct (Request $request, AnalysisData $analysisData) {
        $this->middleware(['auth:sanctum', 'verified']);
        $this->analysisData = $analysisData;
        $this->request      = $request;
        $this->reportData   = new ReportData;
    }
    /**
     * 
     * Participants Analysis
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function participant_analysis ($survey_id) {

        //this is a check To validate user
        CommonController::validateUser($survey_id, 'surveyAnalysis');

        $resps = $this->reportData->get_resp_data($survey_id);
        
        $data['resps']= $resps;
        /* $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray(); */
        $survey_data = Survey::select('survey_id','survey_name')->where('survey_id', $survey_id)->get()->toArray();
        /* $survey =(object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']]; */
        $data['survey'] = (object)($survey_data[0]);

        $tmpPosition = $this->reportData->GetUniqueValues($survey_id,'cust_3');;
        $tmpDepartment = $this->reportData->GetUniqueValues($survey_id,'cust_4') ;
        $tmpGroup = $this->reportData->GetUniqueValues($survey_id,'cust_2') ;
        $tmpLocation = $this->reportData->GetUniqueValues($survey_id,'cust_6') ;
        $tmpCategory = $this->reportData->GetUniqueValues($survey_id,'cust_5') ;
        
        /* foreach ($resps as $resp) {
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
        }
        sort($tmpDepartment);
        sort($tmpPosition);
        sort($tmpGroup);
        sort($tmpLocation);
        sort($tmpCategory); */

        $data['position'] = $tmpPosition;
        $data['department'] = $tmpDepartment;
        $data['group'] = $tmpGroup;
        $data['location'] = $tmpLocation;
        $data['category'] = $tmpCategory;
        $data['survey_status'] = [];

        return view('analysis.participant', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Get the url of exported excel
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportParticipantAnalysisExcel () {
        $excelData = $this->request->input('data');
        $compOption = $this->request->input('compOption');
        $sum_hours = $this->request->input('sum_hours');
        $sum_comp = $this->request->input('sum_comp');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A2', 'Department');
        $sheet->setCellValue('B2', 'Employee Category');
        $sheet->setCellValue('C2', 'Employee ID');
        $sheet->setCellValue('D2', 'Full Name');
        $sheet->setCellValue('E2', 'Location');
        $sheet->setCellValue('F2', 'Position');
        $sheet->setCellValue('G2', 'Compensation Option');
        $sheet->setCellValue('H2', 'SUM(Hours)');
        $sheet->setCellValue('I2', 'SUM(Resp Compensation)');

        $spreadsheet
            ->getActiveSheet()
            ->getStyle("A2:I2")
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('c9c9c7');

        $spreadsheet
            ->getActiveSheet()
            ->getStyle("A2:I2")
            ->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center');

        $sheet->setCellValue('A3', $excelData['cust_4']);
        $sheet->setCellValue('B3', $excelData['cust_5']);
        $sheet->setCellValue('C3', $excelData['cust_1']);
        $sheet->setCellValue('D3', $excelData['resp_last'] . ', ' . $excelData['resp_first']);
        $sheet->setCellValue('E3', $excelData['cust_6']);
        $sheet->setCellValue('F3', $excelData['cust_3']);
        $sheet->setCellValue('G3', $compOption);
        $sheet->setCellValue('H3', $sum_hours);
        $sheet->setCellValue('I3', $sum_comp);
        $sheet->getStyle('H3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('I3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        for ($tmpStr = 'B'; $tmpStr < 'J'; ++$tmpStr) {
            $spreadsheet->getActiveSheet()->getColumnDimension($tmpStr)->setWidth(30);
        }

        $sheet = $this->reportData->addExcelCopyright($sheet, 4, "I", "Participant Analysis({$excelData['resp_last']} {$excelData['resp_first']})");

        $fileName = "Participant Analysis({$excelData['resp_last']} {$excelData['resp_first']}).xlsx";
        $writer = new Xlsx($spreadsheet);

        $writer->save($fileName);        
        header("Content-Type: application/json");
        $data['url'] = url('/') . '/' . $fileName;
        return json_encode($data);
    }
    /**
     * 
     * At-A-Glance Analysis
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function ataglance_analysis ($survey_id) {
        CommonController::validateUser($survey_id, 'surveyAnalysis');

        $resps = $this->reportData->get_resp_data($survey_id);
        //dd($resps[44]);
        $data['resps']= $resps;
        $survey_data = Survey::select('survey_id','survey_name')->where('survey_id', $survey_id)->get()->toArray();
        
        /* $survey =(object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]]; */
        $data['survey'] = (object)($survey_data[0]);
        //dd( $data['survey'] );
        
        
        /* $tmpPosition = array();
        $tmpDepartment = array();
        $tmpGroup = array();
        $tmpLocation = array();
        $tmpCategory = array(); */


        $tmpPosition = $this->reportData->GetUniqueValues($survey_id,'cust_3');
        $tmpDepartment = $this->reportData->GetUniqueValues($survey_id,'cust_4');
        $tmpGroup = $this->reportData->GetUniqueValues($survey_id,'cust_2');
        $tmpLocation = $this->reportData->GetUniqueValues($survey_id,'cust_6');
        $tmpCategory = $this->reportData->GetUniqueValues($survey_id,'cust_5');
        
        /* foreach ($resps as $resp) {
           
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

        } */
       
        /* sort($tmpDepartment);
        sort($tmpPosition);
        sort($tmpGroup);
        sort($tmpLocation);
        sort($tmpCategory); */
        
       
        $data['position'] = $tmpPosition;
        $data['department'] = $tmpDepartment;
        $data['group'] = $tmpGroup;
        $data['location'] = $tmpLocation;
        $data['category'] = $tmpCategory;
        $data['survey_status'] = [];

        $glanceData = $this->analysisData->getAtaGlanceData($survey_id, $resps, 4, 15, 3);
        $data['ataglance_data'] = $glanceData['rows'];
        $data['grand_total_hours'] = $glanceData['grand_total_hours'];
        $data['grand_total_cost'] = $glanceData['grand_total_cost'];

        return view('analysis.ataglance', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Get the data for At-A-Glance Analysis
     * 
     * @param int $survey_id    survey id
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAtAGlanceTableData () {
        $survey_id      = $this->request->input('survey_id');
        $depth_question = (int) $this->request->input('depthQuestion');
        $min_percent    = (int) $this->request->input('minPercent');
        $filter_resp    = (int) $this->request->input('filterResp');
        
        $filter_ary = [
            'position' => json_decode($this->request->input('position')),
            'department' => json_decode($this->request->input('department')),
            'group' => json_decode($this->request->input('group')),
            'location' => json_decode($this->request->input('location')),
            'category' => json_decode($this->request->input('category')),
        ];

        $resps = $this->reportData->get_resp_data($survey_id, $filter_ary);

        $glanceData = $this->analysisData->getAtaGlanceData($survey_id, $resps, $depth_question, $min_percent, $filter_resp);
        $data['ataglance_data'] = $glanceData['rows'];
        $data['grand_total_hours'] = $glanceData['grand_total_hours'];
        $data['grand_total_cost'] = $glanceData['grand_total_cost'];

        return json_encode($data);
    }
    /**
     * 
     * Get the url of the exported excel
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAtAGlanceExcelExport () {
        $survey_id      = $this->request->input('survey_id');
        $depth_question = (int) $this->request->input('depthQuestion');
        $min_percent    = (int) $this->request->input('minPercent');
        $filter_resp    = (int) $this->request->input('filterResp');
        
        $filter_ary = [
            'position' => json_decode($this->request->input('position')),
            'department' => json_decode($this->request->input('department')),
            'group' => json_decode($this->request->input('group')),
            'location' => json_decode($this->request->input('location')),
            'category' => json_decode($this->request->input('category')),
        ];

        $resps = $this->reportData->get_resp_data($survey_id, $filter_ary);

        $glanceData = $this->analysisData->getAtaGlanceData($survey_id, $resps, $depth_question, $min_percent, $filter_resp);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(70);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(30);

        $sheet->setCellValue('C2', 'Hours');
        $sheet->setCellValue('D2', '% of Hours along Legal | Support');
        $sheet->setCellValue('E2', 'Cost');

        $sheet->setCellValue('A3', 'Grand Total');
        $sheet->setCellValue('C3', $glanceData['grand_total_hours']);
        $sheet->setCellValue('E3', $glanceData['grand_total_cost']);

        $rowNum = 3;
        foreach ($glanceData['rows'] as $row) {
            $rowNum++;
            $sheet->setCellValue("A{$rowNum}", $row['option']);
            $sheet->setCellValue("B{$rowNum}", $row['question_desc']);
            $sheet->setCellValue("C{$rowNum}", $row['hours']);
            $sheet->setCellValue("D{$rowNum}", $row['percent'] . '%');
            $sheet->setCellValue("E{$rowNum}", '$' . round($row['cost']));
            $sheet->getStyle("D{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("E{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }

        $surveyData = Survey::where('survey_id', $survey_id)->firstOrFail();

        $sheet = $this->reportData->addExcelCopyright($sheet, $rowNum + 1, "E", "Function At-A-Glance({$surveyData->survey_name})");

        $fileName = "Function At-A-Glance.xlsx";
        $writer = new Xlsx($spreadsheet);

        $writer->save($fileName);       
        header("Content-Type: application/json");
        $data['url'] = url('/') . '/' . $fileName;
        return json_encode($data);
    }
    /**
     * 
     * Comparative Glance Analysis
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function comparative_glance_analysis ($survey_id) {
        CommonController::validateUser($survey_id, 'surveyAnalysis');

        $resps = $this->reportData->get_resp_data($survey_id);

        $data['resps']= $resps;
        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $survey =(object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']];
        $data['survey'] = $survey;

        $tmpPosition = array();
        $tmpDepartment = array();
        $tmpGroup = array();
        $tmpLocation = array();
        $tmpCategory = array();
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

        $glanceData = $this->analysisData->getComparativeGlanceData($survey_id, $resps, 4, 15, 3, 0);
        $data['ataglance_data'] = $glanceData['rows'];
        $data['grand_total_hours'] = $glanceData['grand_total_hours'];
        $data['grand_total_cost'] = $glanceData['grand_total_cost'];

        return view('analysis.comparativeglance', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Get the data for Comparative Glance Analysis
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComparativeGlanceTableData () {
        $survey_id      = $this->request->input('survey_id');
        $depth_question = (int) $this->request->input('depthQuestion');
        $min_percent    = (int) $this->request->input('minPercent');
        $filter_resp_primary    = (int) $this->request->input('filterRespPrimary');
        $filter_resp_secondary  = (int) $this->request->input('filterRespSecondary');
        
        $filter_ary = [
            'position' => json_decode($this->request->input('position')),
            'department' => json_decode($this->request->input('department')),
            'group' => json_decode($this->request->input('group')),
            'location' => json_decode($this->request->input('location')),
            'category' => json_decode($this->request->input('category')),
        ];

        $resps = $this->reportData->get_resp_data($survey_id, $filter_ary);

        $glanceData = $this->analysisData->getComparativeGlanceData($survey_id, $resps, $depth_question, $min_percent, $filter_resp_primary, $filter_resp_secondary);
        $data['ataglance_data'] = $glanceData['rows'];
        $data['grand_total_hours'] = $glanceData['grand_total_hours'];
        $data['grand_total_cost'] = $glanceData['grand_total_cost'];

        return json_encode($data);
    }
    /**
     * 
     * Return the url of the exported excel file
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getComparativeGlanceExcelExport () {
        $survey_id      = $this->request->input('survey_id');
        $depth_question = (int) $this->request->input('depthQuestion');
        $min_percent    = (int) $this->request->input('minPercent');
        $filter_resp_primary    = (int) $this->request->input('filterRespPrimary');
        $filter_resp_secondary  = (int) $this->request->input('filterRespSecondary');
        
        $filter_ary = [
            'position' => json_decode($this->request->input('position')),
            'department' => json_decode($this->request->input('department')),
            'group' => json_decode($this->request->input('group')),
            'location' => json_decode($this->request->input('location')),
            'category' => json_decode($this->request->input('category')),
        ];

        $resps = $this->reportData->get_resp_data($survey_id, $filter_ary);

        $glanceData = $this->analysisData->getComparativeGlanceData($survey_id, $resps, $depth_question, $min_percent, $filter_resp_primary, $filter_resp_secondary);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(50);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(30);

        $sheet->setCellValue('D2', 'Hours');
        $sheet->setCellValue('E2', 'Cost');
        $sheet->setCellValue('F2', '% Hours per Activities');

        $sheet->setCellValue('A3', 'Grand Total');
        $sheet->setCellValue('D3', $glanceData['grand_total_hours']);
        $sheet->setCellValue('E3', $glanceData['grand_total_cost']);

        $rowNum = 3;
        
        foreach ($glanceData['rows'] as $row) {
            $rowNum++;
            $sheet->setCellValue("A{$rowNum}", $row['option']);
            $sheet->setCellValue("B{$rowNum}", $row['sub_option']);
            $sheet->setCellValue("C{$rowNum}", $row['question_desc']);
            $sheet->setCellValue("D{$rowNum}", $row['hours']);
            $sheet->setCellValue("E{$rowNum}", '$' . round($row['cost']));
            $sheet->setCellValue("F{$rowNum}", $row['percent'] . '%');
            $sheet->getStyle("E{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("F{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }

        $surveyData = Survey::where('survey_id', $survey_id)->firstOrFail();
        $sheet = $this->reportData->addExcelCopyright($sheet, $rowNum, "F", "Comparative Function At-A-Glance({$surveyData->survey_name})");

        $fileName = "Comparative Function At-A-Glance.xlsx";
        $writer = new Xlsx($spreadsheet);

        $writer->save($fileName);       
        header("Content-Type: application/json");
        $data['url'] = url('/') . '/' . $fileName;
        return json_encode($data);
    }
}
