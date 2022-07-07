<?php

namespace App\Gateways;

use App\Models\Answer;
use App\Models\Page;
use App\Models\Question;
use App\Models\Setting;
use App\Models\Respondent;
use App\Models\SupportLocation;
use App\RevelationLegal\QuestionIterator\SurveyQuestionCollection;
use Illuminate\Support\Facades\Cache;

class SurveyData
{
    /**
     * @var integer respondent answer
     */
    private $respondent_answers;
    /**
     * @var integer respondent id
     */
    private $respondent_id;

    public function __construct($respondent_id)
    {
        $this->respondent_id = $respondent_id;
        $this->respondent_category = Respondent::find($respondent_id)->cust_5;
        $this->loadRespondentAnswers($respondent_id);
    }
    /**
     * 
     * Returns the data of questions
     * 
     * @param int $survey_id    survey id
     * @return \App\RevelationLegal\QuestionIterator\SureyQuestionCollection
     */
    public function getQuestions($survey_id)
    {
        // FIXME: Code duplication here, need to extract all instances of question query joined with page and answers to model / repo
        

        $questions = Question::join('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->join('tblanswer', function ($tblanswer) {
                $tblanswer->on('tblanswer.question_id', 'tblquestion.question_id')->where('tblanswer.resp_id', $this->respondent_id);
            })
            ->select('tblquestion.*', 'tblpage.question_id_parent', 'tblpage.page_desc', 'tblpage.page_extra', 'tblanswer.answer_value')
            ->where('tblquestion.survey_id', $survey_id)
            ->get();

        $collection = new SurveyQuestionCollection();

        foreach ($questions as $question) {
            $collection->addQuestion($question);
        }

        return $collection;
    }
    /**
     * 
     * Returns the data of root questions
     * 
     * @param int $survey_id    survey id
     * @return array
     */
    public function getRootQuestion($survey_id)
    {

        $settings = Setting::where('survey_id', $survey_id)->first();

        $locations = collect();
        if ($settings->show_location_dist) {
            $locations = SupportLocation::select('tblsupportlocation.id AS location_id', 
                                                'tblrespondentlocation.id AS answer_id', 
                                                'tblsupportlocation.support_location_desc AS name', 
                                                'tblsupportlocation.support_location_id', 
                                                'tblrespondentlocation.resp_pct AS answer')
                ->leftJoin('tblrespondentlocation', function ($tblrespondentlocation) {
                    $tblrespondentlocation->on('tblrespondentlocation.support_location_id', 'tblsupportlocation.id')->where('tblrespondentlocation.resp_id', $this->respondent_id);
                })
                ->where('survey_id', $survey_id)
                ->get();
        }
        
        foreach ($locations as $location) {
            if (!$location->answer)
                $location->answer = 0;
        }

        //FIXME: This is a lot of hardcoded logic because I'm not sure where it gets derived
        // Either way it defines the interface / data structures by the frontend
        $branches = [];

        // support branch
        $support = Question::join('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->leftJoin('tblanswer', function ($tblanswer) {
                $tblanswer->on('tblanswer.question_id', 'tblquestion.question_id')->where('tblanswer.resp_id', $this->respondent_id);
            })
            ->select('tblquestion.*', 'tblpage.question_id_parent', 'tblanswer.answer_value')
            ->where('question_id_parent', 0)
            ->where('tblquestion.survey_id', $survey_id)
            ->where('question_desc', 'LIKE', '%Support%')
            ->first();

        // legal branch
        $legal = Question::join('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->leftJoin('tblanswer', function ($tblanswer) {
                $tblanswer->on('tblanswer.question_id', 'tblquestion.question_id')->where('tblanswer.resp_id', $this->respondent_id);
            })
            ->select('tblquestion.*', 'tblpage.question_id_parent', 'tblanswer.answer_value')
            ->where('question_id_parent', 0)
            ->where('tblquestion.survey_id', $survey_id)
            ->where('question_desc', 'LIKE', '%Legal%')
            ->first();

        $branches[] = [
            'name' => $legal ? $legal->question_desc : '',
            'description' => "<p>You are at the beginning of the Legal Services branch of questions. Within this branch, you will be asked to allocate the {hours} hours you devote annually to providing legal advice and related services irrespective of whether those services are chargeable to clients.</p><p>The survey will guide you through a tiered structure of questions where you will be asked to allocate your time by assigning percentages to various activities. Because the survey is progressive, each level represents a greater level of detail. Accordingly, at each level of the survey, you are asked to allocate 100% of the time you spend within that category only.</p>",
            'question_id' => $legal ? $legal->question_id : 0,
            'completed' => false,
            'answer' => $legal && $legal->answer_value ? $legal->answer_value : 0,
        ];

        $branches[] = [
            'name' => $support->question_desc,
            'description' => "<p>You are at the beginning of the Support Activities branch of questions. Within this branch, you will be asked to allocate the {hours} hours you devote annually to providing support services irrespective of whether or not they are chargeable to a client.</p><p>The survey will guide you through a tiered structure of questions where you will be asked to allocate your time by assigning percentages to various activities. Because the survey is progressive, each level represents a greater level of detail. Accordingly, at each level of the survey, you are asked to allocate 100% of the time you spend within that category only.</p>",
            'question_id' => $support->question_id,
            'completed' => false,
            'answer' => $support->answer_value ? $support->answer_value : 0,
        ];

        return [
            'branches' => $branches,
            'locations' => $locations
        ];
    }
    /**
     * 
     * Returns the current question data in the survey
     * 
     * @param int $question_id    question id
     * @return array 
     */
    public function getCurrentQuestion($question_id)
    {
        $current = $this->getQuestion($question_id);

        $tempQuestions = $this->getChildQuestions($current->question_id);

        if (!$tempQuestions->count())
            $tempQuestions = $this->getChildQuestions($current->question_id_parent);
            
        $questions = [];
        
        if($this->respondent_category == 'Legal'){
            foreach ($tempQuestions as $question) {
                // if()
                // dd($question);
                $questions[] = [
                    'id' => $question->question_id,
                    'label' => $question->question_desc,
                    'description' => $question->question_extra,
                    'answer' => $question->answer_value ?? 0 // TODO: need to join the answer value if it already exists, but we don't want to fire a query to find the answer for each question
                ];
            }
      
        }else{
            foreach ($tempQuestions as $question) {
                // dd($question);
              
                $questions[] = [
                    'id' => $question->question_id,
                    'label' => $question->question_desc,
                    'description' => $question->question_extra,
                    'answer' => $question->answer_value ?? 0 // TODO: need to join the answer value if it already exists, but we don't want to fire a query to find the answer for each question
                ];
            }
    
        }
        
        /* foreach ($tempQuestions as $question) {
            $questions[] = [
                'id' => $question->question_id,
                'label' => $question->question_desc,
                'description' => $question->question_extra,
                'answer' => $question->answer_value ?? 0 // TODO: need to join the answer value if it already exists, but we don't want to fire a query to find the answer for each question
            ];
        }
 */


        // build up a path from the root to the current question
        $question_path = [];
        $tmpQuestion = $current;

        // $current_page = Page::where('page_id', $this->getQuestion($questions[0]['id'])->page_id)->first();

        array_push($question_path, $current->question_desc);
        while ($tmpQuestion->question_id_parent != 0) {
            $tmpQuestion = $this->getQuestion($tmpQuestion->question_id_parent);
            array_push($question_path, $tmpQuestion->question_desc);
        }

        $question_path = array_reverse($question_path);
        // if($current->survey_id != 61){
            $survey_length = $this->getSurveyLength($current->survey_id);
        // }
        
        // if($current->survey_id != 61){
            $data = [
                'questions' => $questions,
                'question' => $current ? $current->question_id : null,
                'parent' => $current ? $current->question_id_parent : null,
                'question_description' => $current ? str_replace('[SURVEY POSITION]', $current->question_desc, $current->page_extra) : '',
                'question_path' => $question_path,
                'percent_complete' => ((int)((array_search($current->question_id, $survey_length) / count($survey_length)) * 100))
            ];
        // }else{
           /*  $data = [
                'questions' => $questions,
                'question' => $current ? $current->question_id : null,
                'parent' => $current ? $current->question_id_parent : null,
                'question_description' => $current ? str_replace('[SURVEY POSITION]', $current->question_desc, $current->page_extra) : '',
                'question_path' => $question_path,
            ];
        } */

        return $data;
    }
    /**
     * 
     * Returns the next question of current one
     * 
     * @param int $question_id    question id
     * @return array 
     */
    public function getNextQuestion($question_id)
    {

        // try and get the next descendant of this question
        $question = $this->getNextChildQuestion($question_id);

        // if no descendants, get an ancestor of this question
        if (!$question)
            $question = $this->getNextParentQuestion($question_id);

        // NOTE: This might cause problems...
        if ($question && $this->getChildQuestions($question->question_id)->count() < 1)
            $question = null;

        return $question;
    }
    /**
     * 
     * Returns the question data with the question id
     * 
     * @param int $question_id    question id
     * @param array $filters
     * @return \App\Models\Question 
     */
    public function getQuestion($question_id, $filters = [])
    {
        $question = Question::join('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->leftJoin('tblanswer', function ($tblanswer) {
                $tblanswer->on('tblanswer.question_id', 'tblquestion.question_id')->where('tblanswer.resp_id', $this->respondent_id);
            })
            ->select('tblquestion.*', 'tblpage.question_id_parent', 'tblpage.page_desc', 'tblpage.page_extra', 'tblanswer.answer_value')
            ->where('tblquestion.question_id', $question_id);


        return $question->first();
    }
    /**
     * 
     * Returns the row updated
     * 
     * @param int $survey_id    survey id
     * @param \Illuminate\Http\Request $req    request updated
     * @return \App\Models\Setting 
     */
    public function getChildQuestion($question_id)
    {
        $questions = Question::join('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->leftJoin('tblanswer', function ($tblanswer) {
                $tblanswer->on('tblanswer.question_id', 'tblquestion.question_id')->where('tblanswer.resp_id', $this->respondent_id);
            })
            ->select('tblquestion.*', 'tblpage.question_id_parent', 'tblpage.page_desc', 'tblanswer.answer_value')
            ->where('tblpage.question_id_parent', $question_id);
        return $questions->first();
    }
    /**
     * 
     * Returns the child questions
     * 
     * @param int $parent_id    parent id
     * @return \App\Models\Question 
     */
    public function getChildQuestions($parent_id)
    {
        $questions = Question::join('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->leftJoin('tblanswer', function ($tblanswer) {
                $tblanswer->on('tblanswer.question_id', 'tblquestion.question_id')->where('tblanswer.resp_id', $this->respondent_id);
            })
            ->select('tblquestion.*', 'tblpage.question_id_parent', 'tblanswer.answer_value')
            ->where('tblpage.question_id_parent', $parent_id)
            ->orderBy('tblquestion.question_seq');

        return $questions->get();
    }
    /**
     * 
     * Returns the sibling question
     * 
     * @param int $parent_id    parent question id
     * @param int $question_id    question id
     * @param int $direction    direction
     * @return \App\Models\Question
     */
    public function getSibling($parent_id, $question_id, $direction)
    {
        // return $this->getSiblings($parent_id)->firstWhere('question_id', $direction, $question_id);
        $question_seq = $this->getQuestion($question_id)->question_seq;

        return $this->getSiblings($parent_id)
            ->filter(function ($sibling) { // dont' want to jump siblings if they don't have any children
                return $this->getChildQuestions($sibling->question_id)->count() > 0;
            })
            ->firstWhere('question_seq', $direction, $question_seq);
    }
    /**
     * 
     * Returns the sibling questions
     * 
     * @param int $parent_id    parent question id
     * @return \App\Models\Question
     */
    public function getSiblings($parent_id)
    {
        $question = Question::join('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->leftJoin('tblanswer', function ($tblanswer) {
                $tblanswer->on('tblanswer.question_id', 'tblquestion.question_id')->where('tblanswer.resp_id', $this->respondent_id);
            })
            ->select('tblquestion.*', 'tblpage.question_id_parent')
            ->whereNotNull('tblanswer.answer_value')
            ->where('tblanswer.answer_value', '>', 0)
            ->where('tblpage.question_id_parent', $parent_id)
            // ->orderBy('tblquestion.question_desc');
            // ->orderBy('tblquestion.question_id');
            ->orderBy('tblquestion.question_seq');

        // ->where('tblquestion.question_id', $this->respondent_answers->pluck('question_id'));
        return $question->get();
    }
    /**
     * 
     * Update the member respodent answer
     * 
     * @param int $respondent_id   respondent id
     */
    public function loadRespondentAnswers($respondent_id)
    {
        $answers = Answer::where('resp_id', $respondent_id)->get();
        $this->respondent_answers = $answers;
    }

    /**
     * Get the next available descendant question
     *
     * @param int $question_id
     * @return Question
     */
    public function getNextChildQuestion($question_id)
    {
        /**
         * Our movement is down first, then across
         * Visit children first, then siblings if there are no more children
         * We have already answered the child questions for this question
         * We are only interested in child questions that have an answered parent question and have children of their own
         * Stop as soon as we find an available question
         */
        // dd()

        $current_question = $this->getQuestion($question_id);
        $child_questions = $this->getChildQuestions($question_id); // FIXME: Refractor so that we are getting only the children with answers without all this loopy stuff
        $answers = $this->getQuestionAnswers($child_questions->pluck('question_id')); // FIXME: Refractor so that we are getting only the children with answers without all this loopy stuff

        // we only want the child questions of parents that have answers (if the parent wasn't answered then we skip the children)
        $child_questions = $child_questions->whereIn('question_id', $answers->pluck('question_id')); // FIXME: Refractor so that we are getting only the children with answers without all this loopy stuff

        // if this question has children, return the first child that has it's own children
        // was just returning the first child (regardless even it didn't have children)
        // that works, but then we end up iterating through children without having any need to
        if ($child_questions->count())
            return $child_questions->filter(function ($question) {
                return $this->getChildQuestion($question->question_id);
            })->first();


        // no grandchildren to visit so return the next sibling in order from least to greatest (or NULL if there are none)
        return $this->getSibling($current_question->question_id_parent, $question_id, '>');
    }
    /**
     * 
     * Returns the next available parent question
     * 
     * @param int $question_id    quesiton_id
     * @return \App\Models\Question
     */
    public function getNextParentQuestion($question_id)
    {
        /**
         * Our movement in this case is across, then up
         * Visiting siblings first, if no sibling move up to parent and repeat
         * Stop as soon as we find a sibling
         */

        $current_question = $this->getQuestion($question_id);

        // already on the root question, return null
        if ($current_question->question_id_parent == 0)
            return null;

        $parent_question = $this->getQuestion($current_question->question_id_parent);

        // walk up through our tree looking for each sibling and then each parent sibling until we find one
        while (!($sibling = $this->getSibling($parent_question->question_id, $current_question->question_id, '>'))) {

            // if question_id_parent is 0, we reached the root of the tree and still no luck - return null
            if ($parent_question->question_id_parent == 0)
                return null;

            // repeat sibling search with parent
            $current_question = $this->getQuestion($parent_question->question_id); // current question becomes parent question
            $parent_question = $this->getQuestion($parent_question->question_id_parent); // parent question becomes grandparent question
        }

        // if we made it here we found a sibling
        return $sibling;
    }
    /**
     * 
     * Returns the question answers of questions
     * 
     * @param array $question_ids    question ids
     * @return \App\Models\Answer
     */
    public function getQuestionAnswers($question_ids)
    {
        return Answer::where('resp_id', $this->respondent_id)
            ->whereIn('question_id', $question_ids)
            ->get();
    }
    /**
     * 
     * Returns the page questions
     * 
     * @param int $survey_id    survey id
     * @param int $question_id    question id
     * @param int $direction    direction
     * @return \App\Models\Page
     */
    public function getPageQuestions($survey_id)
    {
        return Page::join('tblquestion', 'tblquestion.page_id', 'tblpage.page_id')
            // ->where('tblpage.question_id_parent', $parent_id)
            ->where('tblpage.survey_id', $survey_id)
            // ->orderBy('question_id_parent')
            ->orderBy('question_seq')
            ->get();
    }
    /**
     * 
     * Returns the survey length
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Support\Facades\Cache
     */
    // probably want to setup some kind of memoization / caching here so we don't have to run the calculation on each request
    public function getSurveyLength($survey_id)
    {
        ini_set('max_execution_time', 0);

        $cache_key = 'survey_map_' . $survey_id;
        
        if (!Cache::has($cache_key)) {
            $questions = $this->getPageQuestions($survey_id);
            $stack = [];
            $results = [];

            $legal = $questions->where('question_id_parent', 0)->where('question_desc', 'Legal Services')->first(); // FIXME: Need to figure out a way to ensure Legal comes first, even if it's not exactly called 'Legal Services', maybe sort alphabeticaly
            $support = $questions->where('question_id_parent', 0)->where('question_desc', 'Support Activities')->first(); // FIXME: Need to ensure Support comes second, even if that's not what is always called
            $branches = [];

            
            // might not have legal
            if ($legal)
                $branches[] = $legal;

            // always have support
            $branches[] = $support;
            // dd($branches);
        
            foreach ($branches as $branch) {

                array_push($stack, $branch);
                
                while (count($stack)) {
                    // echo $key;
                    $parent = array_pop($stack);
                    // dd($stack,'test',$parent,$questions);
                   /*  echo "<pre>";
                    echo $parent->question_id."<br>"; */
                    
                    $children = $questions->where('question_id_parent', $parent->question_id);

                    $results[] = $parent->question_id;

                    foreach ($children->reverse() as $child) {
                        array_push($stack, $child);
                    }
                    // $key++;
                }
            }
            // exit;

            Cache::put($cache_key, $results);
        }

        return Cache::get($cache_key);
    }
    /**
     * 
     * Returns the page questions
     * 
     * @param int $survey_id    survey id
     * @param int $question_id    question id
     * @param int $direction    direction
     * @return \App\Models\Page
     */
    public function resetQuestions($survey_id, $question_id)
    {

        $child_questions = $this->getChildQuestions($question_id);

        Answer::where([
            ['resp_id', $this->respondent_id],
        ])
            ->whereIn('question_id', $child_questions->pluck('question_id'))
            ->delete();
    }
    /**
     * 
     * Returns the question data by respondent
     * 
     * @param int $survey_id    survey id
     * @param int $parent_id    parent question id
     * @return \App\Models\Question
     */
    public function getRespondentAnswers($survey_id, $parent_id = null)
    {

        $questions = Question::join('tblpage', 'tblpage.page_id', 'tblquestion.page_id')
            ->leftJoin('tblanswer', function ($tblanswer) {
                $tblanswer->on('tblanswer.question_id', 'tblquestion.question_id')->where('tblanswer.resp_id', $this->respondent_id);
             
            })
            ->select('tblquestion.*', 'tblpage.question_id_parent', 'tblanswer.answer_value')
            ->where('tblpage.survey_id', $survey_id)
            ->where('tblanswer.answer_value','!=',0);

        if (isset($parent_id))
            $questions->where('tblpage.question_id_parent', $parent_id);

        return $questions->get();
    }
    /**
     * 
     * Delete the legal answers 
     * 
     * @param \App\Models\Survey $survey    survey
     */
    public function deleteLegalAnswers($survey)
    {
        // find the legal root question
        $questions = $this->getPageQuestions($survey->survey_id);
        $legal = $questions->where('question_id_parent', 0)->where('question_desc', 'Legal Services')->first();

        // iterate through each of the remaining questions, delete the corresponding answers and add child questions to stack
        $remaining_questions = collect([$legal]);

        while (($next = $remaining_questions->pop())) {

            Answer::where([['question_id', $next->question_id], ['resp_id', $this->respondent_id]])
                ->delete();

            foreach ($this->getChildQuestions($next->question_id) as $child) {
                $remaining_questions->push($child);
            }
        }
    }
}
