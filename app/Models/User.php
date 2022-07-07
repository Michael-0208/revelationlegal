<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'user_id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'user_id');
    }

    public function locations()
    {
        return $this->hasMany(AllowedLocation::class, 'user_id');
    }

    public function surveys()
    {
        return $this->hasManyThrough(Survey::class, Account::class, 'account_id', 'survey_id', 'id', 'survey_id');
    }

    public function accountSurveys()
    {
        return $this->hasMany(Account::class, 'account_id');
    }

    public function isAdmin()
    {
        return $this->is_admin === 1;
    }

    public function hasPermission($permission, $survey)
    {
        return $this->isAdmin()
            || $this->permissions->where('name', $permission)->where('value', $survey->survey_id)->count();
    }

    public function hasDepartments($departments = [], $survey)
    {
        return $this->isAdmin()
            || $this->departments->whereIn('name', $departments)->where('value', $survey->survey_id)->count() > 0;
    }

    public function hasLocations($locations = [], $survey)
    {
        return $this->isAdmin()
            || $this->locations->whereIn('name', $locations)->where('value', $survey->survey_id)->count() > 0;
    }

    public function getAllowedProjectsAttribute()
    {
        //dd($this->surveys);
        return $this->surveys->map(function ($survey) {
            return $survey->survey_name;
        })->join('<br>');
    }
}
