<html lang="en">

<head>
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/telwind.output.css')}}"> -->
    <link rel="stylesheet" href="{{ asset('css/additional-styling.css') }}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
</head>

<body> 

    <div class="front-banner ">
        <div class="banner-inner flex justify-between items-center mx-auto">
            <div>
                <h3 class="font-black dark:text-white font-bold text-2xl">Welcome {{request()->user()->first_name}}. <br>
                    <h4 class="text-black dark:text-white font-bold text-lg"> Please select a project to view your results and analysis.</h4>
                </h3>
            </div>
            @if (\Auth::user()->is_admin)
            <a class="manage-pbtn" href="{{ route('projects.index') }}">Manage Projects</a>
            @endif
        </div>
    </div>
    
    <div class="projects-mout flex flex-wrap">
        <div class="frontp-projects">
            @foreach($surveys as $survey)
            <div class="project_names">
                
                @if($survey->survey_active == 0) 
                    <i class="fas fa-caret-right" style="color: #292424;"></i>
                    <a class="" href="{{ route('survey', $survey->survey_id) }}" style="color: #292424;">{{$survey->survey_name}}</a>
                @else
                <i class="fas fa-caret-right"></i>
                    <a class="" href="{{ route('survey', $survey->survey_id) }}" >{{$survey->survey_name}}</a>
                @endif
                


            </div>

            @endforeach
        </div>
        <div class="pro-graphsec">
            <img class="" src="{{asset('imgs/graph-round.png')}}">
            <img class="" src="{{asset('imgs/graph-lines.png')}}">

        </div>
    </div>

</body>

</html>