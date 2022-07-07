<?php

namespace App\Http\Controllers;

use App\Gateways\InvitationData;
use App\Models\Invitation;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvitationsController extends Controller
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;
    /**
     * @var \App\Gateways\InvitationData
     */
    protected $invitationData;

    public function __construct(Request $request, InvitationData $invitationData)
    {
        $this->request = $request;
        $this->invitationData = $invitationData;
    }
    /**
     * 
     * Invitation Settings
     * 
     * @param int $survey_id    survey id
     * @param string $status
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function invitations_settings ($survey_id, $status = '') {
        CommonController::validateUser($survey_id, 'surveyInvitations');

        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $survey =(object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']];
        $data['survey'] = $survey;

        $data['settings'] = $this->invitationData->getInvitationSetting($survey_id);
        if (count($data['settings']) > 0) {
            $data['settings'] = $data['settings'][0];
        } else {
            $data['settings'] = 404;
        }

        if ($status == 'updated') {
            $data['updated'] = 1;
        }

        if ($status == 'inserted') {
            $data['inserted'] = 1;
        }

        return view('invitations.settings', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Update the row of invitation settings
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function update_invitationsettings (Request $request) {
        $updateData = $this->request->all();
       // dd($request->file());
        /* $request->validate([
            'file' => 'mimes:pdf,xlx,csv,docx|max:2048',
        ]); */
        $files = [];
        if($request->file()){
            foreach($request->file() as $file)
            {
                $name = time().rand(1,100).'.'.$file->extension();
                //dd($name);
                $file->move(public_path('instructions'), $name);  
                $files[] = $name;  
            }
        }

        if(count($files) > 0){
            $updateData['instructions_pdf_link_1'] = (isset($files[0]))?$files[0]:'';
            $updateData['instructions_pdf_link_2'] = (isset($files[1]))?$files[1]:'';
        }else{
            $updateData['instructions_pdf_link_1'] = '';
            $updateData['instructions_pdf_link_2'] = '';
        }
       
        //dd($updateData);
        /* dd($request->file->extension()[0]);
        $fileName2 = time().'.'.$updateData['instructions_pdf_link_2']; 
        $fileName1 = time().'.'.$updateData['instructions_pdf_link'*/
        

        //dd($fileName);
        CommonController::validateUser($updateData['survey_id'], 'surveyInvitations');

        $data = array (
            'sender' => $updateData['sender'],
            'complete_by_date' => $updateData['complete_by_date'],
            'managing_partner_name' => $updateData['managing_partner_name'],
            'firm_domain_name' => $updateData['firm_domain_name'],
            'questions_contact_name' => $updateData['questions_contact_name'],
            'instructions_pdf_link' => $updateData['instructions_pdf_link_1'],
            'instructions_pdf_link_2' => $updateData['instructions_pdf_link_2'],
            'invitation_letter_template' => $updateData['invitation_letter_template'],
            'email_subject' => $updateData['email_subject'],
        );

        $existOne = Invitation::where('survey_id', $updateData['survey_id'])->get();
        if (count($existOne) > 0) {
            $updatedRow = $this->invitationData->updateInvitationSettings($updateData['survey_id'], $data);
    
            if ($updatedRow) {
                return $this->invitations_settings($updateData['survey_id'], 'updated');
            }
        } else {
            $insertedRow = $this->invitationData->insertInvitationSettings($updateData);

            if ($insertedRow) {
                return $this->invitations_settings($updateData['survey_id'], 'inserted');
            }
        }

    }
    /**
     * 
     * Invitation Send Index
     * 
     * @param int $survey_id    survey id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function invitations_send ($survey_id) {
        CommonController::validateUser($survey_id, 'surveyInvitations');

        $survey_data = Survey::where('survey_id', $survey_id)->get()->toArray();
        $survey =(object)[
            'survey_id' => $survey_id,
            'survey_name' => $survey_data[0]['survey_name']];
        $data['survey'] = $survey;

        $respData = $this->invitationData->getParticipants($survey_id, 0);
        $data['resp_num'] = count($respData);

        return view('invitations.send', ['data' => $data, 'survey' => Survey::where('survey_id', $survey_id)->firstOrFail()]);
    }
    /**
     * 
     * Get the number of participants
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_participants_num () {
        $survey_id = $this->request->input('survey_id');
        $receiverType = (int) $this->request->input('receiverType');

        $respData = $this->invitationData->getParticipants($survey_id, $receiverType);
        $data['resp_num'] = count($respData);
        
        foreach($respData as $respEmail){
            $data['resp_emails'][] = $respEmail->resp_email;
        }
        /* echo "<pre>";
        print_r($data['resp_emails']);
        exit; */
        return json_encode($data);
    }
    /**
     * 
     * Send emails
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send_emails () {
        $survey_id = $this->request->input('survey_id');
        $receiverType = (int) $this->request->input('receiverType');
        $receiverList = preg_replace('/\s+/', '', $this->request->input('receiverList'));

        $invitation_settings = Invitation::where('survey_id', $survey_id)->firstOrFail();

        $data = array ();
        $data['status'] = 200;
        $mails = array ();

        if ($receiverType == 4) {
            if ($receiverList == '') {
                $data['status'] == 400;
            } else {
                $emailAry = explode(',', $receiverList);
                foreach ($emailAry as $row) {
                    if (filter_var($row, FILTER_VALIDATE_EMAIL)) {
                        Mail::send([], [], function ($message) use ($row, $invitation_settings) {
                            if ($invitation_settings['sender'] != '') {
                                $message->from('support@revelationlegal.com', $invitation_settings['sender']);
                            }
                            $message->to($row);
                            if ($invitation_settings['email_subject'] != '') {
                                $message->subject($invitation_settings['email_subject']);
                            }
                            if ($invitation_settings['invitation_letter_template'] != '') {
                                $body = str_replace("[sender]", $invitation_settings['sender'], $invitation_settings['invitation_letter_template']);
                                $body = str_replace("[date]", $invitation_settings['complete_by_date'], $body);
                                $body = str_replace("[managing_partner]", $invitation_settings['managing_partner_name'], $body);
                                $body = str_replace("[firm]", $invitation_settings['firm_domain_name'], $body);
                                $body = str_replace("[questions_contact]", $invitation_settings['questions_contact_name'], $body);
                                $body = str_replace("[attachment_link_1]", $invitation_settings['instructions_pdf_link'], $body);
                                $body = str_replace("[attachment_link_2]", $invitation_settings['instructions_pdf_link_2'], $body);
                                $message->setBody($body, 'text/html');
                            }
                            if ($invitation_settings['instructions_pdf_link'] != '') {
                                $message->attach($invitation_settings['instructions_pdf_link']);
                            }
                            if ($invitation_settings['instructions_pdf_link_2'] != '') {
                                $message->attach($invitation_settings['instructions_pdf_link_2']);
                            }
                        });
                    }
                }
            }
        } else if ($receiverType == 5) {
            $test_mail = 'support@revelationlegal.com';
            //dd($test_mail);
            Mail::send([], [], function ($message) use ($test_mail, $invitation_settings) {
                if ($invitation_settings['sender'] != '') {
                    $message->from('support@revelationlegal.com', $invitation_settings['sender']);
                }
                $message->to($test_mail);
                if ($invitation_settings['email_subject'] != '') {
                    $message->subject($invitation_settings['email_subject']);
                }
                if ($invitation_settings['invitation_letter_template'] != '') {
                    $body = str_replace("[sender]", $invitation_settings['sender'], $invitation_settings['invitation_letter_template']);
                    $body = str_replace("[date]", $invitation_settings['complete_by_date'], $body);
                    $body = str_replace("[managing_partner]", $invitation_settings['managing_partner_name'], $body);
                    $body = str_replace("[firm]", $invitation_settings['firm_domain_name'], $body);
                    $body = str_replace("[questions_contact]", $invitation_settings['questions_contact_name'], $body);
                    $body = str_replace("[attachment_link_1]", $invitation_settings['instructions_pdf_link'], $body);
                    $body = str_replace("[attachment_link_2]", $invitation_settings['instructions_pdf_link_2'], $body);
                    $message->setBody($body, 'text/html');
                }
                if ($invitation_settings['instructions_pdf_link'] != '') {
                    $message->attach($invitation_settings['instructions_pdf_link']);
                }
                if ($invitation_settings['instructions_pdf_link_2'] != '') {
                    $message->attach($invitation_settings['instructions_pdf_link_2']);
                }
            });
        } else {
            $respData = $this->invitationData->getParticipants($survey_id, $receiverType);
        }

        if ($data['status'] == 200 && $receiverType != 5 && $receiverType != 4) {
            foreach ($respData as $resp) {
                Mail::send([], [], function ($message) use ($resp, $invitation_settings) {
                    if ($invitation_settings['sender'] != '') {
                        $message->from('no-reply@revelationlegal.com', $invitation_settings['sender']);
                    }
                    $message->to($resp->resp_email, $resp->resp_last . ', ' . $resp->resp_first);
                    if ($invitation_settings['email_subject'] != '') {
                        $message->subject($invitation_settings['email_subject']);
                    }
                    if ($invitation_settings['invitation_letter_template'] != '') {
                        $body = str_replace("[sender]", $invitation_settings['sender'], $invitation_settings['invitation_letter_template']);
                        $body = str_replace("[date]", $invitation_settings['complete_by_date'], $body);
                        $body = str_replace("[managing_partner]", $invitation_settings['managing_partner_name'], $body);
                        $body = str_replace("[firm]", $invitation_settings['firm_domain_name'], $body);

                        $surveyLink = route('survey.start', ['sv' => 'NTA', 'ac' => $resp->resp_access_code]);
                        $body = str_replace("[link]", $surveyLink, $body);

                        $body = str_replace("[questions_contact]", $invitation_settings['questions_contact_name'], $body);
                        $body = str_replace("[attachment_link_1]", $invitation_settings['instructions_pdf_link'], $body);
                        $body = str_replace("[attachment_link_2]", $invitation_settings['instructions_pdf_link_2'], $body);
                        $message->setBody($body, 'text/html');
                    }
                    if ($invitation_settings['instructions_pdf_link'] != '') {
                        $message->attach($invitation_settings['instructions_pdf_link']);
                    }
                    if ($invitation_settings['instructions_pdf_link_2'] != '') {
                        $message->attach($invitation_settings['instructions_pdf_link_2']);
                    }
                });
            }
        }
        
        return json_encode($data);
        //response()->json(['data'=>$data]);
    }
}
