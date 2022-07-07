<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportLocation extends Model
{
    use HasFactory;
    protected $table = 'tblsupportlocation';

    protected $primaryKey = 'support_location_id';
}
