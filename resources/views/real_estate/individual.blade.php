@extends('layouts.reports')
@section('content')




<div id="HelpContent" class="modal-body" style="display:none;">
<p><strong>Getting Started</strong></p>
<p>When you initially click on the Real Estate tab, you will notice that you have a multitude of
reports to look at. The information you will view may seem confusing at first, so it’s important to
take note of several points first.</p>

<p>To start off, you will be seeing the term <strong>‘Proximity Factor</strong>’ quite often. The proximity factor is required for each activity within the taxonomy, and each activity will either have a high,
medium, or low proximity factor. This will determine how costly an activity will be:</p>

<p><strong>High: Must be near customer</strong></p>
<p>Service provider must have regular personal interaction with the customer or the service provider
must have access to physical files etc. that are maintained by the customer. Proximity
requirement demands that they reside within the same floor of the same building.</p>

<p><strong>Medium: Needs to be relatively close to customer</strong></p>
<p>Periodic personal interactions are required for effective delivery of services. Service provider
does not need to be in the same space as customer but needs to be in close proximity so periodic
personal meetings can occur without requiring travel (so in the same building on a different floor,
or same city in a different building).</p>

<p><strong>Low: Does not need to be near customer</strong></p>
<p>Interfacing with customers using technology (phone, email, etc.) is sufficient. Collaboration
tools, web conferencing, and shared access to file systems are adequate to facilitate effective
interactions. Service providers can be located anywhere and can work virtually.</p>

<p>Another term that you will see is RSF Rates. This is the cost of the square footage that an
individual takes up. RSF Rates are required for each location. C stands for Current, which is also
a high proximity value. This is the most expensive RSF Rate. A stands for Adjacent, which
means that there is a medium proximity value, which is slightly less expensive. R and O stand
for Regional and Other, which are low proximity values, meaning that they are the cheapest.</p>
<img src="{{asset('imgs/user-guide/image-044.png')}}" alt="img">
<p>You will also see something called RSF Requirement per Participant. This is simply telling you
how much space an individual takes up. The more important the position, the more space they
will take up:</p>
<img src="{{asset('imgs/user-guide/image-045.png')}}" alt="img">


<p><strong>Individual Proximity</strong></p>
<p>The Individual Proximity Report is nearly identical to the ‘Individual Report’ located in the main
‘Reports’ section of our database. This report gives you a detailed job analysis for each
participant, including demographic information, as well as RSF allocation, rental rate, and total
cost of office space.</p>

<p>When you first enter this report, you will see a list of every employee that has completed the
survey. It is listed in alphabetical order. In order to view a specific individual’s report, simply
<strong>click on their name</strong>, and their report will be displayed. The default view of this report is sorted
by employees who have completed the survey, but you may change and modify that setting by
using the filters at the top of your screen. You may sort by Position, Department, Group,
Location, Category, and Survey Status.</p>

<p>When you are viewing the list of employees, you will notice that each name has varying degrees
of colors next to it. The <strong>Red Color means that the employee participates in activities that
have a High proximity factor</strong>. The <strong>Green color means that the employee participates in
activities that have a Medium proximity factor</strong>. Finally, the <strong>Blue color means that the
employee participates in activities that have a Low proximity factor</strong>. You will notice that
these colors are different for each employee listed. For example, if an employee has a significant
amount of green next to their name, a small amount of red, and a small amount of blue, this
means that they spend most of their time participating in Medium proximity activities, and only a
small amount of their time in High and Low proximity activities.</p>
<img src="{{asset('imgs/user-guide/image-051.png')}}" alt="img">
<p>To the right of this list, you will see a set of color coded percentages. These percentages correlate
to the list of names, and tell you the percentage of hours that every employee listed spends in
each activity. In other words, it is all of the data in the list combined into a set of statistics. Once
again,<strong> Red indicates a High proximity factor, Green indicates a Medium proximity factor,
and Blue indicates a Low proximity factor</strong>.</p>
<img src="{{asset('imgs/user-guide/image-052.png')}}" alt="img">
<p>When you want to view a specific individual’s report, simply click on their name, and their data
will be displayed. If the list is long, you have the option to search the individual by name by
using the Search Bar at the top of the list.</p>

<p>Once you have selected a specific individual, you will notice that the report now only shows
their unique data, as opposed to the entire firm’s data. You will be able to see their name, the date
that they took the survey, and their employee ID. Below that, you will also be able to see their
Group, Position, Department, and Location. Finally, below that, you will be able to see the RSF
that this individual occupies, their RSF Rate, and the RSF Cost, which is determined by
multiplying the rate and the RSF.</p>

<p>To the right of this data, you will notice a donut graph. This is simply the information for the
individual in the list, only it is in an easier-to-view format. You will be able to see how many of
their hours are spent in high, medium, and low proximity factor activities.</p>
<img src="{{asset('imgs/user-guide/image-053.png')}}" alt="img">
<p>If the majority of their graph shows the color Red, such as this graph, that tells you that this
individual spends a lot of their time working in High Proximity activities, meaning that you most
likely won’t save money if you were to move them. Please note that each graph is unique to each
individual.</p>

<p>As you scroll further down this report, you will notice that the next section you come to is called
<strong>‘Time by Substantive Area’</strong>. This is a breakdown of the donut chart, and shows you the
percentage of time that the individual spends in specific activities. Please note that this
breakdown is unique to each individual.</p>

<p>The last part of this report is called <strong>‘Complete Time Breakdown for (specific individual’</strong>. This
is a complete breakdown of a user’s time. It will show you the data for all High proximity,
Medium proximity, and Low proximity activities that an individual participates in. You will have
the option to expand all data, as well as collapse all data, so your viewing experience will be
easier, should you choose. You may also expand each activity manually.</p>
<img src="{{asset('imgs/user-guide/image-054.png')}}" alt="img">
<p>The columns will show you the proximity factor that an activity takes place in, the RSF for the
specific activities, and the RSF Cost for them. The last two columns will tell you the percentage
of hours that the individual spends in each activity, as well as the actual number of hours that that
percentage equates to.</p>

<p>When you expand a proximity factor, you will see all of the activities listed in that area. For
example, if you expand “High”, you will see all of the high proximity activities listed that the
user takes part in. You will notice that there are different shades of color as you view the full
report. The darker the color, the more time the individual spends participating in that activity. If
the color is white, or almost white, that means that the individual spends little, if any, of their
time in that specific activity.</p>

<p>Note: The Individual Report is designed as a tool to change individual behavior. It can be
modified to include/exclude RSF cost data so it can be shared with various levels of management
as well as the individual.</p>


</div>
        

<div class="container-fluid px-3 hideinhelppdf">  
    <div class="flex justify-between items-center cont-mtitle  mb-4">
        <h1 class="text-survey">Individual Proximity Report / {{ $survey->survey_name }}</h1>
        <div class="d-flex py-0 py-md-2">
        @if (\Auth::check() && \Auth::user()->hasPermission('surveyPrint', $survey))
            <button class="revnor-btn mr-1" id="pdfBtn">Download PDF</button>             
        @endif
        <button type="button" class="helpguidebtn mx-1" data-toggle="modal" data-target="#helpdetasurvey"></button> 
    </div> 
    </div>
    <div id="individualContent">
        <div class="first_part">
            @include('real_estate.partials.individualfilter') 
        </div>
        <div class="row second_part" style="padding:20px 0;">
            <div class="col-md-4" id="pdfhidden" >
                <div class="table-search">
                    <label for="searchResp" style="margin-bottom:0;margin-right:10px;">Search: </label>
                    <input type="text" id="searchResp" style="">
                <svg id="searchCloseBtn" class="searchCloseBtn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path d="M16 2C8.2 2 2 8.2 2 16s6.2 14 14 14s14-6.2 14-14S23.8 2 16 2zm0 26C9.4 28 4 22.6 4 16S9.4 4 16 4s12 5.4 12 12s-5.4 12-12 12z" fill="currentColor"/><path d="M21.4 23L16 17.6L10.6 23L9 21.4l5.4-5.4L9 10.6L10.6 9l5.4 5.4L21.4 9l1.6 1.6l-5.4 5.4l5.4 5.4z" fill="currentColor"/></svg>
                </div>
                <div class="overhide-div">
                <div class="respondentListParent"> 
                    <div id="respondentList">
                        @foreach ($data['resps'] as $resp)
                            @php
                                $high_percent   = $resp->total_hours > 0 ? round(100 * $resp->prox_high_hours / $resp->total_hours) : 0;
                                $medium_percent = $resp->total_hours > 0 ? round(100 * $resp->prox_medium_hours / $resp->total_hours) : 0;
                                $virtual_percent = $resp->total_hours > 0 ? round(100 * $resp->prox_virtual_hours / $resp->total_hours) : 0;
                                $low_percent    = $resp->prox_low_hours > 0 ? 100 - $high_percent - $medium_percent : 0;
                            //  dd($high_percent,  $medium_percent, $virtual_percent , $low_percent )
                            @endphp
                            
                            <div class="resp_item row resp-{{ $resp->resp_id }}" id="respondantId-{{$resp->resp_id}}" onclick="selectRespondent({{$resp->resp_id}}, {{$resp->support_hours}}, {{$resp->legal_hours}}, {{ $low_percent }}, {{ $medium_percent }}, {{ $high_percent }}, {{$virtual_percent}});">
                                <div class="col-5 text-right" title="{{$resp->resp_last}}, {{$resp->resp_first}}">{{$resp->resp_last}}, {{$resp->resp_first}}</div>
                                <div class="col-7">
                                    <div class="high-bar" data-percent="{{ $high_percent }}" style="width:{{$high_percent}}%;"></div>
                                    <div class="medium-bar" data-percent="{{ $medium_percent }}" style="width:{{$medium_percent}}%;"></div>
                                    <div class="virtual-bar" data-percent="{{ $virtual_percent }}" style="width:{{$virtual_percent}}%;background-color:yellow;"></div>
                                    <div class="low-bar" data-percent="{{ $low_percent }}" style="width:{{$low_percent}}%;"></div>
                            

                                </div>
                            </div>
                        @endforeach
                    </div> 
                </div>
</div>
            </div>
            <div class="col-md-4 px-0 real-individualres" id="respondent_data">
                <div class="flex items-center h-full">
                    &larr; Select a respondent from the list on the left to get started.
                </div>
            </div>
           
            <div class="col-md-4 px-2 flex items-center justify-between">
                <div id="chartContainer" style="height: 250px; width:50%;"></div>
                <div style="width: 50%;">
                    <div class="info-high">
                        <span>{{ $data['rsf_percent_data']['high_percent'] }}%</span> of hours have a <strong>High</strong> proximity factor
                    </div>
                    <div class="info-med">
                        <span>{{ $data['rsf_percent_data']['med_percent'] }}%</span> of hours have a <strong>Med</strong> proximity factor
                    </div>
                    <div class="info-low">
                        <span>{{ $data['rsf_percent_data']['low_percent'] }}%</span> of hours have a <strong>Low</strong> proximity factor
                    </div>
                    <div class="info-virtual">
                        <span background-color:yellow;>{{ $data['rsf_percent_data']['virtual_percent'] }}%</span> of hours have a <strong>Virtual</strong> proximity factor
                    </div>
                   
                    
                </div>
            </div>
        </div>
        <div class="fourth_part" style="background:white;">
            <div style="width:100%;padding-bottom:0;">
                <div style="border-top:6px solid #f2f2f2;"></div>
                <div id="individualReportContainer" style="display:none;background:white;z-index:10;padding-top:20px;">
                    <div class="px-2 mt-10" style="background: white;">
                        <h3>Time by Substantive Area</h3>
                        <div class="canvaschartouter">
                        <div id="CategoryChartContainer" style="height:550px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="third_part pt-4" style="display: none;">
            <div class="py-8 px-2 overflow-x-auto" style="padding-top:0.6rem;">
                <h3>Complete Time Breakdown for <strong id="resp_name"></strong></h3>
                <div class="btnArea-tree">
                    <div>
                        <button id="expendTreeTable" class="btn btn-revelation-primary btn-sm" onclick="expendTreeTable();">Collapse All</button>
                        <button id="collapseTreeTable" class="btn btn-revelation-primary btn-sm" onclick="collapseTreeTable();">Expand All</button>
                    </div>
                </div>
                <div class="breakdownTableArea">
                    <table id="breakdownTable"></table>
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
            <p class="text-phead">Individual Proximity Report / {{ $survey->survey_name }}</p> 
            <p class="redtext-phead">Confidential</p>
            {{-- <img  src="{{asset('imgs/logo-pdfhead.png')}}">   --}}
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
    <p><strong>Getting Started</strong></p>
<p>When you initially click on the Real Estate tab, you will notice that you have a multitude of
reports to look at. The information you will view may seem confusing at first, so it’s important to
take note of several points first.</p>

<p>To start off, you will be seeing the term <strong>‘Proximity Factor</strong>’ quite often. The proximity factor is required for each activity within the taxonomy, and each activity will either have a high,
medium, or low proximity factor. This will determine how costly an activity will be:</p>

<p><strong>High: Must be near customer</strong></p>
<p>Service provider must have regular personal interaction with the customer or the service provider
must have access to physical files etc. that are maintained by the customer. Proximity
requirement demands that they reside within the same floor of the same building.</p>

<p><strong>Medium: Needs to be relatively close to customer</strong></p>
<p>Periodic personal interactions are required for effective delivery of services. Service provider
does not need to be in the same space as customer but needs to be in close proximity so periodic
personal meetings can occur without requiring travel (so in the same building on a different floor,
or same city in a different building).</p>

<p><strong>Low: Does not need to be near customer</strong></p>
<p>Interfacing with customers using technology (phone, email, etc.) is sufficient. Collaboration
tools, web conferencing, and shared access to file systems are adequate to facilitate effective
interactions. Service providers can be located anywhere and can work virtually.</p>

<p>Another term that you will see is RSF Rates. This is the cost of the square footage that an
individual takes up. RSF Rates are required for each location. C stands for Current, which is also
a high proximity value. This is the most expensive RSF Rate. A stands for Adjacent, which
means that there is a medium proximity value, which is slightly less expensive. R and O stand
for Regional and Other, which are low proximity values, meaning that they are the cheapest.</p>
<img src="{{asset('imgs/user-guide/image-044.png')}}" alt="img">
<p>You will also see something called RSF Requirement per Participant. This is simply telling you
how much space an individual takes up. The more important the position, the more space they
will take up:</p>
<img src="{{asset('imgs/user-guide/image-045.png')}}" alt="img">


<p><strong>Individual Proximity</strong></p>
<p>The Individual Proximity Report is nearly identical to the ‘Individual Report’ located in the main
‘Reports’ section of our database. This report gives you a detailed job analysis for each
participant, including demographic information, as well as RSF allocation, rental rate, and total
cost of office space.</p>

<p>When you first enter this report, you will see a list of every employee that has completed the
survey. It is listed in alphabetical order. In order to view a specific individual’s report, simply
<strong>click on their name</strong>, and their report will be displayed. The default view of this report is sorted
by employees who have completed the survey, but you may change and modify that setting by
using the filters at the top of your screen. You may sort by Position, Department, Group,
Location, Category, and Survey Status.</p>

<p>When you are viewing the list of employees, you will notice that each name has varying degrees
of colors next to it. The <strong>Red Color means that the employee participates in activities that
have a High proximity factor</strong>. The <strong>Green color means that the employee participates in
activities that have a Medium proximity factor</strong>. Finally, the <strong>Blue color means that the
employee participates in activities that have a Low proximity factor</strong>. You will notice that
these colors are different for each employee listed. For example, if an employee has a significant
amount of green next to their name, a small amount of red, and a small amount of blue, this
means that they spend most of their time participating in Medium proximity activities, and only a
small amount of their time in High and Low proximity activities.</p>
<img src="{{asset('imgs/user-guide/image-051.png')}}" alt="img">
<p>To the right of this list, you will see a set of color coded percentages. These percentages correlate
to the list of names, and tell you the percentage of hours that every employee listed spends in
each activity. In other words, it is all of the data in the list combined into a set of statistics. Once
again,<strong> Red indicates a High proximity factor, Green indicates a Medium proximity factor,
and Blue indicates a Low proximity factor</strong>.</p>
<img src="{{asset('imgs/user-guide/image-052.png')}}" alt="img">
<p>When you want to view a specific individual’s report, simply click on their name, and their data
will be displayed. If the list is long, you have the option to search the individual by name by
using the Search Bar at the top of the list.</p>

<p>Once you have selected a specific individual, you will notice that the report now only shows
their unique data, as opposed to the entire firm’s data. You will be able to see their name, the date
that they took the survey, and their employee ID. Below that, you will also be able to see their
Group, Position, Department, and Location. Finally, below that, you will be able to see the RSF
that this individual occupies, their RSF Rate, and the RSF Cost, which is determined by
multiplying the rate and the RSF.</p>

<p>To the right of this data, you will notice a donut graph. This is simply the information for the
individual in the list, only it is in an easier-to-view format. You will be able to see how many of
their hours are spent in high, medium, and low proximity factor activities.</p>
<img src="{{asset('imgs/user-guide/image-053.png')}}" alt="img">
<p>If the majority of their graph shows the color Red, such as this graph, that tells you that this
individual spends a lot of their time working in High Proximity activities, meaning that you most
likely won’t save money if you were to move them. Please note that each graph is unique to each
individual.</p>

<p>As you scroll further down this report, you will notice that the next section you come to is called
<strong>‘Time by Substantive Area’</strong>. This is a breakdown of the donut chart, and shows you the
percentage of time that the individual spends in specific activities. Please note that this
breakdown is unique to each individual.</p>

<p>The last part of this report is called <strong>‘Complete Time Breakdown for (specific individual’</strong>. This
is a complete breakdown of a user’s time. It will show you the data for all High proximity,
Medium proximity, and Low proximity activities that an individual participates in. You will have
the option to expand all data, as well as collapse all data, so your viewing experience will be
easier, should you choose. You may also expand each activity manually.</p>
<img src="{{asset('imgs/user-guide/image-054.png')}}" alt="img">
<p>The columns will show you the proximity factor that an activity takes place in, the RSF for the
specific activities, and the RSF Cost for them. The last two columns will tell you the percentage
of hours that the individual spends in each activity, as well as the actual number of hours that that
percentage equates to.</p>

<p>When you expand a proximity factor, you will see all of the activities listed in that area. For
example, if you expand “High”, you will see all of the high proximity activities listed that the
user takes part in. You will notice that there are different shades of color as you view the full
report. The darker the color, the more time the individual spends participating in that activity. If
the color is white, or almost white, that means that the individual spends little, if any, of their
time in that specific activity.</p>

<p>Note: The Individual Report is designed as a tool to change individual behavior. It can be
modified to include/exclude RSF cost data so it can be shared with various levels of management
as well as the individual.</p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>   


    <div class="modal fade" tabindex="-1" role="dialog" id="selectRespModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-body">
                <p>Please select the respondent.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-revelation-primary" data-dismiss="modal">OK</button>
            </div>
            </div>
        </div>
    </div>
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
    <div class="loading-mask"></div>
</div>
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


        var survey_id = @php echo $data['survey']->survey_id; @endphp;

        var highColor = "#e15659";
        var medColor = "#77B55A";
        var lowColor = "#4e7aa5"; 
        var virtualColor = "#ffd700"; 
 
        // var highColor = "#e15659";
        // var medColor = "#77B55A";
        // var lowColor = "#367BC1";

        var treeTableDepth = 1;
        var selectedResp = 0;
        var responseTreeTable = {};
        var imgData_1, imgData_2, imgData_3, imgData_4, copyrightData, headerData;

        let numberFormatter = new Intl.NumberFormat('en-US');

        function selectRespondent (resp_id, support_hours = 0, legal_hours = 0, $low_percent = 0, $med_percent = 0, $high_percent = 0, $virtual_percent = 0) {
            selectedResp = resp_id;
            mask_height = $('body').height();
            $('.loading-mask').css('height', mask_height);
            $('.loading-mask').fadeIn();
            let total_hours = legal_hours + support_hours;

            let high_percent = parseInt($(`.resp-${resp_id} .high-bar`).attr('data-percent'));
            let med_percent = parseInt($(`.resp-${resp_id} .medium-bar`).attr('data-percent'));
            let low_percent = parseInt($(`.resp-${resp_id} .low-bar`).attr('data-percent'));
            let virtual_percent = parseInt($(`.resp-${resp_id} .virtual-bar`).attr('data-percent'));

            $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
            });
            $.ajax({
                url: '{{ route('realestate-getRespData') }}',
                data: {
                    'resp_id': resp_id,
                    'survey_id': survey_id,
                    'low_percent': low_percent,
                    'med_percent': med_percent,
                    'high_percent': high_percent,
                },
                type: 'POST',
                dataType: 'json',
                success: function(data) {
                    $('body .resp_item').css('background','none');
                    $("body #respondantId-" + selectedResp).css('background','rgba(242, 242, 242, 1)');
                    $('#individualReportContainer').css('display', 'block');
                    $('.third_part').css('display', 'block');
                    let formatter = new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD',
                        minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
                        maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
                    });
                    $("#respondent_data").html("");
                    $("#detailHoursTable").empty();
                    let resp = data.resp;
                    let resp_report_data = data.resp_report_data;
                    let benefit_pct = resp.resp_benefit_pct * 100;

                    let personalInfoHtml = `
                        <h3>${resp.resp_last}, ${resp.resp_first}</h3>
                        <p>
                            Survey Taken On: <strong>${resp.last_dt}</strong><br>
                            Email: <strong>${resp.resp_email}</strong><br>
                            Employee ID: <strong>${resp.cust_1}</strong><br>
                        </p>
                        <p>
                            Group: <strong>${resp.cust_2}</strong><br>
                            Position: <strong>${resp.cust_3}</strong><br>
                            Department: <strong>${resp.cust_4}</strong><br>
                            Location: <strong>${resp.cust_6}</strong><br>
                        </p>
                        <p>
                            <div class="flex items-center justify-between" style="width:70%;">
                                <div>RSF</div>
                                <div>${numberFormatter.format(resp.rentable_square_feet)}</div>
                            </div>
                            <div class="flex items-center justify-between" style="width:70%;">
                                <div>RSF Rate</div>
                                <div>${numberFormatter.format(resp.rsf_rate)}</div>
                            </div>
                            <div class="flex items-center justify-between" style="width:70%;">
                                <div>RSF Cost</div>
                                <div>${numberFormatter.format(resp.rsf_cost)}</div>
                            </div>
                        </p>
                    `;

                    $("#respondent_data").html(personalInfoHtml);

                    $('#resp_name').html(resp.resp_last + ', ' + resp.resp_first);

                    $('.info-high span').text(high_percent + '%');
                    $('.info-med span').text(med_percent + '%');
                    $('.info-low span').text(low_percent + '%');
                    $('.info-virtual span').text(virtual_percent + '%');
                    
                    var chart = new CanvasJS.Chart("chartContainer", {
                        toolTip: {
                            enabled: false,
                        },
                        data: [{
                            type: "doughnut",
                            showInLegend: false,
                            dataPoints: [{
                                    y: high_percent,
                                    color: highColor,
                                },
                                {
                                    y: med_percent,
                                    color: medColor,
                                },
                                {
                                    y: low_percent,
                                    color: lowColor,
                                },
                                {
                                    y: virtual_percent,
                                    color: virtualColor,
                                }
                            ]
                        }]
                    });
                    chart.render();

                    tmpServiceAry = new Array();
                    tmpCategoryAry = new Array();
                    let maxCategoryValue = 0;
                    if (resp_report_data.categoryData.length > 0) {
                        let numTmp = 0;
                        resp_report_data.categoryData.forEach(element => {
                            child2_percent = Math.round(element.hours / total_hours * 100);
                            let tmpColor = lowColor;
                            switch (element.prox_factor) {
                                case 4:
                                    tmpColor = virtualColor;
                                    break;
                            
                                case 3:
                                    tmpColor = highColor;
                                    break;
                            
                                case 2:
                                    tmpColor = medColor;
                                    break;
                            
                                case 1:
                                    tmpColor = lowColor;
                                    break;
                            
                                default:
                                    break;
                            }
                            tmpObj = {
                                        y: element.hours,
                                        label: element.question_desc,
                                        toolTipContent: `<div>
                                                            <p style="margin-bottom:3px;padding-bottom:0;">${element.top_parent} | ${element.parent}</p>
                                                            <h5 style="margin-top:0;padding-top:0;margin-bottom:20px;padding-bottom:0;">${element.question_desc}</h5>
                                                            <p style="margin-bottom:5px;">Hours: <strong>${numberFormatter.format(element.hours)} (${child2_percent}% of all)</strong></p>
                                                        </div>`,
                                        color: tmpColor,
                                        indexLabel: child2_percent + '%',
                                        indexLabelPlacement: "outside",
                                        indexLabelFontSize: 14,
                                        grandParent: element.grandParent,
                                    };
                            if (element.hours > maxCategoryValue) {
                                maxCategoryValue = element.hours;
                            }
                            tmpCategoryAry.push(tmpObj);
                            numTmp++;
                        });
                        
                        chartHeight = 30 * numTmp + 20;
                        $('#CategoryChartContainer').css('height', chartHeight + 'px');

                        var categoryChart = new CanvasJS.Chart("CategoryChartContainer", {
                            dataPointWidth: 25,
                            backgroundColor: "rgba(245, 222, 179, 0.4)",
                            axisX: {
                                interval: 1,
                                labelFontSize: 13,
                                margin: 20,
                            },
                            axisY: {
                                maximum: maxCategoryValue,
                                includeZero: true,
                                gridThickness: 0,
                                tickThickness: 0,
                                labelFontSize: 0,
                                margin: 5,
                            },
                            data: [{
                                type: "bar",
                                dataPoints: tmpCategoryAry
                            }]
                        });

                        categoryChart.render();
                    } else {
                        $("#CategoryChartContainer").parent().hide();
                    }

                    if (resp_report_data.tableData.length > 0) {
                        var total_hours_display = 0;
                        for (const i in resp_report_data.tableData) {
                            total_hours_display = resp_report_data.tableData[i].total_hours;
                            resp_report_data.tableData[i].rsf = Math.round(resp.rentable_square_feet * resp_report_data.tableData[i].hours / resp_report_data.tableData[i].total_hours);
                            resp_report_data.tableData[i].rsf_cost = Math.round(resp.rsf_cost * resp_report_data.tableData[i].hours / resp_report_data.tableData[i].total_hours);
                        }
                        $('.breakdownTableArea').empty();
                        $('.breakdownTableArea').append(`<table id="breakdownTable"></table>`);
                        $table = $('#breakdownTable');
                        console.log(resp_report_data.tableData);
                        $table.bootstrapTable({
                            data: resp_report_data.tableData,
                            idField: 'id',
                            // showColumns: true,
                            columns: [
                                {
                                    field: 'question',
                                    title: 'Question'
                                },
                                {
                                    field: 'prox_factor',
                                    title: 'Proximity Factor',
                                    align: 'right',
                                    sortable: false,
                                    formatter: 'proxFactor'
                                },
                                {
                                    field: 'rsf',
                                    title: 'RSF',
                                    align: 'right',
                                    sortable: false,
                                    formatter: 'numberFormat'
                                },
                                {
                                    field: 'rsf_cost',
                                    title: 'RSF Cost',
                                    align: 'right',
                                    sortable: false,
                                    formatter: 'numberFormat'
                                },
                                {
                                    field: 'percent_hour',
                                    title: 'Percent Of Hours',
                                    align: 'right',
                                    sortable: false,
                                    formatter: 'prefixPercent'
                                },
                                {
                                    field: 'hours',
                                    title: 'Hours',
                                    align: 'right',
                                    sortable: false,
                                    formatter: 'numberFormat'
                                },
                                // {
                                //     field: 'cost',
                                //     title: 'Cost',
                                //     align: 'right',
                                //     sortable: false,
                                //     formatter: 'costFormat'
                                // }
                            ],
                            treeShowField: 'question',
                            parentIdField: 'pid',
                            onPostBody: function() {
                                var columns = $table.bootstrapTable('getOptions').columns

                                if (columns && columns[0][1].visible) {
                                    $table.treegrid({
                                        treeColumn: 0,
                                        lines: true,
                                        animate: true,
                                        onChange: function() {
                                            $table.bootstrapTable('resetView')
                                        }
                                    })
                                }
                            }
                        });

                        resp_report_data.tableData.forEach(item => {
                            let color = lowColor;
                            switch (item.prox_factor) {
                                case 1:
                                    color = lowColor;
                                    break;
                            
                                case 2:
                                    color = medColor;
                                    break;
                            
                                case 3:
                                    color = highColor;
                                    break;
                                
                                case 4:
                                    color = virtualColor;
                                    break;
                                
                                default:
                                    break;
                            }
                            let rgbaCol = 'rgba(' + parseInt(color.slice(-6,-4),16)
                                + ',' + parseInt(color.slice(-4,-2),16)
                                + ',' + parseInt(color.slice(-2),16)
                                +',' + (item.percent_hour / 100) + ')';
                            $(`.treegrid-${item.id}`).css('background-color', rgbaCol);
                        });

                        $table.find('thead').append(`<tr>
                                <th class="px-2">Grand Total</th>
                                <th></th>
                                <th class="px-2 text-right">${resp.rentable_square_feet}</th>
                                <th class="px-2 text-right">${resp.rsf_cost}</th>
                                <th class="px-2 text-right">100%</th>
                                <th class="px-2 text-right">${numberFormatter.format(total_hours_display)}</th>
                            </tr>`);

                        $table.find('tr').each(function () {
                            let classStr = String($(this).attr('class'));
                            if (classStr.includes("treegrid-parent")) {
                                $(this).css('display', 'none');
                                if ($(this).hasClass('treegrid-expanded')) {
                                    $(this).removeClass('treegrid-expanded');
                                    $(this).addClass('treegrid-collapsed');
                                    $(this).find('.treegrid-expander').removeClass('treegrid-expander-expanded')
                                    $(this).find('.treegrid-expander').addClass('treegrid-expander-collapsed')
                                }
                            } else {
                                $(this).removeClass('treegrid-expanded');
                                $(this).addClass('treegrid-collapsed');
                                $(this).find('.treegrid-expander').removeClass('treegrid-expander-expanded')
                                $(this).find('.treegrid-expander').addClass('treegrid-expander-collapsed')
                            }
                        });
                    } else {
                        $('#breakdownTable').parent().parent().hide();
                    }

                    if (maxCategoryValue > 90) {
                        maxCategoryValue = 100;
                    } else {
                        maxCategoryValue = maxCategoryValue + 10
                    }

                    $('.loading-mask').fadeOut();
                },
                error: function(request, error) {
                    console.log(request);
                    console.log(error);
                    alert("Request: " + JSON.stringify(request));
                }
            });
        }

        function expendTreeTable () {
            $('#breakdownTable').find('tr').each(function () {
                let classStr = String($(this).attr('class'));
                if (classStr.includes("treegrid-parent")) {
                    $(this).css('display', 'none');
                    if ($(this).hasClass('treegrid-expanded')) {
                        $(this).removeClass('treegrid-expanded');
                        $(this).addClass('treegrid-collapsed');
                        $(this).find('.treegrid-expander').removeClass('treegrid-expander-expanded')
                        $(this).find('.treegrid-expander').addClass('treegrid-expander-collapsed')
                    }
                } else {
                    $(this).removeClass('treegrid-expanded');
                    $(this).addClass('treegrid-collapsed');
                    $(this).find('.treegrid-expander').removeClass('treegrid-expander-expanded')
                    $(this).find('.treegrid-expander').addClass('treegrid-expander-collapsed')
                }
            });
        }

        function collapseTreeTable () {
            $('#breakdownTable').find('tr').each(function () {
                let classStr = String($(this).attr('class'));
                if (classStr.includes("treegrid-parent")) {
                    $(this).css('display', 'table-row');
                    if ($(this).hasClass('treegrid-collapsed')) {
                        $(this).removeClass('treegrid-collapsed');
                        $(this).addClass('treegrid-expanded');
                        $(this).find('.treegrid-expander').removeClass('treegrid-expander-collapsed')
                        $(this).find('.treegrid-expander').addClass('treegrid-expander-expanded')
                    }
                } else {
                    $(this).removeClass('treegrid-collapsed');
                    $(this).addClass('treegrid-expanded');
                }
            });
        }

        /**
        * Return formatted number
        *
        * @param {number} x
        * @return {string}
        */
        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function containsObject(obj, list, field) {
            switch (field) {
                case 'child1':
                    if(list.some(list => list.child1 === obj.child1)){
                        return true;
                    } else{
                        return false;
                    }
                    break;
                case 'child2':
                    if(list.some(list => list.label === obj.label)){
                        return true;
                    } else{
                        return false;
                    }
                    break;

                default:
                    return false;
                    break;
            }

        }

        // Handle the pdf button click, generate image data from the body
        $('#pdfBtn').click(function () {
            $('#headerDiv').show();
            $("#pdfhidden").hide();
            $('#copyright_div').addClass('d-flex');
			$('#copyright_div').show();	 			
            var respondent_name = $('#respondent_data').find('h3').text();
            if (respondent_name != '') {
                $('#generatePDFModal').modal('show');
                source = $('#individualContent .first_part');
                html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    imgData_1 = canvas.toDataURL("image/png", 1.0);
                        
                });
                source = $('#individualContent .second_part');
                html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    imgData_2 = canvas.toDataURL("image/png", 1.0);
                        
                });
                source = $('#individualContent .fourth_part');
                html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    imgData_4 = canvas.toDataURL("image/png", 1.0);
                        
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
                source = $('#individualContent .third_part');
                html2canvas(source, {
                    onrendered: function (canvas) {
                        imgData_3 = canvas.toDataURL('image/jpeg', 1.0)
                    }
                }).then(function () {
                    $('#generatePDFModal .modal-body').html(`<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="24" height="24" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path d="M30 18v-2h-6v10h2v-4h3v-2h-3v-2h4z" fill="currentColor"/><path d="M19 26h-4V16h4a3.003 3.003 0 0 1 3 3v4a3.003 3.003 0 0 1-3 3zm-2-2h2a1.001 1.001 0 0 0 1-1v-4a1.001 1.001 0 0 0-1-1h-2z" fill="currentColor"/><path d="M11 16H6v10h2v-3h3a2.003 2.003 0 0 0 2-2v-3a2.002 2.002 0 0 0-2-2zm-3 5v-3h3l.001 3z" fill="currentColor"/><path d="M22 14v-4a.91.91 0 0 0-.3-.7l-7-7A.909.909 0 0 0 14 2H4a2.006 2.006 0 0 0-2 2v24a2 2 0 0 0 2 2h16v-2H4V4h8v6a2.006 2.006 0 0 0 2 2h6v2zm-8-4V4.4l5.6 5.6z" fill="currentColor"/></svg> &nbsp; Generated a PDF`);
                    $('#generatePDFModal .btn').attr('disabled', false);
                    $('#headerDiv').hide();
                    $("#pdfhidden").show();
					$('#copyright_div').removeClass('d-flex');
			        $('#copyright_div').hide();	     					
                });
            } else {
                $('#selectRespModal').modal();
                $('#headerDiv').hide();
                $("#pdfhidden").show();
                $('#copyright_div').removeClass('d-flex');
                $('#copyright_div').hide();	   
            }
        });

        $('#searchResp').keyup(function () {
            let search_key = $('#searchResp').val();
            if (search_key == '') {
                $('.searchCloseBtn').css('display', 'none');
            } else {
                $('.searchCloseBtn').css('display', 'block');
            }
        });

        $('#searchResp').keypress(function (e) {
            let search_key = $('#searchResp').val();
            var keycode = parseInt(e.keyCode ? e.keyCode : e.which);
            if (keycode == 13) {
                $('#respondentList').html('<div class="text-center">Loading...</div>');
                $.ajax({
                    url: '{{ route('realestate-getRespList') }}',
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "survey_id": survey_id,
                        "search_key": search_key,
                        "position": JSON.stringify(options['position']),
                        "department": JSON.stringify(options['department']),
                        "group": JSON.stringify(options['group']),
                        "location": JSON.stringify(options['location']),
                        "category": JSON.stringify(options['category']),
                        "survey_status": JSON.stringify(options['survey_status']),
                    },
                    dataType: 'json',
                    success: function (data) {
                        var respDataAry = data;
                        $('#respondentList').empty();
                        respDataAry.forEach(resp => {
                            let high_percent = resp.total_hours > 0 ? Math.round(100 * resp.prox_high_hours / resp.total_hours) : 0;
                            let med_percent = resp.total_hours > 0 ? Math.round(100 * resp.prox_medium_hours / resp.total_hours) : 0;
                            let low_percent = resp.prox_low_hours > 0 ? 100 - high_percent - med_percent : 0;
                            $('#respondentList').append(`<div class="resp_item row resp-${resp.resp_id}" onclick="selectRespondent(${resp.resp_id}, ${resp.support_hours}, ${resp.legal_hours}, ${low_percent}, ${med_percent}, ${med_percent});">
                                                            <div class="col-5 text-right" title="${resp.resp_last}, ${resp.resp_first}">${resp.resp_last}, ${resp.resp_first}</div>
                                                            <div class="col-7">
                                                                <div class="high-bar" data-percent="${high_percent}" style="width:${high_percent}%;"></div>
                                                                <div class="medium-bar" data-percent="${med_percent}" style="width:${med_percent}%;"></div>
                                                                <div class="low-bar" data-percent="${low_percent}" style="width:${low_percent}%;"></div>
                                                            </div>
                                                        </div>`);
                        });
                    },
                    error: function(request, error) {
                        alert("Request: " + JSON.stringify(request));
                    }
                });
            }
        });

        $('#searchCloseBtn').click(function () {
            $('#searchResp').val('');
            let search_key = $('#searchResp').val();
            $('#respondentList').html('<div class="text-center">Loading...</div>');
            $.ajax({
                url: '{{ route('realestate-getRespList') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "search_key": search_key,
                    "position": JSON.stringify(options['position']),
                    "department": JSON.stringify(options['department']),
                    "group": JSON.stringify(options['group']),
                    "location": JSON.stringify(options['location']),
                    "category": JSON.stringify(options['category']),
                    "survey_status": JSON.stringify(options['survey_status']),
                },
                dataType: 'json',
                success: function (data) {
                    var respDataAry = data;
                    $('#respondentList').empty();
                    respDataAry.forEach(resp => {
                        let high_percent = resp.total_hours > 0 ? Math.round(100 * resp.prox_high_hours / resp.total_hours) : 0;
                        let med_percent = resp.total_hours > 0 ? Math.round(100 * resp.prox_medium_hours / resp.total_hours) : 0;
                        let low_percent = resp.prox_low_hours > 0 ? 100 - high_percent - med_percent : 0;
                        $('#respondentList').append(`<div class="resp_item row resp-${resp.resp_id}" onclick="selectRespondent(${resp.resp_id}, ${resp.support_hours}, ${resp.legal_hours}, ${low_percent}, ${med_percent}, ${high_percent});">
                                                            <div class="col-5 text-right" title="${resp.resp_last}, ${resp.resp_first}">${resp.resp_last}, ${resp.resp_first}</div>
                                                            <div class="col-7">
                                                                <div class="high-bar" data-percent="${high_percent}" style="width:${high_percent}%;"></div>
                                                                <div class="medium-bar" data-percent="${med_percent}" style="width:${med_percent}%;"></div>
                                                                <div class="low-bar" data-percent="${low_percent}" style="width:${low_percent}%;"></div>
                                                            </div>
                                                        </div>`);
                    });
                    $('#searchCloseBtn').css('display', 'none');
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        });

        $('#generatePDFModal').on('hidden.bs.modal', function () {
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .modal-body').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp; Generating PDF...`);
            $('#generatePDFModal .btn').attr('disabled', true);
        });

        /**
        * Return formatted percentage value
        *
        *@param {number} value
        *@param {number} row
        *@param {number} index
        *@return {string}
        */
        function prefixPercent (value, row, index) {
            return value + '%';
        }

        /**
        * Return formatted number value
        *
        *@param {number} value
        *@param {number} row
        *@param {number} index
        *@return {string}
        */
        function numberFormat (value, row, index) {
            return numberWithCommas(value);
        }

        /**
        * Return formatted currency value
        *
        *@param {number} value
        *@param {number} row
        *@param {number} index
        *@return {string}
        */
        function costFormat (value, row, index) {
            return '$' + numberWithCommas(value);
        }

        function proxFactor (value, row, index) {
            switch (value) {
                case 1:
                    return 'Low';
                    break;
            
                case 2:
                    return 'Med';
                    break;
            
                case 3:
                    return 'High';
                    break;
            
                default:
                    return 'Low';
                    break;
            }
        }

        /**
        * Generate pdf document of report
        *
        * @return {void}
        */
        function generatePDF () {
            var respondent_name = $('#respondent_data').find('h3').text();
            let imgWidth = $('#individualContent .first_part').outerWidth();
            pdfdoc = new jsPDF('p', 'mm', 'a4');
            imgHeight1 = Math.round($('#individualContent .first_part').outerHeight() * 190 / imgWidth);
            y = 16;  
            position = y;  
            doc_page = 1;

            // pdfdoc.addImage(imgData_1, 'JPEG', 10, y, 190, imgHeight1);
            // y += imgHeight1;

            imgHeight2 = Math.round($('#individualContent .second_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_2, 'JPEG', 10, y, 190, imgHeight2); 
            y += imgHeight2;

            imgHeight4 = Math.round($('#individualContent .fourth_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_4, 'JPEG', 10, y, 190, imgHeight4);
            y += imgHeight4;
            /* imgHeight5 = Math.round($('#copyright_div').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_4, 'JPEG', 10, y, 190, imgHeight5);
            y += imgHeight5; */

            imgHeight3 = Math.round($('#individualContent .third_part').outerHeight() * 190 / imgWidth);

            pdfdoc.addPage();
            doc_page++;
            pdfdoc.addImage(imgData_3, 'JPEG', 10, 15, 190, imgHeight3);

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

            pdfdoc.save(`{{$data['survey']->survey_name}}(${respondent_name})`);
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .btn').attr('disabled', true);
        }
    </script>

@endsection
