<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespondentLocation extends Model
{
    use HasFactory;
    protected $table = 'tblrespondentlocation';

    protected $primaryKey = 'id';

    protected $fillable = ['resp_id', 'support_location_id', 'resp_pct'];
}
