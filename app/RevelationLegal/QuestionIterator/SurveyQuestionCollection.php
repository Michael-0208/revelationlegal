<?php

namespace App\RevelationLegal\QuestionIterator;

class SurveyQuestionCollection implements \IteratorAggregate
{

    private $questions = [];

    public function getIterator()
    {
        $this->sortByParent();
        return new QuestionLevelOrderIterator($this);
    }

    public function sortByParent()
    {
        usort($this->questions, function ($q1, $q2) {
            return $q1->question_id_parent - $q2->question_id_parent;
        });
    }

    public function getQuestionByIndex($index)
    {
        return $this->questions[$index];
    }

    public function getQuestionIndex($question_id)
    {
        $question_ids = array_column($this->questions, 'question_id');
        return array_search($question_id, $question_ids);
    }

    public function getQuestionById($question_id)
    {
        $index = $this->getQuestionIndex($question_id);

        if ($index !== false)
            return $this->questions[$index];

        return null;
    }

    public function getChildQuestions($parent_id)
    {
        return array_filter($this->questions, function ($question) use ($parent_id) {
            return $question->question_id_parent == $parent_id;
        });
    }

    public function addQuestion($question)
    {
        $this->questions[] = $question;
    }

    public function count()
    {
        return count($this->questions);
    }
}
