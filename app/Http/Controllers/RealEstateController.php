<?php

namespace App\Http\Controllers;

use App\Gateways\RealEstateData;
use App\Gateways\ReportData;
use App\Models\AllowedLocation;
use App\Models\Location;
use App\Models\Respondent;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RealEstateController extends Controller
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;
    /**
     * @var \App\Gateways\RealEstateData
     */
    protected $rsData;
    /**
     * @var \App\Gateways\ReportData
     */
    protected $reportData;

    public function __construct (Request $request, RealEstateData $rsData, ReportData $reportData) {
        $this->middleware(['auth:sanctum', 'verified']);
        $this->rsData  = $rsData;
        $this->request = $request;
        $this->reportData = $reportData;
    }
    // Location RSF Rates
    /**
     * 
     * Location Index
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function location_rsf_rates ($survey_id) {
        CommonController::validateUser($survey_id, 'surveyRealEstate');

        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $survey =(object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']];
        $data['survey'] = $survey;

        $data['locations'] = Location::where('survey_id', $survey_id)->orderBy('location', 'ASC')->get();
       // dd($data['locations']);
        
        return view('real_estate.locations', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Create Location
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function create_location () {
        $this->request->validate([
            'location' => 'required',
            'location_Current' => 'required',
            'location_Adjacent' => 'required',
            'location_Regional' => 'required',
            'location_OTHER' => 'required',
        ],
        [
            'location_Current.required' => 'The current rate field is required',
            'location_Adjacent.required' => 'The adjacent rate field is required',
            'location_Regional.required' => 'The regional rate field is required',
            'location_OTHER.required' => 'The other rate field is required',
        ]);

        $data = $this->request->all();

        $new = new Location();

        $new->survey_id = $data['survey_id'];
        $new->location = $data['location'];
        $new->location_Current = $data['location_Current'];
        $new->location_Adjacent = $data['location_Adjacent'];
        $new->location_Regional = $data['location_Regional'];
        $new->location_OTHER = $data['location_OTHER'];

        if ($new->save()) {
            $data['status'] = 200;
            $data['new'] = $new;
        } else {
            $data['status'] = 404;
        }
        
        return $data;
    }
    /**
     * 
     * Update Location
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function update_location () {
        $this->request->validate([
            'location_id' => 'required',
            'location' => 'required',
            'location_Current' => 'required',
            'location_Adjacent' => 'required',
            'location_Regional' => 'required',
            'location_OTHER' => 'required',
        ],
        [
            'location_Current.required' => 'The current rate field is required',
            'location_Adjacent.required' => 'The adjacent rate field is required',
            'location_Regional.required' => 'The regional rate field is required',
            'location_OTHER.required' => 'The other rate field is required',
        ]);

        $data = $this->request->all();

        $updated = Location::find($data['location_id']);

        $updated->location = $data['location'];
        $updated->location_Current = $data['location_Current'];
        $updated->location_Adjacent = $data['location_Adjacent'];
        $updated->location_Regional = $data['location_Regional'];
        $updated->location_OTHER = $data['location_OTHER'];

        if ($updated->save()) {
            $data['status'] = 200;
            $data['updated'] = $updated;
        } else {
            $data['status'] = 404;
        }
        
        return $data;
    }
    /**
     * 
     * Destroy Location
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function destroy_location () {
        $location_id = $this->request->input('location_id');

        $destroy = Location::find($location_id);

        $record_error = 0;
        $error_message = '';

        if (Respondent::where('survey_id', $destroy->survey_id)->where('cust_6', $destroy->location)->count() > 0) {
            $record_error = 1;
            $error_message = 'This location has respondents already.';
        } else if (AllowedLocation::where('survey_id', $destroy->survey_id)->where('name', $destroy->location)->count() > 0) {
            $record_error = 1;
            $error_message = 'This location has been using already.';
        }

        if ($record_error == 0) {
            if ($destroy->delete()) {
                $data['status'] = 200;
            } else {
                $data['status'] = 404;
            }
        } else {
            $data['status'] = 400;
            $data['message'] = $error_message;
        }

        return $data;
    }

    // Individual Proximity Report
    /**
     * 
     * Individual Proximity
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function individual_proximity ($survey_id) {
        CommonController::validateUser($survey_id, 'surveyRealEstate');
        
        $resps = $this->rsData->get_resp_data($survey_id);

        $total_hours = 0;
        $high_hours = 0;
        $med_hours = 0;
        $low_hours = 0;
        $virtual_hours = 0;
        foreach ($resps as $resp) {
            $total_hours += $resp->total_hours;
            $high_hours += $resp->prox_high_hours;
            $med_hours += $resp->prox_medium_hours;
            $low_hours += $resp->prox_low_hours;
            $virtual_hours += $resp->prox_virtual_hours;
        }
        $data['rsf_percent_data'] = array ();
        $data['rsf_percent_data']['high_percent'] = $total_hours > 0 ? round(100 * $high_hours / $total_hours) : 0;
        $data['rsf_percent_data']['med_percent'] = $total_hours > 0 ? round(100 * $med_hours / $total_hours) : 0;
        $data['rsf_percent_data']['low_percent'] = $total_hours > 0 ? round(100 * $low_hours / $total_hours) : 0;
        $data['rsf_percent_data']['virtual_percent'] = $total_hours > 0 ? round(100 * $virtual_hours / $total_hours) : 0;

        $data['resps']= $resps;
        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $survey =(object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']];
        $data['survey'] = $survey;

        $data= $this->reportData->getRespFilterDataAry($resps, $data);
        
        return view('real_estate.individual', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Get the respondent data
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRespondentData () {
        $resp_id = $this->request->input('resp_id');
        $survey_id = $this->request->input('survey_id');
        $low_percent = $this->request->input('low_percent');
        $med_percent = $this->request->input('med_percent');
        $high_percent = $this->request->input('high_percent');
        $data = array ();

        $resp = $this->rsData->getIndividualRespData($resp_id, $survey_id);

        $data['resp'] = $resp;

        $survey = Survey::where('survey_id', $survey_id)->firstOrFail();

        $data['resp_report_data'] = $this->reportData->get_resp_timecost($resp_id, $survey_id, $resp);
        
        $data['resp_report_data']['categoryData'] = $this->reportData->getProxCategoryData($survey_id, $data['resp_report_data']['tableData']);

        // Added for display data by proximity factor
        $total_hours = 0;
        foreach ($data['resp_report_data']['tableData'] as $key=>$row) {
            if ($total_hours == 0) {
                $total_hours = $row['total_hours'];
            }
            $data['resp_report_data']['tableData'][$key]['pid'] = 'prox_' . ($row['prox_factor'] ? $row['prox_factor'] : 1);
        }

        $prox_items = array (
            (object) [

                'id' => 'prox_3',
                'val' => 0,
                'question' => 'High',
                'pid' => 0,
                'prox_factor' => 3,
                'cost' => 1,
                'hours' => round($total_hours * $high_percent / 100),
                'percent_hour' => $high_percent,
                'rsf' => 1,
                'rsf_cost' => 1,
                'top_parent' => 1,
                'total_hours' => $total_hours,
                'val' => 1,


            ],
            (object) [
                'id' => 'prox_2',
                'val' => 0,
                'question' => 'Medium',
                'pid' => 0,
                'prox_factor' => 2,
                'cost' => 1,
                'hours' => round($total_hours * $med_percent / 100),
                'percent_hour' => $med_percent,
                'rsf' => 1,
                'rsf_cost' => 1,
                'top_parent' => 1,
                'total_hours' => $total_hours,
                'val' => 1,
            ],
            (object) [
                
                'id' => 'prox_1',
                'val' => 0,
                'question' => 'Low',
                'pid' => 0,
                'prox_factor' => 1,
                'cost' => 1,
                'hours' => round($total_hours * $low_percent / 100),
                'percent_hour' => $low_percent,
                'rsf' => 1,
                'rsf_cost' => 1,
                'top_parent' => 1,
                'total_hours' => $total_hours,
                'val' => 1,
            ],
        );
        $data['resp_report_data']['tableData'] = array_merge($data['resp_report_data']['tableData'], $prox_items);
        // Ended of display data by proximity factor

        return json_encode($data);
    }
    /**
     * 
     * Get the list of respondents
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRespondentList()
    {
        $data = array ();

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

        $resps = $this->rsData->get_resp_data($survey_id, $filter_ary, $search_key);

        $data['resps'] = $resps;

        $total_hours = 0;
        $high_hours = 0;
        $med_hours = 0;
        $low_hours = 0;
        foreach ($resps as $resp) {
            $total_hours += $resp->total_hours;
            $high_hours += $resp->prox_high_hours;
            $med_hours += $resp->prox_medium_hours;
            $low_hours += $resp->prox_low_hours;
        }
        $data['rsf_percent_data'] = array ();
        $data['rsf_percent_data']['high_percent'] = $total_hours > 0 ? round(100 * $high_hours / $total_hours) : 0;
        $data['rsf_percent_data']['med_percent'] = $total_hours > 0 ? round(100 * $med_hours / $total_hours) : 0;
        $data['rsf_percent_data']['low_percent'] = $total_hours > 0 ? round(100 * $low_hours / $total_hours) : 0;

        return response()->json($data);
    }

    // Participant Proximity Report
    /**
     * 
     * Participant Proximity
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function participant_proximity ($survey_id) {
        CommonController::validateUser($survey_id, 'surveyRealEstate');
        
        $resps = $this->rsData->get_resp_data($survey_id);
        // dd($resps);
        $data = array ();
        $data['max_hours'] = 0;

        //run query instead of foreach,....
        
        $total_hours = 0;
        $high_hours = 0;
        $med_hours = 0;
        $low_hours = 0;
        $virtual_hours = 0;

        foreach ($resps as $resp) {
            $total_hours += $resp->total_hours;
            $high_hours += $resp->prox_high_hours;
            $med_hours += $resp->prox_medium_hours;
            $low_hours += $resp->prox_low_hours;
            $virtual_hours += $resp->prox_virtual_hours;
        }

        $data['rsf_percent_data'] = array ();


        $data['rsf_percent_data']['high_hours'] = $high_hours;
        $data['rsf_percent_data']['med_hours'] = $med_hours;
        $data['rsf_percent_data']['low_hours'] = $low_hours;
        $data['rsf_percent_data']['virtual_hours'] = $virtual_hours;
        $data['rsf_percent_data']['high_percent'] = $total_hours > 0 ? round(100 * $high_hours / $total_hours) : 0;
        $data['rsf_percent_data']['med_percent'] = $total_hours > 0 ? round(100 * $med_hours / $total_hours) : 0;
        $data['rsf_percent_data']['low_percent'] = $total_hours > 0 ? round(100 * $low_hours / $total_hours) : 0;
        $data['rsf_percent_data']['virtual_percent'] = $total_hours > 0 ? round(100 * $virtual_hours / $total_hours) : 0;

        $data['resps']= $resps;

        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();

        $survey =(object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']];
        $data['survey'] = $survey;

        $data = $this->reportData->getRespFilterDataAry($resps, $data);

        $data = $this->reportData->getTaxonomyFilterDataAry($survey_id, $data);

        $data['questionAry'] = $this->reportData->getQuestionAryByQuestionId($survey_id);

        foreach ($data['resps'] as $resp) {
            if ($resp->prox_high_hours > $data['max_hours'])
                $data['max_hours'] = $resp->prox_high_hours;

            if ($resp->prox_medium_hours > $data['max_hours'])
                $data['max_hours'] = $resp->prox_medium_hours;

            if ($resp->prox_low_hours > $data['max_hours'])
                $data['max_hours'] = $resp->prox_low_hours;

            if ($resp->prox_virtual_hours > $data['max_hours'])
                $data['max_hours'] = $resp->prox_virtual_hours;
        }
        // dd($data);
        return view('real_estate.participant', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Get the data of participant proximity report
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function getParticipantProximity () {
        $data = array ();

        $survey_id = $this->request->input('survey_id');
        $search_key = "";
        if ($this->request->input('search_key')) {
            $search_key = $this->request->input('search_key');
        }
        $filter_ary = [
            'department' => json_decode($this->request->input('department')),
            'group' => json_decode($this->request->input('group')),
            'location' => json_decode($this->request->input('location')),
            'category' => json_decode($this->request->input('category')),
        ];
        
        $filter_taxonomy = [
            'classification' => json_decode($this->request->input('classification')),
            'substantive' => json_decode($this->request->input('substantive')),
            'process' => json_decode($this->request->input('process')),
        ];

        $resps = $this->rsData->get_resp_data($survey_id, $filter_ary, $search_key, $filter_taxonomy);

        $total_hours = 0;
        $high_hours = 0;
        $med_hours = 0;
        $low_hours = 0;
        foreach ($resps as $resp) {
            $total_hours += $resp->total_hours;
            $high_hours += $resp->prox_high_hours;
            $med_hours += $resp->prox_medium_hours;
            $low_hours += $resp->prox_low_hours;
        }
        $data['rsf_percent_data'] = array ();
        $data['rsf_percent_data']['high_hours'] = $high_hours;
        $data['rsf_percent_data']['med_hours'] = $med_hours;
        $data['rsf_percent_data']['low_hours'] = $low_hours;
        $data['rsf_percent_data']['high_percent'] = $total_hours > 0 ? round(100 * $high_hours / $total_hours) : 0;
        $data['rsf_percent_data']['med_percent'] = $total_hours > 0 ? round(100 * $med_hours / $total_hours) : 0;
        $data['rsf_percent_data']['low_percent'] = $total_hours > 0 ? round(100 * $low_hours / $total_hours) : 0;

        $data['resps']= $resps;

        return $data;
    }
    /**
     * 
     * Get the url of the exported excel file
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function export_participant_excel () {
        $survey_id = $this->request->input('survey_id');
        $tableData = json_decode($this->request->input('tableData'));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A2', '');
        $sheet->setCellValue('B2', '');
        $sheet->setCellValue('C2', '');
        $sheet->setCellValue('D2', '');
        $sheet->setCellValue('E2', '');
        $sheet->setCellValue('F2', '');
        $sheet->setCellValue('G2', 'Hours');
        $sheet->setCellValue('H2', '');
        $sheet->setCellValue('I2', '');
        $sheet->setCellValue('J2', 'RSF Cost');
        $sheet->setCellValue('K2', '');
        $sheet->setCellValue('L2', '');
        $sheet->setCellValue('M2', 'Hours');
        $sheet->setCellValue('N2', 'RSF Cost');

        $sheet->mergeCells("G2:I2");
        $sheet->mergeCells("J2:L2");

        $sheet->setCellValue('A3', 'Full Name');
        $sheet->setCellValue('B3', 'Employee ID');
        $sheet->setCellValue('C3', 'Category(group)');
        $sheet->setCellValue('D3', 'Department');
        $sheet->setCellValue('E3', 'Location');
        $sheet->setCellValue('F3', 'Position');
        $sheet->setCellValue('G3', 'High');
        $sheet->setCellValue('H3', 'Med');
        $sheet->setCellValue('I3', 'Low');
        $sheet->setCellValue('J3', 'High');
        $sheet->setCellValue('K3', 'Med');
        $sheet->setCellValue('L3', 'Low');
        $sheet->setCellValue('M3', 'Total');
        $sheet->setCellValue('N3', 'Total');

        $row = 4;
        foreach ($tableData as $resp) {
            $sheet->setCellValue("A$row", $resp->name);
            $sheet->setCellValue("B$row", $resp->employee_id);
            $sheet->setCellValue("C$row", $resp->category);
            $sheet->setCellValue("D$row", $resp->department);
            $sheet->setCellValue("E$row", $resp->location);
            $sheet->setCellValue("F$row", $resp->position);
            $sheet->setCellValue("G$row", round($resp->high_hours));
            $sheet->setCellValue("H$row", round($resp->med_hours));
            $sheet->setCellValue("I$row", round($resp->low_hours));
            $sheet->setCellValue("J$row", round($resp->total_hours > 0 ? $resp->rsf_cost * $resp->high_hours / $resp->total_hours : 0));
            $sheet->setCellValue("K$row", round($resp->total_hours > 0 ? $resp->rsf_cost * $resp->med_hours / $resp->total_hours : 0));
            $sheet->setCellValue("L$row", round($resp->total_hours > 0 ? $resp->rsf_cost * $resp->low_hours / $resp->total_hours : 0));
            $sheet->setCellValue("M$row", $resp->total_hours);
            $sheet->setCellValue("N$row", $resp->rsf_cost);

            $row++;
        }

        // Stylization
        $sheet->getStyle('G2:N3')
            ->getAlignment()
            ->setHorizontal('center');
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);

        $surveyData = Survey::where('survey_id', $survey_id)->firstOrFail();

        $sheet = $this->reportData->addExcelCopyright($sheet, $row, "N", "Participant Proximity Report({$surveyData->survey_name})");

        $data['filename'] = "Participant Proximity Report(" . $surveyData->survey_name . ").xlsx";
        $filename = "Participant Proximity Report.xlsx";
  
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);      

        header("Content-Type: application/json");
        $data['url'] = url('/') . '/' . $filename;
        return json_encode($data);
    }

    // Activity by Location Report
    /**
     * 
     * Activity by Location Report
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function activity_by_location ($survey_id) {
        CommonController::validateUser($survey_id, 'surveyRealEstate');
        $resps = $this->rsData->get_resp_data($survey_id);

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
            }
        }

        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $survey = (object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']
        ];
        $data['survey'] = $survey;

        $data = $this->reportData->getRespFilterDataAry($resps, $data);

        $data = $this->reportData->getTaxonomyFilterDataAry($survey_id, $data);

        $data['respondents_num'] = number_format(count($resps));

        $activityLocationData = $this->rsData->getActivityByLocation($resps, ['all']);

        $data['total_hours'] = $activityLocationData['total_hours'];
        $data['total_rsf'] = $activityLocationData['total_rsf'];
        $data['total_rsf_cost'] = $activityLocationData['total_rsf_cost'];

        $data['locationData'] = $activityLocationData['rows'];
        $data['resps'] = $activityLocationData['resps'];

        return view('real_estate.activity-by-location', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Filter activity by location report
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter_activity_by_location () {
        $params = $this->request->all();
        CommonController::validateUser($params['survey_id'], 'surveyRealEstate');

        $filter_ary = [
            'position' => json_decode($params['position']),
            'department' => json_decode($params['department']),
            'group' => json_decode($params['group']),
            'category' => json_decode($params['category']),
        ];
        
        $resps = $this->rsData->get_resp_data($params['survey_id'], $filter_ary);

        $survey_data = Survey::where('survey_id', $params['survey_id'])->get()->toArray();
        $survey = (object)[
            'survey_id' => $params['survey_id'],
            'survey_name' => $survey_data[0]['survey_name']
        ];
        $data['survey'] = $survey;

        $data = $this->reportData->getRespFilterDataAry($resps, $data);

        $data = $this->reportData->getTaxonomyFilterDataAry($params['survey_id'], $data);

        $data['respondents_num'] = number_format(count($resps));

        $activityLocationData = $this->rsData->getActivityByLocation($resps, json_decode($params['proximity']));

        $data['total_hours'] = $activityLocationData['total_hours'];
        $data['total_rsf'] = $activityLocationData['total_rsf'];
        $data['total_rsf_cost'] = $activityLocationData['total_rsf_cost'];

        $data['locationData'] = $activityLocationData['rows'];
        $data['resps'] = $activityLocationData['resps'];

        return response()->json($data);
    }
    /**
     * 
     * Get the data of activity by location report
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivityByLocation () {
        $params = $this->request->all();

        $filter_ary = array (
            'position' => json_decode($params['position']),
            'department' => json_decode($params['department']),
            'group' => json_decode($params['group']),
            'category' => json_decode($params['category']),
            'location' => [$params['location']],
        );

        $resps = $this->rsData->get_resp_data($params['survey_id'], $filter_ary);
            
        $questionDataAry = $this->rsData->getQuestionDataByResp($params['survey_id']);
        $questionAry = $this->rsData->getQuestionAry($params['survey_id']);
        $children    = $this->rsData->getFinalQuestionNodes($questionAry);
       
        $pidInfoAry  = $this->reportData->getAryOfQuestionWithParents($questionAry);
        //dd($pidInfoAry);
        $parents = $this->reportData->getQuestionByPid([0], $questionAry);
        $classifications = $this->reportData->getQuestionByPid($parents, $questionAry);
        $questionProxAry = $this->rsData->getQuestionProximity($this->reportData->getQuestionAry($params['survey_id']));

        $data = array ();
        $total = array ();
        $total['resp_num'] = 0;
        $total['total_rsf_cost_adjacent'] = 0;
        $total['total_rsf_cost_current'] = 0;
        $total['total_rsf_cost_other'] = 0;
        $total['total_rsf_cost_regional'] = 0;
        
        $respIds = array ();
        $locationResps = array ();
        
        foreach ($resps as $resp) {
            if (array_key_exists($resp->resp_id, $questionDataAry)) {
                $questionData = $questionDataAry[$resp->resp_id];
                $total['total_rsf_cost_adjacent'] += $resp->rsf_cost_adjacent;
                $total['total_rsf_cost_current'] += $resp->rsf_cost;
                $total['total_rsf_cost_other'] += $resp->rsf_cost_other;
                $total['total_rsf_cost_regional'] += $resp->rsf_cost_regional;
                foreach ($questionData as $question_id => $row) {
                    if (in_array($question_id, $children)) {
                        foreach ($classifications as $classification) {
                            if (in_array($classification, $pidInfoAry[$question_id])) {
                                if (in_array($questionProxAry[$question_id], json_decode($params['proximity']))) {
                                    $hours = $this->reportData->getQuestionHoursFromArray($resp->resp_id, $question_id, $questionDataAry);
                                } else {
                                    $hours = 0;
                                }
    
                                if (!array_key_exists($classification, $data)) {
                                    $parent = $this->reportData->getParentOfQuestion($classification, $questionAry);
                                    $data[$classification] = array ();
                                    $data[$classification]['pid'] = $parent->question_id;
                                    $data[$classification]['parent'] = $parent->question_desc;
                                    $data[$classification]['question_desc'] = $this->reportData->getQuestionDesc($classification, $questionAry);                                
                                    $data[$classification]['hours'] = $hours;
                                    $data[$classification]['rsf'] = $resp->rentable_square_feet * $hours / $resp->total_hours;
                                    $data[$classification]['rsf_cost_current'] = $resp->rsf_cost * $hours / $resp->total_hours;
                                    $data[$classification]['rsf_cost_adjacent'] = $resp->rsf_cost_adjacent * $hours / $resp->total_hours;
                                    $data[$classification]['rsf_cost_regional'] = $resp->rsf_cost_regional * $hours / $resp->total_hours;
                                    $data[$classification]['rsf_cost_other'] = $resp->rsf_cost_other * $hours / $resp->total_hours;                                
                                } else {
                                    $data[$classification]['hours'] += $hours;                              
                                    $data[$classification]['rsf'] += $resp->rentable_square_feet * $hours / $resp->total_hours;
                                    $data[$classification]['rsf_cost_current'] += $resp->rsf_cost * $hours / $resp->total_hours;
                                    $data[$classification]['rsf_cost_adjacent'] += $resp->rsf_cost_adjacent * $hours / $resp->total_hours;
                                    $data[$classification]['rsf_cost_regional'] += $resp->rsf_cost_regional * $hours / $resp->total_hours;
                                    $data[$classification]['rsf_cost_other'] += $resp->rsf_cost_other * $hours / $resp->total_hours;
                                }
                                if (!array_key_exists($classification, $locationResps)) {
                                    $locationResps[$classification] = array ();
                                }
                                if (!array_key_exists($resp->resp_id, $locationResps[$classification])) {
                                    $locationResps[$classification][$resp->resp_id] = array (
                                        'resp_id' => $resp->resp_id,
                                        'resp_first' => $resp->resp_first,
                                        'resp_last' => $resp->resp_last,
                                        'rentable_square_feet' => $resp->rentable_square_feet,
                                        'total_hours' => $resp->total_hours,
                                        'rsf_cost' => $resp->rsf_cost,
                                        'rsf_cost_adjacent' => $resp->rsf_cost_adjacent,
                                        'rsf_cost_regional' => $resp->rsf_cost_regional,
                                        'rsf_cost_other' => $resp->rsf_cost_other,
                                        'cust_3' => $resp->cust_3,
                                        'cust_6' => $resp->cust_6,
                                        'cust_1' => $resp->cust_1,
                                    );
                                }
                            }
                        }
                    }
                }
            }
            $total['resp_num']++;
            array_push($respIds, array(
                'resp_id' => $resp->resp_id,
                'resp_first' => $resp->resp_first,
                'resp_last' => $resp->resp_last,
                'total_hours' => $resp->total_hours,
                'rentable_square_feet' => $resp->rentable_square_feet,
                'rsf_cost_adjacent' => $resp->rsf_cost_adjacent,
                'rsf_cost_current' => $resp->rsf_cost,
                'rsf_cost_regional' => $resp->rsf_cost_regional,
                'rsf_cost_other' => $resp->rsf_cost_other,
                'cust_3' => $resp->cust_3,
                'cust_6' => $resp->cust_6,
                'cust_1' => $resp->cust_1,
            ));
        }

        foreach ($data as $key => $row) {
            $data[$key]['percent_hours'] = $params['hours'] > 0 ? round(100 * $row['hours'] / $params['hours'], 1) : 0;
            $data[$key]['percent_rsf'] = $params['rsf'] > 0 ? round(100 * $row['rsf'] / $params['rsf'], 1) : 0;
            $data[$key]['percent_rsf_cost_adjacent'] = $total['total_rsf_cost_adjacent'] > 0 ? round(100 * $row['rsf_cost_adjacent'] / $total['total_rsf_cost_adjacent'], 1) : 0;
            $data[$key]['percent_rsf_cost_current'] = $total['total_rsf_cost_current'] > 0 ? round(100 * $row['rsf_cost_current'] / $total['total_rsf_cost_current'], 1) : 0;
            $data[$key]['percent_rsf_cost_other'] = $total['total_rsf_cost_other'] > 0 ? round(100 * $row['rsf_cost_other'] / $total['total_rsf_cost_other'], 1) : 0;
            $data[$key]['percent_rsf_cost_regional'] = $total['total_rsf_cost_regional'] > 0 ? round(100 * $row['rsf_cost_regional'] / $total['total_rsf_cost_regional'], 1) : 0;

            $data[$key]['hours'] = round($row['hours']);
            $data[$key]['rsf'] = round($row['rsf']);
            $data[$key]['rsf_cost_adjacent'] = round($row['rsf_cost_adjacent']);
            $data[$key]['rsf_cost_current'] = round($row['rsf_cost_current']);
            $data[$key]['rsf_cost_other'] = round($row['rsf_cost_other']);
            $data[$key]['rsf_cost_regional'] = round($row['rsf_cost_regional']);
        }
        
        $response = array ();
        $response['total'] = $total;
        $response['rows']  = $data;
        $response['resps'] = $respIds;
        $response['locationResps'] = $locationResps;

        return response()->json($response);
    }
    /**
     * 
     * Get the data of activity by question
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivityByQuestion () {
        $params = $this->request->all();

        $questionDataAry = $this->rsData->getQuestionDataByResp($params['survey_id']);
        $questionAry = $this->rsData->getQuestionAry($params['survey_id']);
        $children    = $this->rsData->getFinalQuestionNodes($questionAry);
        $pidInfoAry  = $this->reportData->getAryOfQuestionWithParents($questionAry);

        $thisQuestions = $this->reportData->getQuestionByPid([$params['question_id']], $questionAry);
        $resps = json_decode($params['resps']);

        $data = array ();
        $include_resps = array ();
        $questionResps = array ();
        foreach ($resps as $resp) {
            if (array_key_exists($resp->resp_id, $questionDataAry)) {
                $questionData = $questionDataAry[$resp->resp_id];
                foreach ($questionData as $question_id => $row) {
                    if (in_array($question_id, $children)) {
                        foreach ($thisQuestions as $question) {
                            if (in_array($question, $pidInfoAry[$question_id])) {
                                $hours = $this->reportData->getQuestionHoursFromArray($resp->resp_id, $question_id, $questionDataAry);
                                if (!array_key_exists($resp->resp_id, $include_resps)) {
                                    $include_resps[$resp->resp_id] = $resp;
                                }
                                if (!array_key_exists($question, $data)) {
                                    $data[$question]['question_desc'] = $this->reportData->getQuestionDesc($question, $questionAry);
                                    $data[$question]['question_id'] = $question;
                                    $data[$question]['hours'] = $hours;                                
                                    $data[$question]['rsf'] = $resp->rentable_square_feet * $hours / $resp->total_hours;
                                    $data[$question]['rsf_cost_current'] = $resp->rsf_cost_current * $hours / $resp->total_hours;
                                    $data[$question]['rsf_cost_adjacent'] = $resp->rsf_cost_adjacent * $hours / $resp->total_hours;
                                    $data[$question]['rsf_cost_regional'] = $resp->rsf_cost_regional * $hours / $resp->total_hours;
                                    $data[$question]['rsf_cost_other'] = $resp->rsf_cost_other * $hours / $resp->total_hours;
                                } else {
                                    $data[$question]['hours'] += $hours;
                                    $data[$question]['rsf'] += $resp->rentable_square_feet * $hours / $resp->total_hours;
                                    $data[$question]['rsf_cost_current'] += $resp->rsf_cost_current * $hours / $resp->total_hours;
                                    $data[$question]['rsf_cost_adjacent'] += $resp->rsf_cost_adjacent * $hours / $resp->total_hours;
                                    $data[$question]['rsf_cost_regional'] += $resp->rsf_cost_regional * $hours / $resp->total_hours;
                                    $data[$question]['rsf_cost_other'] += $resp->rsf_cost_other * $hours / $resp->total_hours;
                                }
                                if (!array_key_exists($question, $questionResps)) {
                                    $questionResps[$question] = array ();
                                }
                                $questionResps[$question][$resp->resp_id] = array (
                                    'resp_id' => $resp->resp_id,
                                    'resp_first' => $resp->resp_first,
                                    'resp_last' => $resp->resp_last,
                                    'rentable_square_feet' => $resp->rentable_square_feet,
                                    'total_hours' => $resp->total_hours,
                                    'rsf_cost' => $resp->rsf_cost_current,
                                    'rsf_cost_adjacent' => $resp->rsf_cost_adjacent,
                                    'rsf_cost_regional' => $resp->rsf_cost_regional,
                                    'rsf_cost_other' => $resp->rsf_cost_other,
                                    'cust_3' => $resp->cust_3,
                                    'cust_6' => $resp->cust_6,
                                    'cust_1' => $resp->cust_1,
                                );
                            }
                        }
                    }
                }
            }
        }

        $max = 0;
        foreach ($data as $key => $row) {
            $data[$key]['percent_hours'] = round(100 * $row['hours'] / $params['hours']);
            $data[$key]['percent_rsf'] = round(100 * $row['rsf'] / $params['rsf']);
            $data[$key]['percent_rsf_cost_current'] = $data[$key]['percent_rsf'];
            $data[$key]['percent_rsf_cost_regional'] = $data[$key]['percent_rsf'];
            $data[$key]['percent_rsf_cost_adjacent'] = $data[$key]['percent_rsf'];
            $data[$key]['percent_rsf_cost_other'] = $data[$key]['percent_rsf'];
            $data[$key]['hours'] = round($data[$key]['hours']);                                
            $data[$key]['rsf'] = round($data[$key]['rsf']);
            $data[$key]['rsf_cost_current'] = round($data[$key]['rsf_cost_current']);
            $data[$key]['rsf_cost_adjacent'] = round($data[$key]['rsf_cost_adjacent']);
            $data[$key]['rsf_cost_regional'] = round($data[$key]['rsf_cost_regional']);
            $data[$key]['rsf_cost_other'] = round($data[$key]['rsf_cost_other']);
            if ($data[$key]['percent_hours'] > $max) {
                $max = $data[$key]['percent_hours'];
            }
        }

        $starting_percent = 10 - ceil($max / 10);

        $keys = array_column($data, 'hours');
        array_multisort($keys, SORT_DESC, $data);

        $response = array ();
        $response['total'] = array (
            'resp_num' => count($include_resps),
            'start_percent' => $starting_percent * 10,
        );
        $response['resps'] = $include_resps;
        $response['rows'] = $data;
        $response['questionResps'] = $questionResps;

        return response()->json($response);
    }
    /**
     * 
     * Get the url of the exported excel file
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function export_location_excel () {
        $excelData = json_decode($this->request->input('tableData'));
        $surveyName = (string) $this->request->input('survey_name');
        $label      = $this->request->input('label');
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A2', 'Full Name');
        $sheet->setCellValue('B2', 'Position');
        $sheet->setCellValue('C2', 'Employee ID');
        $sheet->setCellValue('D2', 'Location');
        $sheet->setCellValue('E2', 'RSF');
        $sheet->setCellValue('F2', 'Hours');
        $sheet->setCellValue('G2', 'RSF Cost(Current)');
        $sheet->setCellValue('H2', 'RSF Cost(Adjacent)');
        $sheet->setCellValue('I2', 'RSF Cost(Regional)');
        $sheet->setCellValue('J2', 'RSF Cost(Other)');

        $spreadsheet
            ->getActiveSheet()
            ->getStyle("A2:J2")
            ->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center');

        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(20);
        
        $total_hours = 0;
        $total_rsf = 0;
        $total_rsf_cost = 0;
        $total_rsf_cost_adjacent = 0;
        $total_rsf_cost_regional = 0;
        $total_rsf_cost_other = 0;
        $i = 4;

        foreach ($excelData as $row) {
            $sheet->setCellValue("A$i", $row->name);
            $sheet->setCellValue("B$i", $row->position);
            $sheet->setCellValue("C$i", $row->employee_id);
            $sheet->setCellValue("D$i", $row->location);
            $sheet->setCellValue("E$i", $row->rentable_square_feet);
            $sheet->setCellValue("F$i", $row->total_hours);
            $sheet->setCellValue("G$i", $row->rsf_cost);
            $sheet->setCellValue("H$i", $row->rsf_cost_adjacent);
            $sheet->setCellValue("I$i", $row->rsf_cost_regional);
            $sheet->setCellValue("J$i", $row->rsf_cost_other);
            $total_hours += $row->total_hours;
            $total_rsf += $row->rentable_square_feet;
            $total_rsf_cost += $row->rsf_cost;
            $total_rsf_cost_adjacent += $row->rsf_cost_adjacent;
            $total_rsf_cost_regional += $row->rsf_cost_regional;
            $total_rsf_cost_other += $row->rsf_cost_other;
            $i++;
        }
        
        $sheet->setCellValue('A3', 'Grand Total');
        $sheet->setCellValue('E3', $total_rsf);
        $sheet->setCellValue('F3', $total_hours);
        $sheet->setCellValue('G3', $total_rsf_cost);
        $sheet->setCellValue('H3', $total_rsf_cost_adjacent);
        $sheet->setCellValue('I3', $total_rsf_cost_regional);
        $sheet->setCellValue('J3', $total_rsf_cost_other);
        $sheet->mergeCells("A3:D3");

        $sheet = $this->reportData->addExcelCopyright($sheet, $i, "J", "Activation by Location Report Zoom({$surveyName}) - {$label}");

        $data['filename'] = "Activation by Location Report Zoom({$surveyName}) - {$label}.xlsx";
        $filename = "Activation by Location Report.xlsx";
  
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);      

        header("Content-Type: application/json");
        $data['url'] = url('/') . '/' . $filename;
        return json_encode($data);
    }

    // Opportunity Detail Report
    /**
     * 
     * Opportunity Detail Report
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function opportunity_detail ($survey_id) {
        CommonController::validateUser($survey_id, 'surveyRealEstate');

        $resps = $this->reportData->get_resp_data($survey_id);

        $data['resps']= $resps;
        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $survey =(object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']];
        $data['survey'] = $survey;

        $data = $this->reportData->getRespFilterDataAry($resps, $data);

        $data = $this->reportData->getTaxonomyFilterDataAry($survey_id, $data);

        $data['locationRates'] = Location::where('survey_id', $survey_id)->orderBy('location', 'ASC')->get();

        $rsfData = $this->rsData->getOpportunityDetailData($survey_id, $resps, 2, 'all');
        if($rsfData != 404){
            $data['rows'] = $rsfData['rows'];
            $data['total_hours'] = $rsfData['total_hours'];
            $data['total_rsf_cost'] = $rsfData['total_rsf_cost'];
        }
       

        return view('real_estate.opportunity-detail', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Filter opportunity detail report
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function filter_opportunity_detail () {
        $params = $this->request->all();

        CommonController::validateUser($params['survey_id'], 'surveyRealEstate');

        $filter_ary = array (
            'position' => json_decode($params['position']),
            'department' => json_decode($params['department']),
            'group' => json_decode($params['group']),
            'location' => json_decode($params['location']),
        );

        $resps = $this->reportData->get_resp_data($params['survey_id'], $filter_ary);
        
        $rsfData = $this->rsData->getOpportunityDetailData($params['survey_id'], $resps, $params['depthQuestion'], $params['proximity']);

        return $rsfData;
    }

    // Opportunity Summary Report
    /**
     * 
     * Opportunity Summary Report
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function opportunity_summary ($survey_id) {
        CommonController::validateUser($survey_id, 'surveyRealEstate');

        $resps = $this->rsData->get_resp_data($survey_id);

        $data['resps']= $resps;
        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $survey =(object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']];
        $data['survey'] = $survey;

        $data = $this->reportData->getRespFilterDataAry($resps, $data);

        $data = $this->reportData->getTaxonomyFilterDataAry($survey_id, $data);

        $data['locationRates'] = Location::where('survey_id', $survey_id)->orderBy('location', 'ASC')->get();

        $data['summaryData'] = $this->rsData->getOpportunitySummaryData($survey_id, $resps);

        // dd($data['summaryData']);

        return view('real_estate.opportunity-summary', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Filter opportunity summary report
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function filter_opportunity_summary () {
        $params = $this->request->all();

        CommonController::validateUser($params['survey_id'], 'surveyRealEstate');

        $filter_ary = array (
            'position' => json_decode($params['position']),
            'department' => json_decode($params['department']),
            'group' => json_decode($params['group']),
            'location' => json_decode($params['location']),
        );

        $resps = $this->rsData->get_resp_data($params['survey_id'], $filter_ary);

        $response = $this->rsData->getOpportunitySummaryData($params['survey_id'], $resps);

        return $response;
    }

    // Activity Cost by Location
    /**
     * 
     * Activity Cost by Location
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function activity_cost_by_location ($survey_id) {
        CommonController::validateUser($survey_id, 'surveyRealEstate');

        $resps = $this->reportData->get_resp_data($survey_id);

        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $survey =(object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']];
        $data['survey'] = $survey;

        $data = $this->reportData->getRespFilterDataAry($resps, $data);

        $data = $this->reportData->getTaxonomyFilterDataAry($survey_id, $data);

        $data['thAry'] = array (
            0 => 'Legal | Support',
            1 => 'Classification',
            2 => 'Substantive Area',
            3 => 'Category',
            4 => 'Process',
        );

        $data['depth'] = 4;

        $data['costData'] = $this->rsData->getActivityCostByLocationData($survey_id, $resps, $data['depth'] - 1, 'all');
        $total_employee_cost = 0;
        $resp_num = 0;
        $data['total_rsf'] = 0;
        $data['total_hours'] = 0;
        $data['total_rsf_cost_current'] = 0;
        if($data['costData'] != 404){
            foreach ($data['costData'] as $tmp) {
                $total_employee_cost += $tmp['total_employee_cost_num'];
                $resp_num += $tmp['resps'];
                $data['total_rsf'] += $tmp['total_rsf'];
                $data['total_hours'] += $tmp['total_hours'];
                $data['total_rsf_cost_current'] += $tmp['total_cost_current'];
            }
            $data['total_employee_cost'] = $total_employee_cost / $resp_num;
        }
        
        

        return view('real_estate.activity-cost-by-location', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Filter activity cost by location report
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function filter_activity_cost_by_location () {
        $params = $this->request->all();

        CommonController::validateUser($params['survey_id'], 'surveyRealEstate');

        $filter_ary = array (
            'position' => json_decode($params['position']),
            'department' => json_decode($params['department']),
            'group' => json_decode($params['group']),
            'location' => json_decode($params['location']),
        );

        $resps = $this->reportData->get_resp_data($params['survey_id'], $filter_ary);

        $data['thAry'] = array (
            0 => 'Legal | Support',
            1 => 'Classification',
            2 => 'Substantive Area',
            3 => 'Category',
            4 => 'Process',
        );

        $data['costData'] = $this->rsData->getActivityCostByLocationData($params['survey_id'], $resps, $params['depthQuestion'] - 1, $params['proximity']);

        $total_employee_cost = 0;
        $resp_num = 0;
        $data['total_rsf'] = 0;
        $data['total_hours'] = 0;
        $data['total_rsf_cost_current'] = 0;
        foreach ($data['costData'] as $tmp) {
            $total_employee_cost += $tmp['total_employee_cost_num'];
            $resp_num += $tmp['resps'];
            $data['total_rsf'] += $tmp['total_rsf'];
            $data['total_hours'] += $tmp['total_hours'];
            $data['total_rsf_cost_current'] += $tmp['total_cost_current'];
        }
        $data['total_employee_cost'] = $resp_num > 0 ? $total_employee_cost / $resp_num : 0;

        return $data;
    }
    /**
     * 
     * Proximity by Activity Report
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function proximity_by_activity ($survey_id) {
        CommonController::validateUser($survey_id, 'surveyRealEstate');
        
        $resps = $this->rsData->get_resp_data($survey_id);

        $data = array ();
        $data['max_hours'] = 0;

        $total_hours = 0;
        $high_hours = 0;
        $med_hours = 0;
        $low_hours = 0;
        $virtual_hours = 0;
        foreach ($resps as $resp) {
            $total_hours += $resp->total_hours;
            $high_hours += $resp->prox_high_hours;
            $med_hours += $resp->prox_medium_hours;
            $low_hours += $resp->prox_low_hours;
            $virtual_hours += $resp->prox_virtual_hours;
        }
       
        $data['rsf_percent_data'] = array ();
        $data['rsf_percent_data']['high_hours'] = $high_hours;
        $data['rsf_percent_data']['med_hours'] = $med_hours;
        $data['rsf_percent_data']['low_hours'] = $low_hours;
        $data['rsf_percent_data']['virtual_hours'] = $virtual_hours;
        $data['rsf_percent_data']['high_percent'] = $total_hours > 0 ? round(100 * $high_hours / $total_hours) : 0;
        $data['rsf_percent_data']['med_percent'] = $total_hours > 0 ? round(100 * $med_hours / $total_hours) : 0;
        $data['rsf_percent_data']['low_percent'] = $total_hours > 0 ? round(100 * $low_hours / $total_hours) : 0;
        $data['rsf_percent_data']['virtual_percent'] = $total_hours > 0 ? round(100 * $virtual_hours / $total_hours) : 0;
        // dd(100 * $virtual_hours / $total_hours);
        $data['resps']= $resps;
        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $survey =(object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']];
        $data['survey'] = $survey;

        $data = $this->reportData->getRespFilterDataAry($resps, $data);

        $data = $this->reportData->getTaxonomyFilterDataAry($survey_id, $data);

        $data['depth'] = 2;

        $data['thAry'] = array (
            0 => 'Legal | Support',
            1 => 'Classification',
            2 => 'Substantive Area',
            3 => 'Category',
            4 => 'Process',
        );

        $data['rows'] = $this->rsData->getProximitybyActivityData($survey_id, $resps, $data['depth'] - 1);

        $data['max_high_hours'] = 0;
        $data['max_med_hours'] = 0;
        $data['max_low_hours'] = 0;
        $data['max_virtual_hours'] = 0;
        $data['max_high_rsf'] = 0;
        $data['max_med_rsf'] = 0;
        $data['max_low_rsf'] = 0;
        $data['max_virtual_rsf'] = 0;
        if($data['rows'] != 404){
            foreach ($data['rows'] as $row) {
                if ($row['high_hours'] > $data['max_high_hours'])
                    $data['max_high_hours'] = $row['high_hours'];
                if ($row['med_hours'] > $data['max_med_hours'])
                    $data['max_med_hours'] = $row['med_hours'];
                if ($row['low_hours'] > $data['max_low_hours'])
                    $data['max_low_hours'] = $row['low_hours'];
                if ($row['virtual_hours'] > $data['max_virtual_hours'])
                    $data['max_virtual_hours'] = $row['virtual_hours'];
                if ($row['high_rsf'] > $data['max_high_rsf'])
                    $data['max_high_rsf'] = $row['high_rsf'];
                if ($row['med_rsf'] > $data['max_med_rsf'])
                    $data['max_med_rsf'] = $row['med_rsf'];
                if ($row['low_rsf'] > $data['max_low_rsf'])
                    $data['max_low_rsf'] = $row['low_rsf'];
                if ($row['virtual_rsf'] > $data['max_virtual_rsf'])
                    $data['max_virtual_rsf'] = $row['virtual_rsf'];
            }
        }
        
        
        return view('real_estate.proximity-by-activity', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Filter proximity by activity report
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function filter_proximity_by_activity () {
        $params = $this->request->all();
        $survey_id = $params['survey_id'];

        $filter_ary = array (
            'position' => json_decode($params['position']),
            'department' => json_decode($params['department']),
            'group' => json_decode($params['group']),
            'location' => json_decode($params['location']),
            'category' => json_decode($params['category']),
        );

        CommonController::validateUser($survey_id, 'surveyRealEstate');
        
        $resps = $this->rsData->get_resp_data($survey_id, $filter_ary);

        $data = array ();
        $data['max_hours'] = 0;

        $total_hours = 0;
        $high_hours = 0;
        $med_hours = 0;
        $low_hours = 0;
        foreach ($resps as $resp) {
            $total_hours += $resp->total_hours;
            $high_hours += $resp->prox_high_hours;
            $med_hours += $resp->prox_medium_hours;
            $low_hours += $resp->prox_low_hours;
        }
        $data['rsf_percent_data'] = array ();
        $data['rsf_percent_data']['high_hours'] = $high_hours;
        $data['rsf_percent_data']['med_hours'] = $med_hours;
        $data['rsf_percent_data']['low_hours'] = $low_hours;
        $data['rsf_percent_data']['high_percent'] = $total_hours > 0 ? round(100 * $high_hours / $total_hours) : 0;
        $data['rsf_percent_data']['med_percent'] = $total_hours > 0 ? round(100 * $med_hours / $total_hours) : 0;
        $data['rsf_percent_data']['low_percent'] = $total_hours > 0 ? round(100 * $low_hours / $total_hours) : 0;

        $data['depth'] = $params['depthQuestion'];

        $data['thAry'] = array (
            0 => 'Legal | Support',
            1 => 'Classification',
            2 => 'Substantive Area',
            3 => 'Category',
            4 => 'Process',
        );

        $data['rows'] = $this->rsData->getProximitybyActivityData($survey_id, $resps, $data['depth'] - 1);

        $data['max_high_hours'] = 0;
        $data['max_med_hours'] = 0;
        $data['max_low_hours'] = 0;
        $data['max_high_rsf'] = 0;
        $data['max_med_rsf'] = 0;
        $data['max_low_rsf'] = 0;

        foreach ($data['rows'] as $row) {
            if ($row['high_hours'] > $data['max_high_hours'])
                $data['max_high_hours'] = $row['high_hours'];
            if ($row['med_hours'] > $data['max_med_hours'])
                $data['max_med_hours'] = $row['med_hours'];
            if ($row['low_hours'] > $data['max_low_hours'])
                $data['max_low_hours'] = $row['low_hours'];
            if ($row['high_rsf'] > $data['max_high_rsf'])
                $data['max_high_rsf'] = $row['high_rsf'];
            if ($row['med_rsf'] > $data['max_med_rsf'])
                $data['max_med_rsf'] = $row['med_rsf'];
            if ($row['low_rsf'] > $data['max_low_rsf'])
                $data['max_low_rsf'] = $row['low_rsf'];
        }
        
        return $data;
    }
}
