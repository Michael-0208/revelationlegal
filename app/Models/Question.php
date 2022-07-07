<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $table = 'tblquestion';
    protected $primaryKey = 'question_id';
    protected $fillable = [
        'question_id',
        'question_sortorder_id',
        'question_enabled',
        'question_code',
        'question_desc',
        'question_desc_alt',
        'question_extra',
        'question_extra_alt',
        'question_proximity_factor',
        'page_id',
        'lead_codes',
        'survey_id',
        'question_seq',
    ];
    public $timestamps = false;

    public function answers()
    {
        // return $this->hasOne(Answer::class);
        return $this->hasMany(Answer::class);
    }

    public function scopeRespondentAnswers($query, $respondent_id)
    {
        return $query->where('resp_id', $respondent_id);
    }

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id', 'page_id');
    }
}
