<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\Respondent;
use App\Models\Permission;

class UserSurvey extends Component
{
    public function render()
    {
        if(!Auth::user()){
            return view('auth.login');
        }
        if(Auth::user()->is_admin){
            $surveys=Survey::all();
        }else {
            $permissions = Permission::where('user_id', '=', Auth::user()->id)->get();
            $tmp = [];
            // dd($permissions);
            foreach ($permissions as $permission) {
                $tmp[] = $permission->value;
            }
            $surveys = Survey::whereIn('survey_id',$tmp)->where('survey_active', 1)->get();
        }

        return view('livewire.user-survey',compact('surveys'));
    }
}
