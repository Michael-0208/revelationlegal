<?php

namespace App\Http\Controllers;

use App\Models\AllowedLocation;
use App\Models\Answer;
use App\Models\Department;
use App\Models\Survey;
use Illuminate\Support\Facades\Auth;

class CommonController extends Controller
{
    public static function get_survey_attributes($survey_id)
    {
        //return Answer::where('question_id', '=', 20753)->get();
        return Answer::whereIn('question_id', [20753, 20754])
            ->get();
        //  return Page::where('survey_id', '=', $survey_id)->get();

    }
    /**
     * 
     * Get the survey
     * 
     * @param int $survey_id    survey id
     * @return \App\Models\Survey
     */
    public static function get_survey($survey_id)
    {
        return Survey::where('survey_id', '=', $survey_id)->firstOrFail();
    }

    /**
     * Validate the user is logged in and has the required permissions to continue
     *
     * @param integer $survey_id
     * @param string $permission
     * @return void
     */
    public static function validateUser(int $survey_id, string $permission)
    {
        // if user is not logged in, redirect to login
        if (!Auth::user()) {
            return redirect('login');
        }

        $user = Auth::user();
        $survey = Survey::find($survey_id);
        //dd($survey);
        // if the user is not an admin check that they are assigned to this survey
        if (!$user->is_admin && $user->survey_id != $survey->survey_id) { // admin users should have a survey_id = 0
            //return abort(403);
        }

        /* if (!$user->hasPermission($permission, $survey)) {
            abort(403, 'Unauthorized Action!');
        } */
    }
    /**
     * 
     * Validate the user is allowed in the locations
     * 
     * @param array $locations    locations
     * @param int $survey_id    survey id
     * @return array
     */
    public static function validateUserLocations($locations, $survey_id)
    {
        if (Auth::user()->is_admin == 1) {
            return $locations;
        }
        $userLocations = AllowedLocation::where('user_id', '=', Auth::user()->id)
            ->where('survey_id', '=', $survey_id)
            ->get();
        $allowed_locations = array();
        foreach ($userLocations as $loc) {
            if (!array_key_exists($loc->name, $allowed_locations)) {
                $allowed_locations[$loc->name] = $loc->name;
            }
        }
        return $allowed_locations;
    }
    /**
     * 
     * Validate the user is allowed in the departments
     * 
     * @param array $departments    departments
     * @param int $survey_id    survey id
     * @return array
     */
    public static function validateUserDepartments($departments, $survey_id)
    {
        if (Auth::user()->is_admin == 1) {
            return $departments;
        }
        $userDepartments = Department::where('user_id', '=', Auth::user()->id)
            ->where('survey_id', '=', $survey_id)
            ->get();
        $allowed_departments = array();
        foreach ($userDepartments as $dept) {
            if (!array_key_exists($dept->name, $allowed_departments)) {
                $allowed_departments[$dept->name] = $dept->name;
            }
        }
        return $allowed_departments;
    }
}
