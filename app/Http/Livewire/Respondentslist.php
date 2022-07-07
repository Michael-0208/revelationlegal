<?php

namespace App\Http\Livewire;

use App\Models\Respondent;
use App\Models\User;
use Livewire\Component;

class Respondentslist extends Component
{
    public function mount($survey_id)
    {
        $this->survey_id = $survey_id;
    }
    public function render()
    {
        $resps = Respondent::where('survey_id', '=', $this->survey_id)
            ->where('survey_completed', '=', 1)
            ->orderBy('resp_last', 'asc')
            ->get();
        return view('livewire.respondentslist', compact('resps'));
    }
}
