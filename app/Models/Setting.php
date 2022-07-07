<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'tblsettings';
    protected $primaryKey = 'survey_id';
    
    protected $fillable = ['contact_email','contact_phone','show_splash_page','splash_page','logo_splash','logo_survey','cobrand_logo','begin_page','footer','weekly_hours_text','legal_yn_text','annual_legal_hours_text','location_dist_text','end_page','copyright','survey_id','show_summary'];
}
