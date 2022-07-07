@extends('layouts.realestatereports')
@section('content')
<style>
    .table-borderless > tbody > tr > td,
    .table-borderless > tbody > tr > th,
    .table-borderless > tfoot > tr > td,
    .table-borderless > tfoot > tr > th,
    .table-borderless > thead > tr > td,
    .table-borderless > thead > tr > th {
        border: none;
    }
</style> 




<div id="HelpContent" class="modal-body" style="display:none;">
<p><strong>Getting Started</strong></p>
<p>When you initially click on the Real Estate tab, you will notice that you have a multitude of
reports to look at. The information you will view may seem confusing at first, so it’s important to
take note of several points first.</p>

<p>To start off, you will be seeing the term <strong>‘Proximity Factor</strong>’ quite often. The proximity factor is required for each activity within the taxonomy, and each activity will either have a high,
medium,virtual or low proximity factor. This will determine how costly an activity will be:</p>

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

<p><strong>Virtual: Need to be customer</strong></p>
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


<p><strong>Participant Proximity</strong></p>
<p>The Proximity Report provides you with an overview of the annual hours reported by each
participant. Each participant’s hours are allocated by the activity’s respective proximity factor.
When you first enter this report, you will see that the default view is unfiltered. However, if you
would like to view a more specific report, you may do so by using the filters located at the top of
your screen. The<strong> filters located on the right</strong> allow for you to sort by <strong>demographic</strong>, while the <strong>filters on the left</strong> allow for you to sort by <strong>task</strong>.</p>
<p class="d-flex flex-wrap-wrap "><img src="{{asset('imgs/user-guide/image-056.png')}}" alt="img">
<img src="{{asset('imgs/user-guide/image-055.png')}}" alt="img"></p>
<p>Below these filters, you will see a row of colors that act as a guide to help you view this report.
The color<strong> Red </strong>means that the hours spent in these activities have a<strong> High proximity cost</strong>, meaning that they are the most expensive. The<strong> Golden-Orange</strong> color means that hours spent in these activities have a <strong>Medium proximity cost</strong>, meaning that they are slightly cheaper than High proximity activities. The <strong>Blue</strong> color means that hours spent in these activities have a <strong>Low proximity cost</strong>, meaning that they are the cheapest activities.</p>

<p>Below this, you will notice a list of individuals. Located just above the list of individuals is a
field to search for a specific individual’s results. This list tells you a summary of each individual
report for each individual. It has multiple columns that will tell you their Name and Employee
ID, their Position, the Total RSF Cost for that specific individual, and the Total Amount of Hours
that they reported for the year. You will notice that the last three columns show you how many
hours an individual spent working in High, Medium, and Low proximity factor activities
throughout the year.</p>
<img src="{{asset('imgs/user-guide/image-057.png')}}" alt="img">

<p><strong>Activity by Location</strong></p>
<p>The Activity by Location report shows you the Hours, RSF or RSF Cost (Metric) for the
activities being performed at each location. Activities are allocated to each location based on
actual participant questionnaire responses.</p>

<p>When you first enter this report, you will see two sets of filters at the top of your screen. The first
set of filters, located towards the left, allow for you to sort by Position, Department, Group,
Category, and Proximity Factor. The second set of filters, located on the right, allow for you to
sort by Metric, including Hours and RSF Filter. The default view is sorted by hours, however
you may change this at any point you’d like.</p>
<p class="d-flex flex-wrap-wrap "><img src="{{asset('imgs/user-guide/image-059.png')}}" alt="img">
<img src="{{asset('imgs/user-guide/image-058.png')}}" alt="img"></p>
<p>Right below these filters, you will see the total number of hours of every employee for the year.
Please note that this number only includes employees who responded to the survey.</p>

<p>To view the results in greater detail, simply select a<strong> Location</strong>. Activities performed in that
location will be detailed below. By selecting additional headings below, you can move through
the results, layer by layer, and explore real estate costs by activity in detail. You will notice that
these are color coded. <strong>Blue represents Legal Activities</strong>, while <strong>Green represents support
activities</strong>.</p>

<p>If you would like to view each participant that has participated in a specific activity, simply click
the blue icon all the way to the right that has three people in it. This is also known as the Zoom
Feature. By selecting this feature, you will be provided a detailed list of every individual who
performs the selected activity. The <strong>Back</strong> button returns you to the Participant Proximity Report.</p> 


</div> 

<div class="container-fluid px-3 hideinhelppdf">
    <div class="flex justify-between items-center cont-mtitle  mb-4">
        <h1 class="text-survey">Activity by Location Report / {{ $survey->survey_name }}</h1>
        <div class="d-flex py-0 py-md-2">
            <button class="revnor-btn m-2 " onclick="goBackList();" id="backBtn">Back</button>
            @if (\Auth::check() && \Auth::user()->hasPermission('surveyPrint', $survey))
                <button class="revnor-btn mr-1 " id="pdfBtn">Download PDF</button>
            @endif
            @if (\Auth::check() && \Auth::user()->hasPermission('surveyExport', $survey))                
                {{-- <button class="btn btn-revelation-primary mr-1" id="excelBtn">Export to Excel</button> --}}
            @endif
            <button type="button" class="helpguidebtn mx-1" data-toggle="modal" data-target="#helpdetasurvey"></button>  
        </div> 
    </div>
    <div id="compilationDetailContent">
        <div class="flex  pt-2 justify-between items-center border-b first_part" style="background-color: white;">
            <div class="lead_in">
                <h5 class="text-lg">Activity by Location Report Zoom to 1 to <span class="respNum"></span> Participants</h5>
                <h6 style="color:#215c98;font-weight:bold;" class="questionName">Legal Services</h6>
            </div>
            <!-- <div class="ml-auto">
                <img src="{{asset('imgs/logo-new-small_rev.png')}}" alt="Revelation Legal" class="w-48">
            </div> -->
        </div>
        <div class="second_part pt-2 flex justify-center items-center border-b" style="background-color: white;">
            <div>Filters-</div>
            <div class="detail_group px-4 border-r"><span style="color:lightgray;">Group: </span><span class="filterTitle" style="color: red;">{{ '' }}</span></div>
            <div class="detail_position px-4 border-r"><span style="color:lightgray;">Position: </span><span class="filterTitle" style="color: red;">{{ '' }}</span></div>
            <div class="detail_department px-4 border-r"><span style="color:lightgray;">Department: </span><span class="filterTitle" style="color: red;">{{ '' }}</span></div>
            <div class="detail_category px-4 border-r"><span style="color:lightgray;">Employee Category: </span><span class="filterTitle" style="color: red;">{{ '' }}</span></div>
            <div class="detail_location px-4"><span style="color:lightgray;">Proximity Factor: </span><span class="filterTitle" style="color: red;">{{ '' }}</span></div>
        </div>
        <div class="third_part" style="background-color: white;"> 

        </div>
    </div>
    <div id="compilationReportContent">
        <div class="second_part" style="padding-bottom:10px;background-color:white;">
            @include('real_estate.partials.activity-locationfilter')
        </div>
        <div id="totalInfo" class="third_part" style="background-color: white;">
            <div class="row border-t border-b"> 
                <div class="col-md-12 stat_item text-hours" style="color:black; padding-bottom: 20px;font-size:23px;">
                    <b>{{ number_format($data['total_hours']) }}</b> Total* Hours
                </div>
                <div class="col-md-12 stat_item text-rsf" style="color:black; padding-bottom: 20px;font-size:23px;display:none;">
                    <b>{{ number_format($data['total_rsf']) }}</b> Total* RSF
                </div>
                <div class="col-md-12 stat_item rsf-cost-item" style="color:black; padding-bottom: 20px;font-size:23px;display:none;">
                    <div class="text-rsf-current rsf-item" style="color:black;">
                        <b>{{ number_format($data['total_rsf_cost']['current']) }}</b> Total* RSF Cost (Current)
                    </div>
                    <div class="text-rsf-adjacent rsf-item" style="color:black;">
                        <b>{{ number_format($data['total_rsf_cost']['adjacent']) }}</b> Total* RSF Cost (Adjacent)
                    </div>
                    <div class="text-rsf-regional rsf-item" style="color:black;">
                        <b>{{ number_format($data['total_rsf_cost']['regional']) }}</b> Total* RSF Cost (Regional)
                    </div>
                    <div class="text-rsf-other rsf-item" style="color:black;">
                        <b>{{ number_format($data['total_rsf_cost']['other']) }}</b> Total* RSF Cost (Other)
                    </div>
                </div>
            </div>
            <div class="text-right py-2">
                * Values only include employees who responded to the survey
            </div>
            <div id="ServiceClass">
                <div class="service_container" id="question1-layer">
                    <div>
                        <a class="lightblueback btn-block text-left service_bar" data-toggle="collapse" href="#Root1" role="button" aria-expanded="false" aria-controls="Root1">
                            <span class="service_bar_title">Cost Distribution By Location</span> | {{ $data['respondents_num'] }} respondents
                        </a>
                        <div class="collapse show" id="Root1">
                            <div class="card card-body table-txtmid">
                                <table class="service_table">
                                    <tbody>
                                        @foreach ($data['locationData'] as $location => $row)
                                            <tr>
                                                <td class="text-sm" onclick="getLowBranchData('{{ $location }}', {{ $row['hours'] }}, {{ $row['rsf'] }}, 1, '{{ $location }}');" style="width:35%;text-align:right; color:black;">{{ $location }}</td>
                                                <td style="width:55%;" onclick="getLowBranchData('{{ $location }}', {{ $row['hours'] }}, {{ $row['rsf'] }}, 1, '{{ $location }}');">
                                                    <div class="bar-graph flex items-center justify-start" style="width: 100%;">
                                                        <div class="bg-hours text-hours stat_item" style="width:calc(80% * {{ $row['percent'] }} / 100);height:24px;padding-top: 0;"></div>
                                                        <span class="px-1 text-hours stat_item" style="padding-top: 0; color:black;">{{ $row['percent'] }}% | {{ number_format($row['hours']) }}</span>
                                                        <div class="bg-rsf text-rsf stat_item" style="width:calc(80% * {{ $row['rsf_percent'] }} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                        <span class="px-1 text-rsf stat_item" style="display: none;padding-top: 0; color:black;">{{ $row['rsf_percent'] }}% | {{ number_format($row['rsf']) }}</span>
                                                        <div class="bg-rsf-current text-rsf-current stat_item" style="width:calc(80% * {{ $row['rsf_cost_current_percent'] }} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                        <span class="px-1 text-rsf-current stat_item" style="display: none;padding-top: 0; color:black;">{{ $row['rsf_cost_current_percent'] }}% | {{ number_format($row['rsf_cost_current']) }}</span>
                                                        <div class="bg-rsf-adjacent text-rsf-adjacent stat_item" style="width:calc(80% * {{ $row['rsf_cost_adjacent_percent'] }} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                        <span class="px-1 text-rsf-adjacent stat_item" style="display: none;padding-top: 0; color:black;">{{ $row['rsf_cost_adjacent_percent'] }}% | {{ number_format($row['rsf_cost_adjacent']) }}</span>
                                                        <div class="bg-rsf-regional text-rsf-regional stat_item" style="width:calc(80% * {{ $row['rsf_cost_regional_percent'] }} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                        <span class="px-1 text-rsf-regional stat_item" style="display: none;padding-top: 0; color:black;">{{ $row['rsf_cost_regional_percent'] }}% | {{ number_format($row['rsf_cost_regional']) }}</span>
                                                        <div class="bg-rsf-other text-rsf-other stat_item" style="width:calc(80% * {{ $row['rsf_cost_other_percent'] }} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                        <span class="px-1 text-rsf-other stat_item" style="display: none;padding-top: 0; color:black;">{{ $row['rsf_cost_other_percent'] }}% | {{ number_format($row['rsf_cost_other']) }}</span>
                                                    </div>
                                                </td>
                                                <td class="btn-detailList" style="width:10%;text-align:right;">
                                                    <button class="btn btn-revelation-primary" onclick="getDetailRespByLocation('{{ $location }}');" title="View Participants for {{ $location }}">
                                                        <svg class="respDetailBtn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.25em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 640 512"><path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6c40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32S208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z" fill="white"/></svg>
                                                    </button>    
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="service_container " id="question2-layer"></div>
                <div class="service_container " id="question3-layer"></div>
                <div class="service_container " id="question4-layer"></div>
                <div class="service_container " id="question5-layer"></div>
                <div class="service_container " id="question6-layer"></div>
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
            <p class="text-phead">Activity by Location Report / {{ $survey->survey_name }}</p> 
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


<p><strong>Activity by Location</strong></p>
<p>The Activity by Location report shows you the Hours, RSF or RSF Cost (Metric) for the
activities being performed at each location. Activities are allocated to each location based on
actual participant questionnaire responses.</p>

<p>When you first enter this report, you will see two sets of filters at the top of your screen. The first
set of filters, located towards the left, allow for you to sort by Position, Department, Group,
Category, and Proximity Factor. The second set of filters, located on the right, allow for you to
sort by Metric, including Hours and RSF Filter. The default view is sorted by hours, however
you may change this at any point you’d like.</p>
<p class="d-flex flex-wrap-wrap "><img src="{{asset('imgs/user-guide/image-059.png')}}" alt="img">
<img src="{{asset('imgs/user-guide/image-058.png')}}" alt="img"></p>
<p>Right below these filters, you will see the total number of hours of every employee for the year.
Please note that this number only includes employees who responded to the survey.</p>

<p>To view the results in greater detail, simply select a<strong> Location</strong>. Activities performed in that
location will be detailed below. By selecting additional headings below, you can move through
the results, layer by layer, and explore real estate costs by activity in detail. You will notice that
these are color coded. <strong>Blue represents Legal Activities</strong>, while <strong>Green represents support
activities</strong>.</p>

<p>If you would like to view each participant that has participated in a specific activity, simply click
the blue icon all the way to the right that has three people in it. This is also known as the Zoom
Feature. By selecting this feature, you will be provided a detailed list of every individual who
performs the selected activity. The <strong>Back</strong> button returns you to the Participant Proximity Report.</p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    <div class="modal fade" tabindex="-1" role="dialog" id="generateExcelModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body flex items-center justify-center" style="height: 150px;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp; Generating Excel file ...
                </div>
                <div class="modal-footer">
                    <a class="btn btn-revelation-primary disabled" href="javascript:void(0);">Download</a>
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
        var medColor = "#f28d36";
        var lowColor = "#4e7aa5";

        var imgData_1, imgData_2, imgData_3, imgData_4, copyrightData, headerData; 
        
        let currencyFormatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
            maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
        });
        let numberFormatter = new Intl.NumberFormat('en-US');
        let hoursFormatter  = new Intl.NumberFormat('en-US', {
            maximumFractionDigits: 0,
            minimumFractionDigits: 0
        })

        $(document).ready(function () {
            $('#backBtn').hide();
            $('#excelBtn').hide();
            $('#backBtn').css('opacity', '1');            
        });
        // Handle the event of excel button click
        $('#excelBtn').click(function () {
            let tableData = $('#detailRespTable').bootstrapTable('getData');
            $.ajax({
                url: '{{ route('realestate.exportLocationExcel') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_name": '{{ $survey->survey_name }}',
                    "label": $('.questionName').html(),
                    "tableData": JSON.stringify(tableData)
                },
                dataType: 'json',
                beforeSend: function () {
                    $('#generateExcelModal').modal('show');
                },
                success: function (res) {
                    $('#generateExcelModal .modal-body').html('Generated an Excel file');
                    $('#generateExcelModal .btn').attr('href', res.url);
                    $('#generateExcelModal .btn').attr('download', res.filename);
                    $('#generateExcelModal .btn').removeClass('disabled');
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        });

        // Handle the pdf button click, generate image data from the body
        $('#pdfBtn').click(function () {
            $('#headerDiv').show();
            $('#copyright_div').addClass('d-flex');
			$('#copyright_div').show();	 			
            $('#generatePDFModal').modal('show');
            if ($('#compilationReportContent').css('display') == 'none') {
                
                 source = $('#compilationDetailContent .first_part');
                html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    imgData_1 = canvas.toDataURL("image/png", 1.0);
                        
                });
                /* source = $('#compilationDetailContent .second_part');
                html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    imgData_2 = canvas.toDataURL("image/png", 1.0);
                        
                }); */
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
                source = $('#compilationDetailContent .third_part'); 
                html2canvas(source, {
                    onrendered: function (canvas) {
                        imgData_3 = canvas.toDataURL('image/jpeg', 1.0);
                    }
                }).then(function () {
                    $('#headerDiv').hide();
                    $('#compilationReportContent .btn-detailList').css('display', 'block');                    
                    $('#generatePDFModal .modal-body').html(`<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="24" height="24" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path d="M30 18v-2h-6v10h2v-4h3v-2h-3v-2h4z" fill="currentColor"/><path d="M19 26h-4V16h4a3.003 3.003 0 0 1 3 3v4a3.003 3.003 0 0 1-3 3zm-2-2h2a1.001 1.001 0 0 0 1-1v-4a1.001 1.001 0 0 0-1-1h-2z" fill="currentColor"/><path d="M11 16H6v10h2v-3h3a2.003 2.003 0 0 0 2-2v-3a2.002 2.002 0 0 0-2-2zm-3 5v-3h3l.001 3z" fill="currentColor"/><path d="M22 14v-4a.91.91 0 0 0-.3-.7l-7-7A.909.909 0 0 0 14 2H4a2.006 2.006 0 0 0-2 2v24a2 2 0 0 0 2 2h16v-2H4V4h8v6a2.006 2.006 0 0 0 2 2h6v2zm-8-4V4.4l5.6 5.6z" fill="currentColor"/></svg> &nbsp; Generated a PDF`);
                    $('#generatePDFModal .btn').attr('disabled', false);
					$('#copyright_div').removeClass('d-flex');
			        $('#copyright_div').hide();	
                    $('#headerDiv').hide();					
                    
                }); 
            } else {
                source = $('#compilationReportContent .second_part');
                html2canvas(source, {
                    onrendered: function (canvas) {
                        imgData_2 = canvas.toDataURL('image/jpeg', 1.0);
                    }
                });
                // Copyright
                source = $('#copyright_div'); 
                html2canvas(source, {
                    onrendered: function (canvas) {
                        copyrightData = canvas.toDataURL('image/jpeg', 1.0);
                    }
                });
                source = $('#headerDiv');
                html2canvas(source, {
                    onrendered: function (canvas) {
                        headerData = canvas.toDataURL('image/jpeg', 1.0);
                    }
                });
                $('#compilationReportContent .btn-detailList').css('display', 'none');
                source = $('#compilationReportContent .third_part');
                html2canvas(source, {
                    onrendered: function (canvas) {   
                        imgData_3 = canvas.toDataURL('image/jpeg', 1.0);
                    }
                }).then(function (canvas) {
                    $('#compilationReportContent .btn-detailList').css('display', 'block');
                    $('#generatePDFModal .modal-body').html(`<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="24" height="24" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path d="M30 18v-2h-6v10h2v-4h3v-2h-3v-2h4z" fill="currentColor"/><path d="M19 26h-4V16h4a3.003 3.003 0 0 1 3 3v4a3.003 3.003 0 0 1-3 3zm-2-2h2a1.001 1.001 0 0 0 1-1v-4a1.001 1.001 0 0 0-1-1h-2z" fill="currentColor"/><path d="M11 16H6v10h2v-3h3a2.003 2.003 0 0 0 2-2v-3a2.002 2.002 0 0 0-2-2zm-3 5v-3h3l.001 3z" fill="currentColor"/><path d="M22 14v-4a.91.91 0 0 0-.3-.7l-7-7A.909.909 0 0 0 14 2H4a2.006 2.006 0 0 0-2 2v24a2 2 0 0 0 2 2h16v-2H4V4h8v6a2.006 2.006 0 0 0 2 2h6v2zm-8-4V4.4l5.6 5.6z" fill="currentColor"/></svg> &nbsp; Generated a PDF`);
                    $('#generatePDFModal .btn').attr('disabled', false);
                    $('#headerDiv').hide();
					$('#copyright_div').removeClass('d-flex');
			        $('#copyright_div').hide(); 					
                });
            }
        });

        $('#generatePDFModal').on('hidden.bs.modal', function () {
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .modal-body').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp; Generating PDF...`);
            $('#generatePDFModal .btn').attr('disabled', true);
        });

        $('#generateExcelModal').on('hidden.bs.modal', function () {
            $('#generateExcelModal').modal('hide');
            $('#generateExcelModal .modal-body').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp; Generating Excel file...`);
            $('#generateExcelModal .btn').attr('href', 'javascript:void(0);');
            $('#generateExcelModal .btn').addClass('disabled');
        });

        $('#generateExcelModal .btn').click(function () {
            $('#generateExcelModal').modal('hide');
        });

        /**
        * Generate pdf document of report
        *
        * @return {void}
        */
        function generatePDF () {
            let imgWidth = $('#copyright_div').outerWidth();
            pdfdoc = new jsPDF('p', 'mm', 'a4');
            if ($('#compilationReportContent').css('display') == 'none') {
                imgHeight1 = Math.round($('#compilationDetailContent .first_part').outerHeight() * 190 / imgWidth);
                y = 14;
                position = y;
                doc_page = 1;
                pdfdoc.addImage(imgData_1, 'JPEG', 10, y, 190, imgHeight1);
                y += imgHeight1;
                imgHeight2 = Math.round($('#compilationDetailContent .second_part').outerHeight() * 190 / imgWidth);
                /* pdfdoc.addImage(imgData_2, 'JPEG', 10, y, 190, imgHeight2);
                y += imgHeight2; */
                imgHeight3 = Math.round($('#compilationDetailContent .third_part').outerHeight() * 190 / imgWidth);
                pdfdoc.addImage(imgData_3, 'JPEG', 10, y, 190, imgHeight3);
                y += imgHeight3;
    
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
                    pdfdoc.addImage(headerData, 'JPEG', 10, 0, 190, 12); 
                    pdfdoc.addImage(copyrightData, 'JPEG', 10, 284, 190, 13);
                    pdfdoc.setTextColor(111,107,107); 
                    pdfdoc.setFontSize(8); 
                    pdfdoc.text('Page ' + i + ' of ' + (doc_page-1) , 172, 290, 0, 45);
                }
    
                pdfdoc.save(`Compilation Report({{$data['survey']->survey_name}})`);
            } else {
                imgHeight1 = Math.round($('#compilationDetailContent .first_part').outerHeight() * 190 / imgWidth);
                y = 14;
                position = y;
                doc_page = 1;

                imgHeight2 = Math.round($('#compilationDetailContent .second_part').outerHeight() * 190 / imgWidth);
               /*  pdfdoc.addImage(imgData_2, 'JPEG', 10, y, 190, imgHeight2);
                y += imgHeight2; */
                imgHeight3 = Math.round($('#compilationDetailContent .third_part').outerHeight() * 190 / imgWidth);

                pdfdoc.addImage(imgData_3, 'JPEG', 10, y, 190, imgHeight3);
                y += imgHeight3;

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
                    pdfdoc.addImage(headerData, 'JPEG', 10, 0, 190, 12); 
                    pdfdoc.addImage(copyrightData, 'JPEG', 10, 284, 190, 13); 
                    pdfdoc.setTextColor(111,107,107);
                pdfdoc.setFontSize(8); 
                pdfdoc.text('Page ' + i + ' of ' + (doc_page-1) , 172, 290, 0, 45);  
                } 

                pdfdoc.save(`Activity by Location Report({{$data['survey']->survey_name}})`);
            }
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .btn').attr('disabled', true);
        }

        $('.service_table tbody tr').click(function() {
            $(this).parent().find('tr').removeClass('selected-tr');
            $(this).addClass('selected-tr');
        });

        function getLowBranchData (location, hours, rsf, divNum, title, parentTitle) {
            for (let i = 1; i < 8; i++) {
                if (i > divNum) {
                    $('#question' + i + '-layer').empty();
                }
            }

            let newdivNum = divNum + 1;

            $('#question' + newdivNum + '-layer').html(`<div class="text-gray text-center">Loading...</div>`);

            $.ajax({
                url: '{{ route('realestate.getActivityByLocation') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "location": location,
                    "hours": hours,
                    "rsf": rsf,
                    "position": JSON.stringify(options['position']),
                    "department": JSON.stringify(options['department']),
                    "group": JSON.stringify(options['group']),
                    "category": JSON.stringify(options['category']),
                    "proximity": JSON.stringify(options['proximity']),
                },
                dataType: 'json',
                beforeSend: function () {

                },
                success: function (res) {
                    console.log('test');
                    $('.filter-btn').eq('6').children('.filter-caption').text($('.filter-btn').eq('6').siblings().children().eq('0').text());
                    
                    $('.filter-btn').eq('5').children('.filter-caption').text($('.filter-btn').eq('5').siblings().children().eq('0').text());

                    let strHtml = '';
                    strHtml = `<div>
                        <a class="btn btn-revelation-primary btn-block text-left service_bar" data-toggle="collapse" href="#Root${newdivNum}" role="button" aria-expanded="false" aria-controls="Root${newdivNum}">
                                                <span class="service_bar_title">Cost Distribution of ${title}</span> | ${res.total.resp_num} respondents
                                            </a>
                                            <div class="collapse" id="Root${newdivNum}">
                                                <div class="card card-body">
                                                    <table class="service_table">
                                                        <tbody>`;
                    let rows = res.rows;
                    layerData[newdivNum] = res.resps;
                    /* background-color:rgb(89, 161, 79, 0.1); */
                    for (let i in rows) {
                        let Newcolor;
                        let backgroundColor = "background-color:white";
                        let newtxtcolor ='color:black';
                        if (rows[i].parent.includes("Legal") === true) {
                            backgroundColor = "background-color:white";
                            Newcolor = 'background-color:#8eceec';
                            newtxtcolor = 'color:black';
                        }
                        console.log(rows[i]);
                        strHtml += `<tr style="${backgroundColor}">
                                        <td class="text-sm" onclick="getLowQuestionData(${i}, '${rows[i].question_desc}', ${rows[i].hours}, ${rows[i].rsf}, 2,'${rows[i].parent}');" style="width:35%;text-align:right;">${rows[i].question_desc}</td>
                                        <td style="width:55%;" onclick="">
                                            <div class="bar-graph flex items-center justify-start" style="width: 100%;">
                                            <div class="bg-hours text-hours stat_item" style="width:calc(80% * ${rows[i].percent_hours} / 100);height:24px;padding-top: 0;${Newcolor};"></div>
                                                <span class="px-1 text-hours stat_item" style="padding-top: 0; ${newtxtcolor}">${rows[i].percent_hours}% | ${ numberFormatter.format(rows[i].hours) }</span>
                                                <div class="bg-rsf text-rsf stat_item" style="width:calc(80% * ${rows[i].percent_rsf} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                <span class="px-1 text-rsf stat_item" style="display: none;padding-top: 0; color:black;">${rows[i].percent_rsf}% | ${numberFormatter.format(rows[i].rsf)}</span>
                                                <div class="bg-rsf-current text-rsf-current stat_item" style="width:calc(80% * ${rows[i].percent_rsf_cost_current} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                <span class="px-1 text-rsf-current stat_item" style="display: none;padding-top: 0; color:black;">${rows[i].percent_rsf_cost_current}% | ${numberFormatter.format(rows[i].rsf_cost_current)}</span>
                                                <div class="bg-rsf-adjacent text-rsf-adjacent stat_item" style="width:calc(80% * ${rows[i].percent_rsf_cost_adjacent} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                <span class="px-1 text-rsf-adjacent stat_item" style="display: none;padding-top: 0; color:black;">${rows[i].percent_rsf_cost_adjacent}% | ${numberFormatter.format(rows[i].rsf_cost_adjacent)}</span>
                                                <div class="bg-rsf-regional text-rsf-regional stat_item" style="width:calc(80% * ${rows[i].percent_rsf_cost_regional} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                <span class="px-1 text-rsf-regional stat_item" style="display: none;padding-top: 0; color:black;">${rows[i].percent_rsf_cost_regional}% | ${numberFormatter.format(rows[i].rsf_cost_regional)}</span>
                                                <div class="bg-rsf-other text-rsf-other stat_item" style="width:calc(80% * ${rows[i].percent_rsf_cost_other} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                <span class="px-1 text-rsf-other stat_item" style="display: none;padding-top: 0; color:black;">${rows[i].percent_rsf_cost_other}% | ${numberFormatter.format(rows[i].rsf_cost_other)}</span>
                                            </div>
                                        </td>
                                        <td class="btn-detailList" style="width:10%;text-align:right;">
                                            <button class="btn btn-revelation-primary btn-question-resps" data-question_id="${i}" data-question_desc="${rows[i].question_desc}">
                                                <svg class="respDetailBtn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.25em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 640 512"><path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6c40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32S208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z" fill="white"/></svg>
                                            </button>    
                                        </td>
                                    </tr>`;
                    }

                    $('#question' + newdivNum + '-layer').html(strHtml);
                    $('#question' + newdivNum + '-layer .btn-revelation-primary').click();

                    $('.stat_item').hide();
                    if (metric == 'rsf_cost') {
                        $('.rsf-cost-item').show();
                        $('.rsf-item').hide();
                        $('.text-rsf-' + rsf_filter).show();
                    } else {
                        $('.text-' + metric).show();
                    }
                    
                    $('.service_table tbody tr').click(function() {
                        $(this).parent().find('tr').removeClass('selected-tr');
                        $(this).addClass('selected-tr');
                    });

                    questionResps = res.locationResps;
                    $('.btn-question-resps').click(function () {
                        let question_id = $(this).attr("data-question_id");
                        let question_desc = $(this).attr("data-question_desc");
                        $detailContainer = $('#compilationDetailContent .third_part');
                        $detailContainer.empty();
                        $detailContainer.append(`<table 
                            class="table table-borderless table-sm table-striped text-sm" 
                            id="detailRespTable"
                            data-toggle="table"
                            data-cellspacing="0"
                            data-show-footer="true">
                            <thead>
                                <tr>
                                    <th style="width:100px;" data-sortable="true" data-field="name" data-footer-formatter="table_totalStrFormatter">Full Name</th>
                                    <th data-field="position">Position</th>
                                    <th data-field="employee_id">Employee ID</th>
                                    <th data-field="location">Location</th>
                                    <th class="text-right" data-sortable="true" data-field="rentable_square_feet" data-footer-formatter="table_totalNumFormatter">RSF</th>
                                    <th class="text-right" data-sortable="true" data-field="total_hours" data-formatter="table_numberFormatter" data-footer-formatter="table_totalNumFormatter">Hours</th>
                                    <th class="text-right" data-sortable="true" data-field="rsf_cost" data-formatter="table_numberFormatter" data-footer-formatter="table_totalNumFormatter">RSF Cost (Current)</th>
                                    <th class="text-right" data-sortable="true" data-field="rsf_cost_adjacent" data-formatter="table_numberFormatter" data-footer-formatter="table_totalNumFormatter">RSF Cost (Adjacent)</th>
                                    <th class="text-right" data-sortable="true" data-field="rsf_cost_regional" data-formatter="table_numberFormatter" data-footer-formatter="table_totalNumFormatter">RSF Cost (Regional)</th>
                                    <th class="text-right" data-sortable="true" data-field="rsf_cost_other" data-formatter="table_numberFormatter" data-footer-formatter="table_totalNumFormatter">RSF Cost (Other)</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th></th>    
                                    <th></th>    
                                    <th></th>    
                                    <th></th>    
                                    <th></th>    
                                    <th></th>    
                                    <th></th>    
                                    <th></th>    
                                    <th></th>    
                                    <th></th>    
                                </tr>    
                            </tfoot>
                        </table>`);
                        $detailTable = $('#detailRespTable tbody');
                        $detailTable.empty();
                        let rows = questionResps[question_id];

                        for (const i in rows) {
                            $detailTable.append(`<tr>
                                    <td>${rows[i].resp_last}, ${rows[i].resp_first}</td>
                                    <td>${rows[i].cust_3}</td>
                                    <td>${rows[i].cust_1}</td>
                                    <td>${rows[i].cust_6}</td>
                                    <td>${rows[i].rentable_square_feet}</td>
                                    <td>${rows[i].total_hours}</td>
                                    <td>${rows[i].rsf_cost}</td>
                                    <td>${rows[i].rsf_cost_adjacent}</td>
                                    <td>${rows[i].rsf_cost_regional}</td>
                                    <td>${rows[i].rsf_cost_other}</td>
                                </tr>`);
                        }

                        $('#detailRespTable').bootstrapTable();
                        /* $('.filter-btn').eq('6').children('.filter-caption').text($('.filter-btn').eq('6').siblings().children().eq('0').text());
                        $('.filter-btn').eq('5').children('.filter-caption').text($('.filter-btn').eq('6').siblings().children().eq('0').text()); */

                        

                        $('#compilationReportContent').css('display', 'none');
                        $('#compilationDetailContent').css('display', 'block');
                        $('#backBtn').fadeIn();
                        $('#excelBtn').fadeIn();
                        $('.questionName').html(question_desc);
                        $('.respNum').html(rows.length);
                    });
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        }

        function getLowQuestionData (question_id, title, hours, rsf, divNum, parent) {
            for (let i = 1; i < 8; i++) {
                if (i > divNum) {
                    $('#question' + i + '-layer').empty();
                }
            }

            let newdivNum = divNum + 1;

            $('#question' + newdivNum + '-layer').html(`<div class="text-gray text-center">Loading...</div>`);

            $.ajax({
                url: '{{ route('realestate.getActivityByQuestion') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "hours": hours,
                    "rsf": rsf,
                    "question_id": question_id,
                    "resps": JSON.stringify(layerData[divNum])
                },
                dataType: 'json',
                beforeSend: function () {

                },
                success: function (res) {
                    console.log('test2');
                    $('.filter-btn').eq('6').children('.filter-caption').text($('.filter-btn').eq('6').siblings().children().eq('0').text());
                    
                    $('.filter-btn').eq('5').children('.filter-caption').text($('.filter-btn').eq('5').siblings().children().eq('0').text());


                    if (res.rows.length > 0) {
                        let strHtml = '';
                        strHtml = `<div>
                            <a class="btn btn-revelation-primary btn-block text-left service_bar" data-toggle="collapse" href="#Root${newdivNum}" role="button" aria-expanded="false" aria-controls="Root${newdivNum}">
                                                    <span class="service_bar_title">Cost Distribution of ${title}</span> | ${res.total.resp_num} respondents
                                                </a>
                                                <div class="collapse" id="Root${newdivNum}">
                                                    <div class="card card-body">
                                                        <table class="service_table">
                                                            <tbody>`;
                        let rows = res.rows;
                        layerData[newdivNum] = res.resps;
                        
                        
                        if(parent == 'Legal Services'){
                            NewBgColor='background-color:#8eceec';
                        }else{
                            NewBgColor='background-color:#82BD5E';
                        }
                        for (let i in rows) {
                            console.log(rows[i]);
                            strHtml += `<tr>
                                            <td class="text-sm" onclick="getLowQuestionData(${rows[i].question_id}, '${rows[i].question_desc}', ${rows[i].hours}, ${rows[i].rsf}, ${newdivNum}, '${parent}');" style="width:35%;text-align:right;">${rows[i].question_desc}</td>
                                            <td style="width:55%;" onclick="getLowQuestionData(${rows[i].question_id}, '${rows[i].question_desc}', ${rows[i].hours}, ${rows[i].rsf}, ${newdivNum},'${parent}');">
                                                <div class="bar-graph flex items-center justify-start" style="width: 100%;">
                                                    <div class="bg-hours text-hours stat_item" style="${NewBgColor};width:calc(80% * ${rows[i].percent_hours + res.total.start_percent} / 100);height:24px;padding-top: 0;"></div>
                                                    <span class="px-1 text-hours stat_item" style="padding-top: 0;color:black;">${ numberFormatter.format(rows[i].hours) }</span>
                                                    <div class="bg-rsf text-rsf stat_item" style="width:calc(80% * ${rows[i].percent_rsf + res.total.start_percent} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                    <span class="px-1 text-rsf stat_item" style="display: none;padding-top: 0; color:black;">${numberFormatter.format(rows[i].rsf)}</span>
                                                    <div class="bg-rsf-current text-rsf-current stat_item" style="width:calc(80% * ${rows[i].percent_rsf_cost_current + res.total.start_percent} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                    <span class="px-1 text-rsf-current stat_item" style="display: none;padding-top: 0; color:black;">${numberFormatter.format(rows[i].rsf_cost_current)}</span>
                                                    <div class="bg-rsf-adjacent text-rsf-adjacent stat_item" style="width:calc(80% * ${rows[i].percent_rsf_cost_adjacent + res.total.start_percent} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                    <span class="px-1 text-rsf-adjacent stat_item" style="display: none;padding-top: 0; color:black;">${numberFormatter.format(rows[i].rsf_cost_adjacent)}</span>
                                                    <div class="bg-rsf-regional text-rsf-regional stat_item" style="width:calc(80% * ${rows[i].percent_rsf_cost_regional + res.total.start_percent} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                    <span class="px-1 text-rsf-regional stat_item" style="display: none;padding-top: 0; color:black;">${numberFormatter.format(rows[i].rsf_cost_regional)}</span>
                                                    <div class="bg-rsf-other text-rsf-other stat_item" style="width:calc(80% * ${rows[i].percent_rsf_cost_other + res.total.start_percent} / 100);height:24px;display: none;padding-top: 0;"></div>
                                                    <span class="px-1 text-rsf-other stat_item" style="display: none;padding-top: 0; color:black;">${numberFormatter.format(rows[i].rsf_cost_other)}</span>
                                                </div>
                                            </td>
                                            <td class="btn-detailList" style="width:10%;text-align:right;">
                                                <button class="btn btn-revelation-primary btn-question-resps" data-question_id="${rows[i].question_id}" data-question_desc="${rows[i].question_desc}">
                                                    <svg class="respDetailBtn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.25em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 640 512"><path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6c40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32S208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z" fill="white"/></svg>
                                                </button>    
                                            </td>
                                        </tr>`;
                        }
    
                        $('#question' + newdivNum + '-layer').html(strHtml);
                        $('#question' + newdivNum + '-layer .btn-revelation-primary').click();
    
                        $('.stat_item').hide();
                        if (metric == 'rsf_cost') {
                            $('.rsf-cost-item').show();
                            $('.rsf-item').hide();
                            $('.text-rsf-' + rsf_filter).show();
                        } else {
                            $('.text-' + metric).show();
                        }

                        $('.service_table tbody tr').click(function() {
                            $(this).parent().find('tr').css('background-color', 'white');
                            $(this).css('background-color', 'rgba(54, 123, 193, 0.3)');
                        });

                        questionResps =  {...questionResps, ...res.questionResps};
                        $('.btn-question-resps').click(function () {
                            let question_id = $(this).attr("data-question_id");
                            let question_desc = $(this).attr("data-question_desc");
                            $detailContainer = $('#compilationDetailContent .third_part');
                            $detailContainer.empty();
                            $detailContainer.append(`<table 
                                class="table table-borderless table-sm table-striped text-sm" 
                                id="detailRespTable"
                                data-toggle="table"
                                data-cellspacing="0"
                                data-show-footer="true">
                                <thead>
                                    <tr>
                                        <th style="width:100px;" data-sortable="true" data-field="name" data-footer-formatter="table_totalStrFormatter">Full Name</th>
                                        <th data-field="position">Position</th>
                                        <th data-field="employee_id">Employee ID</th>
                                        <th data-field="location">Location</th>
                                        <th class="text-right" data-sortable="true" data-field="rentable_square_feet" data-footer-formatter="table_totalNumFormatter">RSF</th>
                                        <th class="text-right" data-sortable="true" data-field="total_hours" data-formatter="table_numberFormatter" data-footer-formatter="table_totalNumFormatter">Hours</th>
                                        <th class="text-right" data-sortable="true" data-field="rsf_cost" data-formatter="table_numberFormatter" data-footer-formatter="table_totalNumFormatter">RSF Cost (Current)</th>
                                        <th class="text-right" data-sortable="true" data-field="rsf_cost_adjacent" data-formatter="table_numberFormatter" data-footer-formatter="table_totalNumFormatter">RSF Cost (Adjacent)</th>
                                        <th class="text-right" data-sortable="true" data-field="rsf_cost_regional" data-formatter="table_numberFormatter" data-footer-formatter="table_totalNumFormatter">RSF Cost (Regional)</th>
                                        <th class="text-right" data-sortable="true" data-field="rsf_cost_other" data-formatter="table_numberFormatter" data-footer-formatter="table_totalNumFormatter">RSF Cost (Other)</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>    
                                        <th></th>    
                                        <th></th>    
                                        <th></th>    
                                        <th></th>    
                                        <th></th>    
                                        <th></th>    
                                        <th></th>    
                                        <th></th>    
                                        <th></th>    
                                    </tr>    
                                </tfoot>
                            </table>`);
                            $detailTable = $('#detailRespTable tbody');
                            $detailTable.empty();
                            console.log(questionResps)
                            let rows = questionResps[question_id];
                            console.log(rows)

                            for (const i in rows) {
                                $detailTable.append(`<tr>
                                        <td>${rows[i].resp_last}, ${rows[i].resp_first}</td>
                                        <td>${rows[i].cust_3}</td>
                                        <td>${rows[i].cust_1}</td>
                                        <td>${rows[i].cust_6}</td>
                                        <td>${rows[i].rentable_square_feet}</td>
                                        <td>${rows[i].total_hours}</td>
                                        <td>${rows[i].rsf_cost}</td>
                                        <td>${rows[i].rsf_cost_adjacent}</td>
                                        <td>${rows[i].rsf_cost_regional}</td>
                                        <td>${rows[i].rsf_cost_other}</td>
                                    </tr>`);
                            }

                            $('#detailRespTable').bootstrapTable();

                            $('#compilationReportContent').css('display', 'none');
                            $('#compilationDetailContent').css('display', 'block');
                            $('#backBtn').fadeIn();
                            $('#excelBtn').fadeIn();
                            $('.questionName').html(question_desc);
                            $('.respNum').html(rows.length);
                        });
                    } else {
                        Swal.fire({
                            text: 'There is no detail below this level.',
                            confirmButtonText: 'OK'
                        });
                        $('#question' + newdivNum + '-layer').html('');
                    }
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        }

        function getDetailRespByLocation (location) {
            $detailContainer = $('#compilationDetailContent .third_part');
            $detailContainer.empty();
            $detailContainer.append(`<table 
                class="table table-borderless table-sm table-striped text-sm" 
                id="detailRespTable"
                data-toggle="table"
                data-cellspacing="0"
                data-show-footer="true">
                <thead>
                    <tr>
                        <th style="width:100px;" data-sortable="true" data-field="name" data-footer-formatter="table_totalStrFormatter">Full Name</th>
                        <th data-field="position">Position</th>
                        <th data-field="employee_id">Employee ID</th>
                        <th data-field="location">Location</th>
                        <th class="text-right" data-sortable="true" data-field="rentable_square_feet" data-footer-formatter="table_totalNumFormatter">RSF</th>
                        <th class="text-right" data-sortable="true" data-field="total_hours" data-formatter="table_numberFormatter" data-footer-formatter="table_totalNumFormatter">Hours</th>
                        <th class="text-right" data-sortable="true" data-field="rsf_cost" data-formatter="table_numberFormatter" data-footer-formatter="table_totalNumFormatter">RSF Cost (Current)</th>
                        <th class="text-right" data-sortable="true" data-field="rsf_cost_adjacent" data-formatter="table_numberFormatter" data-footer-formatter="table_totalNumFormatter">RSF Cost (Adjacent)</th>
                        <th class="text-right" data-sortable="true" data-field="rsf_cost_regional" data-formatter="table_numberFormatter" data-footer-formatter="table_totalNumFormatter">RSF Cost (Regional)</th>
                        <th class="text-right" data-sortable="true" data-field="rsf_cost_other" data-formatter="table_numberFormatter" data-footer-formatter="table_totalNumFormatter">RSF Cost (Other)</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th></th>    
                        <th></th>    
                        <th></th>    
                        <th></th>    
                        <th></th>    
                        <th></th>    
                        <th></th>    
                        <th></th>    
                        <th></th>    
                        <th></th>    
                    </tr>    
                </tfoot>
            </table>`);
            $detailTable = $('#detailRespTable tbody');
            $detailTable.empty();
            let rows = initRespData[location];

            for (const i in rows) {
                $detailTable.append(`<tr>
                        <td>${rows[i].resp_last}, ${rows[i].resp_first}</td>
                        <td>${rows[i].cust_3}</td>
                        <td>${rows[i].cust_1}</td>
                        <td>${rows[i].cust_6}</td>
                        <td>${rows[i].rentable_square_feet}</td>
                        <td>${rows[i].total_hours}</td>
                        <td>${rows[i].rsf_cost}</td>
                        <td>${rows[i].rsf_cost_adjacent}</td>
                        <td>${rows[i].rsf_cost_regional}</td>
                        <td>${rows[i].rsf_cost_other}</td>
                    </tr>`);
            }

            $('#detailRespTable').bootstrapTable();

            $('#compilationReportContent').css('display', 'none');
            $('#compilationDetailContent').css('display', 'block');
            $('#backBtn').fadeIn();
            $('#excelBtn').fadeIn();
            $('.questionName').html(location);
            $('.respNum').html(rows.length);
        }
        
        function goBackList () {
            $('#compilationReportContent').css('display', 'block');
            $('#compilationDetailContent').css('display', 'none');
            $('#backBtn').hide();
            $('#excelBtn').hide();
        }
        
        function table_numberFormatter (value) {
            return numberFormatter.format(value);
        }

        function table_totalStrFormatter () {
            return 'Total';
        }

        function table_totalNumFormatter (data) {
            var field = this.field;
            
            let sum = 0;
            for (const i in data) {
                sum += parseFloat(data[i][field]);
            }

            return numberFormatter.format(sum);
        }
    </script>

@endsection
