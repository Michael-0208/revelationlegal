<?php

namespace App\Http\Controllers;

use App\Gateways\ReportData;
use App\Gateways\TaxonomyData;
use App\Models\Page;
use App\Models\Question;
use App\Models\Survey;
use App\Models\TblPageTest;
use App\Models\TblQuestionTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TaxonomyController extends Controller
{
    public function __construct(Request $request, TaxonomyData $taxonomyData)
    {
        $this->middleware(['auth:sanctum', 'verified']);
        $this->request = $request;
        $this->taxonomyData = $taxonomyData;
    }
    /**
     * 
     * Validate the access of user
     * 
     * @param int $survey_id    survey id
     * @return void|\Symfony\Component\Httpkernel\Exception\HttpException
     */
    protected function validateUser($survey_id)
    {
        if (!Auth::user()) {
            return redirect('login');
        }
        if (!Auth::user()->is_admin && Auth::user()->survey_id != $survey_id) {
            abort(403, 'Unauthorized Action!');
        }
    }
    /**
     * 
     * Taxonomy index
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index($survey_id)
    {
        CommonController::validateUser($survey_id, 'surveyTaxonomy');

        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $survey = (object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']
        ];
        $data['survey'] = $survey;

        $data['questions'] = $this->taxonomyData->getQuestionsBySurvey($survey_id);

        $survey = Survey::where('survey_id', $survey_id)->firstOrFail();

        $data['permissionCreate'] = 0;
        $data['permissionUpdate'] = 0;
        $data['permissionDelete'] = 0;
        $data['permissionExport'] = 0;

        if (Auth::user()->hasPermission('surveyTaxonomyCreate', $survey))
            $data['permissionCreate'] = 1;

        if (Auth::user()->hasPermission('surveyTaxonomyUpdate', $survey))
            $data['permissionUpdate'] = 1;

        if (Auth::user()->hasPermission('surveyTaxonomyDelete', $survey))
            $data['permissionDelete'] = 1;

        if (Auth::user()->hasPermission('surveyExport', $survey))
            $data['permissionExport'] = 1;
            // dd($data);

        return view('taxonomy.index', compact('data', 'survey'));
    }
    /**
     * 
     * Create a taxonomy
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $survey_id = $this->request->input('survey_id');

        CommonController::validateUser($survey_id, 'surveyTaxonomyCreate');

        $insert_data = array(
            'survey_id' => $survey_id,
            'question_enabled' => $this->request->input('question_enabled'),
            'question_code' => $this->request->input('question_code') ? $this->request->input('question_code') : '',
            'question_desc' => $this->request->input('question_desc') ? $this->request->input('question_desc') : '',
            'question_desc_alt' => $this->request->input('question_desc_alt') ? $this->request->input('question_desc_alt') : '',
            'question_extra' => $this->request->input('question_extra') ? $this->request->input('question_extra') : '',
            'question_extra_alt' => $this->request->input('question_extra_alt') ? $this->request->input('question_extra_alt') : '',
            'lead_codes' => $this->request->input('lead_codes') ? $this->request->input('lead_codes') : '',
            'question_proximity_factor' => $this->request->input('question_proximity_factor'),
        );
        $question_id_parent = $this->request->input('question_id_parent');

        $insertRow = $this->taxonomyData->insertQuestion($survey_id, $insert_data, $question_id_parent);

        if ($insertRow) {
            $res = $insertRow;
        } else {
            $res['status'] = 400;
        }

        return json_encode($res);
    }
    /**
     * 
     * Update the taxonomy
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        $survey_id = $this->request->input('survey_id');

        CommonController::validateUser($survey_id, 'surveyTaxonomyUpdate');

        $this->validateUser($survey_id);

        if ($this->request->input('update_flag') !== null) {
            $updated_data = array(
                'question_enabled' => $this->request->input('question_enabled'),
                'question_code' => $this->request->input('question_code') ? $this->request->input('question_code') : '',
                'question_desc' => $this->request->input('question_desc') ? $this->request->input('question_desc') : '',
                'question_desc_alt' => $this->request->input('question_desc_alt') ? $this->request->input('question_desc_alt') : '',
                'question_extra' => $this->request->input('question_extra') ? $this->request->input('question_extra') : '',
                'question_extra_alt' => $this->request->input('question_extra_alt') ? $this->request->input('question_extra_alt') : '',
                'lead_codes' => $this->request->input('lead_codes') ? $this->request->input('lead_codes') : '',
                'question_proximity_factor' => $this->request->input('question_proximity_factor'),
                'question_sortorder_id' => $this->request->input('question_sortorder_id'),
            );
        } else {
            $updated_data = array(
                'question_desc' => $this->request->input('update_desc')
            );
        }

        $updatedRow = $this->taxonomyData->updateQuestion($survey_id, $this->request->input('question_id'), $updated_data);
        // dd($updated_data);
        if (!$updatedRow) {
            $data['status'] = 400;
        } else {
            $data['status'] = 200;
        }

        return json_encode($data);
    }
    /**
     * 
     * Delete the taxonomy
     * 
     * @param int $survey_id    survey id
     * @param string $status
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function delete()
    {
        $survey_id = $this->request->input('survey_id');
        $question_id = $this->request->input('question_id');

        CommonController::validateUser($survey_id, 'surveyTaxonomyDelete');

        $deleteRow = $this->taxonomyData->removeQuestion($survey_id, $question_id);

        $data['status'] = $deleteRow;

        return json_encode($data);
    }
    /**
     * 
     * Return exported excel
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function export_excel()
    {
        $survey_id = $this->request->input('survey_id');
        $data_show = $this->request->input('data_show');
        $this->validateUser($survey_id);

        $taxonomyData = $this->taxonomyData->getExcelExportData($survey_id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A2', 'B_Code');
        $sheet->setCellValue('B2', 'Branch');
        $sheet->setCellValue('B3', 'C_Code');
        $sheet->setCellValue('C3', 'Classification');
        $sheet->setCellValue('C4', 'S_Code');
        $sheet->setCellValue('D4', 'Substantive Area/');
        $sheet->setCellValue('D5', 'P_Code');
        $sheet->setCellValue('E5', 'Process');
        $sheet->setCellValue('E6', 'A_Code');
        $sheet->setCellValue('F6', 'Activity');
        $sheet->getStyle('A2')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);
        $sheet->getStyle('B2')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);
        $sheet->getStyle('B3')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);
        $sheet->getStyle('C3')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);
        $sheet->getStyle('C4')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);
        $sheet->getStyle('D4')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);
        $sheet->getStyle('D5')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);
        $sheet->getStyle('E5')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);
        $sheet->getStyle('E6')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);
        $sheet->getStyle('F6')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);
        
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('B3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('D4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('D5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('E5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('E6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('F6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        
        if ($data_show == 0) {
            $sheet->setCellValue('G2', 'Proximity Factor');
            $sheet->getStyle('G2')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);
            $sheet->getStyle('G2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        } else {
            $sheet->setCellValue('G2', 'Description/Definition');
            $sheet->setCellValue('H2', 'Proximity Factor');
            $sheet->getStyle('G2')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);
            $sheet->getStyle('H2')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK); 
            $sheet->getStyle('G2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('H2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }

        $row = 8;
        //dd($taxonomyData);
        foreach ($taxonomyData as $taxonomy) {
            $sheet->setCellValue('A' . $row, $taxonomy['b_code']);
            $sheet->setCellValue('B' . $row, $taxonomy['c_code']);
            $sheet->setCellValue('C' . $row, $taxonomy['s_code']);
            $sheet->setCellValue('D' . $row, $taxonomy['p_code']);
            $sheet->setCellValue('E' . $row, $taxonomy['a_code']);
            $sheet->setCellValue('F' . $row, $taxonomy['activity']);
            if ($data_show == 0) {
                $sheet->setCellValue('G' . $row, $taxonomy['question_proximity_factor']);
            } else {
                $sheet->setCellValue('G' . $row, $taxonomy['question_extra']);
                $sheet->setCellValue('H' . $row, $taxonomy['question_proximity_factor']);
            }
            $row++;
        }

        if ($data_show == 0) {
            $spreadsheet
                ->getActiveSheet()
                ->getStyle('A2:G2')
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('c9c9c7');

            $spreadsheet
                ->getActiveSheet()
                ->getStyle('A2:G2')
                ->getAlignment()
                ->setHorizontal('left')
                ->setVertical('center');
        } else {
            $spreadsheet
                ->getActiveSheet()
                ->getStyle('A2:H2')
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('c9c9c7');

            $spreadsheet
                ->getActiveSheet()
                ->getStyle('A2:H2')
                ->getAlignment()
                ->setHorizontal('left')
                ->setVertical('center');
        }

        $rows = 1 + count($taxonomyData);

        $spreadsheet->getActiveSheet()->getStyle("A2:H$rows")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        if ($data_show == 0) {
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        } else {
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(80);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        }

        $surveyData = Survey::where('survey_id', $survey_id)->firstOrFail();

        // Table Caption and Copyright
        $reportData = new ReportData();
        $sheet = $reportData->addExcelCopyright($sheet, $row, "G", "Taxonomy Data({$surveyData->survey_name})");

        $fileName = "TaxonomyData.xlsx";
        //dd($fileName);
        $writer = new Xlsx($spreadsheet);
       // dd($writer);
        $writer->save('./public/' .$fileName);
        
        $data['url'] = url('/') . '/' . $fileName;
        //dd($data);
        return $data;
    }
    /**
     * 
     * Check if the taxonomy page exists
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkTaxonomyPage () {
        $survey_id = $this->request->input('survey_id');
        $parent_id = $this->request->input('pid');

        $page = Page::where('survey_id', $survey_id)
            ->where('question_id_parent', $parent_id)
            ->get();

        if (count($page) != 0) {
            $data['status'] = 200;
        } else {
            $data['status'] = 400;
            $parent_question = Question::where('survey_id', $survey_id)
                ->where('question_id', $parent_id)
                ->get();
            $data['parent_question'] = $parent_question[0];
        }

        return json_encode($data);
    }
    /**
     * 
     * Create a page
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function createPageForTaxonomy () {
        $survey_id  = $this->request->input('survey_id');
        $page_title = $this->request->input('page_title');
        $page_desc  = $this->request->input('page_desc');
        $question_id_parent = $this->request->input('pid');

        $insert_data = array (
            'survey_id' => $survey_id,
            'page_id' => Page::max('page_id') + 1,
            'question_id_parent' => $question_id_parent,
            'page_type' => 1,
            'page_desc' => $page_title,
            'page_extra' => $page_desc,
            'page_id_original' => Page::max('page_id_original') + 1,
        );
     
        $insertRow = Page::create($insert_data);

        if ($insertRow) {
            return 200;
        }
    }

    public function uploadtaxanomyData(Request $request){
        // dd('taxanomyy');
        // dd($request->all());
        $file = $request->file('exceldata');
        $f = fopen($file->getRealPath(), 'r');
        /* Array
(
    [0] => ﻿553
    [1] => 0
    [2] => Support Activities
    [3] => Direct and indirect support provided to firm personnel as they deliver, or support the delivery, of legal services, advice and counsel.
    [4] => 2
    [5] =>  
    [6] => SA
)*/
        // $data = [];
        $key = 553;
        while (($filedata = fgetcsv($f, 1000, ",")) !== false) {
            //if()
           /*  echo "<pre>";
            print_r($filedata); */
            
            $question_parent_id = (int)$filedata[1];
            $question_id = (int)$filedata[0];
            $questions = $filedata[2];
            $questions_desc_alt = $filedata[3];
            $question_ext = $filedata[3];
            $proximity_factor = (int)$filedata[4];
            $lead_codes = $filedata[5];
            $question_UTBMS = $filedata[5];
            $question_codes = $filedata[6];
            // $question_UTBMS = $filedata[6];

            // echo $question_id;
            $page = new Page();
            $page->survey_id = 54;
            $page->page_id = $key;
            $page->question_id_parent = $question_parent_id;
            $page->page_desc = $questions;
            /* if($question_parent_id == 0){
                
                $page->page_extra = '<p>You are at the beginning of the Legal Services branch of questions.  Within this branch, you will be asked to allocate the <strong>[ANSWER VALUE]</strong> hours you devote annually to providing support services irrespective of whether or not they are chargeable to a client.</p>
                <p>The survey will guide you through a tiered structure of questions where you will be asked to allocate your time by assigning percentages to various activities.  Because the survey is progressive, each level represents a greater level of detail.  Accordingly, at each level of the survey, you are asked to allocate 100% of the time you spend within that category only.</p>' ;
            } */ 

            if($page->save()){
               /*  echo $page->page_id;*/
                echo "Inserted into TBLPAGE - "; 
                // $page_id = $key;

                $question = new Question();
                $question->survey_id = 54;
                $question->question_id = $question_id;
                $question->page_id = $page->page_id;
                $question->question_desc = $questions;
                $question->question_desc_alt = $questions_desc_alt;
                $question->question_extra = $question_ext;
                $question->question_proximity_factor = $proximity_factor;
                $question->lead_codes = $lead_codes;
                $question->question_code = $question_codes;
                $question->question_UTBMS = $lead_codes;  
                $insertedQuestion = $question->save();
                
                if($insertedQuestion){
                    echo "Inserted into TBLQUESTION <br>";
                }
            }
            
            
            $key++;
        }
        exit;
        

        
       
    }
}
