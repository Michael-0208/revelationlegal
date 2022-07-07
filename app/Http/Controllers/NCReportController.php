<?php

namespace App\Http\Controllers;

use App\Gateways\ReportData;
use App\Models\Respondent;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class NCReportController extends Controller
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
    /**
     * 
     * Individual Report
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function individual_report($survey_id)
    {
        CommonController::validateUser($survey_id, 'surveyNoCompReport');
        $attrs = CommonController::get_survey_attributes($survey_id);

        $resps = $this->reportData->get_resp_data($survey_id);

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

        return view('reports.ncreports.individual', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
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
        CommonController::validateUser($survey_id, 'surveyNoCompReport');
        $resps = $this->reportData->get_resp_data($survey_id);

        $parents = DB::table('tblquestion')
            ->leftJoin('tblpage', 'tblquestion.page_id', 'tblpage.page_id')
            ->where('tblquestion.survey_id', $survey_id)
            ->where('tblpage.question_id_parent', 0)
            ->get();

        foreach ($parents as $item) {
            if ($item->question_desc == 'Legal Services') {
                $data['legal_id'] = $item->question_id;
            } else {
                $data['support_id'] = $item->question_id;
                $data['support_label'] = $item->question_desc;
            }
        }

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

        return view('reports.ncreports.compilation', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Get the data of child service for compilation report
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompilationChildServiceData()
    {

        if (!\Auth::check())
            abort(403);

        $survey_id = $this->request->input('survey_id');
        $parent_hours = $this->request->input('parent_hours');
        $parent_cost = $this->request->input('parent_cost');
        $total_cost = $this->request->input('total_cost');
        $total_hours = $this->request->input('total_hours');
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

        $services = $this->reportData->get_nc_child_service_data($survey_id, $parent_id, $parent_hours, $parent_cost, $total_hours, $filter_ary);

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

        return json_encode($data);
    }
    /**
     * 
     * Get the url of the exported excel file
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportNCCompilationExcelData ()
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
        $sheet = $this->reportData->addExcelCopyright($sheet, $i, "G", "Compilation NC Report Zoom({$surveyName}) - {$label}");

        $data['filename'] = "Compilation NC Report Zoom({$surveyName}) - {$label}.xlsx";
        $filename = 'Compilation NC Report.xlsx';
  
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);      

        header("Content-Type: application/json");
        $data['url'] = url('/') . '/' . $filename;
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
        $this->validateUser($survey_id);
        $data = $this->reportData->get_demographic_data($survey_id, array(), 10, 'responseratecomplete');
        $survey = Survey::where('survey_id', $survey_id)->firstOrFail();

        return view('reports.ncreports.demographic', compact('data', 'survey'));
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
}
