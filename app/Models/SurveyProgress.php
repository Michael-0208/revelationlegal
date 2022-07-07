<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyProgress extends Model
{
    use HasFactory;
    protected $table = 'tblsurveyprogress';

    protected $primaryKey = 'id';
}
