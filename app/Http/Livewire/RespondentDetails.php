<?php

namespace App\Http\Livewire;

use Livewire\Component;

class RespondentDetails extends Component
{
    public function render()
    {
        return view('livewire.respondent-details') ->layout('layouts.reports');
    }
}
