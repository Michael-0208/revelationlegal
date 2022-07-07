<?php

namespace App\Gateways;

use App\Models\AllowedLocation;
use App\Models\Department;
use App\Models\Respondent;

class PermissionData {
    /**
     * 
     * Returns the allowed locations of the user
     * 
     * @param int $survey_id    survey id
     * @param \App\Models\User $user
     * @return array 
     */
    public function getAllowedLocationsByUser ($survey_id, $user) {
        $res = array ();
        if ($user->isAdmin()) {
            $query = Respondent::where('survey_id', $survey_id)
                ->select('cust_6')
                ->groupBy('cust_6')
                ->get();
            foreach ($query as $row) {
                array_push($res, $row->cust_6);
            }
        } else {
            $query = AllowedLocation::where('survey_id', $survey_id)
                ->where('user_id', $user->id)
                ->select('name')
                ->get();

            foreach ($query as $row) {
                array_push($res, $row->name);
            }
        }

        return $res;
    }
    /**
     * 
     * Returns the allowed departments of the user
     * 
     * @param int $survey_id    survey id
     * @param \App\Models\User $user
     * @return array 
     */
    public function getAllowedDepartmentsByUser ($survey_id, $user) {
        $res = array ();

        if ($user->isAdmin()) {
            $query = Respondent::where('survey_id', $survey_id)
                ->select('cust_4')
                ->groupBy('cust_4')
                ->get();

            foreach ($query as $row) {
                array_push($res, $row->cust_4);
            }
        } else {
            $query = Department::where('survey_id', $survey_id)
                ->where('user_id', $user->id)
                ->select('name')
                ->get();

            foreach ($query as $row) {
                array_push($res, $row->name);
            }
        }

        return $res;
    }
}