<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = 'tblanswer';
    protected $fillable = ['answer_value', 'question_id', 'resp_id'];

    use HasFactory;

    public function respondent()
    {
        return $this->belongsTo(Respondent::class, 'resp_id', 'resp_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function scopeQuestionsBySurvey($query, $survey_id)
    {
        return $query->where('survey_id', $survey_id);
    }
}
