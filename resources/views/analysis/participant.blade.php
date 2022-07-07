@extends('layouts.reports')
@section('content')


<div id="HelpContent" class="modal-body" style="display:none;">
<p><strong>Participant</strong></p>
<p>When you first enter the Participant Analysis, you will notice a list of employees who have
completed the questionnaire. This list is sorted in alphabetical order. You will see that each
employee has a set of colors next to their name. Please note that these are specific to each
individual. The<strong> Green color represents support activities</strong>, while the <strong>Blue color represents
Legal Services</strong>. In order to view a specific individual’s analysis, simply click on their name, and
their data will appear on your screen.</p>

<p>You will also notice that there are a set of filters located at the top of your screen. The default
view for this analysis is sorted by individuals who have completed the survey, but you may
change that at any given time.</p>
<img src="{{asset('imgs/user-guide/image-039.png')}}" alt="img">
<p>Once you have selected a specific individual, you will be provided with a donut chart, as well as
a set of statistics that correlate to that chart. The chart is split into two colors: blue and green, and
it is simply a more visual way to see how an employee spends their time, and which activities
they participate in.</p>

<p>Along with this, and further to the right, you will see more of an individual’s specific
information. You will be able to see the date in which the individual took their survey, their email
address, and their Employee ID. You will also be able to see their Group, Position, Department,
and Location. Finally, you will be able to see their Compensation, Benefits, and Total
Compensation for the entire year.</p>

<p>Below this information, and further down into the analysis, you will be able to compare the
specific individual’s hours and compensation against all other participants. This is a dotted graph
that simply tells you how the individual that you have selected at the top spends their time
compared to other users who participated in the survey.</p>

<p>You will notice that the graph mostly consists of smaller gray dots, but that there is one larger
blue dot. This blue dot represents the individual whom you’ve selected back at the top of the
analysis. As you scroll over each dot, you will see that information for specific individuals is
displayed. Should you choose to do so, you may click on one of the dots. Doing so will display a
screen that tells you a user’s Department, Employee Category and ID, and name, as well as other
information as shown in the picture below.</p>
<img src="{{asset('imgs/user-guide/image-041.png')}}" alt="img">
<p>You will also notice that this display window also has four options for you to choose from:
Download as a Text File, Keep Only, Exclude, and Close. Download as a Text File allows for
you to download the data you are viewing into a word document. Keep Only means that you will
only keep this employee in the graph. Exclude means that this employee will not be shown on
the data graph, and Close simply means that you are closing the pop up window that you are
viewing.</p>

<img src="{{asset('imgs/user-guide/image-040.png')}}" alt="img">  
</div> 


<div class="container-fluid px-3 hideinhelppdf">
    <div class="flex justify-between items-center cont-mtitle  mb-4">
        <h1 class="text-survey">Participant Analysis / {{ $survey->survey_name }}</h1>
        <div class="d-flex py-0 py-md-2">
        @if (\Auth::check() && \Auth::user()->hasPermission('surveyPrint', $survey))
            <button class="revnor-btn mr-1" id="pdfBtn">Download PDF</button>            
        @endif
        <button type="button" class="helpguidebtn mx-1" data-toggle="modal" data-target="#helpdetasurvey">         
</button>
        </div> 
    </div>
    <div id="individualContent">
        <div class="first_part">
        @include('analysis.partial.participantfilter')
        </div>
        <div class="row second_part">
            <div class="col-md-4 pl-0" id="pdfhidden"> 
                <div class="mb-2">
                    <div class=" table-search px-0">
                        <label for="searchResp" style="margin-bottom:0;margin-right:10px;">Search: </label>
                        <input type="text" class="" id="searchResp">
                        <svg id="searchCloseBtn" class="searchCloseBtn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path d="M16 2C8.2 2 2 8.2 2 16s6.2 14 14 14s14-6.2 14-14S23.8 2 16 2zm0 26C9.4 28 4 22.6 4 16S9.4 4 16 4s12 5.4 12 12s-5.4 12-12 12z" fill="currentColor"/><path d="M21.4 23L16 17.6L10.6 23L9 21.4l5.4-5.4L9 10.6L10.6 9l5.4 5.4L21.4 9l1.6 1.6l-5.4 5.4l5.4 5.4z" fill="currentColor"/></svg>
                    </div>
                    <div class="pb-2">
                        <select class="form-control" name="narrow" id="narrowSelect" >
                            <option value="0">(All)</option>
                            <option value="1">Legal Services</option>
                            <option value="2">Support Activities</option>
                        </select>
                    </div>
                </div>
                <div class="overhide-div">
                <div class="respondentListParent">
                    <div id="respondentList">
                        @foreach ($data['resps'] as $resp)
                            @php
                                $total_hours = $resp->support_hours + $resp->legal_hours;
                                if ($total_hours > 0) {
                                    $support_percent = round(100 * $resp->support_hours / $total_hours);
                                } else {
                                    $support_percent = 0;
                                }
                                $legal_percent = 100 - $support_percent;
                            @endphp
                            <div class="resp_item row" id="respondantId-{{$resp->resp_id}}" onclick="selectRespondent({{$resp->resp_id}}, {{$resp->support_hours}}, {{$resp->legal_hours}});">
                                <div class="col-5 text-right">{{$resp->resp_last}}, {{$resp->resp_first}}</div>
                                <div class="col-7">
                                    <div class="support-bar" style="width:{{$support_percent}}%;"><span style="@if ($support_percent < 10) left: 3px; @endif">{{ $support_percent }}%</span></div>
                                    <div class="legal-bar" style="width:{{$legal_percent}}%;"><span style="@if ($legal_percent < 10) right: 17px; @endif">{{ $legal_percent }}%</span></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                            </div>
                </div>
            </div>
            <div class="col-md-8 row mx-0">
                <div class="col-md-3  pt-3">
                    <h4 id="resp_name" style="text-align: center;"></h4>
                    <div style="display: flex; justify-content: center; align-items:center; height: 90%;">
                    <div class="canvaschartouter nooverflow"> 
                        <div id="chartContainer" style="height: 200px; width:100%;">
                            <p style="padding-top: 50px;">&larr; Select a respondent from the list on the left to get started</p>
                        </div>
                        </div>
                        <div class="chart-total"></div>
                            
                    </div>
                </div>
                <div class="col-md-3" style="display: flex;align-items:center;justify-content:center;">
                    <div>
                        <p class="support_info"></p>
                        <p class="legal_info"></p>
                    </div>
                </div>
                <div class="col-md-6" id="respondent_data" style="display: flex;align-items:center;">
                </div>
            </div>
        </div>
        <div class="third_part" style="padding-top: 0;">
            <div style="height: 20px; border-bottom:4px solid #9f9f9f;"></div>
            <div class="pa-filter-area">
                <div class="flex justify-between items-center px-4 py-2">
                    <div>select to compare to employees with same...</div>
                    <div>
                        <label for="compareCompensation">compare using:</label>
                        <select class="select-sm" name="compareCompensation" id="compareCompensation">
                            <option value="0">Compensation</option>
                            <option value="1">Compensation + Benefits</option>
                        </select>
                    </div>
                </div>
                <div class="p-2 pa-resp-cust" style="border-bottom: 4px solid #9f9f9f;">

                </div>
            </div> 
            <div class="pafa-filter-text">
                <div class="title">Comparing <strong class="comparing-num">{{ count($data['resps']) }}</strong> Employees</div>
                <div>
                    <span class="pafaft-category">Category: <strong>All</strong></span>
                    &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
                    <span class="pafaft-group">Practice Group: <strong>All</strong></span>
                    &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
                    <span class="pafaft-department">Department: <strong>All</strong></span>
                    &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
                    <span class="pafaft-location">Location: <strong>All</strong></span>
                    &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
                    <span class="pafaft-position">Title: <strong>All</strong></span>
                </div>
            </div>
            <div class="pa-chart-area">
                <div id="scatterChartContainer"></div>
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
            <p class="text-phead">Participant Analysis / {{ $survey->survey_name }}</p> 
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
    <p><strong>Participant</strong></p>
<p>When you first enter the Participant Analysis, you will notice a list of employees who have
completed the questionnaire. This list is sorted in alphabetical order. You will see that each
employee has a set of colors next to their name. Please note that these are specific to each
individual. The<strong> Green color represents support activities</strong>, while the <strong>Blue color represents
Legal Services</strong>. In order to view a specific individual’s analysis, simply click on their name, and
their data will appear on your screen.</p>

<p>You will also notice that there are a set of filters located at the top of your screen. The default
view for this analysis is sorted by individuals who have completed the survey, but you may
change that at any given time.</p>
<img src="{{asset('imgs/user-guide/image-039.png')}}" alt="img">
<p>Once you have selected a specific individual, you will be provided with a donut chart, as well as
a set of statistics that correlate to that chart. The chart is split into two colors: blue and green, and
it is simply a more visual way to see how an employee spends their time, and which activities
they participate in.</p>

<p>Along with this, and further to the right, you will see more of an individual’s specific
information. You will be able to see the date in which the individual took their survey, their email
address, and their Employee ID. You will also be able to see their Group, Position, Department,
and Location. Finally, you will be able to see their Compensation, Benefits, and Total
Compensation for the entire year.</p>

<p>Below this information, and further down into the analysis, you will be able to compare the
specific individual’s hours and compensation against all other participants. This is a dotted graph
that simply tells you how the individual that you have selected at the top spends their time
compared to other users who participated in the survey.</p>

<p>You will notice that the graph mostly consists of smaller gray dots, but that there is one larger
blue dot. This blue dot represents the individual whom you’ve selected back at the top of the
analysis. As you scroll over each dot, you will see that information for specific individuals is
displayed. Should you choose to do so, you may click on one of the dots. Doing so will display a
screen that tells you a user’s Department, Employee Category and ID, and name, as well as other
information as shown in the picture below.</p>
<img src="{{asset('imgs/user-guide/image-041.png')}}" alt="img">
<p>You will also notice that this display window also has four options for you to choose from:
Download as a Text File, Keep Only, Exclude, and Close. Download as a Text File allows for
you to download the data you are viewing into a word document. Keep Only means that you will
only keep this employee in the graph. Exclude means that this employee will not be shown on
the data graph, and Close simply means that you are closing the pop up window that you are
viewing.</p>
<img src="{{asset('imgs/user-guide/image-040.png')}}" alt="img">        
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>


    <div class="modal fade" tabindex="-1" role="dialog" id="respDataModal">
        <div class="modal-dialog" role="document" style="max-width:80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <strong>View Data</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <table class="table">
                            <thead>
                                <th>Department</th>
                                <th>Employee Category</th>
                                <th>Employee ID</th>
                                <th>Full Name</th>
                                <th>Location</th>
                                <th>Position</th>
                                <th>Compensation Option</th>
                                <th>SUM(Hours)</th>
                                <th>SUM(Resp Compensation)</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <div class="excel-href">
                            <a href="">Download as a text file</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-revelation-primary">Keep Only</button>
                    <button type="button" class="btn btn-danger">Exclude</button>
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
        var respData  = @php echo $data['resps']; @endphp;
        var currentRespChartData;
        var currentFilter = [];
        var supportPointAry = new Array();
        var legalPointAry = new Array();
        let supportColor = "#82BD5E";
        let legalColor = "#367BC1";
        var selectedResp = 0;
        var responseTreeTable = {};
        var imgData_1, imgData_2, imgData_3, imgData_4, copyrightData, headerData;

        let formatter = new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD',
                        minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
                        maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
                    });

        let numberFormatter = new Intl.NumberFormat('en-US');
        /**
        * Update the body with the selected respondent data
        *
        * @param {number} resp_id   Respondent id
        * @param {number} support_hours   Support activity hours
        * @param {number} legal_hours   Legal service hours
        * @return {void}
        */
        function selectRespondent (resp_id, support_hours = 0, legal_hours = 0) {
            selectedResp = resp_id;
            mask_height = $('body').height();
            $('.loading-mask').css('height', mask_height);
            $('.loading-mask').fadeIn();
            let total_hours = legal_hours + support_hours;
            let support_percent = Math.round((support_hours / total_hours) * 100);
            let legal_percent = Math.round((legal_hours / total_hours) * 100);

            let supportColor = "#82BD5E";
            let legalColor = "#367BC1";

            $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
            });
            $.ajax({
                url: '{{url("/")}}/getRespondentData/' + resp_id + '/' + survey_id,
                type: 'GET',
                data: {
                    'resp_id': resp_id,
                    'survey_id': survey_id
                },
                dataType: 'json',
                success: function(data) {
                    selectedResp = data.resp.resp_id;
                    $('body .resp_item').css('background','none');
                    $("body #respondantId-" + selectedResp).css('background','rgba(242, 242, 242, 1)');
                    $('#individualReportContainer').css('display', 'block');
                    $('.third_part').css('display', 'block');

                    $("#respondent_data").html("");
                    $("#detailHoursTable").empty();
                    let resp = data.resp;
                    let totalPay = Math.round(parseInt(data.resp.resp_total_compensation));
                    let hrlyRate = totalPay / total_hours;
                    let legalCompensation = Math.round(hrlyRate * legal_hours);
                    let supportCompensation = Math.round(hrlyRate * support_hours);
                    let resp_report_data = data.resp_report_data;
                    let benefit_pct = resp.resp_benefit_pct * 100;

                    $('#resp_name').html(`<strong>${resp.resp_last}, ${resp.resp_first}</strong>`);

                    $('.support_info').html(`<p style="color:${supportColor};text-align:center;"><strong>Support Activities</strong><br><strong>${numberFormatter.format(support_hours)} hours</strong><br>(<strong>${support_percent}</strong>% of total)<br><strong>${formatter.format(supportCompensation)}</strong> cost to firm</p>`);
                    $('.legal_info').html(`<p style="color:${legalColor};text-align:center;"><strong>Legal Services</strong><br><strong>${numberFormatter.format(legal_hours)} hours</strong><br>(<strong>${legal_percent}</strong>% of total)<br><strong>${formatter.format(legalCompensation)}</strong> cost to firm</p>`);

                    $("#respondent_data").html("<div><p>Survey Taken On: <strong>" + resp.last_dt + "</strong><br>" +
                        "E-Mail: <strong>" + resp.resp_email + "</strong></p> <p>Employee ID: <strong>" + resp.cust_1 + "</strong><br>Group: <strong>" + resp.cust_2 + "</strong><br>Position: <strong>" + resp.cust_3 +
                        "</strong><br>Department: <strong>" + resp.cust_4 + "</strong><br>Location: <strong>" + resp.cust_6 + "</strong></p><p>Compensation: <strong>" + formatter.format(resp.resp_compensation) + "</strong><br>" +
                        "Benefit %: <strong>" + benefit_pct.toFixed(2) + "% </strong><br>Total Compensation: <strong>" + formatter.format(resp.resp_total_compensation) + "</strong></p></div>");

                    var chart = new CanvasJS.Chart("chartContainer", {
                        legend: {
                            maxWidth: 350,
                            itemWidth: 120,
                            fontSize: 5
                        },
                        toolTip: {
                            //: false, //disable here
                            //animationEnabled: false //disable here
                        },
                        data: [{
                            type: "doughnut",
                            dataPoints: [{
                                    y: legal_hours,
                                    indexLabelFontSize: 13,
                                    color: legalColor,
                                    indexLabelFontColor: legalColor,
                                    legendText: "Legal Services"
                                },
                                {
                                    y: support_hours,
                                    indexLabelFontSize: 13,
                                    color: supportColor,
                                    indexLabelFontColor: supportColor,
                                    legendText: "Support Activities"
                                }
                            ]
                        }]
                    });
                    chart.render();

                    let chart_total_text = `<p class="text-center"><strong>${numberFormatter.format(total_hours)}</strong> hours <br><strong>${formatter.format(resp.resp_total_compensation)}</strong> cost</p>`;
                    $('.chart-total').html(chart_total_text);

                    $('#total_percents').html('100%');
                    $('#total_hours').html(total_hours);
                    $('#total_cost').html(formatter.format(resp.resp_total_compensation));

                    // Draw Filter
                    let filter_count = [];
                    filter_count['category'] = 0;
                    filter_count['group'] = 0;
                    filter_count['department'] = 0;
                    filter_count['location'] = 0;
                    filter_count['position'] = 0;
                    respData.forEach(row => {
                        if (row.cust_5 == resp.cust_5) {
                            filter_count['category']++;
                        }
                        if (row.cust_2 == resp.cust_2) {
                            filter_count['group']++;
                        }
                        if (row.cust_4 == resp.cust_4) {
                            filter_count['department']++;
                        }
                        if (row.cust_6 == resp.cust_6) {
                            filter_count['location']++;
                        }
                        if (row.cust_3 == resp.cust_3) {
                            filter_count['position']++;
                        }
                    });
                    let filterHtml = ` <div class="pafa-filter-item">
                                            <div class="item-title">Category</div>
                                            <div class="item-body pafafi-category" onclick="compareRenderChart('category', '${resp.cust_5}');">${resp.cust_5}(${filter_count['category']})</div>
                                        </div>
                                        <div class="pafa-filter-item">
                                            <div class="item-title">Practice Group</div>
                                            <div class="item-body pafafi-group" onclick="compareRenderChart('group', '${resp.cust_2}');">${resp.cust_2}(${filter_count['group']})</div>
                                        </div>
                                        <div class="pafa-filter-item">
                                            <div class="item-title">Department</div>
                                            <div class="item-body pafafi-department" onclick="compareRenderChart('department', '${resp.cust_4}');">${resp.cust_4}(${filter_count['department']})</div>
                                        </div>
                                        <div class="pafa-filter-item">
                                            <div class="item-title">Location</div>
                                            <div class="item-body pafafi-location" onclick="compareRenderChart('location', '${resp.cust_6}');">${resp.cust_6}(${filter_count['location']})</div>
                                        </div>
                                        <div class="pafa-filter-item">
                                            <div class="item-title">Position</div>
                                            <div class="item-body pafafi-position" onclick="compareRenderChart('position', '${resp.cust_3}');">${resp.cust_3}(${filter_count['position']})</div>
                                        </div>
                                        <div style="clear: both;"></div>`;
                    $('.pa-resp-cust').html(filterHtml);

                    let compFlag = $('#compareCompensation').val();
                    renderScatterChart(compFlag, respData, selectedResp);

                    $('.loading-mask').fadeOut();
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
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
        /**
        * Generate pdf document of report
        *
        * @return {void}
        */
        function generatePDF () {
            let imgWidth = $('#individualContent .first_part').outerWidth();
            pdfdoc = new jsPDF('p', 'mm', 'a4');
            imgHeight1 = Math.round($('#individualContent .first_part').outerHeight() * 190 / imgWidth);
            y = 16;
            position = y;
            doc_page = 1; 
/*
            pdfdoc.addImage(imgData_1, 'JPEG', 10, y, 190, imgHeight1);
            y += imgHeight1;
*/
             imgHeight2 = Math.round($('#individualContent .second_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_2, 'JPEG', 10, y, 190, imgHeight2);
            y += imgHeight2; 

/*
            imgHeight4 = Math.round($('#copyright_div').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(copyrightData, 'JPEG', 10, y, 190, imgHeight4);
            y += imgHeight4; 
*/
            imgHeight3 = Math.round($('#individualContent .third_part').outerHeight() * 190 / imgWidth);
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
                pdfdoc.setTextColor(111,107,107); 
                pdfdoc.setFontSize(8); 
                pdfdoc.text('Page ' + i + ' of ' + (doc_page-1) , 168, 290, 0, 45);
            }

            pdfdoc.save(`Participant Analysis({{$data['survey']->survey_name}})`);
            $('#pdfBtn').html('Download PDF');
            $('#pdfBtn').prop('disabled', false);
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .btn').attr('disabled', true);
        }
        // Handle the pdf button click, generate image data from the body
        $('#pdfBtn').click(function () {
            $('#headerDiv').show();
            $("#pdfhidden").hide();
			 $('#copyright_div').addClass('d-flex');
			 $('#copyright_div').show(); 			 
            $('#generatePDFModal').modal('show');
            source = $('#individualContent .first_part');
            html2canvas(source, {
                onrendered: function (canvas) {
                    imgData_1 = canvas.toDataURL('image/jpeg', 1.0);
                }
            });
            source = $('#individualContent .second_part');
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
            source = $('#individualContent .third_part');
            html2canvas(source, {
                onrendered: function (canvas) {
                    imgData_3 = canvas.toDataURL('image/jpeg', 1.0);
                }
            }).then(function () {
                $('#generatePDFModal .modal-body').html('Generated a PDF');
                $('#generatePDFModal .btn').attr('disabled', false);
                $('#headerDiv').hide();
                $("#pdfhidden").show();
				$('#copyright_div').removeClass('d-flex');
				$('#copyright_div').hide(); 
            });
        });

        $('#searchResp').keypress(function (e) {
            var keycode = parseInt(e.keyCode ? e.keyCode : e.which);
            if (keycode == 13) {
                let search_key = $('#searchResp').val();
                $('#respondentList').empty();
                $.ajax({
                    url: '{{ route('getRespondentList') }}',
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
                        var supportPointAry = new Array();
                        var legalPointAry = new Array();
                        let supportColor = "#82BD5E";
                        let legalColor = "#367BC1";
                        respDataAry.forEach(resp => {
                            let support_percent = Math.round(100 * resp.support_hours / (resp.support_hours + resp.legal_hours));
                            let legal_percent = 100 - support_percent;
                            $('#respondentList').append(`<div class="resp_item row" onclick="selectRespondent(${resp.resp_id}, ${resp.support_hours}, ${resp.legal_hours});">
                                                            <div class="col-5 text-right">${resp.resp_last}, ${resp.resp_first}</div>
                                                            <div class="col-7">
                                                                <div class="support-bar" style="width:${support_percent}%;"><span style="${support_percent < 10 ? 'left:3px;' : ''}">${support_percent}%</span></div>
                                                                <div class="legal-bar" style="width:${legal_percent}%;"><span style="${legal_percent < 10 ? 'right:17px;' : ''}">${legal_percent}%</span></div>
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

        $('#searchResp').keyup(function () {
            let search_key = $('#searchResp').val();
            if (search_key == '') {
                $('.searchCloseBtn').css('display', 'none');
            } else {
                $('.searchCloseBtn').css('display', 'block');
            }
        });

        $('#searchCloseBtn').click(function () {
            $('#searchResp').val('');
            let search_key = $('#searchResp').val();
            $('#respondentList').empty();
            $.ajax({
                url: '{{ route('getRespondentList') }}',
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
                    var supportPointAry = new Array();
                    var legalPointAry = new Array();
                    let supportColor = "#82BD5E";
                    let legalColor = "#367BC1";
                    respDataAry.forEach(resp => {
                        let support_percent = Math.round(100 * resp.support_hours / (resp.support_hours + resp.legal_hours));
                        let legal_percent = 100 - support_percent;
                        $('#respondentList').append(`<div class="resp_item row" onclick="selectRespondent(${resp.resp_id}, ${resp.support_hours}, ${resp.legal_hours});">
                                                        <div class="col-5 text-right">${resp.resp_last}, ${resp.resp_first}</div>
                                                        <div class="col-7">
                                                            <div class="support-bar" style="width:${support_percent}%;"><span style="${support_percent < 10 ? 'left:3px;' : ''}">${support_percent}%</span></div>
                                                            <div class="legal-bar" style="width:${legal_percent}%;"><span style="${legal_percent < 10 ? 'right:17px;' : ''}">${legal_percent}%</span></div>
                                                        </div>
                                                    </div>`);
                        $('#searchCloseBtn').css('display', 'none');
                    });
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
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
        
        /**
        * Render scatter chart with the data
        *
        *@param {number} compFlag
        *@param {array} data
        *@param {number} resp_id
        *@return {void}
        */
        function renderScatterChart (compFlag, data, resp_id = 0) {
            let chartData    = [];
            let compensation = 0;
            let chartColor   = '#8f8f8f';
            let totalComp    = 0;
            let totalHours   = 0;
            let countResp    = 0;
            data.forEach(row => {
                compensation = 0;
                if (compFlag == 0) {
                    compensation = row.resp_compensation;
                } else {
                    compensation = Math.round(row.resp_compensation + row.resp_compensation * row.resp_benefit_pct);
                }
                let tmpTotalHours = row.support_hours + row.legal_hours;
                totalComp += compensation;
                if (tmpTotalHours)
                    totalHours += tmpTotalHours;

                if (resp_id != 0 && resp_id == row.resp_id) {
                    chartColor = '#367BC1';
                    chartData.push({
                        resp_id: row.resp_id,
                        x: tmpTotalHours,
                        y: compensation,
                        //name: row.resp_last + ', ' + row.resp_first,
                        /* indexLabel: row.resp_last + ', ' + row.resp_first,
                        indexLabelPlacement: "outside",
                        indexLabelFontWeight: "bold",
                        indexLabelTextAlign: "right", */
                        highLightEnabled: true,
                        markerSize: 20,
                        //markerType: "triangle",
                        employee_id: row.cust_1,
                        position: row.cust_3,
                        category: row.cust_5,
                        department: row.cust_4,
                        location: row.cust_6,
                        hours: numberFormatter.format(tmpTotalHours),
                        compensation: formatter.format(compensation),
                        color: chartColor
                    });
                } else {
                    chartColor = '#8f8f8f';
                    chartData.push({
                        resp_id: row.resp_id,
                        x: tmpTotalHours,
                        y: compensation,
                        name: row.resp_last + ', ' + row.resp_first,
                        employee_id: row.cust_1,
                        position: row.cust_3,
                        category: row.cust_5,
                        department: row.cust_4,
                        location: row.cust_6,
                        hours: numberFormatter.format(tmpTotalHours),
                        compensation: formatter.format(compensation),
                        color: chartColor
                    });
                }
                countResp++;
            });

            let tooltipContent = `<div class="p-2"><h4>{name} <span style="color:#5f5f5f;font-size:14px;">(Employee ID: {employee_id})</span></h4><strong>{position}</strong><br><br>
            <p>Category: <strong>{category}</strong><br>Department: <strong>{department}</strong><br>Location: <strong>{location}</strong><br></p> <p><strong>{hours}</strong> Hours <br><strong>{compensation}</strong> Compensation</p></div>`;

            let avgComp   = Math.round(totalComp / countResp);
            let avgHours  = Math.round(totalHours / countResp);
            $('.comparing-num').html(countResp);

            var scatterChart = new CanvasJS.Chart("scatterChartContainer", {
                toolTip: {
                    content: tooltipContent
                },
                axisX: {
                    title: "Total Hours",
                    valueFormatString: "#,##0",
                    labelFontSize: 14,
                    titleFontSize: 18,
                    stripLines: [
                        {
                            value: avgHours,
                            thickness: 2,
                            color: "#3f3f3f",
                            lineDashType: "dash",
                            label: "Avg. Hours: " + numberFormatter.format(avgHours),
                            labelFontColor: "#5f5f5f",
                            showOnTop: true
                        }
                    ]
                },
                axisY: {
                    title: "Compensation",
                    valueFormatString: "#, ##0",
                    prefix: "$",
                    labelFontSize: 14,
                    titleFontSize: 18,
                    stripLines: [
                        {
                            value: avgComp,
                            thickness: 2,
                            color: "#3f3f3f",
                            lineDashType: "dash",
                            label: "Avg.Comp " + formatter.format(avgComp),
                            labelFontColor: "#5f5f5f",
                            showOnTop: true
                        }
                    ]
                },
                data: [
                    {
                        type: "scatter",
                        color: "#8f8f8f",
                        click: clickRespDataPoint,
                        dataPoints: chartData
                    }
                ]
            });

            scatterChart.render();
        }

        /**
        * Handle the event of click point of respondent row
        *
        *@param {element} e
        *@return {void}
        */
        function clickRespDataPoint (e) {

            let compOption = $('#compareCompensation').val();
            if (compOption == '0') {
                compOption = 'Compensation';
            } else {
                compOption = 'Compensation + Benefits';
            }

            let strHtml = `<tr>
                            <td>${e.dataPoint.department}</td>
                            <td>${e.dataPoint.category}</td>
                            <td>${e.dataPoint.employee_id}</td>
                            <td>${e.dataPoint.name}</td>
                            <td>${e.dataPoint.location}</td>
                            <td>${e.dataPoint.position}</td>
                            <td>${compOption}</td>
                            <td>${e.dataPoint.hours}</td>
                            <td>${e.dataPoint.compensation}</td>
                        </tr>`;
            $('#respDataModal tbody').html(strHtml);

            $('#respDataModal .excel-href').html(`<a href="javascript:void(0);" onclick="exportExcelData(${e.dataPoint.resp_id});">Download as a text file</a>`);

            let footerHtml = `<button type="button" class="btn btn-revelation-primary" onclick="keepOnlyResp(${e.dataPoint.resp_id});">Keep Only</button>
                    <button type="button" class="btn btn-danger" onclick="excludeResp(${e.dataPoint.resp_id})">Exclude</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>`;
            $('#respDataModal .modal-footer').html(footerHtml);

            $('#respDataModal').modal('show');
        }
        /**
        * Export excel data
        *
        *@param {number} resp_id
        *@return {void}
        */
        function exportExcelData (resp_id) {
            let excelData;
            respData.forEach(row => {
                if (resp_id == row.resp_id) {
                    excelData = row;
                }
            });
            let compOpt = $('#compareCompensation').val();
            let sum_hours = numberFormatter.format(excelData.legal_hours + excelData.support_hours);
            let sum_comp;
            if (compOpt == '0') {
                sum_comp = formatter.format(excelData.resp_compensation);
                compOpt = 'Compensation';
            } else {
                sum_comp = formatter.format(excelData.resp_compensation + excelData.resp_compensation * excelData.resp_benefit_pct);
                compOpt = 'Compensation + Benefits';
            }
            $.ajax({
                url: '{{ route('exportParticipantAnalysisExcel') }}',
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'data': excelData,
                    'compOption': compOpt,
                    'sum_hours': sum_hours,
                    'sum_comp': sum_comp
                },
                dataType: 'json',
                beforeSend: function () {

                },
                success: function (response) {
                    location.href = response.url;
                },
                error: function (request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        }
        // Keep the respondent selected
        function keepOnlyResp (resp_id) {
            let tmpResp;
            respData.forEach(row => {
                if (row.resp_id == resp_id) {
                    tmpResp = row;
                }
            });
            respData = [];
            respData.push(tmpResp);

            currentRespChartData.forEach(row => {
                if (row.resp_id == resp_id) {
                    tmpResp = row;
                }
            });
            currentRespChartData = [];
            currentRespChartData.push(tmpResp);

            let compFlag = $('#compareCompensation').val();
            renderScatterChart(compFlag, currentRespChartData, selectedResp);

            $('#respDataModal').modal('hide');
        }
        // Remove the respondent selected
        function excludeResp (resp_id) {
            let tmpIndex;
            respData.forEach((row, index) => {
                if (row.resp_id == resp_id) {
                    tmpIndex = index;
                }
            });
            respData.splice(tmpIndex, 1);

            currentRespChartData.forEach((row, index) => {
                if (row.resp_id == resp_id) {
                    tmpIndex = index;
                }
            });
            currentRespChartData.splice(tmpIndex, 1);

            let compFlag = $('#compareCompensation').val();
            renderScatterChart(compFlag, currentRespChartData, selectedResp);

            $('#respDataModal').modal('hide');
        }
        // Render the compare chart
        function compareRenderChart (filter, val)  {
            if (currentFilter[filter] == '') {
                currentFilter[filter] = val;
                $('.pafafi-' + filter).addClass('pafafi-active');
                $('.pafaft-' + filter + ' strong').html(val);
            } else {
                currentFilter[filter] = '';
                $('.pafafi-' + filter).removeClass('pafafi-active');
                $('.pafaft-' + filter + ' strong').html('All');
            }

            currentRespChartData = [];
            let count_num = 0;
            respData.forEach(row => {
                if (row.cust_5 == currentFilter['category']) {
                    currentRespChartData.push(row);
                    count_num++;
                } else if (row.cust_2 == currentFilter['group']) {
                    currentRespChartData.push(row);
                    count_num++;
                } else if (row.cust_4 == currentFilter['department']) {
                    currentRespChartData.push(row);
                    count_num++;
                } else if (row.cust_6 == currentFilter['location']) {
                    currentRespChartData.push(row);
                    count_num++;
                } else if (row.cust_3 == currentFilter['position']) {
                    currentRespChartData.push(row);
                    count_num++;
                }
            });

            if (!$('.pafafi-active')[0]) {
                currentRespChartData = respData;
            }

            let compFlag = $('#compareCompensation').val();

            renderScatterChart(compFlag, currentRespChartData, selectedResp);
        }

        $('#compareCompensation').change(function () {
            let compFlag = $(this).val();
            renderScatterChart(compFlag, currentRespChartData, selectedResp);
        });

        $('#narrowSelect').change(function () {
            flag = $(this).val();
            switch (flag) {
                case '0':
                    $('#respondentList .legal-bar').show();
                    $('#respondentList .support-bar').show();
                    $('#respondentList .legal-bar span').css('right', '17px');
                    break;

                case '1':
                    $('#respondentList .support-bar').hide();
                    $('#respondentList .legal-bar').show();
                    $('#respondentList .legal-bar span').css('right', '0px');
                    break;

                case '2':
                    $('#respondentList .legal-bar').hide();
                    $('#respondentList .support-bar').show();
                    break;

                default:
                    break;
            }
        });
        
        $('#generatePDFModal').on('hidden.bs.modal', function () {
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .modal-body').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp; Generating PDF...`);
            $('#generatePDFModal .btn').attr('disabled', true);
        });

        $(document).ready(function () {
            renderScatterChart(0, respData, selectedResp);
            currentRespChartData = respData;
            currentFilter['category'] = '';
            currentFilter['group'] = '';
            currentFilter['department'] = '';
            currentFilter['location'] = '';
            currentFilter['position'] = '';
        });
    </script>

@endsection
