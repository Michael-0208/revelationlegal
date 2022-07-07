<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpManager extends Model
{
    use HasFactory;
    protected $table = 'tblManageHelp';

    protected $fillable = ['page_name','help_content'];
}
