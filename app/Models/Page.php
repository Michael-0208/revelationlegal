<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    protected $table='tblpage';
    protected $primaryKey = 'page_id';
    protected $fillable = [
        'page_id',
        'survey_id',
        'question_id_parent',
        'page_seq',
        'page_type',
        'page_desc',
        'page_extra',
        'page_id_original',
    ];
    
    public $timestamps = false;
}
