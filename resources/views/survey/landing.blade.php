@extends('layouts.user_survey')

@section('content')
    <div class="container py-5 my-5">
        <div class="row">
            <div class="col text-center">
                <h1 class="">{{ $survey->survey_name }}</h1> 
            </div>
        </div>
        <div class="row mt-2">
            <div class="col text-center">
                <h4 class="">
                    {!! App\Models\Setting::where('survey_id', $survey->survey_id)->first()->splash_page !!}
                </h4>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col text-center">
            <a href="{{ route('survey.questionnaire') }}" class="text-decoration-none revnor-btn" style="font-size: 18px;">Continue</a>
            </div>
        </div>
    </div>
    <script>
        localStorage.clear();
    </script>
@endsection
