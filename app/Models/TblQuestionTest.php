<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblQuestionTest extends Model
{
    use HasFactory;
    protected $table = 'tblquestionTest';

    protected $fillable = [
        'survey_id',
        'question_id_parent',
        'page_seq',
        'page_type',
        'page_desc',
        'page_extra',
        'page_id_original'
    ];

}
