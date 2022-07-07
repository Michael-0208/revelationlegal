<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;
   // public $timestamps = false;

    protected $table = 'tblinvitations';

    protected $primaryKey = 'survey_id';
}
