<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="data()">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RevelationLegal - Survey') }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
<link rel="icon" type="image/png" href="{{ asset('imgs/favicon.png') }}">  
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <script src="{{ asset('js/jquery-3.6.0.js') }}"></script>
    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">


    <!-- Styles -->

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/additional-styling.css') }}">
    <script src="{{ asset('js/jquery-3.2.1.slim.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>



    @livewireStyles

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/jquery.canvasjs.min.js')}}"></script>
    <script src="{{ asset('js/init-alpine.js') }}"></script>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
</head>

<body class="surveyurl-body font-sans antialiased">
    <header class="surveyurl-head container-fluid px-0 px-md-5 @if(!(isset($survey) && isset($settings))) bg-black @endif">
        <div class="header-inner mx-auto">
        <div id="google_translate_element"></div>

    <script type="text/javascript">
            function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}


</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>  
        @if(isset($survey) && isset($settings))
            <div class="row">
                <div class="col-md-3 my-auto">
                    <img src="{{ $settings->logo_survey }}" style="height: 100px;"/>  
                </div>
                <div class="col-md-9 text-right my-auto">
           <a class="revnor-btn d-inline-block mb-2" href="{{route('user-guide')}}">User guide</a>
                    <h4 class="mt-2" style="color: #000; font-size:16px; font-weight: 900;">{{ $survey->survey_name }}</h4>
                </div>
                <div class="col-md-12 text-right my-auto">
                    <h4  style="color: #000; font-size:16px; font-weight: 900;">{{ session()->get('respondent')->resp_first }} {{session()->get('respondent')->resp_last}}</h4>
                </div>


                {{-- 
                <div class="col-md-3 text-right my-auto">
                    @if($settings->cobrand_logo && \App\Gateways\SettingsData::checkFileExistWithPath($settings->cobrand_logo))
                        <img src="{{ $settings->cobrand_logo }}" class="mx-0 float-right" style="width: 200px;"/>
                    @else
                        <a class="text-ns" href="mailto:{{ $settings->contact_email }}"><i class="fas fa-envelope" style="margin-right:4px;"></i>{{ $settings->contact_email }}</a>
                    @endif
                </div>
            --}} 


            </div>
            @else
                <div class="row py-2">
                    <div class="col-6">
                        <img class="w-44 mr-auto" src="{{asset('public/imgs/logo-new-small-rev.png')}}"> 
                    </div>
                    <div class="col-6 text-right my-auto text-white">
                        <a href="mailto:support@revelationlegal.com" class="text-color"><span class="fa fa-envelope mr-1"></span><strong>support@revelationlegal.com</strong></a>
                    </div>
                </div>
            @endif
        </div>
    </header>
    <main class="overflow-auto surveyurl-maincont" >  
        @yield('content')
    </main>
    <footer class="surveyurl-footer">
        <div class="container my-3">
            <div class="row">
                <div class="col text-center">

                        @if(isset($settings))
                            {!! $settings->footer !!}  
                        @else
                        <div>For survey instructions please refer to the <a href="{{ route('guides.participants') }}">Participant Guide</a></div>
                        <div>Support: <a href="mailto:support@revelationlegal.com" class="text-color">support@revelationlegal.com</a></div>
                        <div>Copyright {{ Carbon\Carbon::now()->year }} ofPartner LLC, All Rights Reserved.</div>
                    @endif
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
