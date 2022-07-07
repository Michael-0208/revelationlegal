<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;
    protected $table = 'tblsurvey';

    protected $primaryKey = 'survey_id';

    public function creator()
    {
        return $this->belongsTo(User::class, 'account_id');
    }

    public function accounts() {
        return $this->hasMany(Account::class, 'survey_id');
    }

    public function allowedLocations () {
        return $this->hasMany(AllowedLocation::class, 'survey_id');
    }

    public function departments () {
        return $this->hasMany(Department::class, 'survey_id');
    }

    public function permissions () {
        return $this->hasMany(Permission::class, 'value', 'survey_id');
    }

    public function invitations () {
        return $this->hasMany(Invitation::class, 'survey_id');
    }

    public function locations () {
        return $this->hasMany(Location::class, 'survey_id');
    }

    public function questions () {
        return $this->hasMany(Question::class, 'survey_id');
    }

    public function respondents () {
        return $this->hasMany(Respondent::class, 'survey_id');
    }

    public function setting () {
        return $this->hasOne(Setting::class, 'survey_id');
    }

    public function supportLocations () {
        return $this->hasMany(SupportLocation::class, 'survey_id');
    }
}
