<?php

namespace App\Gateways;

use App\Models\Invitation;
use App\Models\Respondent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvitationData {
    /**
     * 
     * Returns the data of invitation settings
     * 
     * @param int $survey_id    survey id
     * @return \App\Models\Invitation 
     */
    public function getInvitationSetting ($survey_id) {
        $data = Invitation::where('survey_id', $survey_id)
            ->select(
                '*',
                DB::raw("DATE_FORMAT(complete_by_date, '%Y-%m-%d') AS custom_date")
            )->get();

        return $data;
    }
    /**
     * 
     * Update the row of invitation settings
     * 
     * @param int $survey_id    survey id
     * @param array $updateData  invitation settings form data
     * @return \App\Models\Invitation 
     */
    public function updateInvitationSettings ($survey_id, $updateData) {
        $row = Invitation::where('survey_id', $survey_id)
            ->update($updateData);

        return $row;
    }
    /**
     * 
     * Insert a new row to the invitation settings
     * 
     * @param array $data    invitation settings form data
     * @return \App\Models\Invitation 
     */
    public function insertInvitationSettings ($data) {
        $row = new Invitation();
        $row->survey_id = $data['survey_id'];
        $row->sender = $data['sender'];
        $row->complete_by_date = $data['complete_by_date'];
        $row->managing_partner_name = $data['managing_partner_name'];
        $row->firm_domain_name = $data['firm_domain_name'];
        $row->questions_contact_name = $data['questions_contact_name'];
        $row->instructions_pdf_link = $data['instructions_pdf_link'];
        $row->instructions_pdf_link_2 = $data['instructions_pdf_link_2'];
        $row->invitation_letter_template = $data['invitation_letter_template'];
        $row->email_subject = $data['email_subject'];
        $row->save();

        return $row;
    }
    /**
     * 
     * Returns the participants fit the cases
     * 
     * @param int $survey_id    survey id
     * @param int $type    participants types classified with the participate status
     * @return array 
     */
    public function getParticipants ($survey_id, $type) {
        $permissionData = new PermissionData();
        $allowed_locationAry = $permissionData->getAllowedLocationsByUser($survey_id, Auth::user());
        $allowed_deptAry = $permissionData->getAllowedDepartmentsByUser($survey_id, Auth::user());

        $all_resps = Respondent::where('survey_id', $survey_id)
            ->whereIn('cust_4', $allowed_deptAry)
            ->whereIn('cust_6', $allowed_locationAry)
            ->get();

        $res = array ();

        foreach ($all_resps as $resp) {
            switch ($type) {
                case 0: // All participants
                    $res[] = $resp;
                    break;
                
                case 1: // New participants
                    if ($resp->invitation_sent == 0) {
                        $res[] = $resp;
                    }
                    break;
                
                case 2: // Non-responders
                    if (!$resp->last_dt) {
                        $res[] = $resp;
                    }
                    break;
                
                case 3: // Partial completed participants
                    if ($resp->last_dt != '' && $resp->survey_completed == 0) {
                        $res[] = $resp;
                    }
                    break;
                
                default:
                    $res[] = $resp;
                    break;
            }
        }

        return $res;
    }
}