<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowedLocation extends Model
{
    use HasFactory;
    protected $table = 'allowed_locations';
    protected $fillable = ['user_id', 'name', 'survey_id'];
}
