<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respondent extends Model
{
    use HasFactory;
    protected $table = 'tblrespondent';
    
    protected $primaryKey = 'resp_id';

    public function answer()
    {
        return $this->hasMany(Answer::class, 'resp_id');
    }
}
/*  resp_id                 | int              | NO   | PRI | NULL    | auto_increment |
| survey_id               | int              | NO   |     | NULL    |                |
| resp_access_code        | varchar(20)      | NO   |     | NULL    |                |
| resp_email              | varchar(80)      | YES  |     | NULL    |                |
| resp_first              | varchar(80)      | NO   |     | NULL    |                |
| resp_last               | varchar(80)      | NO   |     | NULL    |                |
| resp_alt                | tinyint(1)       | NO   |     | 0       |                |
| cust_1                  | varchar(255)     | YES  |     | NULL    |                |
| cust_2                  | varchar(255)     | YES  |     | NULL    |                |
| cust_3                  | varchar(255)     | YES  |     | NULL    |                |
| cust_4                  | varchar(255)     | YES  |     | NULL    |                |
| cust_5                  | varchar(255)     | YES  |     | NULL    |                |
| cust_6                  | varchar(255)     | YES  |     | NULL    |                |
| cust_7                  | varchar(255)     | YES  |     | NULL    |                |
| cust_8                  | varchar(255)     | YES  |     | NULL    |                |
| cust_9                  | varchar(255)     | YES  |     | NULL    |                |
| cust_10                 | varchar(255)     | YES  |     | NULL    |                |
| cust_11                 | varchar(255)     | YES  |     | NULL    |                |
| cust_12                 | varchar(255)     | YES  |     | NULL    |                |
| cust_13                 | varchar(255)     | YES  |     | NULL    |                |
| cust_14                 | varchar(255)     | YES  |     | NULL    |                |
| cust_15                 | varchar(255)     | YES  |     | NULL    |                |
| cust_16                 | varchar(255)     | YES  |     | NULL    |                |
| cust_17                 | varchar(255)     | YES  |     | NULL    |                |
| rentable_square_feet    | int              | YES  |     | NULL    |                |
| resp_compensation       | float(10,2)      | NO   |     | 0.00    |                |
| resp_bonus              | float(10,2)      | YES  |     | 0.00    |                |
| resp_benefit_pct        | int              | YES  |     | NULL    |                |
| resp_total_compensation | float(10,2)      | NO   |     | 0.00    |                |
| start_dt                | datetime         | YES  |     | NULL    |                |
| last_dt                 | datetime         | YES  |     | NULL    |                |
| last_page_id            | int              | YES  |     | NULL    |                |
| survey_completed        | tinyint(1)       | NO   |     | 0       |                |
| invitation_sent         | tinyint unsigned | NO   |     | 0       |                |
| survey_reviewed         | int              | NO   |     | 0       |                |
| last_invitation_sent    | datetime         | YES  |     | NULL    |                |
| reportLocation1         | varchar(200)     | YES  |     | NULL    |                |
| reportLocation2         | varchar(200)     | YES  |     | NULL    |                |
| created_at              | timestamp        | YES  |     | NULL    |                |
| updated_at              | timestamp        | YES  |     | NULL    |                |
| deleted_at */