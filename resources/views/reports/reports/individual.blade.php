@extends('layouts.reports')
@section('content')


<div id="HelpContent" class="modal-body" style="display:none;">
<p><strong>Individual Report</strong></p>
    <p>
        The Individual Report is the second tab that you will see in the Reports section, and it is
        incredibly important. Initially, when you enter the Individual Report, you will see a list of every
        employee that has completed the survey. It is listed in alphabetical order. In order to view a
        specific individual’s report, simply <strong>click on their name,</strong> and their report will be displayed. At
        the top of the report, to the right of the list of names, you will see the specific individual’s
        information. Information displayed includes the date that they participated in the survey, their
        Email Address, Employee ID, Group, Position, Department, and Location.
    </p>
    <p>
        If you scroll down on your screen, the next thing that you will see is a donut chart. This chart
        will tell you what activities the user takes part in (either legal or support), and how much of their
        time is allocated to each activity. This chart is unique to each individual’s report. <strong>The Green
        aspect of the chart represents Support Activities, while the Blue aspect of the chart
        represents Legal Activities</strong>.
    </p>
    <img src="{{ asset('imgs/userguide-img1.png') }}" alt="tag">
    <p>
        Directly next to this chart, you will find a section of the report titled <strong>“Time by Classification”</strong>.
        This is essentially the same as the donut chart, only the information is in written form, and
        specifies the type of legal and support activities that the individual partakes in. Also listed here
        are the percentage of hours that the employee spends in each activity, the actual number of hours
        that equates to, and the cost to the firm that those amount of hours cost. In short, this shows you
        how the employee’s time was allocated across what we call Classifications. Classification is a layer in our taxonomy, and it is at a high level– it tells you where a specific employee spends the
        majority of their time.
    </p>
    <p>
    Further down the report, you will see a type of bar graph titled <strong>“Time by Substantive Area”</strong>.
    This is a layer down in the taxonomy, meaning that the information you view is more specific.
    As mentioned above, Green represents support activities, while Blue represents legal activities.
    This section tells you the types of activities that the specific individual does, and which ones they
    do a little, or a lot of.
    </p>
    <img src="{{ asset('imgs/userguide-img2.png') }}" alt="tag">
    <p>
    The final part of the Individual Report is the <strong>Complete Time peakdown.</strong> This is the complete
    taxonomy of the individual’s responses in the survey. You have options to Expand All, or
    Collapse All, or you may expand the taxonomy manually for each activity. Once again, Blue
    represents Legal Services, while Green represents support activities. You may manually expand
    each of these categories by clicking the triangle-shaped arrow directly next to the title of activity.
    It is important to note that this is not the entire taxonomy as a whole, this is simply the taxonomy
    for a specific individual’s responses based on their survey. When you expand the taxonomy for
    an individual, you will notice that it is in a hierarchical structure, and you will also see
    percentages for each activity within the classifications (either Legal Services or Support
    Activities).
    </p>
    <p>
    Percentages are calculated based on their survey responses. On the right to the percentage, you
    will see ‘hours’. Hours is simply how many hours this employee’s percentage equates to. You
    may notice that some of the percentages show a 0%. This is because the percentage is a fraction,
    meaning that the individual is involved in this activity a minimal amount.
    Should you wish to print any of this information, or save it as a PDF file, you may do so at the
    top of the report. It will be listed with the project name.
    </p>   
    <img src="{{ asset('imgs/userguide-img3.png') }}" alt="tag">
   
</div>


<div  class="container-fluid px-3 hideinhelppdf "> 
<div id="headerDiv" class="pdfheaderdiv">   
    <p class="text-phead">Individual Report /{{ $survey->survey_name }}</p>
    <p class="redtext-phead">Confidential</p>
    {{-- <img  src="{{asset('imgs/logo-pdfhead.png')}}">   --}}
</div>  

    <div id="hiddenprint" class="flex flex-wrap justify-between items-center cont-mtitle mb-4"> 
        <h1 class="text-survey">Individual Report / {{ $survey->survey_name }}</h1>
        @if (\Auth::check() && \Auth::user()->hasPermission('surveyPrint', $survey))
        <div class="d-flex py-0 py-md-2"> 
            <button class="revnor-btn mx-1 mb-3 mb-md-0" id="pdfBtn">Download PDF</button>             
            <button class="revnor-btn mr-1 mb-3 mb-md-0" id="printBtn">Print</button>
            @if($survey->survey_id == 54)
                <button class="revnor-btn mr-1 mb-3 mb-md-0" id="SendReport">Send Report</button>
            @endif
            <button type="button" class="helpguidebtn" data-toggle="modal" data-target="#helpdetasurvey">         
</button>
</div>             
        @endif
    </div>
    <div id="individualContent">
        <div class="first_part">
            @include('reports.partials.reportfilter') 
        </div>
        <div class="row second_part" style="padding:0 0 20px 0;"> 
            <div id="pdfhidden" class="col-md-4 pl-0 pr-3 pr-md-0">  
                <div class="table-search"> 
                    <label for="searchResp" style="margin-bottom:0;margin-right:10px;">Search: </label>
                    <input type="text" id="searchResp">
                    <svg id="searchCloseBtn" class="searchCloseBtn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path d="M16 2C8.2 2 2 8.2 2 16s6.2 14 14 14s14-6.2 14-14S23.8 2 16 2zm0 26C9.4 28 4 22.6 4 16S9.4 4 16 4s12 5.4 12 12s-5.4 12-12 12z" fill="currentColor"/><path d="M21.4 23L16 17.6L10.6 23L9 21.4l5.4-5.4L9 10.6L10.6 9l5.4 5.4L21.4 9l1.6 1.6l-5.4 5.4l5.4 5.4z" fill="currentColor"/></svg>
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
                                $legal_percent = $resp->legal_hours > 0 ? 100 - $support_percent : 0;
                            @endphp
                            <div class="resp_item row" id="respondantId-{{$resp->resp_id}}" onclick="selectRespondent({{$resp->resp_id}}, {{$resp->support_hours}}, {{$resp->legal_hours}});">
                                <div class="col-5 text-right" title="{{$resp->resp_last}}, {{$resp->resp_first}}">{{$resp->resp_last}}, {{$resp->resp_first}}</div>
                                <div class="col-7">
                                    <div class="support-bar" style="width:{{$support_percent}}%;"></div>
                                    <div class="legal-bar" style="width:{{$legal_percent}}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
           
            </div> 
        </div>
                                           
        <div class="col-md-1 "></div>
            <div class="col-md-7 pl-md-0" id="respondent_data">
                <div class="flex items-center h-full">
                    &larr; Select a respondent from the list on the left to get started.
                </div>
            </div>
        </div>
        <p class="text-small mt-2">Individuals displayed only reflect those that have completed the questionnaire.</p> 
        <div class="fourth_part" style="background:white;">
            <div style="width:100%;padding-top:20px;">
                <div style="border-top:6px solid #f2f2f2;"></div>
                <div id="individualReportContainer" style="display:none;background:white;z-index:10;padding-top:20px;">
                    <div class="row flex items-center">
                        <div class="col-md-7 canvaschartouter"> 
                            <div id="chartContainer" style="height: 330px; width:100%;"></div> 
                        </div>
                        <div class="col-md-5">
                            <div class="" style="background-color: white;">
                                <h3>Time by Classification</h3>
                                <table class="table-auto w-full legal_table">
                                    <thead>
                                        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                            <th class="px-8"></th>
                                            <th class="py-2 text-right">Percent of Hours</th>
                                            <th class="px-2 text-right">Hours</th>
                                            <th class="px-2 text-right">Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white text-center text-sm divide-y dark:divide-gray-700 dark:bg-gray-800" id="legal_tbody" style="color: #337ab7;">
                                    </tbody>
                                </table>

                                <table class="table-auto w-full support_table" style="margin-top:20px;">
                                    <thead>
                                        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                            <th class="px-8"></th>
                                            <th class="py-2 text-right">Percent of Hours</th>
                                            <th class="px-2 text-right">Hours</th>
                                            <th class="px-2 text-right">Cost</th>
                                        </tr>
                                    </thead>

                                    <tbody class="bg-white divide-y text-center text-sm dark:divide-gray-700 dark:bg-gray-800" id="support_tbody" style="color:#82BD5E;;">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- <hr style="height:6px;border:none;color:#F2F2F2;background-color:#F2F2F2;" /> -->

                    <div class="px-0 mt-10" style="background: white;">
                        <h3>Time by Substantive Area</h3>
                        <div class="canvaschartouter">
                        <div id="CategoryChartContainer" style="height:550px;"></div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="third_part pt-4" style="display: none;">
            <div class="py-8  overflow-x-auto" style="padding-top:0.6rem;">
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

    <div id="copyright_div" class="flex justify-content-between items-end" style="display:none">
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

     
<!-- Modal -->
<div class="modal fade" id="helpdetasurvey" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header align-items-center">
        <h5 class="modal-title" id="exampleModalCenterTitle">User Guide</h5> 
        <button class="revnor-btn ml-auto mr-2 mb-3 mb-md-0 bg-white text-dark" id="printHelp">Print</button>
        <button type="button" class="close ml-0" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <p><b>Reports</b></p>
        <p>When you click on Reports, you will see four different report options listed: <b>Demographic,Individual, Compilation, and Crosstab</b>.</p> 
        <p>
            The Individual Report is the second tab that you will see in the Reports section, and it is
            incredibly important. Initially, when you enter the Individual Report, you will see a list of every
            employee that has completed the survey. It is listed in alphabetical order. In order to view a
            specific individual’s report, simply <strong>click on their name,</strong> and their report will be displayed. At
            the top of the report, to the right of the list of names, you will see the specific individual’s
            information. Information displayed includes the date that they participated in the survey, their
            Email Address, Employee ID, Group, Position, Department, and Location.
        </p>
        <p>
            If you scroll down on your screen, the next thing that you will see is a donut chart. This chart
            will tell you what activities the user takes part in (either legal or support), and how much of their
            time is allocated to each activity. This chart is unique to each individual’s report. <strong>The Green
            aspect of the chart represents Support Activities, while the Blue aspect of the chart
            represents Legal Activities</strong>.
        </p>
        <img src="{{ asset('imgs/userguide-img1.png') }}" alt="tag">
        <p>
            Directly next to this chart, you will find a section of the report titled <strong>“Time by Classification”</strong>.
            This is essentially the same as the donut chart, only the information is in written form, and
            specifies the type of legal and support activities that the individual partakes in. Also listed here
            are the percentage of hours that the employee spends in each activity, the actual number of hours
            that equates to, and the cost to the firm that those amount of hours cost. In short, this shows you
            how the employee’s time was allocated across what we call Classifications. Classification is a layer in our taxonomy, and it is at a high level– it tells you where a specific employee spends the
            majority of their time.
        </p>
        <p>
        Further down the report, you will see a type of bar graph titled <strong>“Time by Substantive Area”</strong>.
        This is a layer down in the taxonomy, meaning that the information you view is more specific.
        As mentioned above, Green represents support activities, while Blue represents legal activities.
        This section tells you the types of activities that the specific individual does, and which ones they
        do a little, or a lot of.
        </p>
        <img src="{{ asset('imgs/userguide-img2.png') }}" alt="tag">
        <p>
        The final part of the Individual Report is the <strong>Complete Time Breakdown.</strong> This is the complete
        taxonomy of the individual’s responses in the survey. You have options to Expand All, or
        Collapse All, or you may expand the taxonomy manually for each activity. Once again, Blue
        represents Legal Services, while Green represents support activities. You may manually expand
        each of these categories by clicking the triangle-shaped arrow directly next to the title of activity.
        It is important to note that this is not the entire taxonomy as a whole, this is simply the taxonomy
        for a specific individual’s responses based on their survey. When you expand the taxonomy for
        an individual, you will notice that it is in a hierarchical structure, and you will also see
        percentages for each activity within the classifications (either Legal Services or Support
        Activities).
        </p>
        <p>
        Percentages are calculated based on their survey responses. On the right to the percentage, you
        will see ‘hours’. Hours is simply how many hours this employee’s percentage equates to. You
        may notice that some of the percentages show a 0%. This is because the percentage is a fraction,
        meaning that the individual is involved in this activity a minimal amount.
        Should you wish to print any of this information, or save it as a PDF file, you may do so at the
        top of the report. It will be listed with the project name.
        </p>   
        <img src="{{ asset('imgs/userguide-img3.png') }}" alt="tag">
   
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!--- help data popup end ---->

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
    <!-- <div class="modal fade" tabindex="-1" role="dialog" id="generatePDFModalForHelp">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body flex items-center justify-center" style="height: 150px;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp; Generating Printable PDF...
                </div>
                <div class="modal-footer">
                    <button class="btn btn-revelation-primary" onclick="generatePDFforHelp();" disabled>Download</button>
                </div>
            </div>
        </div>
    </div> -->
    <div class="modal fade" tabindex="-1" role="dialog" id="SendReportModel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body flex items-center justify-center" style="height: 150px;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp; Generating Mail...
                </div>
                <div class="modal-footer">
                    <button class="btn btn-revelation-primary" onclick="SaveGeneratedPdf();" disabled>Send To Respondent</button>
                </div>
            </div>
        </div>
    </div>
    <div class="loading-mask"></div>
</div>
    <script>
        var survey_id = @php echo $data['survey']->survey_id; @endphp;
        var supportPointAry = new Array();
        var legalPointAry = new Array();
        let supportColor = "#82BD5E";
        let legalColor = "#367BC1";
        var treeTableDepth = 1;
        var selectedResp = 0;
        var responseTreeTable = {};
        var imgData_1, imgData_2, imgData_3, imgData_4,imgData_2Help, copyrightData, headerData;

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
            $('#SendReport').attr('data-id',resp_id);
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
                    $("#legal_tbody").empty();
                    $("#support_tbody").empty();
                    $("#detailHoursTable").empty();
                    let resp = data.resp;
                    let totalPay = Math.round(parseInt(data.resp.resp_total_compensation));
                    let hrlyRate = totalPay / total_hours;
                    let legalCompensation = Math.round(hrlyRate * legal_hours);
                    let supportCompensation = Math.round(hrlyRate * support_hours);
                    let legal_caption = "Legal Services. Accounts for  " + legal_percent + "% of time. That's a total of " + numberFormatter.format(legal_hours) + " hours, which costs the firm " + formatter.format(legalCompensation);
                    let support_caption = "Support Services. Accounts for  " + support_percent + "% of time. That's a total of " + numberFormatter.format(support_hours) + " hours, which costs the firm " + formatter.format(supportCompensation);
                    let resp_report_data = data.resp_report_data;
                    let benefit_pct = resp.resp_benefit_pct * 100;

                    $("#respondent_data").html("<h3>" + resp.resp_last + "," + resp.resp_first + "</h3><p>Survey Taken On: " + resp.last_dt + "<br>" +
                        "E-Mail: " + resp.resp_email + "<br> Employee ID: " + resp.cust_1 + "</p><p>Group: " + resp.cust_2 + "<br>Position:" + resp.cust_3 +
                        "<br>Department: " + resp.cust_4 + "<br>Location: " + resp.cust_6 + "</p><p>Compensation: " + formatter.format(resp.resp_compensation) + "<br>" +
                        "Benefit %: " + benefit_pct.toFixed(2) + "% <br>Total Compensation: " + formatter.format(resp.resp_total_compensation) + "</p>");

                    $('#resp_name').html(resp.resp_last + ', ' + resp.resp_first);

                    var chart = new CanvasJS.Chart("chartContainer", {
                        title: {
                            text: ""
                        },
                        legend: {
                            maxWidth: 350,
                            itemWidth: 120,
                            fontSize: 13
                        },
                        toolTip: {
                            enabled: true, //disable here
                            animationEnabled: true //disable here
                        },
                        data: [{
                            type: "doughnut",
                            showInLegend: true,
                            dataPoints: [{
                                    y: legal_hours,
                                    indexLabel: legal_caption,
                                    indexLabelFontSize: 13,
                                    color: legalColor,
                                    indexLabelFontColor: legalColor,
                                    legendText: "Legal Services"
                                },
                                {
                                    y: support_hours,
                                    indexLabel: support_caption,
                                    indexLabelFontSize: 13,
                                    color: supportColor,
                                    indexLabelFontColor: supportColor,
                                    legendText: "Support Activities"
                                }
                            ]
                        }]
                    });
                    chart.render();

                    $('#total_percents').html('100%');
                    $('#total_hours').html(total_hours);
                    $('#total_cost').html(formatter.format(resp.resp_total_compensation));

                    tmpServiceAry = new Array();
                    tmpCategoryAry = new Array();
                    let maxCategoryValue = 0;
                    if (resp_report_data.detail_table.categoryData.length > 0) {
                        let numTmp = 0;
                        resp_report_data.detail_table.categoryData.forEach(element => {
                            child2_percent = Math.round(element.hours / total_hours * 100);
                            tmpObj = {
                                        y: element.hours,
                                        label: element.question_desc,
                                        toolTipContent: `<table>
                                                            <tr>
                                                                <td>Legal | Support:</td>
                                                                <td>${element.grandParent}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Classification:</td>
                                                                <td>${element.parent}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Substantive Area:</td>
                                                                <td>${element.question_desc}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>% of Total Hours along Table(Across):</td>
                                                                <td>${child2_percent}%</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Hours:</td>
                                                                <td>${element.hours}</td>
                                                            </tr>
                                                        </table>`,
                                        color: element.grandParent.includes("Legal") ? legalColor : supportColor,
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
                        tmpCategoryAry.sort(function(a, b) {
                            return a.grandParent > b.grandParent ? -1 : 1;
                        });

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


                    console.log(resp_report_data);


                    if (resp_report_data.tableData.length > 0) {
                        $('.breakdownTableArea').empty();
                        $('.breakdownTableArea').append(`<table id="breakdownTable"></table>`);
                        $table = $('#breakdownTable');
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
                                    field: 'lead_codes',
                                    title: 'LEDES Code'
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
                                {
                                    field: 'cost',
                                    title: 'Cost',
                                    align: 'right',
                                    sortable: false,
                                    formatter: 'costFormat'
                                }
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
                            if (item.top_parent.includes('Legal') !== false) {
                                let color = legalColor;
                                let rgbaCol = 'rgba(' + parseInt(color.slice(-6,-4),16)
                                    + ',' + parseInt(color.slice(-4,-2),16)
                                    + ',' + parseInt(color.slice(-2),16)
                                    +',' + (item.percent_hour / 100) + ')';
                                $(`.treegrid-${item.id}`).css('background-color', rgbaCol);
                            } else if (item.top_parent.includes('Support') !== false || item.top_parent.includes('Shared') !== false) {
                                let color = supportColor;
                                let rgbaCol = 'rgba(' + parseInt(color.slice(-6,-4),16)
                                    + ',' + parseInt(color.slice(-4,-2),16)
                                    + ',' + parseInt(color.slice(-2),16)
                                    +',' + (item.percent_hour / 100) + ')';
                                $(`.treegrid-${item.id}`).css('background-color', rgbaCol);
                            }
                        });

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

                    resp_report_data.detail_table.child1Data.forEach(service => {
                       
                        if (service.parent.includes("Legal")) {
                            console.log('legal',service);
                            $('#legal_tbody').append(`
                                <tr>
                                    <td class="text-left px-2 w-1/3">${service.question_desc}</td>
                                    <td class="text-right px-2">${Math.round(service.hours / total_hours * 100)}%</td>
                                    <td class="text-right px-2">${numberFormatter.format(service.hours)}</td>
                                    <td class="text-right px-2">${formatter.format(Math.round(hrlyRate * service.hours))}</td>
                                </tr>
                            `);
                        }

                        if (service.parent.includes("Support") || service.parent.includes("Shared")) {
                            console.log('support',service); 
                            $('#support_tbody').append(`
                                <tr>
                                    <td class="text-left px-2 w-1/3">${service.question_desc}</td>
                                    <td class="text-right px-2">${Math.round(service.hours / total_hours * 100)}%</td>
                                    <td class="text-right px-2">${numberFormatter.format(service.hours)}</td>
                                    <td class="text-right px-2">${formatter.format(Math.round(hrlyRate * service.hours))}</td>
                                </tr>
                            `);
                        }
                    });

                    if ($('#legal_tbody').is(':empty')) {
                        $('#legal_tbody').append(`<tr><td colspan="4" class="text-center">No data for Legal Services</td></tr>`);
                    }

                    if ($('#support_tbody').is(':empty')) {
                        $('#support_tbody').append(`<tr><td colspan="4" class="text-center">No data for Support Activities</td></tr>`);
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



        $('#printBtnForHelp').on('click',function(){
            $('#generatePDFModalForHelp').modal('show');

            source = $('#HelpContent');
            html2canvas(source, {
                onrendered: function (canvas) {
                    imgData_2Help = canvas.toDataURL('image/jpeg', 1.0);
                }
               
            }).then(function () {
                $('#generatePDFModalForHelp .modal-body').html(`<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="24" height="24" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path d="M30 18v-2h-6v10h2v-4h3v-2h-3v-2h4z" fill="currentColor"/><path d="M19 26h-4V16h4a3.003 3.003 0 0 1 3 3v4a3.003 3.003 0 0 1-3 3zm-2-2h2a1.001 1.001 0 0 0 1-1v-4a1.001 1.001 0 0 0-1-1h-2z" fill="currentColor"/><path d="M11 16H6v10h2v-3h3a2.003 2.003 0 0 0 2-2v-3a2.002 2.002 0 0 0-2-2zm-3 5v-3h3l.001 3z" fill="currentColor"/><path d="M22 14v-4a.91.91 0 0 0-.3-.7l-7-7A.909.909 0 0 0 14 2H4a2.006 2.006 0 0 0-2 2v24a2 2 0 0 0 2 2h16v-2H4V4h8v6a2.006 2.006 0 0 0 2 2h6v2zm-8-4V4.4l5.6 5.6z" fill="currentColor"/></svg> &nbsp; Generated your Help PDF`);
                $('#generatePDFModalForHelp .btn').attr('disabled', false);
                console.log(imgData_2Help);
            });
            
        });

        function generatePDFforHelp(){
            
            let imgWidthForHelp = $('#HelpContent .first_part').outerWidth();
            
            pdfdoc = new jsPDF('p', 'mm', 'a4');
            //pdfdoc.internal.scaleFactor = 20;
            imgHeight1ForHelp = Math.round($('#HelpContent').outerHeight());
            //imgHeight1 = Math.round($('#individualContent .first_part').outerHeight() * 190 / imgWidth);
            y = 14;
            position = y;
            doc_page = 1; 

            pdfdoc.addImage(imgData_2Help, 'JPEG', 12, y, 190, imgHeight1ForHelp);
            y += imgHeight1ForHelp;
            
            pdfdoc.save(`Help For Individual report`);
           

            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .btn').attr('disabled', true);
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
                
                // $('#generatePDFModal').modal('show');
                
                /* source = $('#individualContent .first_part');
                html2canvas(source, {
                    onrendered: function (canvas) {
                        imgData_1 = canvas.toDataURL('image/jpeg', 1.0);
                    }
                }); */

                source = $('#individualContent .second_part');
                html2canvas(source, {
                    onrendered: function (canvas) {
                        imgData_2 = canvas.toDataURL('image/jpeg', 1.0);
                    }
                        
                });

                source = $('#individualContent .fourth_part');

                html2canvas(source, {
                    onrendered: function (canvas) {
                        imgData_4 = canvas.toDataURL('image/jpeg', 1.0);
                    }
                        
                });
                // Copyright copyrightData
                source = $('#copyright_div');
                html2canvas(source, {
                    onrendered: function (canvas) {
                        copyrightData = canvas.toDataURL("image/jpeg", 1.0);
                    }
                        
                    
                         
                });
                //console.log(copyrightData);
                //return;
                source = $('#headerDiv');
                html2canvas(source, {
                    onrendered: function (canvas) {
                        headerData = canvas.toDataURL("image/jpeg", 1.0);
                    }
                        
                   
                        
                });
                //console.log(headerData);
               // return;
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
               // $('#generatePDFModal .btn').attr('disabled', false);
                $('#headerDiv').hide();
                $("#pdfhidden").show();  				
            }
        });

        $('#SendReport').click(function () {
            $('#headerDiv').show();
            $("#pdfhidden").hide();  
            $('#copyright_div').addClass('d-flex');
			$('#copyright_div').show();	
            var respondent_name = $('#respondent_data').find('h3').text();
            if (respondent_name != '') {

                $('#SendReportModel').modal('show');
                
               
                
                /* source = $('#individualContent .first_part');
                html2canvas(source, {
                    onrendered: function (canvas) {
                        imgData_1 = canvas.toDataURL('image/jpeg', 1.0);
                    }
                }); */

                source = $('#individualContent .second_part');
                html2canvas(source, {
                    onrendered: function (canvas) {
                        imgData_2 = canvas.toDataURL('image/jpeg', 1.0);
                    }
                        
                });

                source = $('#individualContent .fourth_part');

                html2canvas(source, {
                    onrendered: function (canvas) {
                        imgData_4 = canvas.toDataURL('image/jpeg', 1.0);
                    }
                        
                });
                // Copyright copyrightData
                source = $('#copyright_div');
                html2canvas(source, {
                    onrendered: function (canvas) {
                        copyrightData = canvas.toDataURL("image/jpeg", 1.0);
                    }
                        
                    
                         
                });
                //console.log(copyrightData);
                //return;
                source = $('#headerDiv');
                html2canvas(source, {
                    onrendered: function (canvas) {
                        headerData = canvas.toDataURL("image/jpeg", 1.0);
                    }
                        
                   
                        
                });
                //console.log(headerData);
               // return;
                source = $('#individualContent .third_part');
                html2canvas(source, { 
                    onrendered: function (canvas) {
                        imgData_3 = canvas.toDataURL('image/jpeg', 1.0)
                    }
                }).then(function () {
                    $('#SendReportModel .modal-body').html(`<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="24" height="24" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path d="M30 18v-2h-6v10h2v-4h3v-2h-3v-2h4z" fill="currentColor"/><path d="M19 26h-4V16h4a3.003 3.003 0 0 1 3 3v4a3.003 3.003 0 0 1-3 3zm-2-2h2a1.001 1.001 0 0 0 1-1v-4a1.001 1.001 0 0 0-1-1h-2z" fill="currentColor"/><path d="M11 16H6v10h2v-3h3a2.003 2.003 0 0 0 2-2v-3a2.002 2.002 0 0 0-2-2zm-3 5v-3h3l.001 3z" fill="currentColor"/><path d="M22 14v-4a.91.91 0 0 0-.3-.7l-7-7A.909.909 0 0 0 14 2H4a2.006 2.006 0 0 0-2 2v24a2 2 0 0 0 2 2h16v-2H4V4h8v6a2.006 2.006 0 0 0 2 2h6v2zm-8-4V4.4l5.6 5.6z" fill="currentColor"/></svg> &nbsp; Report is ready To send!`);
                    $('#SendReportModel .btn').attr('disabled', false);
                    $('#headerDiv').hide();
                    $("#pdfhidden").show();
                    $('#copyright_div').addClass('d-flex');
			        $('#copyright_div').show();	
                });
            } else {
                $('#selectRespModal').modal();
               // $('#generatePDFModal .btn').attr('disabled', false);
                $('#headerDiv').hide();
                $("#pdfhidden").show()
                $('#copyright_div').addClass('d-flex');
			    $('#copyright_div').hide();	
            }
        });







        $('#printBtn').on('click', function(){
            
            var respondent_name_print = $('#respondent_data').find('h3').text();
            if(respondent_name_print != ''){

                $('#headerDiv').show();
                $('#hiddenprint').hide();
                $('#pdfhidden').hide();
                $('#copyright_div').addClass('fixedbottompdf d-flex');
				$('#copyright_div').show();
                $('#headerDiv').addClass('fixedtoppdf');

                const hideElements = ['#desktop_sidebar','#hideinpdf', '.site-footer','.site-header','.first_part', '#pdfPrint', 'header > div > ul'];

                $.each(hideElements, function(_, el){ $(el).hide(); });

                window.print();

                $.each(hideElements, function(_, el){ $(el).show(); });
                $('#headerDiv').hide();
                $('#hiddenprint').show();
                $('#pdfhidden').show(); 
                $('#copyright_div').removeClass('fixedbottompdf d-flex'); 
			    $('#copyright_div').hide();	
                $('#headerDiv').removeClass('fixedtoppdf');
            }else{ 
                $('#selectRespModal').modal();

            }

           
        });


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

        $('#SendReport').on('click',function(){

            var respondent_name = $('#respondent_data').find('h3').text();

            if (respondent_name != '') {
                
               
               
                
            }else{
                $('#selectRespModal').modal();
            }
        })










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
                            let legal_percent = resp.legal_hours > 0 ? 100 - support_percent : 0;
                            $('#respondentList').append(`<div class="resp_item row" onclick="selectRespondent(${resp.resp_id}, ${resp.support_hours}, ${resp.legal_hours});">
                                                            <div class="col-5 text-right" title="${resp.resp_last}, ${resp.resp_first}">${resp.resp_last}, ${resp.resp_first}</div>
                                                            <div class="col-7">
                                                                <div class="support-bar" style="width:${support_percent}%;"></div>
                                                                <div class="legal-bar" style="width:${legal_percent}%;"></div>
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
                        let legal_percent = resp.legal_hours > 0 ? 100 - support_percent : 0;
                        $('#respondentList').append(`<div class="resp_item row" onclick="selectRespondent(${resp.resp_id}, ${resp.support_hours}, ${resp.legal_hours});">
                                                        <div class="col-5 text-right" title="${resp.resp_last}, ${resp.resp_first}">${resp.resp_last}, ${resp.resp_first}</div>
                                                        <div class="col-7">
                                                            <div class="support-bar" style="width:${support_percent}%;"></div>
                                                            <div class="legal-bar" style="width:${legal_percent}%;"></div>
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

        /**
        * Generate pdf document of report
        *
        * @return {void}
        */
        function generatePDF () {
            var respondent_name = $('#respondent_data').find('h3').text();
            let imgWidth = $('#individualContent .first_part').outerWidth();
            
            pdfdoc = new jsPDF('p', 'mm', 'a4');
            //pdfdoc.internal.scaleFactor = 20;
            imgHeight1 = Math.round($('#individualContent .first_part').outerHeight() * 190 / imgWidth);
            //imgHeight1 = Math.round($('#individualContent .first_part').outerHeight() * 190 / imgWidth);
            y = 14;
            position = y;
            doc_page = 1; 

           /*  pdfdoc.addImage(imgData_1, 'JPEG', 10, y, 190, imgHeight1);
            y += imgHeight1; */
            //console.log(imgData_2);
            imgHeight2 = Math.round($('#individualContent .second_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_2, 'JPEG', 12, y, 190, imgHeight2);
            y += imgHeight2;

            imgHeight4 = Math.round($('#individualContent .fourth_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_4, 'JPEG', 10, y, 190, imgHeight4);
            y += imgHeight4;

            imgHeight3 = Math.round($('#individualContent .third_part').outerHeight() * 190 / imgWidth);

            pdfdoc.addPage();
            doc_page++;
            pdfdoc.addImage(imgData_3, 'JPEG', 10 , 20, 190, imgHeight3);

            pageHeight = pdfdoc.internal.pageSize.height - 20;
            heightLeft = imgHeight3 - pageHeight + 10;

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

            pdfdoc.save(`{{$data['survey']->survey_name}}(${respondent_name})`);
           

            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .btn').attr('disabled', true);
        }

        function SaveGeneratedPdf(){
           
            var respondent_name = $('#respondent_data').find('h3').text();
            let imgWidth = $('#individualContent .first_part').outerWidth();
            
            pdfdoc = new jsPDF('p', 'mm', 'a4');
            //pdfdoc.internal.scaleFactor = 20;
            imgHeight1 = Math.round($('#individualContent .first_part').outerHeight() * 190 / imgWidth);
            //imgHeight1 = Math.round($('#individualContent .first_part').outerHeight() * 190 / imgWidth);
            y = 14;
            position = y;
            doc_page = 1; 

           /*  pdfdoc.addImage(imgData_1, 'JPEG', 10, y, 190, imgHeight1);
            y += imgHeight1; */
            //console.log(imgData_2);
            imgHeight2 = Math.round($('#individualContent .second_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_2, 'JPEG', 12, y, 190, imgHeight2);
            y += imgHeight2;

            imgHeight4 = Math.round($('#individualContent .fourth_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_4, 'JPEG', 10, y, 190, imgHeight4);
            y += imgHeight4;

            imgHeight3 = Math.round($('#individualContent .third_part').outerHeight() * 190 / imgWidth);

            pdfdoc.addPage();
            doc_page++;
            pdfdoc.addImage(imgData_3, 'JPEG', 10 , 20, 190, imgHeight3);

            pageHeight = pdfdoc.internal.pageSize.height - 20;
            heightLeft = imgHeight3 - pageHeight + 10;

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

             //formData.append('test','data');
            var blob = pdfdoc.output('blob');
            var resp_id = $('#SendReport').data('id');;
            var formData = new FormData();
            formData.append('resp_id',resp_id);
            formData.append('RespondentPDF',blob);
            formData.append('_token','{{csrf_token()}}');

            $.ajax({
                url:'{{route("SendReports")}}',
                method:'POST',
                processData: false,
                contentType: false,
                data:formData,
                success:function(response){
                    $('#SendReportModel').modal('hide');
                    alert('Mail has been sent');
                }
            })


        }
    </script>

@endsection
