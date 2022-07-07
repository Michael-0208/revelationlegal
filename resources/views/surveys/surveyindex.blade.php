<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">


</head> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js" integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<body>

    @extends('layouts.reports')
    @section('content') 

    <div id="HelpContent" class="modal-body" style="display:none;">
    <p><b>Status</b></p>
    <p> 
This gives you an overview of the current project that you are working on. Please note that every
page should have the name of the current project on the top. The Status tab will tell you if the
project is currently active or deactive.
</p>
<p>
If a project is active, it means that the survey tool is accepting new participant responses, which
essentially means that the survey is allowing participants to submit a response. If a project is
deactive, it means that the survey tool is no longer accepting new participant responses, and
participants will be blocked from submitting a response.
</p>
<img src="{{asset('imgs/user-guide/image-001.png')}}">
<p>
<b>Response Rate:</b> This statistic tells you how many people have responded to the survey out of
how many were invited to participate.
</p>
<p>
Completion Rate: Since one of the ways that you can respond to a survey is to start but not
finish it, we offer the next statistic, which is the completion rate. This tells you how many people
actually finished the survey out of how many were invited to participate.
</p>
<p>
Ideally, we really would like for the result for the response rate to be over 75%, and the
completion rate to be 100%, especially if the survey is conducted at a high level.
</p> 

</div>



    <div class="container" id="pdfhidden">
        <div class="cont-mtitle  mt-8 flex flex-wrap justify-between items-center">
            <h1 class=" text-survey">{{$data['survey']->survey_name}}</h1>
            <button type="button" class="helpguidebtn mx-2" data-toggle="modal" data-target="#helpdetasurvey"></button> 
        </div>
        <div class="survaymain-cont   mx-auto">
            <div class="max-w-7xl mx-auto">
                <div class="survaymain-inner"> 
<div class="row mx-0"><div class="col-md-7 px-0">
                    <h3 class="">Status</h3>
                    <div>
                        <div class="survay-status flex">
                            <h1 class="text-survey font-bold">Survey is Currently:</h1>
                            <span class="active-status font-bold {{ $survey->survey_active == 1 ? 'text-support-activity' : 'text-red-700' }} m-0">{{$data['survey_active']}}</span>

                        </div>
                        <div class="survay-response flex ">
                            <h1 class="font-bold text-survey">Response Rate:</h1>
                            <div class="font-bold text-survey m-0">{{$data['total_resp']}} of {{$data['invitations_sent']}}</div>
                        </div>
                        <div class="survay-completion flex ">
                            <h1 class="font-bold text-survey">Completion Rate:</h1>
                            <div class="text-survey font-bold m-0">{{$data['completed_resp']}} of {{$data['total_resp']}} </div>
                        </div>
                        <div class="survays-btns">
                            <button id="activeBtn" class="" onclick="toggleActivation('{{ $survey->survey_id }}', 1);" style="{{ $survey->survey_active == 1 ? 'display:none;' : '' }}">Click Here Activate</button>
                            <button id="deactiveBtn" class="" onclick="toggleActivation('{{ $survey->survey_id }}', 0);" style="{{ $survey->survey_active == 1 ? '' : 'display:none;' }}">Click Here Deactivate</button>
                        </div>

                    </div></div> 
					<div class="col-md-5">
                    <div class="" style="width: 100%">
					<!--<h4 style="position: absolute;margin-top: 67px;left: 19rem;z-index: 99;color: white;"> {{$data['percent_completed']}}</h4> 
     <h4 style="position: absolute;margin-top: 128px;left: 17rem;z-index: 99;color: white;">{{$data['percent_total']}}</h4> --> 
    <canvas id="myChart"  width="200px" height="200px"></canvas></div>
<div class="survey-caninfo">
<p class="caninfogreen"><span></span> Completion Rate - {{$data['percent_completed']}}%</p>
<p class="caninfoblue"><span></span> Response Rate - {{$data['percent_total']}}%</p>  
</div>

	</div> 
	
	
	</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="helpdetasurvey" tabindex="-1" aria-labelledby="exampleModalCenterTitle"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header align-items-center">
        <h5 class="modal-title" id="exampleModalCenterTitle">User Guide</h5> 
        <button class="revnor-btn ml-auto mr-2 mb-3 mb-md-0 bg-white text-dark" id="printHelp">Print</button> 
        <button type="button" class="close ml-0" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        @php echo $data['helpContent']->course; @endphp 
         {{--  <p> 
This gives you an overview of the current project that you are working on. Please note that every
page should have the name of the current project on the top. The Status tab will tell you if the
project is currently active or deactive.
</p>
<p>
If a project is active, it means that the survey tool is accepting new participant responses, which
essentially means that the survey is allowing participants to submit a response. If a project is
deactive, it means that the survey tool is no longer accepting new participant responses, and
participants will be blocked from submitting a response.
</p>
<img src="{{asset('imgs/user-guide/image-001.png')}}">
<p>
<b>Response Rate:</b> This statistic tells you how many people have responded to the survey out of
how many were invited to participate.
</p>
<p>
Completion Rate: Since one of the ways that you can respond to a survey is to start but not
finish it, we offer the next statistic, which is the completion rate. This tells you how many people
actually finished the survey out of how many were invited to participate.
</p>
<p>
Ideally, we really would like for the result for the response rate to be over 75%, and the
completion rate to be 100%, especially if the survey is conducted at a high level.
</p>  --}}


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
    <div id="copyright_div" class="flex justify-content-between items-end" style="">
        <div>
            <img  src="{{asset('imgs/logo-pdfhead.png')}}">
        </div>
        <div class="text-center">
            <a href="{{ url('/') }}">{{ url('/') }}</a> <br>
            <span >© ofPartner LLC {{date("Y")}}, All Rights Reserved.</span>
        </div>
        <div>
            <span>Report Generated @php echo date('m/d/Y h:i:s') @endphp</span>
        </div>
    </div> 

    <div id="headerDiv" class="pdfheaderdiv">   
            <p class="text-phead">Demographic Report /{{ $survey->survey_name }}</p>
            <p class="redtext-phead">Confidential</p>
            <img style="display:none" src="{{asset('imgs/logo-pdfhead.png')}}"> 
    </div>    
    

<!--- help data popup end ---->
    <script>  
        $('#printHelp').on('click', function(){
            
            // var respondent_name_print = $('#respondent_data').find('h3').text();
            // if(respondent_name_print != ''){
		  $('#headerDiv').addClass('helppdfhead');
            $('#headerDiv').show();
            $('#hiddenprint').hide();
            $('.modal-backdrop').hide();
            $('#helpdetasurvey').modal('hide');
            $('#pdfhidden').hide();
            // $('#helpdetasurvey').hide();
            $('#HelpContent').show();
            $('#copyright_div').addClass('fixedbottompdf');
            $('#headerDiv').addClass('fixedtoppdf');
            $(".entrymain-content")[0].style.minHeight = "0";
            const hideElements = ['#desktop_sidebar','#hideinpdf', '.site-footer','.site-header','.first_part', '#pdfPrint', 'header > div > ul'];

            $.each(hideElements, function(_, el){ $(el).hide(); });

            window.print();

            $.each(hideElements, function(_, el){ $(el).show(); });
          $('#headerDiv').removeClass('helppdfhead');            
            $('#headerDiv').hide();
            $('#HelpContent').hide();
            $('#hiddenprint').show();
            $('#pdfhidden').show();
           // $('#helpdetasurvey').show();
            $('#copyright_div').removeClass('fixedbottompdf');
            $('#headerDiv').removeClass('fixedtoppdf');
            $(".entrymain-content")[0].style.minHeight = "100vh";
           /*  }else{ 
                $('#selectRespModal').modal();

            } */

           
        }); 

        function toggleActivation(survey_id, survey_active) {
            $.ajax({
                url: "{{ route('survey.toggle-active') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "survey_active": survey_active,
                },
                dataType: 'json',
                success: function(res) {
                    if (res == 200) {
                        if (survey_active == 1) {
                            $('.active-status').removeClass('text-red-700');
                            $('.active-status').addClass('text-support-activity');
                            $('.active-status').html('Active');
                            $('#activeBtn').hide();
                            $('#deactiveBtn').show();
                        } else {
                            $('.active-status').removeClass('text-support-activity');
                            $('.active-status').addClass('text-red-700');
                            $('.active-status').html('Inactive');
                            $('#activeBtn').show();
                            $('#deactiveBtn').hide();
                        }
                    }
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        }
    </script>
    <script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {
        labels: [ 'Completion Rate', '','Response Rate', ''],
        datasets: [
          {
            backgroundColor: ['hsla(97, 42%, 55%, 1)','hsla(0, 0%, 91%, 1)'],
            data: [{{$data['percent_completed']}},{{100 - $data['percent_completed']}} ]
    },
    {
      backgroundColor: ['hsla(209, 100%,52%, 1)','hsla(0, 0%, 91%, 1)' ],
      data: [{{$data['percent_total']}},{{100  - $data['percent_total']}} ]
    },
    {
      backgroundColor: ['hsla(209, 100%, 100%, 1)', 'hsla(209, 100%, 100%, 1)'],
      data: [0, 0]
    },
   
       
   /* {
      backgroundColor: ['hsl(100, 100%, 60%)', 'hsl(100, 100%, 35%)'],
      data: [20, 80]
    }, {
      backgroundColor: ['hsl(100, 100%, 60%)', 'hsl(100, 100%, 35%)'],
      data: [20, 80]
    }, {
      backgroundColor: ['hsl(100, 100%, 60%)', 'hsl(100, 100%, 35%)'],
      data: [20, 80]
    }, {
      backgroundColor: ['hsl(100, 100%, 60%)', 'hsl(100, 100%, 35%)'],
      data: [20, 80]
    },*/
  ]
    },
    options: {
    responsive: true,
    plugins: {
      legend: {
        display:false,
        position: 'bottom',
        labels: {
          generateLabels: function(chart) {
            // Get the default label list
          const original = Chart.overrides.pie.plugins.legend.labels.generateLabels;
            const labelsOriginal = original.call(this, chart);

            // Build an array of colors used in the datasets of the chart
            var datasetColors = chart.data.datasets.map(function(e) {
              return e.backgroundColor;
            });
            datasetColors = datasetColors.flat();

            // Modify the color and hide state of each label
            labelsOriginal.forEach(label => {
              // There are twice as many labels as there are datasets. This converts the label index into the corresponding dataset index
              label.datasetIndex = (label.index - label.index % 2) / 2;

              // The hidden state must match the dataset's hidden state
              label.hidden = !chart.isDatasetVisible(label.datasetIndex);

              // Change the color to match the dataset
              label.fillStyle = datasetColors[label.index];
            });

            return labelsOriginal;
          }
        },
        onClick: function(mouseEvent, legendItem, legend) {
          // toggle the visibility of the dataset from what it currently is
          legend.chart.getDatasetMeta(
            legendItem.datasetIndex
          ).hidden = legend.chart.isDatasetVisible(legendItem.datasetIndex);
          legend.chart.update();
        }
      },
      tooltip: {
        callbacks: {
          label: function(context) {
            const labelIndex = (context.datasetIndex * 2) + context.dataIndex;
            return context.chart.data.labels[labelIndex] + ': ' + context.formattedValue;
          }
        }
      }
    }
  },
});
</script>
    @endsection
</body>

</html>