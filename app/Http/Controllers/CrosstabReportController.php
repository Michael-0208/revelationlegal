<?php

namespace App\Http\Controllers;

use App\Gateways\ReportData;
use Illuminate\Http\Request;
use App\Models\Survey;
use App\Http\Controllers\CommonController;
use App\Models\Question;
use App\Models\Respondent;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CrosstabReportController extends Controller
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
     * 
     * Crosstab Individual Report
     * 
     * @param \App\Models\Survey
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function individual_report(Survey $survey)
    {
        CommonController::validateUser($survey->survey_id, 'surveyReports');

        $attrs = CommonController::get_survey_attributes($survey->survey_id);
        $resps = $this->reportData->get_resp_data($survey->survey_id);

        $data['resps'] = $resps;
        $data['survey'] = $survey;
        $data = array_merge($data, $this->reportData->getRespondentData($resps));
        $data['taxonomy'] = $this->reportData->getSurveyTaxonomy($survey->survey_id);

        //array_filter($data['resps']);
        //dd($this->reportData->getRespondentData($resps));

        return view('reports.reports.crosstab', compact('data', 'survey'));

        /**
         * Notes:
         * The data for the vertical breakdown is pulled from the tblquestion table
         * The entries in the vertical breakdown selector, filter the questions based on question_code?
         *
         * In the horizontal breakdown, just filter on the respondent data columns in $data
         * The entries in the horizontal breakdown selector come from the survery (with title = position)
         *
         * Metric just shows what calculations / values to use with the total going in its own column and the breakdown going to the right
         */
    }
    /**
     * 
     * Get the data for individual crosstab report data
     * 
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function fetchIndividualReportData(Request $request)
    {
        
        if (!\Auth::check()) {
            abort(403);
        }
        
        $data = $request->all();

        $survey = Survey::find($data['survey_id']);

        $filter = [];
        $results = [];

        $metric = $data['metric']; // determines what calculations to use

        if (isset($data['location'])) {
            $filter['tblrespondent.cust_6'] = $data['location'];
        }

        /* if (isset($data['taxonomy'])) {
            $filter['tblquestion.question_desc'] = $data['taxonomy'];
        } */
        
        // map the horizontal breakdown value passed to use to the column name on the repondents table
        $table_column = CrosstabReportController::filterNameToTableColumnMap($data['horizontal_breakdown']);
        
        // dd($filter);
        // get a breakdown of the questions for the entire survey indexed by their depth
        $report_data = $this->reportData->getQuestionsByArea($survey,  $filter);
        //  
        // dd($report_data[1]);
        if (isset($report_data[$data['vertical_breakdown']])) {
            $report_data = collect($report_data[$data['vertical_breakdown']]); // retrieve the questions at the specified depth
        } else {
            return $results;
        }
        // dd($report_data[0]);

        $column_headings = [
            'EMPTY',
        ];

        $total_column = '';
        if ($metric == 'hours_per_employee')
            $total_column = 'Hours / Employee';
        elseif ($metric == 'cost_per_employee')
            $total_column = 'Cost / Employee';
        elseif ($metric == 'percent_of_time_per_employee')
            $total_column = 'Time Percentage / Employee';
        elseif ($metric == 'total_hours')
            $total_column = 'Total Hours';
        elseif ($metric == 'total_cost')
            $total_column = 'Total Cost';
        elseif ($metric == 'cost_per_hour')
            $total_column = 'Cost / Hour';

        $column_headings[] = $total_column;

        $maxAnswer = 0;
        $answer_count = 0;
        $answer_sum = 0;

        $questions = $report_data->groupBy('question');
        // dd($questions);

        foreach ($questions as $row => $respondent_answers) {

            $maxAnswer = max($maxAnswer, $respondent_answers->max('answer'));
            // $results[$row] = $respondent_answers->question_parent_id;
            $grouped_answers = $respondent_answers->groupBy($table_column);
            // dd($results);

            foreach ($grouped_answers as $column => $answers) {
                // dd($column);
                if ($column == 'EMPTY')
                    continue;

                if (!in_array($column, $column_headings))
                    $column_headings[] = $column;

                $answers = $answers->where('answer', '>', 0);
                $total_answers = $answers->count();
                
                $answer_count += $total_answers;

                // initialize this array element if it doesn't exist already
                if (!isset($results[$row][$column]))
                    $results[$row][$column] = 0;

                // initialize this array element if it doesn't exist already
                if (!isset($results[$row][$total_column]))
                    $results[$row][$total_column] = 0;

                $current_cell = &$results[$row][$column];
                $overall_cell = &$results[$row][$total_column];

                // echo "<pre>";
                // print_r($results);
                // echo "</pre>";

                if ($metric == 'hours_per_employee') {
                    if ($total_answers) {

                        $sum = $answers->sum('answer');
                        $total = $sum / $total_answers;
                        $answer_sum += $sum;
                    } else {
                        $total = 0;
                    }


                    foreach ($answers as $answer) {
                        $results[$row]['question_parent_id']= $answer->question_parent_id;
                        
                    }

                    $current_cell = $total;
                    $overall_cell += $total;
                } elseif ($metric == 'cost_per_employee') {
                    if ($total_answers) {
                        $cost = $answers->reduce(function ($overall_cost, $respondent) {
                            return $overall_cost + ($respondent->percentage * $respondent->resp_compensation); // 0.1 > 0.06, %-> 0.0601, % -> 0.2
                        });
                        $total = $cost / $total_answers;
                    } else {
                        $total = 0;
                    }

                    foreach ($answers as $answer) {
                        $results[$row]['question_parent_id']= $answer->question_parent_id;
                        
                    }

                    $current_cell = $total;
                    $overall_cell += $total;
                    $answer_sum += $total;

                } elseif ($metric == 'percent_of_time_per_employee') {

                    if ($total_answers) {
                        $percentage = $answers->reduce(function ($overall_percentage, $respondent) {
                            return $overall_percentage + ($respondent->percentage * 100);
                        });
                        $total = $percentage / $total_answers;
                    } else {
                        $total = 0;
                    }

                    foreach ($answers as $answer) {
                        $results[$row]['question_parent_id']= $answer->question_parent_id;
                        
                    }

                    $current_cell = $total;
                    $overall_cell += $total;
                    $answer_sum += $total;
                } elseif ($metric == 'total_hours') {
                    $hours = $answers->sum('answer');
                    /*  */
                    $current_cell = $hours;
                    $overall_cell += $hours;
                    
                    foreach ($answers as $answer) {
                        $results[$row]['question_parent_id']= $answer->question_parent_id;
                        
                    }
                    /* echo "<pre>";
                    print_r($overall_cell.' - ');
                    echo "</pre>"; */

                } elseif ($metric == 'total_cost') {
                   
                    $cost = collect($answers)->reduce(function ($overall_cost, $respondent) {
                        
                        return ($overall_cost + ( $respondent->percentage * $respondent->resp_compensation ));
                        //  $overall_cost;
                       
                    });
                    // cost);
                    foreach ($answers as $answer) {
                        $results[$row]['question_parent_id']= $answer->question_parent_id;
                        
                    }
                   
                   
                    
                    $current_cell = $cost;
                    $overall_cell += $cost;
                    $answer_sum += $cost;
                    
                   /*  $cost = $answers->reduce(function ($overall_cost,$respondent){
                        $overall_cost = $respondent->resp_compensation + $respondent->resp_compensation * $respondent->resp_benefit_pct;

                        return $overall_cost;
                    });

                    $current_cell = $cost;
                    $overall_cell += $cost;
                    $answer_sum += $cost; */


                } elseif ($metric == 'cost_per_hour') {
                    $hours = $answers->sum('answer');
                    $cost = $answers->reduce(function ($overall_cost, $respondent) {
                        return $overall_cost + ($respondent->percentage * $respondent->resp_compensation);
                    });
                    $total = $hours ? $cost / $hours : 0;

                    foreach ($answers as $answer) {
                        $results[$row]['question_parent_id']= $answer->question_parent_id;
                        
                    }

                    $current_cell = $total;
                    $overall_cell += $total;
                    $answer_sum += $total;
                }
                
            }
        }

    
        ksort($results);

        if (isset($data['is_test'])){

        }else{
            return [
                'average' => $answer_sum / $answer_count,
                'breakdown' => $results,
                'headings' => $column_headings,
                'max' => $maxAnswer
            ];
        }
    }
    /**
     * 
     * Return the map of metric keys
     * 
     * @return array
     */
    public static function getMetricMap()
    {
        return [
            'hours_per_employee' => 'Hours / Employee',
            'cost_per_employee' => 'Cost / Employee',
            'percent_of_time_per_employee' => '% of Time / Employee',
            'total_hours' => 'Total Hours',
            'total_cost' => 'Total Cost',
            'cost_per_hour' => 'Cost / Hour',
        ];
    }
    /**
     * 
     * Return the custom fields 
     * 
     * @param string $name
     * @return string
     */
    public static function filterNameToTableColumnMap($name)
    {
        return $name == 'position' ? 'cust_3'
            : ($name == 'department' ? 'cust_4'
                : ($name == 'group' ? 'cust_2'
                    : ($name == 'location' ? 'cust_6'
                        : 'cust_5')));
    }
    /**
     * 
     * Get the url of the exported excel file
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportExcel () {
        $param = $this->request->all();

        $headers = json_decode($param['headers']);
        $rowsData = json_decode($param['rows']);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $colStr = 'A';
        foreach ($headers as $col) {
            $sheet->setCellValue($colStr . '2', $col);
            ++$colStr;
        }

        $colStr = chr(ord($colStr) - 1);
        /* $spreadsheet
            ->getActiveSheet()
            ->getStyle("A1:{$colStr}1")
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('00FF7F'); */

        $spreadsheet
            ->getActiveSheet()
            ->getStyle("A1:H1")
            ->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center');

        $rows = 3;
        foreach ($rowsData as $row) {
            $colStr = 'A';
            foreach ($row as $item) {
                $sheet->setCellValue($colStr . $rows, $item);
                if ($colStr != 'A') {
                    $conditional = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                    $conditional2 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                    $conditional3 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                    $conditional->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
                    $conditional2->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
                    $conditional3->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
                    $conditional->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_GREATERTHAN);
                    $conditional2->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_LESSTHAN);
                    $conditional3->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_EQUAL);
                    $conditional->addCondition(500);
                    $conditional2->addCondition(500);
                    $conditional3->addCondition(0);
                    //$conditional->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN);
                    
                    $conditional->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
                    $conditional2->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
                    $conditional3->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
                    $conditional->getStyle()->getFill()->getStartColor()->applyFromArray(['rgb' => 'ffc28a']);
                    $conditional2->getStyle()->getFill()->getStartColor()->applyFromArray(['rgb' => '00FF7F']);
                    $conditional3->getStyle()->getFill()->getStartColor()->setARGB(Color::COLOR_RED);
                    $conditional->getStyle()->getFill()->getEndColor()->applyFromArray(['rgb' => 'ffc28a']);
                    $conditional2->getStyle()->getFill()->getEndColor()->applyFromArray(['rgb' => '00FF7F']);
                    $conditional3->getStyle()->getFill()->getEndColor()->setARGB(Color::COLOR_RED);
                    
                    $conditionalStyles = $sheet->getStyle($colStr . $rows)->getConditionalStyles();
                    $conditionalStyles[] = $conditional;
                    $conditionalStyles[] = $conditional2;
                    $conditionalStyles[] = $conditional3;
                    //dd($conditionalStyles);
                    $sheet->getStyle($colStr . $rows)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle($colStr . $rows)->setConditionalStyles($conditionalStyles);
                }
                ++$colStr;
            }
            $rows++;
        }

        $sheet->getColumnDimension('A')->setWidth(30);
        for ($tmpStr = 'B'; $tmpStr < $colStr; ++$tmpStr) {
            $spreadsheet->getActiveSheet()->getColumnDimension($tmpStr)->setWidth(25);
        }

        $surveyData = Survey::where('survey_id', $param['survey_id'])->firstOrFail();
        //dd($surveyData);
        $sheet = $this->reportData->addExcelCopyright($sheet, $rows, $tmpStr, "Crosstab Report({$surveyData->survey_name})");
        //dd($sheet);
        $fileName = public_path('crosstab_individual.xlsx');
        $writer = new Xlsx($spreadsheet);
        
        $writer->save($fileName);        
        header("Content-Type: application/json");
        $data['url'] = url('/') . '/public/' . 'crosstab_individual.xlsx';
        $data['filename'] = "Crosstab Report({$surveyData->survey_name}).xlsx";
        return json_encode($data);
    }
}
