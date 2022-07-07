@extends('layouts.user_survey')

@section('content')

    <style>
        .overlay {
            position: fixed;
            display: none;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0, 0.9);
            transition: 0.5s;
            z-index: 999;
            cursor: pointer;
            color: #818181;
        } 
    </style>

    <div id="saving-spinner" class="overlay">
        <div class="container-fluid h-100">
            <div class="row h-100">
                <div class="col-12 my-auto">
                    <div class="row">
                        <div class="col text-center">
                            <span class="fa fa-4x fa-spin fa-spinner"></span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col text-center">
                            <h3>Saving your answers...</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="loading-spinner" class="overlay">
        <div class="container-fluid h-100">
            <div class="row h-100">
                <div class="col-12 my-auto">
                    <div class="row">
                        <div class="col text-center">
                            <span class="fa fa-4x fa-spin fa-sync"></span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col text-center">
                            <h3>Retrieving questions...</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="reseting-spinner" class="overlay">
        <div class="container-fluid h-100">
            <div class="row h-100">
                <div class="col-12 my-auto">
                    <div class="row">
                        <div class="col text-center">
                            <span class="fa fa-4x fa-circle-notch fa-spin"></span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col text-center">
                            <h3>Reseting Answers...</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="app"></div>
    <script>
        const survey_id = @JSON(session('survey')->survey_id);
        const survey_name = @JSON(session('survey')->survey_name);
        const respondent_id = @JSON(session('respondent')->resp_id);
        const survey_start_dt = @JSON(session('respondent')->start_dt);
        const survey_last_dt = @JSON(session('respondent')->last_dt);
        const survey_completed = @JSON(session('respondent')->survey_completed);
        const respondent = @JSON(session('respondent'));
        const survey_hash = '{{ session('survey_hash') }}';
        const code_hash = '{{ session('code_hash') }}';
        const survey_settings = @JSON(App\Models\Setting::where('survey_id', session('survey')->survey_id)->first());
        const survey_progress = @JSON(App\Models\SurveyProgress::where('survey_id', session('survey')->survey_id)->where('resp_id', session('respondent')->resp_id)->first());

        function showLoader(type) {
            hideLoader()
                .then(function(){
                    if (type == 'loading')
                        $('#loading-spinner').show();
                    else if (type == 'reseting')
                        $('#reseting-spinner').show();
                    else
                        $('#saving-spinner').show();
                });
        }

        function hideLoader() {
            return new Promise(function(resolve){
                $('.overlay').fadeOut('fast', function(){
                    resolve();
                });
            })
        }
        $(document).ready(function(){
            // var f =  / $('.posurveslider').parent().width() * 100;
            
        })

    </script>
    <script src="{{ asset('js/user_survey.js') }}"></script>
@endsection
