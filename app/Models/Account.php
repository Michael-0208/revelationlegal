<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'account_survey';
    protected $fillable = ['account_id', 'survey_id'];

    protected function user()
    {
        return $this->belongsTo([App\Models\User::class], 'account_id');
    }

    protected function survey()
    {
        return $this->belongsTo([App\Models\Survey::class], 'survey_id');
    }
}
