<?php

namespace App\RevelationLegal\QuestionIterator;


class QuestionLevelOrderIterator implements \Iterator
{

    private $question_collection = null;
    private $current_question = null;
    private $next_questions = [];
    private $position = 0;

    public function __construct(SurveyQuestionCollection $question_collection)
    {
        $this->question_collection = $question_collection;
    }

    public function current()
    {
        return $this->current_question;
    }

    public function next()
    {
        $this->current_question = array_shift($this->next_questions);

        $this->addChildQuestions($this->current_question->question_id);

        $this->position++;
    }

    public function key()
    {
        return $this->position;
    }

    /**
     * Reset iterator
     *
     * @return void
     */
    public function rewind()
    {
        // reset position to 0
        $this->position = 0;

        // queue the root questions (survey branches)
        $this->next_questions = $this->question_collection->getChildQuestions(0);

        // load the last branch into the current question
        $this->current_question = array_shift($this->next_questions);

        // queue the last branch / current question children for iteration
        $this->addChildQuestions($this->current_question->question_id);
    }

    public function valid()
    {
        return !is_null($this->current_question) && count($this->next_questions);
    }

    private function addChildQuestions($parent_id)
    {
        foreach ($this->question_collection->getChildQuestions($parent_id) as $child) {
            $this->next_questions[] = $child;
        }
    }
}
