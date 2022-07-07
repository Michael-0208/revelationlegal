@extends('layouts.reports')
@section('content')
{{-- {{dd($data)}} --}}
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js">
    </script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js">
    </script>
    <div id="HelpContent" class="modal-body" style="display:none;">
    <p><b>Reports</b></p>
<p>When you click on Reports, you will see four different report options listed: <b>Demographic,Individual, Compilation, and Crosstab</b>.</p> 
    <p><strong>Compilation Report</strong></p>
<p>The Compilation Report is exactly how it sounds– it is a compilation of all of the Individual
Reports that have been put into a single dataset. The Compilation Report is essentially the core
of the whole project– it’s incredibly important. It shows you how a particular group of
employees, or a department, or even a grouping, have worked.</p>

<p>When you first enter this report, the default view that you will see is unfiltered. However, this
can be changed at the top of your screen. You may sort by Position, Department, Group,
Location, and Category.</p>
<img src="{{asset('imgs/user-guide/image-019.png')}}" alt="img">
<p>Right below the filters, you will see the total cost, hours, and total average hourly cost of all
employees combined. Please note that these numbers are only including individuals who have
responded to the survey.</p>

<p>The next portion of the report is the entire taxonomy for all of the individuals compiled together.</p>
<img src="{{asset('imgs/user-guide/image-021.png')}}" alt="img">
<p>The attached picture above is what you will first see. You may click on either Legal Services, or
Support Activities to expand the taxonomy of either. You will find that the taxonomy is listed in
a hierarchical structure, and the further you expand the taxonomy, the more specific it will get.
Eventually, you will find that the taxonomy ends, and you can not get any more specific. Once
you reach that point, you will receive this pop up:</p>
<img src="{{asset('imgs/user-guide/image-022.png')}}" alt="img">
<p>To collapse the activities that you have chosen to expand, simply click on a blue bar, such as the
one shown here, that specifies exactly which activity you are looking at:</p>
<img src="{{asset('imgs/user-guide/image-023.png')}}" alt="img">
<p>You will notice that on the far right side of each layer of the taxonomy, there is a blue icon with
people on it. This is called the Zoom Feature. The zoom feature allows for you to see each
individual that participates in a specific activity. Once you click on this icon, you will be taken to
the list of employees who are a part of whichever taxonomy level you have chosen to expand.</p>
<img src="{{asset('imgs/user-guide/image-024.png')}}" alt="img">
<p>You will be able to sort this list of individuals through a set of filters at the top of this page:</p>
<img src="{{asset('imgs/user-guide/image-025.png')}}" alt="img">
<p>Once you are done viewing the zoom feature, you may scroll to the top and click the ‘Back’
button, which will take you back to the compilation report. Should you choose to do so, you may
also download this list as a PDF file. Once you are back to viewing the compilation report, you
may also choose to download what you are viewing as a PDF file by clicking that option at the
top right of your screen.</p>

</div> 

    <div class="container-fluid px-3 hideinhelppdf">  
    <div class="flex justify-between items-center cont-mtitle  mb-4" >
        <h1 class="text-survey">Compilation Dashboard / {{ $survey->survey_name }}</h1>
        <div class="d-flex py-0 py-md-2">
             
            <button class="revnor-btn ml-3  mr-md-1 mb-3" onclick="goBackList();" id="backBtn">Back</button>
            @if (\Auth::check() && \Auth::user()->hasPermission('surveyPrint', $survey))
                <button class="revnor-btn mr-1 " id="pdfBtn">Download PDF</button>
            @endif
            @if (\Auth::check() && \Auth::user()->hasPermission('surveyExport', $survey))                
                {{-- <button class="btn btn-revelation-primary mr-1" id="excelBtn">Export to Excel</button> --}}
            @endif
            <button type="button" class="helpguidebtn mx-1" data-toggle="modal" data-target="#helpdetasurvey"></button>
        </div> 
    </div> 
    <div  id="compilationDetailContent" style="background-color:white;">
        <div class="flex px-4 pt-2 justify-between items-center border-b first_part" style="background-color: white;">
            <div class="lead_in">
                <h5 class="text-lg">Compilation Report Zoom to <span class="respNum"></span> Participants</h5>
                <h6 style="color:#215c98;font-weight:bold;" class="questionName">Legal Services</h6>
            </div>

        </div>
        <div class="second_part  pt-2 flex justify-center items-center border-b" style="background-color: white;">
            <div>Filters-</div> 
            <div class="detail_group px-4 border-r"><span style="color:lightgray;">Group: </span><span class="filterTitle" style="color: red;">{{ '' }}</span></div>
            <div class="detail_position px-4 border-r"><span style="color:lightgray;">Position: </span><span class="filterTitle" style="color: red;">{{ '' }}</span></div>
            <div class="detail_department px-4 border-r"><span style="color:lightgray;">Department: </span><span class="filterTitle" style="color: red;">{{ '' }}</span></div>
            <div class="detail_category px-4 border-r"><span style="color:lightgray;">Employee Category: </span><span class="filterTitle" style="color: red;">{{ '' }}</span></div>
            <div class="detail_location px-4"><span style="color:lightgray;">Location: </span><span class="filterTitle" style="color: red;">{{ '' }}</span></div>
        </div>
        <div class="third_part table-responsive" style="background-color: white; border:1px solid #fff;">
 
        </div>
    </div>
    <div id="compilationReportContent" style="background-color: white;">
        <div class="second_part" style="padding-bottom:10px;background-color:white;">
            @include('reports.partials.compilationfilter')
        </div>
        <div class="third_part" style="background-color: white; border:1px solid #fff;">
            <div class="row border-t mx-0 border-b">
                <div class="col-md-4 stat_item">
                    <h3>$<span id="varyingTotalCost">{{ number_format($data['total_cost']) }}</span></h3>
                    <p>Total Cost*</p>
                </div>
                <div class="col-md-4 stat_item">
                    <h3 id="varyingTotalHours">{{ number_format($data['total_hours']) }}</h3>
                    <p>Total Hours*</p>
                </div>
                <div class="col-md-4 stat_item">
                    <h3>$<span id="varyingAvgHourlyCost">{{ number_format($data['hourly_rate']) }}</span></h3>
                    <p>Total Avg. Hourly Cost*</p>
                </div>
            </div>
            <div class="text-right p-2">
                * Values only include eployees who responded to the survey
            </div>
            <div id="ServiceClass">
                <div class="service_container px-0" id="question1-layer">
                    <div>
                        <a class="lightblueback btn-block text-left service_bar" data-toggle="collapse" href="#Root1" role="button" aria-expanded="false" aria-controls="Root1">
                            <span class="service_bar_title">Legal and Support</span> | {{ $data['respondents_num'] }} respondents
                        </a>
                        <div class="collapse show" id="Root1">
                            <div class="card card-body border-none p-0">
                                <table class="service_table table-responsive"> 
                                    <thead>
                                        <tr>
                                            <th style="width: 30%;"></th>
                                            <th style="text-align: right; width: 10%;">Hours</th>
                                            <th style="text-align: right; width: 10%;">Cost</th>
                                            <th style="text-align: right; width: 10%;">Avg.Hourly Cost</th>
                                            <th style="text-align: left;color:#82BD5E;width: 15%;">% of Total Cost</th>
                                            <th style="text-align: left;color:#367BC1;width: 15%;">% of Cost within selection</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($data['legal_id']))
                                        <tr onclick="getChildServiceData({{ $data['legal_id'] }}, {{ $data['legal_hours'] }}, {{ $data['legal_cost'] }}, 1, 'Legal Services', '');">
                                            @php
                                                if ($data['legal_hours'] > 0) {
                                                    $legal_hourly = round($data['legal_cost'] / $data['legal_hours']);
                                                } else {
                                                    $legal_hourly = 0;
                                                }
                                                if ($data['total_cost'] > 0) {
                                                    $legal_percent = round($data['legal_cost'] / $data['total_cost'] * 100);
                                                } else {
                                                    $legal_percent = 0;
                                                }
                                            @endphp
                                            <td style="text-align:right;">Legal Services</td>
                                            <td style="text-align: right;">{{ number_format($data['legal_hours']) }}</td>
                                            <td style="text-align: right;">${{ number_format($data['legal_cost']) }}</td>
                                            <td style="text-align: right;">${{ number_format($legal_hourly) }}</td>
                                            <td>
                                                <div class="bar-graph flex items-center justify-start" style="width: 100%;">
                                                    <div class="bg-support" style="width:calc(80% * {{ $legal_percent }} / 100);height:24px;"></div>
                                                    <span class="px-1">{{ $legal_percent }}%</span>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td class="btn-detailList">
                                                <button class="btn btn-revelation-primary" onclick="getDetailResp({{ $data['legal_id'] }}, 'Legal Service', {{ $data['legal_hours'] }});" title="View Participants for Legal Services">
                                                    <svg class="respDetailBtn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.25em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 640 512"><path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6c40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32S208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z" fill="white"/></svg>
                                                </button>

                                            </td>
                                        </tr>
                                        @endif
                                        @if (isset($data['support_id']))
                                        <tr onclick="getChildServiceData({{ $data['support_id'] }}, {{ $data['support_hours'] }}, {{ $data['support_cost'] }}, 1, '{{ $data['support_label'] }}', '');">
                                            @php
                                                if ($data['support_hours'] > 0) {
                                                    $support_hourly = round($data['support_cost'] / $data['support_hours']);
                                                } else {
                                                    $support_hourly = 0;
                                                }
                                                if ($data['support_cost'] > 0) {
                                                    $support_percent = round($data['support_cost'] / $data['total_cost'] * 100);
                                                } else {
                                                    $support_percent = 0;
                                                }
                                            @endphp
                                            <td style="text-align:right;">{{ $data['support_label'] }}</td>
                                            <td style="text-align: right;">{{ number_format($data['support_hours']) }}</td>
                                            <td style="text-align: right;">${{ number_format($data['support_cost']) }}</td>
                                            <td style="text-align: right;">${{ number_format($support_hourly) }}</td>
                                            <td>
                                                <div class="bar-graph flex items-center justify-start" style="width: 100%;">
                                                    <div class="bg-support" style="width:calc(80% * {{ $support_percent }} / 100);height:24px;"></div>
                                                    <span class="px-1">{{ $support_percent }}%</span>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td class="btn-detailList">
                                                <button class="btn btn-revelation-primary" onclick="getDetailResp({{ $data['support_id'] }}, 'Support Activities', {{ $data['support_hours'] }});" title="View Participants for Support Activities">
                                                    <svg class="respDetailBtn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.25em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 640 512"><path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6c40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32S208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z" fill="white"/></svg>
                                                </button>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="service_container" id="question2-layer"></div>
                <div class="service_container" id="question3-layer"></div>
                <div class="service_container" id="question4-layer"></div>
                <div class="service_container" id="question5-layer"></div>
                <div class="service_container" id="question6-layer"></div>
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
            <p class="text-phead">Compilation Dashboard /{{ $survey->survey_name }}</p>
            <p class="redtext-phead">Confidential</p>
            {{-- <img  src="{{asset('imgs/logo-pdfhead.png')}}">   --}}  
    </div> 
    
    
    <div class="loading-mask"></div>
    <div class="modal fade" tabindex="-1" role="dialog" id="notimeModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-body">
                <p>There is no record for this level.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
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
    <p><b>Reports</b></p>
<p>When you click on Reports, you will see four different report options listed: <b>Demographic,Individual, Compilation, and Crosstab</b>.</p>   
    <p><strong>Compilation Report</strong></p>
<p>The Compilation Report is exactly how it sounds– it is a compilation of all of the Individual
Reports that have been put into a single dataset. The Compilation Report is essentially the core
of the whole project– it’s incredibly important. It shows you how a particular group of
employees, or a department, or even a grouping, have worked.</p>

<p>When you first enter this report, the default view that you will see is unfiltered. However, this
can be changed at the top of your screen. You may sort by Position, Department, Group,
Location, and Category.</p>
<img src="{{asset('imgs/user-guide/image-019.png')}}" alt="img">
<p>Right below the filters, you will see the total cost, hours, and total average hourly cost of all
employees combined. Please note that these numbers are only including individuals who have
responded to the survey.</p>

<p>The next portion of the report is the entire taxonomy for all of the individuals compiled together.</p>
<img src="{{asset('imgs/user-guide/image-021.png')}}" alt="img">
<p>The attached picture above is what you will first see. You may click on either Legal Services, or
Support Activities to expand the taxonomy of either. You will find that the taxonomy is listed in
a hierarchical structure, and the further you expand the taxonomy, the more specific it will get.
Eventually, you will find that the taxonomy ends, and you can not get any more specific. Once
you reach that point, you will receive this pop up:</p>
<img src="{{asset('imgs/user-guide/image-022.png')}}" alt="img">
<p>To collapse the activities that you have chosen to expand, simply click on a blue bar, such as the
one shown here, that specifies exactly which activity you are looking at:</p>
<img src="{{asset('imgs/user-guide/image-023.png')}}" alt="img">
<p>You will notice that on the far right side of each layer of the taxonomy, there is a blue icon with
people on it. This is called the Zoom Feature. The zoom feature allows for you to see each
individual that participates in a specific activity. Once you click on this icon, you will be taken to
the list of employees who are a part of whichever taxonomy level you have chosen to expand.</p>
<img src="{{asset('imgs/user-guide/image-024.png')}}" alt="img">
<p>You will be able to sort this list of individuals through a set of filters at the top of this page:</p>
<img src="{{asset('imgs/user-guide/image-025.png')}}" alt="img">
<p>Once you are done viewing the zoom feature, you may scroll to the top and click the ‘Back’
button, which will take you back to the compilation report. Should you choose to do so, you may
also download this list as a PDF file. Once you are back to viewing the compilation report, you
may also choose to download what you are viewing as a PDF file by clicking that option at the
top right of your screen.</p>
 
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
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



        var survey_id = {{ $data['survey']->survey_id }};
        var total_cost = {{ $data['total_cost'] }};
        var excelData;
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
        var imgData_1, imgData_2, imgData_3, imgData_4, copyrightData, headerData;

        Object.size = function(obj) {
            var size = 0,
                key;
            for (key in obj) {
                if (obj.hasOwnProperty(key)) size++;
            }
            return size;
        };

        $(document).ready(function() {
            $('#backBtn').hide();
            $('#excelBtn').hide();
            $('#backBtn').css('opacity', '1');
        });
        // Get the child service data for compilation report
        function getChildServiceData (parent_id, parent_hours, parent_cost, divNum, title, parentTitle) {
            for (let i = 1; i < 8; i++) {
                if (i > divNum) {
                    $('#question' + i + '-layer').empty();
                }
            }

            let newdivNum = divNum + 1;

            $('#question' + newdivNum + '-layer').html(`<div class="text-gray text-center">Loading...</div>`);

            $.ajax({
                url: '{{ route('getCompilationChildServiceData') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "parent_id": parent_id,
                    "parent_hours": parent_hours,
                    "parent_cost": parent_cost,
                    "total_cost": total_cost,
                    "position": JSON.stringify(options['position']),
                    "department": JSON.stringify(options['department']),
                    "group": JSON.stringify(options['group']),
                    "location": JSON.stringify(options['location']),
                    "category": JSON.stringify(options['category']),
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status && response.status == 400) {
                        $('#question' + newdivNum + '-layer').empty();
                        // $('#notimeModal').modal();
                        Swal.fire({
                            text: 'There is no detail below this level.',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        let strHtml = "";
                        if (parentTitle != '') {
                            title = parentTitle + ` &rarr; ` + title;
                        }
                        response.forEach(item => {
                            // console.log(item);

                            

                            if (strHtml == "") {
                                strHtml = `<div>
                                            <a class="btn btn-revelation-primary btn-block text-left service_bar" data-toggle="collapse" href="#Root${newdivNum}" role="button" aria-expanded="false" aria-controls="Root${newdivNum}">
                                                <span class="service_bar_title">${title}</span> | ${item.resp_num} respondents
                                            </a>
                                            <div class="collapse" id="Root${newdivNum}">
                                                <div class="card card-body border-none p-0">
                                                    <table class="service_table table-responsive"> 
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 30%;"></th>
                                                                <th style="text-align: right;width: 10%;">LEDES Codes</th>
                                                                <th style="text-align: right;width: 10%;">Hours</th>
                                                                <th style="text-align: right;width: 10%;">Cost</th>
                                                                <th style="text-align: right;width: 10%;">Avg.Hourly Cost</th>
                                                                <th style="text-align: left;color:#82BD5E;width: 15%;">% of Total Cost</th>
                                                                <th style="text-align: left;color:#367BC1;width: 15%;">% of Cost within selection</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>`;

                            }
                            strHtml += `<tr>
                                            <td onclick="getChildServiceData(${item.question_id}, ${item.hours}, ${item.cost}, ${newdivNum}, '${item.question_desc}', '${title}');" style="text-align:right;width: 30%;">${item.question_desc}</td>
                                            
                                            <td style="text-align:right;width: 10%;">${(item.lead_code !== null) ? item.lead_code :''}</td>
                                            
                                            <td onclick="getChildServiceData(${item.question_id}, ${item.hours}, ${item.cost}, ${newdivNum}, '${item.question_desc}', '${title}');" style="text-align: right;width: 10%;">${numberFormatter.format(item.hours)}</td>
                                            <td onclick="getChildServiceData(${item.question_id}, ${item.hours}, ${item.cost}, ${newdivNum}, '${item.question_desc}', '${title}');" style="text-align: right;width: 10%;">${currencyFormatter.format(item.cost)}</td>
                                            <td onclick="getChildServiceData(${item.question_id}, ${item.hours}, ${item.cost}, ${newdivNum}, '${item.question_desc}', '${title}');" style="text-align: right;width: 10%;">${currencyFormatter.format(item.avg_hourly_cost)}</td>
                                            <td onclick="getChildServiceData(${item.question_id}, ${item.hours}, ${item.cost}, ${newdivNum}, '${item.question_desc}', '${title}');" style="width:15px">
                                                <div class="bar-graph flex items-center justify-start" style="width: 100%;">
                                                    <div class="bg-support" style="width:calc(80% * ${item.total_cost_pct} / 100);height:24px;"></div>
                                                    <span class="px-1">${item.total_cost_pct}%</span>
                                                </div>
                                            </td>
                                            <td onclick="getChildServiceData(${item.question_id}, ${item.hours}, ${item.cost}, ${newdivNum}, '${item.question_desc}', '${title}');" style="width:15px"  >
                                                <div class="bar-graph flex items-center justify-start" style="width: 100%;">
                                                    <div class="bg-revelation" style="width:calc(80% * ${item.selection_cost_pct} / 100);height:24px;"></div>
                                                    <span class="px-1">${item.selection_cost_pct}%</span>
                                                </div>
                                            </td>
                                            <td class="btn-detailList">
                                                <button class="btn btn-revelation-primary btn-detail-list" data-questionId="${item.question_id}" data-questionDesc="${item.question_desc}" data-hours="${item.hours}" title="View Participants for ${item.question_desc}">
                                                    <svg class="respDetailBtn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.25em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 640 512"><path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6c40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32S208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z" fill="white"/></svg>
                                                </button>
                                            </td>
                                        </tr>`;
                        });

                        strHtml += `    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>`;

                        $('#question' + newdivNum + '-layer').html(strHtml);
                        $('#question' + newdivNum + '-layer .btn-revelation-primary').click();

                        $('.service_table tbody tr').click(function() {
                            $(this).parent().find('tr').css('background-color', 'white');
                            $(this).css('background-color', 'rgba(54, 123, 193, 0.3)');
                        });

                        $('.btn-detail-list').click(function() {
                            mask_height = $('body').height();
                            $('.loading-mask').css('height', mask_height);
                            $('.loading-mask').fadeIn();
                            let question_id = $(this).attr('data-questionId');
                            let question_desc = $(this).attr('data-questionDesc');
                            let total_hours = $(this).attr('data-hours');
                            $detailContainer = $('#compilationDetailContent .third_part');
                            $detailContainer.empty();
                            $detailContainer.append(`<table class="table table-sm border-0" border="0" id="detailRespTable">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Employee ID</th>
                                        <th>Employee Category</th>
                                        <th>Department</th>
                                        <th>Position</th>
                                        <th>Location</th>
                                        <th>Hours</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>`);
                            $.ajax({
                                url: '{{ route('getDetailCompilationRespsList') }}',
                                type: 'POST',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "survey_id": survey_id,
                                    "question_id": question_id,
                                    "position": JSON.stringify(options['position']),
                                    "department": JSON.stringify(options['department']),
                                    "group": JSON.stringify(options['group']),
                                    "location": JSON.stringify(options['location']),
                                    "category": JSON.stringify(options['category']),
                                },
                                dataType: 'json',
                                success: function (response) {
                                    $('.loading-mask').fadeOut();
                                    $('#compilationReportContent').css('display', 'none');
                                    $('#compilationDetailContent').css('display', 'block');
                                    $('#backBtn').fadeIn();
                                    $('#excelBtn').fadeIn();
                                    $('.questionName').html(question_desc);

                                    excelData = response;
                                    let size = Object.size(response)
                                    $('.respNum').html(size);
                                    $detailTable = $('#detailRespTable tbody');
                                    $detailTable.empty();
                                    $headerTable = $('#detailRespTable thead');
                                    $('#detailRespTable thead .table-primary').remove();
                                    $headerTable.append(`<tr class="table-primary" style="font-weight:bold;">
                                                            <th colspan="7"><div style="float:left;">Grand Total</div><div style="float:right;">${hoursFormatter.format(total_hours)}</div></th>
                                                        </tr>`);
                                    response.forEach(item => {
                                        let strHtml = `<tr>
                                                    <td>${item.name}</td>
                                                    <td>${item.employee_id}</td>
                                                    <td>${item.employee_category}</td>
                                                    <td>${item.department}</td>
                                                    <td>${item.position}</td>
                                                    <td>${item.location}</td>
                                                    <td style="text-align:right;">${hoursFormatter.format(item.hours)}</td>
                                                </tr>`;
                                        $detailTable.append(strHtml);
                                    });
                                    $('#detailRespTable').DataTable({
                                        paging: false,
                                        searching: false,
                                        columnsDefs: [
                                            { orderable: false, targets: -1}
                                        ]
                                    });
                                }
                            });
                        });
                    }
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        }
        // Get the respondent detail list
        function getDetailResp (question_id, question_desc, total_hours) {
            mask_height = $('body').height();
            $('.loading-mask').css('height', mask_height);
            $('.loading-mask').fadeIn();
            $detailContainer = $('#compilationDetailContent .third_part');
            $detailContainer.empty();
            $detailContainer.append(`<table class="table table-sm border-0" id="detailRespTable">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Employee ID</th>
                        <th>Employee Category</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Location</th>
                        <th>Hours</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>`);
            $detailTable = $('#detailRespTable tbody');
            $detailTable.empty();
            $headerTable = $('#detailRespTable thead');
            $('#detailRespTable thead .table-primary').remove();
            $.ajax({
                url: '{{ route('getDetailCompilationRespsList') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "question_id": question_id,
                    "position": JSON.stringify(options['position']),
                    "department": JSON.stringify(options['department']),
                    "group": JSON.stringify(options['group']),
                    "location": JSON.stringify(options['location']),
                    "category": JSON.stringify(options['category']),
                },
                dataType: 'json',
                success: function (response) {
                    $('.loading-mask').fadeOut();
                    $('#compilationReportContent').css('display', 'none');
                    $('#compilationDetailContent').css('display', 'block');
                    $('#backBtn').fadeIn();
                    $('#excelBtn').fadeIn();
                    $('.questionName').html(question_desc);
                    excelData = response;
                    let size = Object.size(response)
                    $('.respNum').html(size);
                    $headerTable.append(`<tr class="table-primary" style="font-weight:bold;">
                                            <th colspan="7"><div style="float:left;">Grand Total</div><div style="float:right;">${hoursFormatter.format(total_hours)}</div></th>
                                        </tr>`);
                    response.forEach(item => {
                        let strHtml = `<tr>
                                    <td>${item.name}</td>
                                    <td>${item.employee_id}</td>
                                    <td>${item.employee_category}</td>
                                    <td>${item.department}</td>
                                    <td>${item.position}</td>
                                    <td>${item.location}</td>
                                    <td style="text-align:right;">${hoursFormatter.format(item.hours)}</td>
                                </tr>`;
                        $detailTable.append(strHtml);
                    });
                    $('#detailRespTable').DataTable({
                        paging: false,
                        searching: false,
                        columnsDefs: [
                            { orderable: false, targets: -1}
                        ]
                    });
                },
                complete: function () {
                    $(this).data('requestRunning', false);
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        }

        function goBackList () {
            $('#compilationReportContent').css('display', 'block');
            $('#compilationDetailContent').css('display', 'none');
            $('#backBtn').hide();
            $('#excelBtn').hide();
        }

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
                //imgHeight2 = Math.round($('#compilationDetailContent .second_part').outerHeight() * 190 / imgWidth);
               // pdfdoc.addImage(imgData_2, 'JPEG', 10, y, 190, imgHeight2);
                //y += imgHeight2;
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
                    pdfdoc.addImage(headerData, 'JPEG', 10, 0, 190, 14); 
                    pdfdoc.addImage(copyrightData, 'JPEG', 10, 282, 190, 14.5);  
                    pdfdoc.setTextColor(111,107,107); 
                pdfdoc.setFontSize(8); 
                pdfdoc.text('Page ' + i + ' of ' + (doc_page-1) , 168, 290, 0, 45);    
                }
    
                pdfdoc.save(`Compilation Report({{$data['survey']->survey_name}})`);
            } else {
                imgHeight1 = Math.round($('#compilationDetailContent .first_part').outerHeight() * 190 / imgWidth);
                y = 14;
                position = y;
                doc_page = 1;

                imgHeight2 = Math.round($('#compilationDetailContent .second_part').outerHeight() * 190 / imgWidth);
                pdfdoc.addImage(imgData_2, 'JPEG', 10, y, 190, imgHeight2); 
                y += imgHeight2;
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
                    pdfdoc.addImage(headerData, 'JPEG', 10, 0, 190, 14); 
                    pdfdoc.addImage(copyrightData, 'JPEG', 10, 282, 190, 14.5);
                    // pdfdoc.text('Page ' + i + ' of ' + (doc_page-1) , 8, 275, 90.916667, 52.916667, null, null, 45);
                    pdfdoc.setFontSize(8); 
                     pdfdoc.text('Page ' + i + ' of ' + (doc_page-1) , 168, 291, 0, 45);  
                } 

                pdfdoc.save(`Compilation Report({{$data['survey']->survey_name}})`);
            }
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .btn').attr('disabled', true);
        }

        $('.service_table tbody tr').click(function() {
            $(this).parent().find('tr').css('background-color', 'white');
            $(this).css('background-color', 'rgba(54, 123, 193, 0.3)');
        });

        // Handle the pdf button click, generate image data from the body
        $('#pdfBtn').click(function () {
            $('#headerDiv').show();
			 $('#copyright_div').addClass('d-flex');
			$('#copyright_div').show();
            $('td.btn-detailList').hide(); 
            $('#generatePDFModal').modal('show');
            if ($('#compilationReportContent').css('display') == 'none') {
                source = $('#compilationDetailContent .first_part');
                html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    imgData_1 = canvas.toDataURL("image/png", 1.0);
                        
                });
                source = $('#compilationDetailContent .second_part');

                html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    imgData_2 = canvas.toDataURL("image/png", 1.0);
                        
                });
                // Copyright
                source = $('#copyright_div');
                html2canvas(source, {
                    //s//cale:3
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
                    $('#compilationReportContent .btn-detailList').css('display', 'block');                    
                    $('#generatePDFModal .modal-body').html(`<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="24" height="24" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path d="M30 18v-2h-6v10h2v-4h3v-2h-3v-2h4z" fill="currentColor"/><path d="M19 26h-4V16h4a3.003 3.003 0 0 1 3 3v4a3.003 3.003 0 0 1-3 3zm-2-2h2a1.001 1.001 0 0 0 1-1v-4a1.001 1.001 0 0 0-1-1h-2z" fill="currentColor"/><path d="M11 16H6v10h2v-3h3a2.003 2.003 0 0 0 2-2v-3a2.002 2.002 0 0 0-2-2zm-3 5v-3h3l.001 3z" fill="currentColor"/><path d="M22 14v-4a.91.91 0 0 0-.3-.7l-7-7A.909.909 0 0 0 14 2H4a2.006 2.006 0 0 0-2 2v24a2 2 0 0 0 2 2h16v-2H4V4h8v6a2.006 2.006 0 0 0 2 2h6v2zm-8-4V4.4l5.6 5.6z" fill="currentColor"/></svg> &nbsp; Generated a PDF`);
                    $('#generatePDFModal .btn').attr('disabled', false);
                    $('#headerDiv').hide();
                    $('td.btn-detailList').show();									$('#copyright_div').removeClass('d-flex');
					$('#copyright_div').hide();  
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
                $('#compilationReportContent .btn-detail-list').css('display', 'none');
                source = $('#compilationReportContent .third_part');
                html2canvas(source, {
                    onrendered: function (canvas) {   
                        imgData_3 = canvas.toDataURL('image/jpeg', 1.0);
                    }
                }).then(function (canvas) {
                    $('#compilationReportContent .btn-detail-list').css('display', 'block');
                    $('#generatePDFModal .modal-body').html(`<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="24" height="24" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path d="M30 18v-2h-6v10h2v-4h3v-2h-3v-2h4z" fill="currentColor"/><path d="M19 26h-4V16h4a3.003 3.003 0 0 1 3 3v4a3.003 3.003 0 0 1-3 3zm-2-2h2a1.001 1.001 0 0 0 1-1v-4a1.001 1.001 0 0 0-1-1h-2z" fill="currentColor"/><path d="M11 16H6v10h2v-3h3a2.003 2.003 0 0 0 2-2v-3a2.002 2.002 0 0 0-2-2zm-3 5v-3h3l.001 3z" fill="currentColor"/><path d="M22 14v-4a.91.91 0 0 0-.3-.7l-7-7A.909.909 0 0 0 14 2H4a2.006 2.006 0 0 0-2 2v24a2 2 0 0 0 2 2h16v-2H4V4h8v6a2.006 2.006 0 0 0 2 2h6v2zm-8-4V4.4l5.6 5.6z" fill="currentColor"/></svg> &nbsp; Generated a PDF`);
                    $('#generatePDFModal .btn').attr('disabled', false);
                    $('#headerDiv').hide();
					$('#copyright_div').removeClass('d-flex');
					$('#copyright_div').hide();  
                });
            }
        });

        // Handle the event of excel button click
        $('#excelBtn').click(function () {
            $.ajax({
                url: '{{ route("exportCompilationExcelData") }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "excelData": excelData,
                    "survey_name": "{{ $data['survey']->survey_name }}",
                    "label": $('.questionName').html()
                },
                dataType: 'json',
                success: function (res) {
                    var downloadLink = document.createElement("a");
                    downloadLink.href = res.url;
                    downloadLink.download = res.filename;
                    downloadLink.click();
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
    </script>
@endsection
