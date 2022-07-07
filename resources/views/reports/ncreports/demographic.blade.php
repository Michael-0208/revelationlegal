@extends('layouts.reports')
@section('content')


<div id="HelpContent" class="modal-body" style="display:none;">
<p><strong>Demographic Report</strong></p>
<p>The Demographic Report is the first report that you will come across. This essentially tells you
who is in the specific data that you are looking at. The default view that you will see is
unfiltered; however, the tabs listed across the top of the page allow for you to change your view.
You can sort by position, department, group, location, and category.</p>
<img src="{{asset('imgs/user-guide/image-027.png')}}" alt="img">
<p>When you view the Demographic Report, you will first see a donut graph that tells you how
many surveys were sent, and how many employees participated. The<strong> Blue </strong>section of the graph
shows you how many individuals participated without completing the survey, meaning that they
started it, but did not finish it. The<strong> Green </strong>section of the graph shows you how many individuals
both started and finished the survey. Finally, the <strong>Red</strong> section of the graph shows you how many
users did not participate, meaning that they didn’t start the survey at all.</p>
<img src="{{asset('imgs/user-guide/image-028.png')}}" alt="img">
<p>To the right of this donut graph, you will see a few very important sets of numbers. Firstly, you
will see <strong>Total Annual Hours of Participants</strong>, which tells you the amount of hours that all
participants worked throughout the year. Below that you will see <strong>Average Annual Hours of
Participants</strong>, which shows you the average amount of hours each individual worked throughout
the year.</p>

<p>If you scroll further down the report, you will be able to sort the data by different classifications.
There are five different classifications:<strong> by category, by group, by location, by department, and
by position</strong>. The default view will show you the response rate (complete) for these
classifications. However, you can change this by choosing a metric.</p>
<img src="{{asset('imgs/user-guide/image-029.png')}}" alt="img">
<p>Choosing a Metric simply allows for you to refine the data that you see. When you click on
number of invites, number of surveys complete, number of surveys not finished, and number of
surveys not started, the classifications will show exactly that. The data will still be sorted by
category, group, location, department, and position, but your results will now show you what
metric you have selected. Response rate (completed) and response rate (started or complete) will
show you the amount of surveys that were either completely finished, or show both surveys that
were started and completed. The final metric that you can choose is Average Annual Hours (All
Participants). This will sort your data so you can view each classification by the average number
of hours that every individual worked throughout the year.</p>

<p>At the bottom of the Choose a Metric box, you will see an option titled Group into “All Other”
for anything under # individuals. By default, any group that has ten or less individuals will be
put into the “other” category. You can change this number to any number that you wish. The
number that is chosen will determine what groups you see. For example, if you select the number
five, any group that has five or less individuals will be put into the “other” category. If you select
the number 20, any group that has 20 or less individuals will be put into the “other” category.</p> 

</div> 


<div class="container-fluid px-3 hideinhelppdf"> 
    <div class="flex flex-wrap justify-between items-center cont-mtitle mb-4">
        <h1 class="text-survey">Demographic Report / {{ $survey->survey_name }}</h1>
        <div class="d-flex py-0 py-md-2">
        @if (\Auth::check() && \Auth::user()->hasPermission('surveyPrint', $survey))
            <button class="revnor-btn  mr-1 " id="pdfBtn">Download PDF</button>            
        @endif
        <button type="button" class="helpguidebtn mx-1" data-toggle="modal" data-target="#helpdetasurvey">         
</button> 
        </div>
    </div>
    <div id="demographicReportContent">
        @include('reports.partials.demographicfilter')
        <div class="first_part">
            
            <div class="row border-b-4  px-md-2 pb-2 flex items-center mx-0">
                <div class="col-md-7 border-r-4 px-0 border-rytnone">
                    <h5 class="px-4 py-6 text-center"><span id="stat_sent">{{$data['sent']}}</span> surveys were sent. <span id="stat_completed">{{$data['completed']}}</span> employees participated.</h5>
                    <div class="canvaschartouter"><div id="chartContainer" style="min-height:300px;"></div></div>
                </div>
                <div class="col-md-5">
                    <div class="flex py-4 justify-between items-center">
                        <div>Total Annual Hours of Participants</div>
                        <div id="stat_totalHours">{{$data['totalHours']}}</div>
                    </div>
                    <div class="flex py-4 justify-between items-center">
                        <div>Avg. Annual Hours of Participants</div>
                        <div id="stat_avgAnnualHours">{{$data['avgAnnualHours']}}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-0 px-md-2 pb-6 third_part">
            <div class="row" style="margin:25px 0;">
                <div class="flex flex-wrap justify-between w-full">
                    <div class="choose_metric col-md-5">
                        <h5><strong>Choose a Metric</strong></h5>    
                        <div class="form-check"> 
                            <input
                                class="form-check-input"
                                type="radio"
                                name="metric"
                                id="invites_radio"
                                data-text="# Invites"
                            />
                            <label class="form-check-label" for="invites_radio"> # Invites </label>
                        </div>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="metric"
                                id="surveycomplete_radio"
                                data-text="# Surveys Complete"
                            />
                            <label class="form-check-label" for="surveycomplete_radio"> # Surveys Complete </label>
                        </div>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="metric"
                                id="surveynotfinished_radio"
                                data-text="# Surveys Not Finished"
                            />
                            <label class="form-check-label" for="surveynotfinished_radio"> # Surveys Not Finished </label>
                        </div>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="metric"
                                id="surveynotstarted_radio"
                                data-text="# Surveys Not Started"
                            />
                            <label class="form-check-label" for="surveynotstarted_radio"> # Surveys Not Started </label>
                        </div>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="metric"
                                id="responseratecomplete_radio"
                                data-text="Response Rate (complete)"
                                checked
                            />
                            <label class="form-check-label" for="responseratecomplete_radio"> Response Rate (complete) </label>
                        </div>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="metric"
                                id="responseratestarted_radio"
                                data-text="Response Rate (started or complete)"
                            />
                            <label class="form-check-label" for="responseratestarted_radio"> Response Rate (started or complete) </label>
                        </div>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="metric"
                                id="avgannualhours_radio"
                                data-text="Avg. Annual Hours (All Participants)"
                            />
                            <label class="form-check-label" for="avgannualhours_radio"> Avg. Annual Hours (All Participants) </label>
                        </div>
                        <h5 class="mt-5"><strong>Group into "All Other" for anything under</strong></h5>
                        <div>
                            <input type="text" class="form-control w-full" value="10 individuals" id="min_rates">
                        </div>
                    </div>
                    <div class="col-md-7 flex w-full justify-center items-center">
                        <div class="rate_category" style="width: 80%;"> 
                            <h4 class="rate_title"><span>Response Rate (complete)</span> by <strong>Category</strong></h4>
                            <div class="border-t border-b border-gray-400 rate_body">
                                @foreach ($data['metric_data']['category'] as $item)
                                    <div class="bar-item flex justify-between items-center {{ $item['field'] == 'xothers' ? 'border-t border-gray-400' : '' }}">
                                        <div class="bar-index flex justify-between items-center">
                                            <div title="{{ $item['field'] == 'xothers' ? 'All Other Categories' : $item['field'] }}">{{ $item['field'] == 'xothers' ? 'All Other Categories' : $item['field'] }}</div>
                                            <div>{{ $item['invite_num'] }} invites</div>
                                        </div>
                                        <div class="bar-graph flex items-center justify-start">
                                            <div class="bg-revelation" style="width:calc(80% * {{ $item['percent'] }} / 100);height:24px;"></div>
                                            <span class="px-1">{{ $item['percent'] }}%</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-4">
                <div class="col-md-6">
                    <div class="rate_location">
                        <h4 class="rate_title"><span>Response Rate (complete)</span> by <strong>Location</strong></h4>
                        <div class="border-t border-b border-gray-400 rate_body">
                            @foreach ($data['metric_data']['location'] as $item)
                                <div class="bar-item flex justify-between items-center {{ $item['field'] == 'xothers' ? 'border-t border-gray-400' : '' }}">
                                    <div class="bar-index flex justify-between items-center">
                                        <div title="{{ $item['field'] == 'xothers' ? 'All Other Cities' : $item['field'] }}">{{ $item['field'] == 'xothers' ? 'All Other Cities' : $item['field'] }}</div>
                                        <div>{{ $item['invite_num'] }} invites</div>
                                    </div>
                                    <div class="bar-graph flex items-center justify-start">
                                        <div class="bg-revelation" style="width:calc(80% * {{ $item['percent'] }} / 100);height:24px;"></div>
                                        <span class="px-1">{{ $item['percent'] }}%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="pt-4 rate_department">
                        <h4 class="rate_title"><span>Response Rate (complete) by</span> <strong>Department</strong></h4>
                        <div class="border-t border-b border-gray-400 rate_body">
                            @foreach ($data['metric_data']['department'] as $item)
                                <div class="bar-item flex justify-between items-center {{ $item['field'] == 'xothers' ? 'border-t border-gray-400' : '' }}">
                                    <div class="bar-index flex justify-between items-center">
                                        <div title="{{ $item['field'] == 'xothers' ? 'All Other Departments' : $item['field'] }}">{{ $item['field'] == 'xothers' ? 'All Other Departments' : $item['field'] }}</div>
                                        <div>{{ $item['invite_num'] }} invites</div>
                                    </div>
                                    <div class="bar-graph flex items-center justify-start">
                                        <div class="bg-revelation" style="width:calc(80% * {{ $item['percent'] }} / 100);height:24px;"></div>
                                        <span class="px-1">{{ $item['percent'] }}%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="rate_group">
                        <h4 class="rate_title"><span>Response Rate (complete)</span> by <strong>Group</strong></h4>
                        <div class="border-t border-b border-gray-400 rate_body">
                            @foreach ($data['metric_data']['group'] as $item)
                                <div class="bar-item flex justify-between items-center {{ $item['field'] == 'xothers' ? 'border-t border-gray-400' : '' }}">
                                    <div class="bar-index flex justify-between items-center">
                                        <div title="{{ $item['field'] == 'xothers' ? 'All Other Groups' : $item['field'] }}">{{ $item['field'] == 'xothers' ? 'All Other Groups' : $item['field'] }}</div>
                                        <div>{{ $item['invite_num'] }} invites</div>
                                    </div>
                                    <div class="bar-graph flex items-center justify-start">
                                        <div class="bg-revelation" style="width:calc(80% * {{ $item['percent'] }} / 100);height:24px;"></div>
                                        <span class="px-1">{{ $item['percent'] }}%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="pt-4 rate_position">
                        <h4 class="rate_title"><span>Response Rate (complete)</span> by <strong>Position</strong></h4>
                        <div class="border-t border-b border-gray-400 rate_body">
                            @foreach ($data['metric_data']['position'] as $item)
                                <div class="bar-item flex justify-between items-center {{ $item['field'] == 'xothers' ? 'border-t border-gray-400' : '' }}">
                                    <div class="bar-index flex justify-between items-center">
                                        <div title="{{ $item['field'] == 'xothers' ? 'All Other Titles' : $item['field'] }}">{{ $item['field'] == 'xothers' ? 'All Other Titles' : $item['field'] }}</div>
                                        <div>{{ $item['invite_num'] }} invites</div>
                                    </div>
                                    <div class="bar-graph flex items-center justify-start">
                                        <div class="bg-revelation" style="width:calc(80% * {{ $item['percent'] }} / 100);height:24px;"></div>
                                        <span class="px-1">{{ $item['percent'] }}%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
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
            {{-- <img  src="{{asset('imgs/logo-pdfhead.png')}}">   --}}  
    </div>   

    <div id="temp"></div>

    <div class="modal fade" tabindex="-1" role="dialog" id="generatePDFModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body flex items-center justify-center" style="height: 150px;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp; Generating PDF...
                </div>
                <div class="modal-footer">
                    <button class="btn btn-revelation-primary" onclick="generatePDF();" disabled>Download</button>
                </div>
            </div>
        </div>
    </div>
</div>
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
    <p><strong>Demographic Report</strong></p>
<p>The Demographic Report is the first report that you will come across. This essentially tells you
who is in the specific data that you are looking at. The default view that you will see is
unfiltered; however, the tabs listed across the top of the page allow for you to change your view.
You can sort by position, department, group, location, and category.</p>
<img src="{{asset('imgs/user-guide/image-027.png')}}" alt="img">
<p>When you view the Demographic Report, you will first see a donut graph that tells you how
many surveys were sent, and how many employees participated. The<strong> Blue </strong>section of the graph
shows you how many individuals participated without completing the survey, meaning that they
started it, but did not finish it. The<strong> Green </strong>section of the graph shows you how many individuals
both started and finished the survey. Finally, the <strong>Red</strong> section of the graph shows you how many
users did not participate, meaning that they didn’t start the survey at all.</p>
<img src="{{asset('imgs/user-guide/image-028.png')}}" alt="img">
<p>To the right of this donut graph, you will see a few very important sets of numbers. Firstly, you
will see <strong>Total Annual Hours of Participants</strong>, which tells you the amount of hours that all
participants worked throughout the year. Below that you will see <strong>Average Annual Hours of
Participants</strong>, which shows you the average amount of hours each individual worked throughout
the year.</p>

<p>If you scroll further down the report, you will be able to sort the data by different classifications.
There are five different classifications:<strong> by category, by group, by location, by department, and
by position</strong>. The default view will show you the response rate (complete) for these
classifications. However, you can change this by choosing a metric.</p>
<img src="{{asset('imgs/user-guide/image-029.png')}}" alt="img">
<p>Choosing a Metric simply allows for you to refine the data that you see. When you click on
number of invites, number of surveys complete, number of surveys not finished, and number of
surveys not started, the classifications will show exactly that. The data will still be sorted by
category, group, location, department, and position, but your results will now show you what
metric you have selected. Response rate (completed) and response rate (started or complete) will
show you the amount of surveys that were either completely finished, or show both surveys that
were started and completed. The final metric that you can choose is Average Annual Hours (All
Participants). This will sort your data so you can view each classification by the average number
of hours that every individual worked throughout the year.</p>

<p>At the bottom of the Choose a Metric box, you will see an option titled Group into “All Other”
for anything under # individuals. By default, any group that has ten or less individuals will be
put into the “other” category. You can change this number to any number that you wish. The
number that is chosen will determine what groups you see. For example, if you select the number
five, any group that has five or less individuals will be put into the “other” category. If you select
the number 20, any group that has 20 or less individuals will be put into the “other” category.</p> 
 
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>  
    <div class="loading-mask"></div>
    <script>

$('#printHelp').on('click', function(){
          
          // var respondent_name_print = $('#respondent_data').find('h3').text();
          // if(respondent_name_print != ''){

          $('#headerDiv').show();
          $('#hiddenprint').hide();
          $('.modal-backdrop').hide();
          $('#helpdetasurvey').modal('hide');
          $('#pdfhidden').hide();
          $('.hideinhelppdf').hide(); 
          $('#HelpContent').show();
          $('#copyright_div').addClass('fixedbottompdf');
          $('#headerDiv').addClass('fixedtoppdf');
          $(".entrymain-content")[0].style.minHeight = "0";
          const hideElements = ['#desktop_sidebar','#hideinpdf', '.site-footer','.site-header','.first_part', '#pdfPrint', 'header > div > ul'];

          $.each(hideElements, function(_, el){ $(el).hide(); });

          window.print();

          $.each(hideElements, function(_, el){ $(el).show(); });
          
          $('#headerDiv').hide();
          $('#HelpContent').hide();
          $('#hiddenprint').show();
          $('#pdfhidden').show();
          $('.hideinhelppdf').show();
          $('#copyright_div').removeClass('fixedbottompdf');
          $('#headerDiv').removeClass('fixedtoppdf');
          $(".entrymain-content")[0].style.minHeight = "100vh"; 
         /*  }else{ 
              $('#selectRespModal').modal();

          } */

         
      });



        var survey_id = @php echo $data['survey'] -> survey_id; @endphp;
        var imgData_1, imgData_2, imgData_3, copyrightData, headerData;
        $(document).ready(function(){
            let respondents = "{{$data['respondents']}}";
            let completed = "{{$data['completed']}}";
            let sent = "{{$data['sent']}}";
            let participated = "{{$data['participated']}}";
            let non_participants = parseInt(sent) - parseInt(completed) - parseInt(participated);
            let supportColor ="#82BD5E";
            let legalColor = "#367BC1";
            let percent_completed = Math.round((parseInt(completed) / parseInt(sent)) * 100);
            let percent_participated = Math.round((parseInt(participated) / parseInt(sent)) * 100);
            let percent_nonparticipated = Math.round((non_participants / parseInt(sent)) * 100);
            let completedCaption = completed + " individuals completed the survey. That is " + percent_completed + "% of surveys sent";
            let participatedCaption = participated + " individuals participated without completing the survey. That is " + percent_participated + "% of surveys sent";
            let NotParticipatedCaption = non_participants + " individuals did not participate. That is " + percent_nonparticipated + "% of surveys sent";
            let chart = new CanvasJS.Chart("chartContainer",
                {
                    title:{
                        text: ""
                    },
                    legend: {
                    maxWidth: 350,
                    itemWidth: 120,
                    // fontSize: 18
                    },
                    toolTip:{
                        enabled: true,       //disable here
                        animationEnabled: false, //disable here
                        // tooltipContent: "{indexLabel}"
                        content: "{indexLabel}"
                    },
                    data: [
                        {
                        type: "doughnut",
                        showInLegend: true,
                        legendText: "{legend}",
                        animationEnabled: true,

                        dataPoints: [
                            { y: completed, indexLabel: completedCaption, color: "#77b55a",  indexLabelFontColor: "#77b55a", legend: "Completed Survey"},
                            { y: participated, indexLabel: participatedCaption, color: "#367BC1", indexLabelFontColor: "#367BC1", legend: "Participated without completing" },
                            { y: non_participants, indexLabel: NotParticipatedCaption, color: "#e15659", indexLabelFontColor: "#e15659", legend: "Did not participate"}
                        ]
                    }
                ]
            });
            chart.render();
            var base64Image = chart.canvas.toDataURL();
            // document.getElementById('chartContainer').style.display = 'none';
            // document.getElementById('chartImage').src = base64Image;
        });

        $('input[name=metric]').click(function () {
            let metric = $(this).attr('id');
            let metric_text = $(this).attr('data-text');

            let min_text = $('#min_rates').val();
            let ind_position = min_text.indexOf(' individuals');
            let min_rates = min_text.substr(0, ind_position);   
            mask_height = $('body').height();
            $('.loading-mask').css('height', mask_height);
            $('.loading-mask').fadeIn();

            $.ajax({
                url: '{{ route('getDemographicData') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'survey_id': survey_id,
                    'metric': metric,
                    'position': JSON.stringify(options['position']),
                    'department': JSON.stringify(options['department']),
                    'group': JSON.stringify(options['group']),
                    'location': JSON.stringify(options['location']),
                    'category': JSON.stringify(options['category']),
                    'min_rates': min_rates,
                },
                dataType: 'json',
                success: function (data) {
                    let completed = data.stat.completed;
                    let sent = data.stat.sent;
                    let participated = data.stat.participated;
                    let non_participants = parseInt(sent) - parseInt(completed) - parseInt(participated);
                    let supportColor ="#82BD5E";
                    let legalColor = "#367BC1";
                    let percent_completed = Math.round((parseInt(completed) / parseInt(sent)) * 100);
                    let percent_participated = Math.round((parseInt(participated) / parseInt(sent)) * 100);
                    let percent_nonparticipated = Math.round((non_participants / parseInt(sent)) * 100);
                    let completedCaption = completed + " individuals completed the survey. That is " + percent_completed + "% of surveys sent";
                    let participatedCaption = participated + " individuals participated without completing the survey. That is " + percent_participated + "% of surveys sent";
                    let NotParticipatedCaption = non_participants + " individuals did not participate. That is " + percent_nonparticipated + "% of surveys sent";
                    let chart = new CanvasJS.Chart("chartContainer",
                        {
                            title:{
                                text: ""
                            },
                            legend: {
                            maxWidth: 350,
                            itemWidth: 120,
                            // fontSize: 18
                            },
                            toolTip:{
                                enabled: true,       //disable here
                                animationEnabled: false, //disable here
                                // tooltipContent: "{indexLabel}"
                                content: "{indexLabel}"
                            },
                            data: [
                                {
                                type: "doughnut",
                                showInLegend: true,
                                legendText: "{legend}",
                                animationEnabled: true,

                                dataPoints: [
                                    { y: completed, indexLabel: completedCaption, color: "#77b55a",  indexLabelFontColor: "#77b55a", legend: "Completed Survey"},
                                    { y: participated, indexLabel: participatedCaption, color: "#367BC1", indexLabelFontColor: "#367BC1", legend: "Participated without completing" },
                                    { y: non_participants, indexLabel: NotParticipatedCaption, color: "#e15659", indexLabelFontColor: "#e15659", legend: "Did not participate"}
                                ]
                            }
                        ]
                    });
                    chart.render();
                    $('#stat_avgAnnualHours').html(data.stat.avgAnnualHours);
                    $('#stat_nonparticipatedComp').html('$' + data.stat.nonparticipatedComp);
                    $('#stat_respondentsComp').html('$' + data.stat.respondentsComp);
                    $('#stat_totalHours').html(data.stat.totalHours);
                    $('#stat_sent').html(data.stat.sent);
                    $('#stat_completed').html(data.stat.completed);

                    let res = data.metric;
                    $('.rate_category .rate_title span').html(metric_text);
                    $('.rate_category .rate_body').empty();
                    res.category.forEach(element => {
                        let percent = 0;
                        if (metric == 'invites_radio') {
                            percent = element.invite_num;
                        } else if (metric == 'surveycomplete_radio'
                                || metric == 'surveynotfinished_radio'
                                || metric == 'surveynotstarted_radio') {
                            percent = element.complete_num;
                        } else if (metric == 'percentinvite_radio' || metric == 'percentinvite_radio') {
                            percent = element.percentVal + '%';
                        } else if (metric == 'avgannualhours_radio') {
                            percent = element.hours;
                        } else {
                            percent = element.percent + '%';
                        }
                        if (element.field != "") {                    
                            $('.rate_category .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                        <div class="bar-index flex justify-between items-center">
                                                                            <div title="${ element.field == 'xothers' ? 'All Other Categories' : element.field }">${ element.field == 'xothers' ? 'All Other Categories' : element.field }</div>
                                                                            <div>${ element.invite_num } invites</div>
                                                                        </div>
                                                                        <div class="bar-graph flex items-center justify-start">
                                                                            <div class="bg-revelation" style="width:calc(80% * ${ element.percent } / 100);height:24px;"></div>
                                                                            <span class="px-1">${ percent }</span>
                                                                        </div>
                                                                    </div>`);
                        }
                    });

                    $('.rate_location .rate_title span').html(metric_text);
                    $('.rate_location .rate_body').empty();
                    res.location.forEach(element => {
                        let percent = 0;
                        if (metric == 'invites_radio') {
                            percent = element.invite_num;
                        } else if (metric == 'surveycomplete_radio'
                                || metric == 'surveynotfinished_radio'
                                || metric == 'surveynotstarted_radio') {
                            percent = element.complete_num;
                        } else if (metric == 'avgannualhours_radio') {
                            percent = element.hours;
                        } else {
                            percent = element.percent + '%';
                        }
                        if (element.field != "") {                    
                            $('.rate_location .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                        <div class="bar-index flex justify-between items-center">
                                                                            <div  title="${ element.field == 'xothers' ? 'All Other Cities' : element.field }">${ element.field == 'xothers' ? 'All Other Cities' : element.field }</div>
                                                                            <div>${ element.invite_num } invites</div>
                                                                        </div>
                                                                        <div class="bar-graph flex items-center justify-start">
                                                                            <div class="bg-revelation" style="width:calc(80% * ${element.percent} / 100);height:24px;"></div>
                                                                            <span class="px-1">${percent}</span>
                                                                        </div>
                                                                    </div>`);
                        }
                    });

                    $('.rate_department .rate_title span').html(metric_text);
                    $('.rate_department .rate_body').empty();
                    res.department.forEach(element => {
                        let percent = 0;
                        if (metric == 'invites_radio') {
                            percent = element.invite_num;
                        } else if (metric == 'surveycomplete_radio'
                                || metric == 'surveynotfinished_radio'
                                || metric == 'surveynotstarted_radio') {
                            percent = element.complete_num;
                        } else if (metric == 'avgannualhours_radio') {
                            percent = element.hours;
                        } else {
                            percent = element.percent + '%';
                        }
                        if (element.field != "") {                    
                            $('.rate_department .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                        <div class="bar-index flex justify-between items-center">
                                                                            <div title="${ element.field == 'xothers' ? 'All Other Departments' : element.field }">${ element.field == 'xothers' ? 'All Other Departments' : element.field }</div>
                                                                            <div>${ element.invite_num } invites</div>
                                                                        </div>
                                                                        <div class="bar-graph flex items-center justify-start">
                                                                            <div class="bg-revelation" style="width:calc(80% * ${ element.percent } / 100);height:24px;"></div>
                                                                            <span class="px-1">${ percent }</span>
                                                                        </div>
                                                                    </div>`);
                        }
                    });

                    $('.rate_group .rate_title span').html(metric_text);
                    $('.rate_group .rate_body').empty();
                    res.group.forEach(element => {
                        let percent = 0;
                        if (metric == 'invites_radio') {
                            percent = element.invite_num;
                        } else if (metric == 'surveycomplete_radio'
                                || metric == 'surveynotfinished_radio'
                                || metric == 'surveynotstarted_radio') {
                            percent = element.complete_num;
                        } else if (metric == 'avgannualhours_radio') {
                            percent = element.hours;
                        } else {
                            percent = element.percent + '%';
                        }
                        if (element.field != "") {                    
                            $('.rate_group .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                    <div class="bar-index flex justify-between items-center">
                                                                        <div title="${ element.field == 'xothers' ? 'All Other Groups' : element.field }">${ element.field == 'xothers' ? 'All Other Groups' : element.field }</div>
                                                                        <div>${ element.invite_num } invites</div>
                                                                    </div>
                                                                    <div class="bar-graph flex items-center justify-start">
                                                                        <div class="bg-revelation" style="width:calc(80% * ${ element.percent } / 100);height:24px;"></div>
                                                                        <span class="px-1">${ percent }</span>
                                                                    </div>
                                                                </div>`);
                        }
                    });

                    $('.rate_position .rate_title span').html(metric_text);
                    $('.rate_position .rate_body').empty();
                    res.position.forEach(element => {
                        let percent = 0;
                        if (metric == 'invites_radio') {
                            percent = element.invite_num;
                        } else if (metric == 'surveycomplete_radio'
                                || metric == 'surveynotfinished_radio'
                                || metric == 'surveynotstarted_radio') {
                            percent = element.complete_num;
                        } else if (metric == 'avgannualhours_radio') {
                            percent = element.hours;
                        } else {
                            percent = element.percent + '%';
                        }
                        if (element.field != "") {                    
                            $('.rate_position .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                        <div class="bar-index flex justify-between items-center">
                                                                            <div title="${ element.field == 'xothers' ? 'All Other Titles' : element.field }">${ element.field == 'xothers' ? 'All Other Titles' : element.field }</div>
                                                                            <div>${ element.invite_num } invites</div>
                                                                        </div>
                                                                        <div class="bar-graph flex items-center justify-start">
                                                                            <div class="bg-revelation" style="width:calc(80% * ${ element.percent } / 100);height:24px;"></div>
                                                                            <span class="px-1">${ percent }</span>
                                                                        </div>
                                                                    </div>`);
                        }
                    });


                    $('.bar-item').click(function () {
                        $progressBar = $(this).find('.progress-bar');
                        $titleBar = $(this).find('.bar-index');
                        if ($progressBar.hasClass('progress-bar-animated')) {
                            $progressBar.removeClass('progress-bar-animated');
                            $titleBar.css('background', 'none');
                        } else {
                            $progressBar.addClass('progress-bar-animated');
                            $titleBar.css('background', '#cce2f9');
                        }
                    });

                    $('.loading-mask').fadeOut();
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        });

        $('.bar-item').click(function () {
            $progressBar = $(this).find('.progress-bar');
            $titleBar = $(this).find('.bar-index');
            if ($progressBar.hasClass('progress-bar-animated')) {
                $progressBar.removeClass('progress-bar-animated');
                $titleBar.css('background', 'none');
            } else {
                $progressBar.addClass('progress-bar-animated');
                $titleBar.css('background', '#cce2f9');
            }
        });

        // Handle the pdf button click, generate image data from the body
        $('#pdfBtn').click(function () {
            $('#generatePDFModal').modal('show');
            $('#headerDiv').show();
			 $('#copyright_div').addClass('d-flex');
			 $('#copyright_div').show(); 			
            let checked_radioId = $('input[type=radio][name=metric]:checked').attr('id').toString();
            source = $('#demographicReportContent .first_part');
            html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    imgData_1 = canvas.toDataURL("image/png", 1.0);
                        
                });
            // Copyright
            source = $('#copyright_div');
            html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    copyrightData = canvas.toDataURL("image/png", 1.0);
                        
                });
            source = $('#headerDiv');
            html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    headerData = canvas.toDataURL("image/png", 1.0);
                        
                });
            $('#temp').html($('#demographicReportContent .third_part').html());
            $(`#temp #${checked_radioId}`).prop('checked', true);
            $('#temp .rate_body').css('max-height', 'unset');
            $('#temp .rate_body').css('overflow-y', 'none');
            source = $('#temp');
            html2canvas(source, {
                onrendered: function (canvas) {
                    imgData_3 = canvas.toDataURL('image/jpeg', 1.0);
                }
            }).then(function (canvas, radio = checked_radioId) {
                $('#generatePDFModal .modal-body').html(`<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="24" height="24" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path d="M30 18v-2h-6v10h2v-4h3v-2h-3v-2h4z" fill="currentColor"/><path d="M19 26h-4V16h4a3.003 3.003 0 0 1 3 3v4a3.003 3.003 0 0 1-3 3zm-2-2h2a1.001 1.001 0 0 0 1-1v-4a1.001 1.001 0 0 0-1-1h-2z" fill="currentColor"/><path d="M11 16H6v10h2v-3h3a2.003 2.003 0 0 0 2-2v-3a2.002 2.002 0 0 0-2-2zm-3 5v-3h3l.001 3z" fill="currentColor"/><path d="M22 14v-4a.91.91 0 0 0-.3-.7l-7-7A.909.909 0 0 0 14 2H4a2.006 2.006 0 0 0-2 2v24a2 2 0 0 0 2 2h16v-2H4V4h8v6a2.006 2.006 0 0 0 2 2h6v2zm-8-4V4.4l5.6 5.6z" fill="currentColor"/></svg> &nbsp; Generated a PDF`);
                $('#generatePDFModal .btn').attr('disabled', false);
                $('#headerDiv').hide();
                $('#temp').empty();
					$('#copyright_div').removeClass('d-flex');
					$('#copyright_div').hide();  				
                $(`#${checked_radioId}`).prop('checked', true);
            });
        });

        $('#min_rates').focus(function () {
            let text = $(this).val();
            let ind_position = text.indexOf(' individuals');
            $(this).val(text.substr(0, ind_position));
        });

        $('#min_rates').blur(function () {
            let value = $(this).val();

            if (value.match(/^[0-9]+$/)) {
                if (value > 50) {
                    value = 50;
                }

                if (value < 1) {
                    value = 1;
                }

                $(this).val(value + ' individuals');

                let metric = $('input[name="metric"]:checked').attr('id');
                let metric_text = $('input[name="metric"]:checked').attr('data-text');

                mask_height = $('body').height();
                $('.loading-mask').css('height', mask_height);
                $('.loading-mask').fadeIn();
                $.ajax({
                    url: '{{ route('getDemographicData') }}',
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'survey_id': survey_id,
                        'metric': metric,
                        'position': JSON.stringify(options['position']),
                        'department': JSON.stringify(options['department']),
                        'group': JSON.stringify(options['group']),
                        'location': JSON.stringify(options['location']),
                        'category': JSON.stringify(options['category']),
                        'min_rates': value,
                    },
                    dataType: 'json',
                    success: function (data) {
                        let completed = data.stat.completed;
                        let sent = data.stat.sent;
                        let participated = data.stat.participated;
                        let non_participants = parseInt(sent) - parseInt(completed) - parseInt(participated);
                        let supportColor ="#82BD5E";
                        let legalColor = "#367BC1";
                        let percent_completed = Math.round((parseInt(completed) / parseInt(sent)) * 100);
                        let percent_participated = Math.round((parseInt(participated) / parseInt(sent)) * 100);
                        let percent_nonparticipated = Math.round((non_participants / parseInt(sent)) * 100);
                        let completedCaption = completed + " individuals completed the survey. That is " + percent_completed + "% of surveys sent";
                        let participatedCaption = participated + " individuals participated without completing the survey. That is " + percent_participated + "% of surveys sent";
                        let NotParticipatedCaption = non_participants + " individuals did not participate. That is " + percent_nonparticipated + "% of surveys sent";
                        let chart = new CanvasJS.Chart("chartContainer",
                            {
                                title:{
                                    text: ""
                                },
                                legend: {
                                maxWidth: 350,
                                itemWidth: 120,
                                // fontSize: 18
                                },
                                toolTip:{
                                    enabled: true,       //disable here
                                    animationEnabled: false, //disable here
                                    // tooltipContent: "{indexLabel}"
                                    content: "{indexLabel}"
                                },
                                data: [
                                    {
                                    type: "doughnut",
                                    showInLegend: true,
                                    legendText: "{legend}",
                                    animationEnabled: true,

                                    dataPoints: [
                                        { y: completed, indexLabel: completedCaption, color: "#77b55a",  indexLabelFontColor: "#77b55a", legend: "Completed Survey"},
                                        { y: participated, indexLabel: participatedCaption, color: "#367BC1", indexLabelFontColor: "#367BC1", legend: "Participated without completing" },
                                        { y: non_participants, indexLabel: NotParticipatedCaption, color: "#e15659", indexLabelFontColor: "#e15659", legend: "Did not participate"}
                                    ]
                                }
                            ]
                        });
                        chart.render();
                        $('#stat_avgAnnualHours').html(data.stat.avgAnnualHours);
                        $('#stat_nonparticipatedComp').html('$' + data.stat.nonparticipatedComp);
                        $('#stat_respondentsComp').html('$' + data.stat.respondentsComp);
                        $('#stat_totalHours').html(data.stat.totalHours);
                        $('#stat_sent').html(data.stat.sent);
                        $('#stat_completed').html(data.stat.completed);

                        let res = data.metric;
                        $('.rate_category .rate_title span').html(metric_text);
                        $('.rate_category .rate_body').empty();
                        res.category.forEach(element => {
                            let percent = 0;
                            if (metric == 'invites_radio') {
                                percent = element.invite_num;
                            } else if (metric == 'surveycomplete_radio'
                                    || metric == 'surveynotfinished_radio'
                                    || metric == 'surveynotstarted_radio') {
                                percent = element.complete_num;
                            } else if (metric == 'percentinvite_radio' || metric == 'percentinvite_radio') {
                                percent = element.percentVal + '%';
                            } else if (metric == 'avgannualhours_radio') {
                                percent = element.hours;
                            } else {
                                percent = element.percent + '%';
                            }
                            if (element.field != "") {                    
                                $('.rate_category .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                            <div class="bar-index flex justify-between items-center">
                                                                                <div title="${element.field == 'xothers' ? 'All Other Categories' : element.field}">${ element.field == 'xothers' ? 'All Other Categories' : element.field }</div>
                                                                                <div>${ element.invite_num } invites</div>
                                                                            </div>
                                                                            <div class="bar-graph flex items-center justify-start">
                                                                                <div class="bg-revelation" style="width:calc(80% * ${element.percent} / 100);height:24px;"></div>
                                                                                <span class="px-1">${percent}</span>
                                                                            </div>
                                                                        </div>`);
                            }
                        });

                        $('.rate_location .rate_title span').html(metric_text);
                        $('.rate_location .rate_body').empty();
                        res.location.forEach(element => {
                            let percent = 0;
                            if (metric == 'invites_radio') {
                                percent = element.invite_num;
                            } else if (metric == 'surveycomplete_radio'
                                    || metric == 'surveynotfinished_radio'
                                    || metric == 'surveynotstarted_radio') {
                                percent = element.complete_num;
                            } else if (metric == 'avgannualhours_radio') {
                                percent = element.hours;
                            } else {
                                percent = element.percent + '%';
                            }
                            if (element.field != "") {                    
                                $('.rate_location .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                            <div class="bar-index flex justify-between items-center">
                                                                                <div title="${element.field == 'xothers' ? 'All Other Cities' : element.field}">${ element.field == 'xothers' ? 'All Other Cities' : element.field }</div>
                                                                                <div>${ element.invite_num } invites</div>
                                                                            </div>
                                                                            <div class="bar-graph flex items-center justify-start">
                                                                                <div class="bg-revelation" style="width:calc(80% * ${element.percent} / 100);height:24px;"></div>
                                                                                <span class="px-1">${percent}</span>
                                                                            </div>
                                                                        </div>`);
                            }
                        });

                        $('.rate_department .rate_title span').html(metric_text);
                        $('.rate_department .rate_body').empty();
                        res.department.forEach(element => {
                            let percent = 0;
                            if (metric == 'invites_radio') {
                                percent = element.invite_num;
                            } else if (metric == 'surveycomplete_radio'
                                    || metric == 'surveynotfinished_radio'
                                    || metric == 'surveynotstarted_radio') {
                                percent = element.complete_num;
                            } else if (metric == 'avgannualhours_radio') {
                                percent = element.hours;
                            } else {
                                percent = element.percent + '%';
                            }
                            if (element.field != "") {                    
                                $('.rate_department .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                            <div class="bar-index flex justify-between items-center">
                                                                                <div title="${element.field == 'xothers' ? 'All Other Departments' : element.field}">${ element.field == 'xothers' ? 'All Other Departments' : element.field }</div>
                                                                                <div>${ element.invite_num } invites</div>
                                                                            </div>
                                                                            <div class="bar-graph flex items-center justify-start">
                                                                                <div class="bg-revelation" style="width:calc(80% * ${element.percent} / 100);height:24px;"></div>
                                                                                <span class="px-1">${percent}</span>
                                                                            </div>
                                                                        </div>`);
                            }
                        });

                        $('.rate_group .rate_title span').html(metric_text);
                        $('.rate_group .rate_body').empty();
                        res.group.forEach(element => {
                            let percent = 0;
                            if (metric == 'invites_radio') {
                                percent = element.invite_num;
                            } else if (metric == 'surveycomplete_radio'
                                    || metric == 'surveynotfinished_radio'
                                    || metric == 'surveynotstarted_radio') {
                                percent = element.complete_num;
                            } else if (metric == 'avgannualhours_radio') {
                                percent = element.hours;
                            } else {
                                percent = element.percent + '%';
                            }
                            if (element.field != "") {                    
                                $('.rate_group .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                        <div class="bar-index flex justify-between items-center">
                                                                            <div title="${element.field == 'xothers' ? 'All Other Groups' : element.field}">${ element.field == 'xothers' ? 'All Other Groups' : element.field }</div>
                                                                            <div>${ element.invite_num } invites</div>
                                                                        </div>
                                                                        <div class="bar-graph flex items-center justify-start">
                                                                            <div class="bg-revelation" style="width:calc(80% * ${element.percent} / 100);height:24px;"></div>
                                                                            <span class="px-1">${percent}</span>
                                                                        </div>
                                                                    </div>`);
                            }
                        });

                        $('.rate_position .rate_title span').html(metric_text);
                        $('.rate_position .rate_body').empty();
                        res.position.forEach(element => {
                            let percent = 0;
                            if (metric == 'invites_radio') {
                                percent = element.invite_num;
                            } else if (metric == 'surveycomplete_radio'
                                    || metric == 'surveynotfinished_radio'
                                    || metric == 'surveynotstarted_radio') {
                                percent = element.complete_num;
                            } else if (metric == 'avgannualhours_radio') {
                                percent = element.hours;
                            } else {
                                percent = element.percent + '%';
                            }
                            if (element.field != "") {                    
                                $('.rate_position .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                            <div class="bar-index flex justify-between items-center">
                                                                                <div title="${element.field == 'xothers' ? 'All Other Titles' : element.field}">${ element.field == 'xothers' ? 'All Other Titles' : element.field }</div>
                                                                                <div>${ element.invite_num } invites</div>
                                                                            </div>
                                                                            <div class="bar-graph flex items-center justify-start">
                                                                                <div class="bg-revelation" style="width:calc(80% * ${element.percent} / 100);height:24px;"></div>
                                                                                <span class="px-1">${percent}</span>
                                                                            </div>
                                                                        </div>`);
                            }
                        });


                        $('.bar-item').click(function () {
                            $progressBar = $(this).find('.progress-bar');
                            $titleBar = $(this).find('.bar-index');
                            if ($progressBar.hasClass('progress-bar-animated')) {
                                $progressBar.removeClass('progress-bar-animated');
                                $titleBar.css('background', 'none');
                            } else {
                                $progressBar.addClass('progress-bar-animated');
                                $titleBar.css('background', '#cce2f9');
                            }
                        });
                        $('.loading-mask').fadeOut();
                    },
                    error: function(request, error) {
                        alert("Request: " + JSON.stringify(request));
                    }
                });
            } else {
                alert("Please input only numbers!");
                $(this).val("10 individuals");
            }

        });

        $('#min_rates').on("keypress", function (e) {
            if (e.keyCode == 13) {
                let value = $('#min_rates').val();
                if (value.match(/^[0-9]+$/)) {
                    let metric = $('input[name="metric"]:checked').attr('id');
                    let metric_text = $('input[name="metric"]:checked').attr('data-text');

                    mask_height = $('body').height();
                    $('.loading-mask').css('height', mask_height);
                    $('.loading-mask').fadeIn();
                    $.ajax({
                        url: '{{ route('getDemographicData') }}',
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'survey_id': survey_id,
                            'metric': metric,
                            'position': JSON.stringify(options['position']),
                            'department': JSON.stringify(options['department']),
                            'group': JSON.stringify(options['group']),
                            'location': JSON.stringify(options['location']),
                            'category': JSON.stringify(options['category']),
                            'min_rates': value,
                        },
                        dataType: 'json',
                        success: function (data) {
                            let completed = data.stat.completed;
                            let sent = data.stat.sent;
                            let participated = data.stat.participated;
                            let non_participants = parseInt(sent) - parseInt(completed) - parseInt(participated);
                            let supportColor ="#82BD5E";
                            let legalColor = "#367BC1";
                            let percent_completed = Math.round((parseInt(completed) / parseInt(sent)) * 100);
                            let percent_participated = Math.round((parseInt(participated) / parseInt(sent)) * 100);
                            let percent_nonparticipated = Math.round((non_participants / parseInt(sent)) * 100);
                            let completedCaption = completed + " individuals completed the survey. That is " + percent_completed + "% of surveys sent";
                            let participatedCaption = participated + " individuals participated without completing the survey. That is " + percent_participated + "% of surveys sent";
                            let NotParticipatedCaption = non_participants + " individuals did not participate. That is " + percent_nonparticipated + "% of surveys sent";
                            let chart = new CanvasJS.Chart("chartContainer",
                                {
                                    title:{
                                        text: ""
                                    },
                                    legend: {
                                    maxWidth: 350,
                                    itemWidth: 120,
                                    // fontSize: 18
                                    },
                                    toolTip:{
                                        enabled: true,       //disable here
                                        animationEnabled: false, //disable here
                                        // tooltipContent: "{indexLabel}"
                                        content: "{indexLabel}"
                                    },
                                    data: [
                                        {
                                        type: "doughnut",
                                        showInLegend: true,
                                        legendText: "{legend}",
                                        animationEnabled: true,

                                        dataPoints: [
                                            { y: completed, indexLabel: completedCaption, color: "#77b55a",  indexLabelFontColor: "#77b55a", legend: "Completed Survey"},
                                            { y: participated, indexLabel: participatedCaption, color: "#367BC1", indexLabelFontColor: "#367BC1", legend: "Participated without completing" },
                                            { y: non_participants, indexLabel: NotParticipatedCaption, color: "#e15659", indexLabelFontColor: "#e15659", legend: "Did not participate"}
                                        ]
                                    }
                                ]
                            });
                            chart.render();
                            $('#stat_avgAnnualHours').html(data.stat.avgAnnualHours);
                            $('#stat_nonparticipatedComp').html('$' + data.stat.nonparticipatedComp);
                            $('#stat_respondentsComp').html('$' + data.stat.respondentsComp);
                            $('#stat_totalHours').html(data.stat.totalHours);
                            $('#stat_sent').html(data.stat.sent);
                            $('#stat_completed').html(data.stat.completed);

                            let res = data.metric;
                            $('.rate_category .rate_title span').html(metric_text);
                            $('.rate_category .rate_body').empty();
                            res.category.forEach(element => {
                                let percent = 0;
                                if (metric == 'invites_radio') {
                                    percent = element.invite_num;
                                } else if (metric == 'surveycomplete_radio'
                                        || metric == 'surveynotfinished_radio'
                                        || metric == 'surveynotstarted_radio') {
                                    percent = element.complete_num;
                                } else if (metric == 'percentinvite_radio' || metric == 'percentinvite_radio') {
                                    percent = element.percentVal + '%';
                                } else if (metric == 'avgannualhours_radio') {
                                    percent = element.hours;
                                } else {
                                    percent = element.percent + '%';
                                }
                                if (element.field != "") {                    
                                    $('.rate_category .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                                <div class="bar-index flex justify-between items-center">
                                                                                    <div title="${element.field == 'xothers' ? 'All Other Categories' : element.field}">${ element.field == 'xothers' ? 'All Other Categories' : element.field }</div>
                                                                                    <div>${ element.invite_num } invites</div>
                                                                                </div>
                                                                                <div class="bar-graph flex items-center justify-start">
                                                                                    <div class="bg-revelation" style="width:calc(80% * ${element.percent} / 100);height:24px;"></div>
                                                                                    <span class="px-1">${percent}</span>
                                                                                </div>
                                                                            </div>`);
                                }
                            });

                            $('.rate_location .rate_title span').html(metric_text);
                            $('.rate_location .rate_body').empty();
                            res.location.forEach(element => {
                                let percent = 0;
                                if (metric == 'invites_radio') {
                                    percent = element.invite_num;
                                } else if (metric == 'surveycomplete_radio'
                                        || metric == 'surveynotfinished_radio'
                                        || metric == 'surveynotstarted_radio') {
                                    percent = element.complete_num;
                                } else if (metric == 'avgannualhours_radio') {
                                    percent = element.hours;
                                } else {
                                    percent = element.percent + '%';
                                }
                                if (element.field != "") {                    
                                    $('.rate_location .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                                <div class="bar-index flex justify-between items-center">
                                                                                    <div title="${element.field == 'xothers' ? 'All Other Cities' : element.field}">${ element.field == 'xothers' ? 'All Other Cities' : element.field }</div>
                                                                                    <div>${ element.invite_num } invites</div>
                                                                                </div>
                                                                                <div class="bar-graph flex items-center justify-start">
                                                                                    <div class="bg-revelation" style="width:calc(80% * ${element.percent} / 100);height:24px;"></div>
                                                                                    <span class="px-1">${percent}</span>
                                                                                </div>
                                                                            </div>`);
                                }
                            });

                            $('.rate_department .rate_title span').html(metric_text);
                            $('.rate_department .rate_body').empty();
                            res.department.forEach(element => {
                                let percent = 0;
                                if (metric == 'invites_radio') {
                                    percent = element.invite_num;
                                } else if (metric == 'surveycomplete_radio'
                                        || metric == 'surveynotfinished_radio'
                                        || metric == 'surveynotstarted_radio') {
                                    percent = element.complete_num;
                                } else if (metric == 'avgannualhours_radio') {
                                    percent = element.hours;
                                } else {
                                    percent = element.percent + '%';
                                }
                                if (element.field != "") {                    
                                    $('.rate_department .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                                <div class="bar-index flex justify-between items-center">
                                                                                    <div title="${element.field == 'xothers' ? 'All Other Departments' : element.field}">${ element.field == 'xothers' ? 'All Other Departments' : element.field }</div>
                                                                                    <div>${ element.invite_num } invites</div>
                                                                                </div>
                                                                                <div class="bar-graph flex items-center justify-start">
                                                                                    <div class="bg-revelation" style="width:calc(80% * ${element.percent} / 100);height:24px;"></div>
                                                                                    <span class="px-1">${percent}</span>
                                                                                </div>
                                                                            </div>`);
                                }
                            });

                            $('.rate_group .rate_title span').html(metric_text);
                            $('.rate_group .rate_body').empty();
                            res.group.forEach(element => {
                                let percent = 0;
                                if (metric == 'invites_radio') {
                                    percent = element.invite_num;
                                } else if (metric == 'surveycomplete_radio'
                                        || metric == 'surveynotfinished_radio'
                                        || metric == 'surveynotstarted_radio') {
                                    percent = element.complete_num;
                                } else if (metric == 'avgannualhours_radio') {
                                    percent = element.hours;
                                } else {
                                    percent = element.percent + '%';
                                }
                                if (element.field != "") {                    
                                    $('.rate_group .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                            <div class="bar-index flex justify-between items-center">
                                                                                <div title="${element.field == 'xothers' ? 'All Other Groups' : element.field}">${ element.field == 'xothers' ? 'All Other Groups' : element.field }</div>
                                                                                <div>${ element.invite_num } invites</div>
                                                                            </div>
                                                                            <div class="bar-graph flex items-center justify-start">
                                                                                <div class="bg-revelation" style="width:calc(80% * ${element.percent} / 100);height:24px;"></div>
                                                                                <span class="px-1">${percent}</span>
                                                                            </div>
                                                                        </div>`);
                                }
                            });

                            $('.rate_position .rate_title span').html(metric_text);
                            $('.rate_position .rate_body').empty();
                            res.position.forEach(element => {
                                let percent = 0;
                                if (metric == 'invites_radio') {
                                    percent = element.invite_num;
                                } else if (metric == 'surveycomplete_radio'
                                        || metric == 'surveynotfinished_radio'
                                        || metric == 'surveynotstarted_radio') {
                                    percent = element.complete_num;
                                } else if (metric == 'avgannualhours_radio') {
                                    percent = element.hours;
                                } else {
                                    percent = element.percent + '%';
                                }
                                if (element.field != "") {                    
                                    $('.rate_position .rate_body').append(`<div class="bar-item flex justify-between items-center ${ element.field == 'xothers' ? 'border-t border-gray-400' : '' }">
                                                                                <div class="bar-index flex justify-between items-center">
                                                                                    <div title="${element.field == 'xothers' ? 'All Other Titles' : element.field}">${ element.field == 'xothers' ? 'All Other Titles' : element.field }</div>
                                                                                    <div>${ element.invite_num } invites</div>
                                                                                </div>
                                                                                <div class="bar-graph flex items-center justify-start">
                                                                                    <div class="bg-revelation" style="width:calc(80% * ${element.percent} / 100);height:24px;"></div>
                                                                                    <span class="px-1">${percent}</span>
                                                                                </div>
                                                                            </div>`);
                                }
                            });


                            $('.bar-item').click(function () {
                                $progressBar = $(this).find('.progress-bar');
                                $titleBar = $(this).find('.bar-index');
                                if ($progressBar.hasClass('progress-bar-animated')) {
                                    $progressBar.removeClass('progress-bar-animated');
                                    $titleBar.css('background', 'none');
                                } else {
                                    $progressBar.addClass('progress-bar-animated');
                                    $titleBar.css('background', '#cce2f9');
                                }
                            });
                            $('.loading-mask').fadeOut();
                        },
                        error: function(request, error) {
                            alert("Request: " + JSON.stringify(request));
                        }
                    });
                } else {
                    alert("Please input only numbers!");
                    $("#min_rates").val("10 individuals");
                }
            }
        });

        /**
        * Generate pdf document of report
        *
        * @return {void}
        */
        function generatePDF () {
            let imgWidth = $('#demographicReportContent .first_part').outerWidth();
            pdfdoc = new jsPDF('p', 'mm', 'a4');
            imgHeight1 = Math.round($('#demographicReportContent .first_part').outerHeight() * 190 / imgWidth);
            y = 15;
            position = y;
            doc_page = 1;
            pdfdoc.addImage(imgData_1, 'JPEG', 10, y, 190, imgHeight1);
            imgHeight3 = Math.round($('#demographicReportContent .third_part').outerHeight() * 157 / imgWidth);
            y += imgHeight1;
            pdfdoc.addImage(imgData_3, 'JPEG', 10, y, 190, imgHeight3); 

            pageHeight = pdfdoc.internal.pageSize.height - 20;
            heightLeft = y - pageHeight;

            while (heightLeft >= -pageHeight) {
                position = heightLeft - imgHeight3;
                pdfdoc.addPage();
                doc_page++;
                pdfdoc.addImage(imgData_3, 'JPEG', 10, position, 190, imgHeight3);
                heightLeft -= pageHeight;
            }

            pdfdoc.deletePage(doc_page);

            for (i = 1; i < doc_page; i++) {
                pdfdoc.setPage(i);
                pdfdoc.addImage(headerData, 'JPEG', 10, 0, 190, 14); 
                pdfdoc.addImage(copyrightData, 'JPEG', 10, 282, 190, 14.5);
                pdfdoc.setTextColor(111,107,107); 
                pdfdoc.setFontSize(8); 
                pdfdoc.text('Page ' + i + ' of ' + (doc_page-1) , 168, 290, 0, 45);
            }

            pdfdoc.save(`{{$data['survey']->survey_name}}(Demographic Profile)`);
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .btn').attr('disabled', true);
        }
    </script>
@endsection
