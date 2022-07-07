<?php

namespace App\Http\Livewire;

use App\Models\Respondent;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowsurveyIndex extends Component
{
    public $user;
    public $survey;
    public $respondents;
    public function mount($survey_id)
    {
        $this->user=User::where('id','=', Auth::user()->id)
            ->with('permissions')
            ->firstOrFail();
        $this->respondents=Respondent::where('survey_id', '=', $survey_id)

            ->get();
        $this->survey = Survey::where('survey_id','=', $survey_id)->firstOrFail();
        $this->survey_id = $survey_id;
    }
    public function render()
    {
        $user=$this->user;
        $survey=$this->survey;
        $respondents = $this->respondents;
        $authorized = false;
        foreach ($user->permissions as $permission){
            if ($permission->value == $this->survey_id){
                $authorized = true;
            }
        }
        if(!$authorized && !$user->is_admin){
            return abort(403, 'You are not authorized to view this page.');
        }
        $total_resp = 0;
        $completed_resp = 0;
        $sent = 0;
        foreach ($respondents as $respondent){
            if ($respondent->last_dt){
                $total_resp++;
            }
            if($respondent->survey_completed == 1){
                $completed_resp++;
            }

                $sent++;

        }
        $percent_total = round(($total_resp /$sent) * 100);
        $percent_completed =  round(($completed_resp /$total_resp) * 100);
        $survey_active = 'Active';
        if ($survey->is_active == 0){
            // $survey_active="Inactive";
        }
        $tmp=[];
        foreach ($user->permissions as $permission) {
            $tmp[]= $permission->name;
        }
        print_r($survey);exit();
        return view('livewire.showsurvey-index', compact('sent','percent_total','percent_completed', 'survey_active','total_resp','completed_resp','survey' ));
    }
}
