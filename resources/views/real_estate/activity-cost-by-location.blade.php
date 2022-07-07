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

<p><strong>Activity Cost by Location</strong><p>
<p>When you enter the Activity Cost by Location Report, you will first see a datatable with various
numbers in it. Originally, the dataset view is unfiltered; however, you may change this by using
the set of filters located at the top of your screen. These filters allow for you to sort by Position,
Department, Group, Location, and Proximity Factor. These filters will provide a more specific
viewing of the report for you. Should you choose to do so, you may also download the report in
the form of a PDF file. There is a button located above the filters to allow you to do so.</p>
<img src="{{asset('imgs/user-guide/image-060.png')}}" alt="img">
<p>Below these filters is the datatable, which essentially shows you the locations in which activities
are taking place, the type of activities, and the cost for all of them. The specific sections of this
table will be further elaborated below:</p>

<p>Location- The first tab of this table simply tells you what location activities are taking place in.
Some locations may have more activities than others.</p>
<img src="{{asset('imgs/user-guide/image-061.png')}}" alt="img">
<p>Legal/Support- This tab of the report will tell you whether the activities you are viewing fall
under the Legal Services Category, or the Support Activities category. Legal Services refers to
those individuals who are actively practicing law– i.e., a lawyer. Support Activities refers to
individuals who are supporting the practice of law. An example of this would be somebody who
works in the Financial Department, or Administrative Services.</p>

<p>Classification- This part of the report simply tells you where users spend their time. For example,
this could include Litigation, Human Resources, Bankruptcy, Counseling, and more.</p>

<p>Substantive Area- This tells you what area of a department an individual spends their time in. It’s
essentially one step lower in the taxonomy, meaning that what you are viewing is a bit more
specific. For example, if you are viewing the activities under the ‘litigation’ department, the
substantive area would show you activities that take place in that department, such as Appeal,
Case Discovery, Trial Preparation and Trial, and more.</p>

<p>Category- This is the next tab of the report, and it is also the next step below Substantive Area in
the taxonomy. This will tell you a specific type of job in a department that individuals take part
in. For example, Invoicing (Billing) is a part of Credit and Collections, which is a part of
Finance, which is a Support Activity.</p>

<p>Employee Cost– This section of the datatable simply tells you the cost to the firm of all
employees within a certain activity. If a number is higher, that just means that there are more
employees within that activity, thus it is more expensive.</p>

<p>RSF- This tells you the amount of square footage that employees take up within their activities.
RSF stands for ‘Rental Square Footage’. The higher up in a firm that a position is, the higher the
RSF will be.</p>

<p>Hours- This simply tells you the number of hours that employees reported in their survey
responses. It’s showing you the amount of hours that users spend in each activity. If the number
shows a zero, that’s because the amount of hours is a fraction; it’s a very small amount of time.</p>

<p>RSF Cost (Current)- This last section on the report will show you the current RSF cost for each
activity. RSF Cost varies by location. If you would like more clarification on what an RSF Cost
is, please see the section titled <strong>‘Getting Started’</strong> and <strong>‘Location RSF Rates’</strong>.</p> 

</div> 



<div class="container-fluid px-3 hideinhelppdf">
    <div class="flex justify-between items-center cont-mtitle  mb-4">
        <h1 class="text-survey">Activity Cost by Location / {{ $survey->survey_name }}</h1>
        <div class="d-flex py-0 py-md-2"> 
            @if (\Auth::check() && \Auth::user()->hasPermission('surveyExport', $survey))                
                <button class="revnor-btn mr-1" id="pdfBtn">Download PDF</button>                 
            @endif
            <button type="button" class="helpguidebtn mx-1" data-toggle="modal" data-target="#helpdetasurvey"></button>   
        </div>
    </div>
    <div id="individualContent"> 
        <div class="first_part mb-2">
            @include('real_estate.partials.activity-cost-by-location-filter')
        </div>
        <link rel="stylesheet" href="{{ asset('css/report-additional-style.css') }}">
        <div class="row second_part flex items-center justify-center" style="border-top:1px solid #dfdfdf;">
            
        </div>
        <div class="third_part mt-4" style="padding-top: 0;border-top: 3px solid #bfbfbf;">
            <div class="tableContainer table-txtmid table-responsive">
                <table id="costbyLocationTable" 
                    class="table "  
                    style="margin:25px 0;display:none;">
                    <thead>
                        <tr>
                            <th style="border-bottom: none;padding-top:20px;width:120px;">Location</th>
                            @for ($i = 0; $i < $data['depth']; $i++)
                                <th style="border-bottom: none;">{{ $data['thAry'][$i] }}</th>
                            @endfor
                            <th class="text-right" style="border-bottom: none;">Employee Cost</th>
                            <th class="text-right" style="border-bottom: none;">RSF</th>
                            <th class="text-right" style="border-bottom: none;">Hours</th>
                            <th class="text-right" style="border-bottom: none;">RSF Cost (Current)</th>
                        </tr>
                        <tr style="height:20px">                            
                            <th style="border-top: none;"></th>
                            @for ($i = 0; $i < $data['depth']; $i++)
                                <th class="jump-th" style="border-top: none;">
                                    <div class="flex justify-center jump-btn">
                                        @if ($i == $data['depth'] - 1) 
                                            <svg onclick="JumpToQuestionsByDepth({{ $i + 2 }});" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"><path d="M328 544h152v152c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V544h152c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H544V328c0-4.4-3.6-8-8-8h-48c-4.4 0-8 3.6-8 8v152H328c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8z" fill="currentColor"/><path d="M880 112H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V144c0-17.7-14.3-32-32-32zm-40 728H184V184h656v656z" fill="currentColor"/></svg>
                                        @else
                                            <svg onclick="JumpToQuestionsByDepth({{ $i + 1 }});" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"><path d="M328 544h368c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H328c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8z" fill="currentColor"/><path d="M880 112H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V144c0-17.7-14.3-32-32-32zm-40 728H184V184h656v656z" fill="currentColor"/></svg>
                                        @endif
                                    </div>
                                </th>
                            @endfor
                            <th class="text-right" style="border-top: none;"></th>
                            <th class="text-right" style="border-top: none;"></th>
                            <th class="text-right" style="border-top: none;"></th>
                            <th class="text-right" style="border-top: none;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['costData'] as $locationData)
                            @foreach ($locationData['rows'] as $row)
                                @php
                                    $questionDescAry = explode('..', $row['question_desc']);
                                @endphp
                                <tr>
                                    <td class="questionDescTD0"><b>{{ $row['location'] }}</b></td>
                                    @for ($j = 0; $j < $data['depth']; $j++)
                                        <td class="questionDescTD{{ $j + 1 }}" data-option="{{ $row['location'] }}">{{ array_key_exists($j, $questionDescAry) ? $questionDescAry[$j] : '' }}</td>
                                    @endfor
                                    <td class="text-right">{{ number_format(round($row['employee_cost'])) }}</td>
                                    <td class="text-right">{{ number_format((float) $row['rsf']) }}</td>
                                    <td class="text-right">{{ number_format((float) $row['hours']) }}</td>
                                    <td class="text-right">{{ number_format((float) $row['rsf_cost_current']) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td style="border: none;"></td>
                                <td colspan="{{ $data['depth'] }}">Total</td>
                                <td class="text-right"><b>{{ number_format($locationData['total_employee_cost']) }}</b></td>
                                <td class="text-right"><b>{{ number_format($locationData['total_rsf']) }}</B></td>
                                <td class="text-right"><b>{{ number_format($locationData['total_hours']) }}</B></td>
                                <td class="text-right"><b>{{ number_format($locationData['total_cost_current']) }}</B></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td><b>Grand Total</b></td>
                            <td colspan="{{ $data['depth'] }}"></td>
                            <td class="text-right"><b>{{ number_format($data['total_employee_cost']) }}</b></td>
                            <td class="text-right"><b>{{ number_format($data['total_rsf']) }}</b></td>
                            <td class="text-right"><b>{{ number_format($data['total_hours']) }}</b></td>
                            <td class="text-right"><b>{{ number_format($data['total_rsf_cost_current']) }}</b></td>
                        </tr>
                    </tbody>
                </table>
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
            <p class="text-phead">Activity Cost by Location / {{ $survey->survey_name }}</p> 
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


<p><strong>Activity Cost by Location</strong><p>
<p>When you enter the Activity Cost by Location Report, you will first see a datatable with various
numbers in it. Originally, the dataset view is unfiltered; however, you may change this by using
the set of filters located at the top of your screen. These filters allow for you to sort by Position,
Department, Group, Location, and Proximity Factor. These filters will provide a more specific
viewing of the report for you. Should you choose to do so, you may also download the report in
the form of a PDF file. There is a button located above the filters to allow you to do so.</p>
<img src="{{asset('imgs/user-guide/image-060.png')}}" alt="img">
<p>Below these filters is the datatable, which essentially shows you the locations in which activities
are taking place, the type of activities, and the cost for all of them. The specific sections of this
table will be further elaborated below:</p>

<p>Location- The first tab of this table simply tells you what location activities are taking place in.
Some locations may have more activities than others.</p>
<img src="{{asset('imgs/user-guide/image-061.png')}}" alt="img">
<p>Legal/Support- This tab of the report will tell you whether the activities you are viewing fall
under the Legal Services Category, or the Support Activities category. Legal Services refers to
those individuals who are actively practicing law– i.e., a lawyer. Support Activities refers to
individuals who are supporting the practice of law. An example of this would be somebody who
works in the Financial Department, or Administrative Services.</p>

<p>Classification- This part of the report simply tells you where users spend their time. For example,
this could include Litigation, Human Resources, Bankruptcy, Counseling, and more.</p>

<p>Substantive Area- This tells you what area of a department an individual spends their time in. It’s
essentially one step lower in the taxonomy, meaning that what you are viewing is a bit more
specific. For example, if you are viewing the activities under the ‘litigation’ department, the
substantive area would show you activities that take place in that department, such as Appeal,
Case Discovery, Trial Preparation and Trial, and more.</p>

<p>Category- This is the next tab of the report, and it is also the next step below Substantive Area in
the taxonomy. This will tell you a specific type of job in a department that individuals take part
in. For example, Invoicing (Billing) is a part of Credit and Collections, which is a part of
Finance, which is a Support Activity.</p>

<p>Employee Cost– This section of the datatable simply tells you the cost to the firm of all
employees within a certain activity. If a number is higher, that just means that there are more
employees within that activity, thus it is more expensive.</p>

<p>RSF- This tells you the amount of square footage that employees take up within their activities.
RSF stands for ‘Rental Square Footage’. The higher up in a firm that a position is, the higher the
RSF will be.</p>

<p>Hours- This simply tells you the number of hours that employees reported in their survey
responses. It’s showing you the amount of hours that users spend in each activity. If the number
shows a zero, that’s because the amount of hours is a fraction; it’s a very small amount of time.</p>

<p>RSF Cost (Current)- This last section on the report will show you the current RSF cost for each
activity. RSF Cost varies by location. If you would like more clarification on what an RSF Cost
is, please see the section titled <strong>‘Getting Started’</strong> and <strong>‘Location RSF Rates’</strong>.</p>
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



        var survey_id = @php echo $data['survey'] -> survey_id; @endphp;

        var imgData_1, imgData_2, imgData_3, imgData_4, copyrightData, headerData;

        let formatter = new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD',
                        minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
                        maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
                    });

        let numberFormatter = new Intl.NumberFormat('en-US');

        // Handle the pdf button click, generate image data from the body
        $('#pdfBtn').click(function () {
            $('#headerDiv').show();
            $('#copyright_div').addClass('d-flex');
			$('#copyright_div').show();	 			
            $('#generatePDFModal').modal('show');
            source = $('#individualContent .first_part');
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
            html2canvas(source).then(function (canvas) {
                imgData_3 = canvas.toDataURL('image/jpeg', 1.0);
                $('#generatePDFModal .modal-body').html('Generated a PDF');
                $('#generatePDFModal .btn').attr('disabled', false);
                $('#headerDiv').hide();
					$('#copyright_div').removeClass('d-flex');
			        $('#copyright_div').hide();	 				
            });
        });

        $(document).ready(function () {
            mask_height = $('body').height();
            $('.loading-mask').css('height', mask_height);
            $('.loading-mask').show();
            for (let i = 0; i < depthQuestion; i++) {
                var span = 1;
                var prevTD = "";
                var prevTDVal = "";
                var prevTDOption = "";
                $(`td.questionDescTD${i}`).each(function() {
                    var $this = $(this);
                    if ($this.text() == prevTDVal && $this.attr('data-option') == prevTDOption) { // check value of previous td text
                        span++;
                        if (prevTD != "") {
                            prevTD.attr("rowspan", span); // add attribute to previous td
                            $this.remove(); // remove current td
                        }
                    } else {
                        prevTD     = $this; // store current td
                        prevTDVal  = $this.text();
                        prevTDOption  = $this.attr('data-option');
                        span       = 1;
                    }
                });
            }
            $('#costbyLocationTable').css('display', 'table');
            $('.loading-mask').hide();
        });

        /**
        * Generate pdf document of report
        *
        * @return {void}
        */
        function generatePDF () {
            let imgWidth = $('#individualContent .first_part').outerWidth();
            pdfdoc = new jsPDF('p', 'mm', 'a4');
            imgHeight1 = Math.round($('#individualContent .first_part').outerHeight() * 190 / imgWidth);
            y = 10;
            position = y;
            doc_page = 1;

            /* pdfdoc.addImage(imgData_1, 'JPEG', 10, y, 190, imgHeight1);
            y += imgHeight1; */

            imgHeight2 = Math.round($('#individualContent .second_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_2, 'JPEG', 10, y, 190, imgHeight2);
            y += imgHeight2;

            imgHeight3 = Math.round($('#individualContent .third_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_3, 'JPEG', 10, y, 190, imgHeight3);
            y += imgHeight3;

            pageHeight = pdfdoc.internal.pageSize.height - 40;
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

            pdfdoc.save(`Activity Cost by Location Report({{$data['survey']->survey_name}})`);
            $('#pdfBtn').html('Download PDF');
            $('#pdfBtn').prop('disabled', false);
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .btn').attr('disabled', true);
        }
        /**
        * Zoom in or out the report with the depth of taxonomy
        *
        * @param {number} depth
        * @return {void}
        */
        function JumpToQuestionsByDepth (depth) {
            depthQuestion = depth;

            $.ajax({
                url: "{{ route('realestate.filter-activity-cost-by-location') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "position": JSON.stringify(options['position']),
                    "department": JSON.stringify(options['department']),
                    "group": JSON.stringify(options['group']),
                    "location": JSON.stringify(options['location']),
                    "proximity": options['proximity'],
                    "depthQuestion": depthQuestion,
                },
                dataType: 'json',
                beforeSend: function () {
                    mask_height = $('body').height();
                    $('.loading-mask').css('height', mask_height);
                    $('.loading-mask').show();
                    $('.dropdown-menu').removeClass('show');
                },
                success: function (res) {
                    if (res.costData == 404) {
                        Toast.fire({
                            icon: 'error',
                            title: 'No more record.'
                        });
                    } else {
                        costData = res.costData;
                        $tableContainer = $('.tableContainer');

                        strHtml = `<table id="costbyLocationTable" 
                                        class="table" 
                                        style="width:96%;margin:25px 2%;display:none;">
                                        <thead>
                                            <tr>
                                                <th style="border-bottom: none;padding-top:20px;width:120px;">Location</th>`;

                        for (let i = 0; i < depthQuestion; i++) {
                            strHtml += `<th style="border-bottom: none;">${res.thAry[i]}</th>`;
                        }

                        strHtml += `<th class="text-right" style="border-bottom: none;">Employee Cost</th>
                                    <th class="text-right" style="border-bottom: none;">RSF</th>
                                    <th class="text-right" style="border-bottom: none;">Hours</th>
                                    <th class="text-right" style="border-bottom: none;">RSF Cost(Current)</th>
                                </tr>
                                <tr style="height:20px">                            
                                    <th style="border-top: none;"></th>`;

                        for (let i = 0; i < depthQuestion; i++) {
                            strHtml += `<th class="jump-th" style="border-top: none;">
                                            <div class="flex justify-center jump-btn">`;
                            if (i == depthQuestion - 1) {
                                strHtml += `<svg onclick="JumpToQuestionsByDepth(${i + 2});" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"><path d="M328 544h152v152c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V544h152c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H544V328c0-4.4-3.6-8-8-8h-48c-4.4 0-8 3.6-8 8v152H328c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8z" fill="currentColor"/><path d="M880 112H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V144c0-17.7-14.3-32-32-32zm-40 728H184V184h656v656z" fill="currentColor"/></svg>`;
                            } else {
                                strHtml += `<svg onclick="JumpToQuestionsByDepth(${i + 1});" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"><path d="M328 544h368c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H328c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8z" fill="currentColor"/><path d="M880 112H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V144c0-17.7-14.3-32-32-32zm-40 728H184V184h656v656z" fill="currentColor"/></svg>`;
                            }
                            strHtml += `</div>
                                    </th>`;
                        }

                        strHtml += `<th class="text-right" style="border-top: none;"></th>
                                    <th class="text-right" style="border-top: none;"></th>
                                    <th class="text-right" style="border-top: none;"></th>
                                    <th class="text-right" style="border-top: none;"></th>
                                </tr>
                            </thead>
                            <tbody>`;                        
                        
                        for (const location in costData) {
                            rows = costData[location].rows;
                            
                            for (let i in rows) {
                                strHtml += `<tr>`;
                                strHtml += `<td class="questionDescTD0"><b>${rows[i].location}</b></td>`;
                                questionDescAry = rows[i].question_desc.split("..");
                                for (j = 0; j < depthQuestion ; j++) {
                                    strHtml += `<td class="questionDescTD${j + 1}" data-option="${rows[i].location}" title="${questionDescAry[j]}">${questionDescAry[j]}</td>`;
                                }                                
                                strHtml += `<td class="text-right">${numberFormatter.format(Math.round(rows[i].employee_cost))}</td>
                                            <td class="text-right">${numberFormatter.format(Math.round(rows[i].rsf))}</td>
                                            <td class="text-right">${numberFormatter.format(Math.round(rows[i].hours))}</td>
                                            <td class="text-right">${numberFormatter.format(Math.round(rows[i].rsf_cost_current))}</td>
                                        </tr>`;
                            }

                            strHtml += `<tr>
                                            <td style="border: none;"></td>
                                            <td colspan="${depthQuestion}">Total</td>
                                            <td class="text-right"><b>${numberFormatter.format(Math.round(costData[location].total_employee_cost))}</b></td>
                                            <td class="text-right"><b>${numberFormatter.format(Math.round(costData[location].total_rsf))}</b></td>
                                            <td class="text-right"><b>${numberFormatter.format(Math.round(costData[location].total_hours))}</b></td>
                                            <td class="text-right"><b>${numberFormatter.format(Math.round(costData[location].total_cost_current))}</b></td>
                                        </tr>`;
                        }

                        strHtml += `<tr>
                                        <td><b>Grand Total</b></td>
                                        <td colspan="${depthQuestion}"></td>
                                        <td class="text-right"><b>${numberFormatter.format(Math.round(res.total_employee_cost))}</b></td>
                                        <td class="text-right"><b>${numberFormatter.format(Math.round(res.total_rsf))}</b></td>
                                        <td class="text-right"><b>${numberFormatter.format(Math.round(res.total_hours))}</b></td>
                                        <td class="text-right"><b>${numberFormatter.format(Math.round(res.total_rsf_cost_current))}</b></td>
                                    </tr>`;

                        strHtml += `</tbody>
                                </table>`;

                        $tableContainer.html(strHtml);

                        for (let i = 0; i < depthQuestion; i++) {
                            var span = 1;
                            var prevTD = "";
                            var prevTDVal = "";
                            var prevTDOption = "";
                            $(`td.questionDescTD${i}`).each(function() { 
                                var $this = $(this);
                                if ($this.text() == prevTDVal && $this.attr('data-option') == prevTDOption) { // check value of previous td text
                                    span++;
                                    if (prevTD != "") {
                                        prevTD.attr("rowspan", span); // add attribute to previous td
                                        $this.remove(); // remove current td
                                    }
                                } else {
                                    prevTD     = $this; // store current td 
                                    prevTDVal  = $this.text();
                                    prevTDOption  = $this.attr('data-option');
                                    span       = 1;
                                }
                            });
                        }

                        $('#costbyLocationTable').css('display', 'table');
                    }

                    $('.loading-mask').hide();
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        }
    </script>

@endsection
