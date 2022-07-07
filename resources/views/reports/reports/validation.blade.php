@extends('layouts.reports')
@section('content')


@if (Auth::user()->id != 11)
    <script>
        window.location.href = '{{url("/")}}/projects';
    </script>
@endif


<div class="container-fluid px-3"> 
    <div class="flex justify-between items-center cont-mtitle  mb-4">
        <h1 class="text-survey">Validation Report (Beta) / {{ $data['survey']->survey_name }}</h1>
        <div>
            @if (\Auth::check() && \Auth::user()->hasPermission('surveyPrint', $survey))
            <button class="revnor-btn ml-3 mr-2 mb-3 mb-md-0" id="pdfBtn">Download PDF</button>                 
            @endif
            <button style="display:none; " class="revnor-btn mr-md-2 my-md-1 " onclick="goBackList();" id="backBtn">Back</button>

        </div>  
    </div>

    <div id="validationReportContainer" class="px-0" style="background: white;">
        <div class="project-tab">
            <nav>
                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-overall-tab" data-toggle="tab" href="#nav-overall" role="tab" aria-controls="nav-overall" aria-selected="true">Overall</a>
                    <a class="nav-item nav-link" id="nav-irregular-tab" data-toggle="tab" href="#nav-irregular" role="tab" aria-controls="nav-irregular" aria-selected="false">Irregular (List)</a>
                    <a class="nav-item nav-link" id="nav-classification-tab" onclick="loadClassificationData();" data-toggle="tab" href="#nav-classification" role="tab" aria-controls="nav-classification" aria-selected="false">Classification >= % of Time</a>
                    <a class="nav-item nav-link" id="nav-options-tab" onclick="loadOptionsData();" data-toggle="tab" href="#nav-options" role="tab" aria-controls="nav-options" aria-selected="false">All Options Selected</a>
                    <a class="nav-item nav-link" id="nav-deviation-tab" onclick="loadDeviationData();" data-toggle="tab" href="#nav-deviation" role="tab" aria-controls="nav-deviation" aria-selected="false">Hours Deviation</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-overall" role="tabpanel" aria-labelledby="nav-overall-tab">
                    <div class="loading-mask" style="display:block !important;position: relative;width: 100%;height: 560px;"></div>
                    <div id="chartIrregular" style="height:10px;"></div>
                </div>
                <div class="tab-pane fade" id="nav-irregular" role="tabpanel" aria-labelledby="nav-irregular-tab">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="max_selection">Max # selections</label>
                            <input class="form-control" type="number" id="max_selection" value="8" min="1" />
                        </div>
                        <div class="col-md-4">
                            <label for="max_percent">Max % of time to one classification</label>
                            <input class="form-control" type="number" id="max_percent" value="0.35" step="0.01" max="1.00" min="0.00"/>
                        </div>
                        {{-- <div class="col-md-3">
                            <label for="depth">Level of Evaluation</label>
                            <select class="form-control" name="depth" id="depth">
                                <option value="1" selected>Classification</option>
                                <option value="2">Substantive Area</option>
                                <option value="3">Child Area</option>
                            </select>
                        </div> --}}
                        <div class="col-md-4 row">
                            <div class="col-6">
                                <label for="min_hr">Min Hours</label>
                                <input class="form-control input-sm" type="number" id="min_hr" value="5" min="0" />
                            </div>
                            <div class="col-6">
                                <label for="max_hr">Max Hours</label>
                                <input class="form-control input-sm" type="number" id="max_hr" value="55" min="0" />
                            </div>
                        </div>
                    </div>
                    <div class="irregularTableDiv table-responsive mt-2">
                        <table class="table-hover" id="irregularTable"> 
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Resp Id</th>
                                    <th>Count Options</th>
                                    <th>Participant Time</th>
                                    <th>Exaggerated Hours</th>
                                    <th></th>                                    
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="loading-mask" style="display:block !important;position: relative;width: 100%;height: 560px;"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-classification" role="tabpanel" aria-labelledby="nav-classification-tab">
                    <div class="flex items-center justify-between">
                        <h4>Classification >= <span id="classification_flag">0.35</span></h4>
                        <div>
                            <label for="classification_max_percent">Validation_% of time</label>
                            <input class="form-control input-sm" type="number" step="0.01" id="classification_max_percent" value="0.35" max="1.00" min="0.00">
                        </div>
                    </div>
                    <div class="mt-2 classificationTableDiv table-responsive">
                        <table class="table-hover" id="classificationTable">
                            <thead>
                                <tr>
                                    <th>Resp Id</th>
                                    <th>Full Name</th>
                                    <th>Classification</th>
                                    <th></th>
                                    <th></th>                                    
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="loading-mask" style="display:block !important;position: relative;width: 100%;height: 560px;"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-options" role="tabpanel" aria-labelledby="nav-options-tab">
                    <div class="flex items-center justify-between">
                        <h4>All Options Selected</h4>
                        <div>
                            <label for="option_selected">Validation_options selected</label>
                            <input type="number" class="form-control input-sm" step="1" id="option_selected" min="1" value="8">
                        </div>
                    </div>
                    <div class="mt-2 optionsTableDiv table-responsive">
                        <table class="table-hover" id="optionsTable">
                            <thead>
                                <tr>
                                    <th>Resp Id</th>
                                    <th>Full Name</th>              
                                    <th>Count</th>   
                                    <th></th>                        
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="loading-mask" style="display:block !important;position: relative;width: 100%;height: 560px;"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-deviation" role="tabpanel" aria-labelledby="nav-deviation-tab">
                    <div class="flex items-center justify-start">
                        <div>
                            <label for="set_max_hours">Set Max Hours:</label>
                            <input type="number" class="form-control input-sm" id="set_max_hours" step="1" min="1" value="55">
                        </div>
                        <div class="ml-2">                          
                            <label for="set_min_hours">Set Min Hours:</label>
                            <input type="number" class="form-control input-sm" id="set_min_hours" step="1" min="1" value="5">
                        </div>
                    </div>
                    <div class="mt-2 deviationTableDiv table-responsive">
                        <table class="table-hover" id="deviationTable">
                            <thead>
                                <tr>
                                    <th>Resp Id</th>
                                    <th>Full Name</th>
                                    <th>Position</th>
                                    <th>Weekly Hours</th>
                                    <th>Hours</th> 
                                    <th></th>                                   
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="loading-mask" style="display:block !important;position: relative;width: 100%;height: 560px;"></div>
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
            <p class="text-phead">Validation Report (Beta) / {{ $survey->survey_name }}</p>
            <p class="redtext-phead">Confidential</p>
            {{-- <img  src="{{asset('imgs/logo-pdfhead.png')}}">   --}}
    </div>  

    <div class="loading-mask"></div>
    <div class="modal fade" id="detailViewModal" tabindex="-1" role="dialog" aria-labelledby="detailView" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="min-width:86%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailView">View Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="tabs">
                        <div class="tab-button-outer">
                          <ul id="tab-button">
                            <li id="tab1-button"><a href="#tab01">Summary</a></li>
                            <li id="tab2-button"><a href="#tab02">Full Data</a></li>
                          </ul>
                        </div>
                        <div class="tab-select-outer">
                          <select id="tab-select">
                            <option value="#tab01">Summary</option>
                            <option value="#tab02">Full Data</option>
                          </select>
                        </div>
                      
                        <div id="tab01" class="tab-contents" style="min-height:500px;">
                            
                        </div>
                        <div id="tab02" class="tab-contents">
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-revelation-primary" data-dismiss="modal">Close</button>
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
    <script>
        var survey_id = {{ $data['survey']->survey_id }};
        var irregularData      = {};
        var classificationData = {};
        var optionsData        = {};
        var deviationData      = {};

        var excelSummaryData   = {};
        var excelFullData      = {};

        var imgData_1, imgData_2, imgData_3, imgData_4, copyrightData, headerData;

        $(document).ready(function () {
            ajaxCallWithIrregular();

            var $tabButtonItem = $('#tab-button li'),
            $tabSelect = $('#tab-select'),
            $tabContents = $('.tab-contents'),
            activeClass = 'is-active';

            $tabButtonItem.first().addClass(activeClass);
            $tabContents.not(':first').hide();

            $tabButtonItem.find('a').on('click', function(e) {
                var target = $(this).attr('href');

                $tabButtonItem.removeClass(activeClass);
                $(this).parent().addClass(activeClass);
                $tabSelect.val(target);
                $tabContents.hide();
                $(target).show();
                e.preventDefault();
            });

            $tabSelect.on('change', function() {
                var target = $(this).val(),
                    targetSelectNum = $(this).prop('selectedIndex');

                $tabButtonItem.removeClass(activeClass);
                $tabButtonItem.eq(targetSelectNum).addClass(activeClass);
                $tabContents.hide();
                $(target).show();
            });
        });

        $('#nav-irregular input').change(function () {
            ajaxCallWithIrregular();
        });

        function ajaxCallWithIrregular () {
            $.ajax({
                url: "{{ route('getIrregularList') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "count": $('#max_selection').val(),
                    "max_percent": $('#max_percent').val(),
                    "max_hr": $('#max_hr').val(),
                    "min_hr": $('#min_hr').val(),
                    "depth": 1,
                },
                dataType: 'json',
                beforeSend: function () {
                    $('#chartIrregular').empty();
                    $('#irregularTable tbody').empty();
                    $('#nav-overall .loading-mask').show();
                    $('.irregularTableDiv .loading-mask').show();
                },
                success: function (response) {
                    $('#nav-overall .loading-mask').hide();
                    $('.irregularTableDiv .loading-mask').hide();
                    $('#chartIrregular').css('height', '560px');
                    $tbody = $('#irregularTable tbody');
                    if (response.status == 200) {
                        irregularData = response.res;
                        for (const i in response.res) {
                            $tbody.append(`<tr class="irregular-list" id="irregular-list-${i}">
                                    <td>${response.res[i].full_name}</td>
                                    <td>${response.res[i].resp_id}</td>
                                    <td>${response.res[i].count == 1 ? 'FLAG' : 'OK'}</td>
                                    <td>${response.res[i].participated_time == 1 ? 'FLAG' : 'OK'}</td>
                                    <td>${response.res[i].exaggerated_time == 1 ? 'FLAG' : 'OK'}</td>
                                    <td class="validtab-btns">
                                        <button class="btn btn-sm btn-revelation-success" title="Keep Only" onclick="keepOnly(${i}, 'irregular-list');"><i class="fa fa-check" aria-hidden="true"></i></button>    
                                        <button class="btn btn-sm btn-revelation-danger" title="Exclude" onclick="excludeList(${i}, 'irregular-list');"><i class="fa fa-ban" aria-hidden="true"></i></button>    
                                        <button class="btn btn-sm btn-revelation-primary" title="View Data" onclick="viewIrregularData(${response.res[i].resp_id});"><i class="fa fa-th-list" aria-hidden="true"></i></button>    
                                    </td>
                                </tr>`);
                        }

                        var chart = new CanvasJS.Chart("chartIrregular", {
                            axisY: {
                                gridThickness: 0,
                                tickThickness: 0,
                                includeZero: true,
                            },
                            data: [{
                                type: "column",
                                dataPoints: [
                                    { label: "Irregular", y: response.irregular_num, color: '#008EC1' },
                                    { label: "OK", y: response.resp_num - response.irregular_num, color: '#008EC1' },
                                ]
                            }]
                        });
                        chart.render();
                    } else if (response.status == 400) {
                        
                    }
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        }

        /**
        * Generate pdf document of report
        *
        * @return {void}
        */
        function generatePDF () {
            pdfdoc = new jsPDF('p', 'mm', 'a4');
            imgWidth = $('#validationReportContainer').outerWidth();
            imgHeight1 = Math.round($('#validationReportContainer').outerHeight() * 190 / imgWidth);
            y = 16; 
            position = y;
            doc_page = 1;

            pdfdoc.addImage(imgData_1, 'JPEG', 10, y, 190, imgHeight1); 
            y += imgHeight1;

            pageHeight = pdfdoc.internal.pageSize.height - 20;
            heightLeft = y - pageHeight;

            while (heightLeft >= -pageHeight) {
                position = heightLeft - imgHeight1 + 20;
                pdfdoc.addPage();
                doc_page++;
                pdfdoc.addImage(imgData_1, 'JPEG', 10, position, 190, imgHeight1);
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
            
            $activeEl = $('a.nav-item.nav-link.active');
            switch ($activeEl.attr('id')) {
                case 'nav-overall-tab':
                    pdfdoc.save('Overall');    
                    break;                        
                case 'nav-irregular-tab':
                    pdfdoc.save('Irregular (List)'); 
                    break;                        
                case 'nav-classification-tab':
                    pdfdoc.save('Classification _% of Time'); 
                    break;                        
                case 'nav-options-tab':
                    pdfdoc.save('All Options Selected'); 
                    break;                        
                case 'nav-deviation-tab':
                    pdfdoc.save('Hours Deviation'); 
                    break;
            
                default:
                    break;
            }

            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .btn').attr('disabled', true);
        }
        
        // Handle the pdf button click, generate image data from the body
        $('#pdfBtn').click(function () {
            $('#generatePDFModal').modal('show');
            $('#headerDiv').show();
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
            source = $('#validationReportContainer');
            html2canvas(source, {
                onrendered: function (canvas) {
                    imgData_1 = canvas.toDataURL('image/jpeg', 1.0);
                }
            }).then(function () {
                $('#generatePDFModal .modal-body').html(`<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="24" height="24" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path d="M30 18v-2h-6v10h2v-4h3v-2h-3v-2h4z" fill="currentColor"/><path d="M19 26h-4V16h4a3.003 3.003 0 0 1 3 3v4a3.003 3.003 0 0 1-3 3zm-2-2h2a1.001 1.001 0 0 0 1-1v-4a1.001 1.001 0 0 0-1-1h-2z" fill="currentColor"/><path d="M11 16H6v10h2v-3h3a2.003 2.003 0 0 0 2-2v-3a2.002 2.002 0 0 0-2-2zm-3 5v-3h3l.001 3z" fill="currentColor"/><path d="M22 14v-4a.91.91 0 0 0-.3-.7l-7-7A.909.909 0 0 0 14 2H4a2.006 2.006 0 0 0-2 2v24a2 2 0 0 0 2 2h16v-2H4V4h8v6a2.006 2.006 0 0 0 2 2h6v2zm-8-4V4.4l5.6 5.6z" fill="currentColor"/></svg> &nbsp; Generated a PDF`);
                $('#generatePDFModal .btn').attr('disabled', false);
                $('#headerDiv').hide();
            });           
        });

        $('#classification_max_percent').change(function () {
            let max_percent = $(this).val();
            $('#classification_flag').html(max_percent);
            callAjaxWithClassification();
        });

        $('#depth').change(function () {
            ajaxCallWithIrregular();
        });

        function loadClassificationData() {
            $tbody = $('#classificationTable tbody');
            if ($tbody.children().length == 0) {
                callAjaxWithClassification();
            }
        }

        function callAjaxWithClassification () {
            let max_percent = $('#classification_max_percent').val();

            $.ajax({
                url: "{{ route('getClassificationIrregular') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "max_percent": max_percent,
                },
                dataType: "json",
                beforeSend: function () {
                    $("#classificationTable tbody").empty();
                    $(".classificationTableDiv .loading-mask").show();
                },
                success: function (res) {
                    classificationData = res.data;
                    $(".classificationTableDiv .loading-mask").hide();
                    $tbody = $("#classificationTable tbody");
                    for (const i in res.data) {
                        $tbody.append(`<tr class="classification-list" id="classification-list-${i}">
                            <td>${ res.data[i].resp_id }</td>
                            <td>${ res.data[i].resp_last + ', ' + res.data[i].resp_first }</td>
                            <td>${ res.data[i].classification }</td>
                            <td>${ res.data[i].answer_value }%</td>
                            <td class="validtab-btns">
                                <button class="btn btn-sm btn-revelation-success" title="Keep Only" onclick="keepOnly(${i}, 'classification-list');"><i class="fa fa-check" aria-hidden="true"></i></button>    
                                <button class="btn btn-sm btn-revelation-danger" title="Exclude" onclick="excludeList(${i}, 'classification-list');"><i class="fa fa-ban" aria-hidden="true"></i></button>    
                                <button class="btn btn-sm btn-revelation-primary" title="View Data" onclick="viewClassificationData(${i}, ${res.data[i].resp_id});"><i class="fa fa-th-list" aria-hidden="true"></i></button>    
                            </td>
                        </tr>`);
                    }
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        }

        $('#option_selected').change(function () {
            callAjaxWithOptions();
        });

        function loadOptionsData () {
            $tbody = $('#optionsTable tbody');
            if ($tbody.children().length == 0) {
                callAjaxWithOptions();
            }
        }

        function callAjaxWithOptions () {
            let max_options = $('#option_selected').val();

            $.ajax({
                url: "{{ route('getOptionsIrregular') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "max_options": max_options,
                },
                dataType: "json",
                beforeSend: function () {
                    $("#optionsTable tbody").empty();
                    $(".optionsTableDiv .loading-mask").show();
                },
                success: function (res) {
                    $(".optionsTableDiv .loading-mask").hide();
                    $tbody = $("#optionsTable tbody");
                    optionsData = res.data;
                    let flag_respId = 0;
                    for (const i in res.data) {
                        if (res.data[i].resp_id != flag_respId) {
                            $tbody.append(`<tr class="options-list" id="options-list-${i}">
                                <td>${ res.data[i].resp_id }</td>
                                <td>${ res.data[i].resp_last + ', ' + res.data[i].resp_first }</td>
                                <td>${ res.data[i].count_num }</td>
                                <td class="validtab-btns">
                                    <button class="btn btn-sm btn-revelation-success" title="Keep Only" onclick="keepOnly(${i}, 'options-list');"><i class="fa fa-check" aria-hidden="true"></i></button>    
                                    <button class="btn btn-sm btn-revelation-danger" title="Exclude" onclick="excludeList(${i}, 'options-list');"><i class="fa fa-ban" aria-hidden="true"></i></button>    
                                    <button class="btn btn-sm btn-revelation-primary" title="View Data" onclick="viewOptionsData(${i}, ${res.data[i].resp_id});"><i class="fa fa-th-list" aria-hidden="true"></i></button>
                                </td>
                            </tr>`);
                            flag_respId = res.data[i].resp_id;
                        }
                    }
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        }

        $('#set_max_hours').change(function () {
            callAjaxWithDeviation();
        });

        $('#set_min_hours').change(function () {
            callAjaxWithDeviation();
        });

        function loadDeviationData () {
            $tbody = $('#deviationTable tbody');
            if ($tbody.children().length == 0) {
                callAjaxWithDeviation();
            }
        }

        function callAjaxWithDeviation () {
            let max_hrs = $('#set_max_hours').val();
            let min_hrs = $('#set_min_hours').val();

            $.ajax({
                url: "{{ route('getDeviationIrregular') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "max_hrs": max_hrs,
                    "min_hrs": min_hrs,
                },
                dataType: "json",
                beforeSend: function () {
                    $("#deviationTable tbody").empty();
                    $(".deviationTableDiv .loading-mask").show();
                },
                success: function (res) {
                    $(".deviationTableDiv .loading-mask").hide();
                    $tbody = $("#deviationTable tbody");
                    deviationData = res.data;
                    for (const i in res.data) {
                        $tbody.append(`<tr class="deviation-list" id="deviation-list-${i}">
                            <td>${ res.data[i].resp_id }</td>
                            <td>${ res.data[i].resp_last + ', ' + res.data[i].resp_first }</td>
                            <td>${ res.data[i].cust_3 }</td>
                            <td align="right">${ res.data[i].week_hours }</td>
                            <td align="right">${ numberWithCommas(res.data[i].legal_hours + res.data[i].support_hours) }</td>
                            <td class="validtab-btns">
                                <button class="btn btn-sm btn-revelation-success" title="Keep Only" onclick="keepOnly(${i}, 'deviation-list');"><i class="fa fa-check" aria-hidden="true"></i></button>    
                                <button class="btn btn-sm btn-revelation-danger" title="Exclude" onclick="excludeList(${i}, 'deviation-list');"><i class="fa fa-ban" aria-hidden="true"></i></button>    
                                <button class="btn btn-sm btn-revelation-primary" title="View Data" onclick="viewDeviationData(${i}, ${res.data[i].resp_id});"><i class="fa fa-th-list" aria-hidden="true"></i></button>    
                            </td>
                        </tr>`);
                    }
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

        function keepOnly (id, str_list) {
            $(`.${str_list}`).each(function () {
                selector = str_list + '-' + id;
                if ($(this).attr('id') != selector) {
                    $(this).remove();
                }
            });
        }

        function excludeList (id, str_list) {
            $(`#${str_list}-${id}`).remove();
        }

        function viewIrregularData (resp_id) {
            focusSummaryTab();            
            $fulldata_container = $('#tab02');
            $summary_container = $('#tab01');
            $summary_container.empty();
            $summary_container.append(`<div>
                    <p>Showing first 1 row</p>
                    <a href="javascript:void(0);" onclick="exportExcelSummaryData('overall');">Download all rows as a text file</a>
                </div>`);
            $summary_container.append(`<div>
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Full Name</th>    
                                <th>Resp Id</th>    
                                <th>Count Options</th>    
                                <th>Participant Time</th>    
                                <th>AGG(Exaggerated Hours)</th>    
                                <th>AGG(validation_overall)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>${irregularData[resp_id].full_name}</td>
                                <td>${irregularData[resp_id].resp_id}</td>
                                <td>${irregularData[resp_id].count == 1 ? 'FLAG' : 'OK'}</td>
                                <td>${irregularData[resp_id].participated_time == 1 ? 'FLAG' : 'OK'}</td>
                                <td>${irregularData[resp_id].exaggerated_time == 1 ? 'FLAG' : 'OK'}</td>
                                <td>IRREGULAR</td>
                            </tr>
                        </tbody>    
                    </table>
                </div>`);
            excelSummaryData['columns'] = [
                'Full Name',
                'Resp Id',
                'Count Options',
                'Participant Time',
                'AGG(Exaggerated Hours)',
                'AGG(validation_overall)'];
            excelSummaryData['data'] = [
                irregularData[resp_id].full_name,
                irregularData[resp_id].resp_id,
                irregularData[resp_id].count == 1 ? 'FLAG' : 'OK',
                irregularData[resp_id].participated_time == 1 ? 'FLAG' : 'OK',
                irregularData[resp_id].exaggerated_time == 1 ? 'FLAG' : 'OK',
                'IRREGULAR'
            ];
            
            $.ajax({
                url: "{{ route('getFullDataOfIrregular') }}",
                type: 'POST',
                data: {
                    "_token": '{{ csrf_token() }}',
                    "survey_id": survey_id,
                    "resp_id": resp_id,
                    "sort": 'irregular',
                    "count": $('#max_selection').val(),
                    "max_percent": $('#max_percent').val(),
                    "max_hr": $('#max_hr').val(),
                    "min_hr": $('#min_hr').val(),
                    "depth": 1,
                },
                dataType: 'json',
                beforeSend: function () {
                    $fulldata_container.empty();
                    $('#tab02').append(`<p class="text-center" style="margin:100px 0;">Loading...</p>`);
                },
                success: function (res) {
                    if (res.status == 200) {
                        $fulldata_container.empty(); 
                        let strHtml = `<div class="fulldataTableDiv table-responsive"><table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Resp Id</th>
                                        <th>Count Options</th>
                                        <th>Participant Time</th>
                                        <th>Classification</th>
                                        <th>Employee Total Hours</th>
                                        <th>Survey ID</th>
                                        <th>Exaggerated Hours</th>
                                        <th>Excessive Options</th>
                                        <th>Hours</th>
                                        <th>Percent of Hours</th>
                                        <th>Total Annual Hours of Participants</th>
                                        <th>Weekly Hours</th>
                                    </tr>    
                                </thead>
                                <tbody>`;
                        excelFullData['columns'] = [
                            'Full Name',
                            'Resp Id',
                            'Count Options',
                            'Participant Time',
                            'Classification',
                            'Employee Total Hours',
                            'Survey ID',
                            'Exaggerated Hours',
                            'Excessive Options',
                            'Hours',
                            'Percent of Hours',
                            'Total Annual Hours of Participants',
                            'Weekly Hours'
                        ];

                        excelFullData['data'] = [];
                        
                        res.data.forEach (row => {
                            let exaggeratedStr = 'OK';
                            let excessive_option = 0;
                            if (row.weekly_hours > $('#max_hr').val() || row.weekly_hours < $('#min_hr').val()) {
                                exaggeratedStr = 'FLAG';
                                excessive_option = 1;
                            }
                            strHtml += `<tr>
                                    <td style="width:150px;">${ row.resp_first + ', ' + row.resp_last }</td>
                                    <td>${ row.resp_id }</td>
                                    <td>${ row.count_options > $('#max_selection').val() ? 'FLAG' : 'OK' }</td>
                                    <td>${ row.hours_percent > $('#max_percent').val() * 100 ? 'FLAG' : 'OK' }</td>
                                    <td>${ row.question_desc }</td>
                                    <td>${ (row.legal_hours + row.support_hours).toFixed(2) }</td>
                                    <td>${ row.survey_id }</td>
                                    <td>${ exaggeratedStr }</td>
                                    <td>${ excessive_option }</td>
                                    <td>${ row.total_hours }</td>
                                    <td>${ row.hours_percent }%</td>
                                    <td>${ row.total_hours.toFixed(3) }</td>
                                    <td>${ row.weekly_hours.toFixed(4) }</td>
                                </tr>`;
                            excelFullData['data'].push([
                                row.resp_first + ', ' + row.resp_last,
                                row.resp_id,
                                row.count_options > $('#max_selection').val() ? 'FLAG' : 'OK',
                                row.hours_percent > $('#max_percent').val() * 100 ? 'FLAG' : 'OK',
                                row.question_desc,
                                (row.legal_hours + row.support_hours).toFixed(2),
                                row.survey_id,
                                exaggeratedStr,
                                excessive_option,
                                row.total_hours,
                                row.hours_percent,
                                row.total_hours.toFixed(3),
                                row.weekly_hours.toFixed(4)
                            ]);
                        });
                    
                        strHtml += `</tbody>
                            </table></div>`;
                        
                        $fulldata_container.append(`<div>
                                <p>Showing first ${res.data.length} rows</p>
                                <a href="javascript:void(0);" onclick="exportExcelFullData('overall');">Download all rows as a text file</a>
                            </div>`);

                        $fulldata_container.append(strHtml);
                    } else if (res.status == 400) {
                        $fulldata_container = $('#tab02');
                        $fulldata_container.empty();
                        $fulldata_container.append(`<div class="text-center"><p>No data to show</p></div>`)
                    }
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
            $('#detailViewModal').modal('show');
        }

        function viewClassificationData (id, resp_id) {
            focusSummaryTab();
            $fulldata_container = $('#tab02');
            $summary_container = $('#tab01');
            $summary_container.empty();
            $summary_container.append(`<div>
                    <p>Showing first 1 row</p>
                    <a href="javascript:void(0);" onclick="exportExcelSummaryData('classification');">Download all rows as a text file</a>
                </div>`);
            $summary_container.append(`<div>
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Resp Id</th>    
                                <th>Full Name</th>    
                                <th>Classification</th>    
                                <th>AVG(Percent of Hours)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>${classificationData[id].resp_id}</td>
                                <td>${classificationData[id].resp_first + ', ' + classificationData[id].resp_last}</td>
                                <td>${classificationData[id].classification}</td>
                                <td>${classificationData[id].answer_value}%</td>
                            </tr>
                        </tbody>    
                    </table>
                </div>`);
            excelSummaryData['columns'] = [
                'Resp Id',
                'Full Name',
                'Classification',
                'AVG(Percent of Hours)'];
            excelSummaryData['data'] = [
                classificationData[id].resp_id,
                classificationData[id].resp_first + ', ' + classificationData[id].resp_last,
                classificationData[id].question_desc,
                classificationData[id].answer_value + '%'
            ];
            $.ajax({
                url: "{{ route('getFullDataOfIrregular') }}",
                type: 'POST',
                data: {
                    "_token": '{{ csrf_token() }}',
                    "survey_id": survey_id,
                    "resp_id": resp_id,
                    "sort": 'classification',
                    "count": $('#max_selection').val(),
                    "max_percent": $('#max_percent').val(),
                    "max_hr": $('#max_hr').val(),
                    "min_hr": $('#min_hr').val(),
                    "depth": 1,
                },
                dataType: 'json',
                beforeSend: function () {
                    $fulldata_container.empty();
                    $('#tab02').append(`<p class="text-center" style="margin:100px 0;">Loading...</p>`);
                },
                success: function (res) {
                    if (res.status == 200) {
                        $fulldata_container.empty();
                        let strHtml = `<div class="fulldataTableDiv table-responsive"><table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Resp Id</th>
                                        <th>Full Name</th>
                                        <th>Classification</th>
                                        <th>Taxonomy</th>
                                        <th>Participant Time</th>
                                        <th>Employee Total Hours</th>
                                        <th>Survey ID</th>
                                        <th>Hours</th>
                                        <th>Percent of Hours</th>
                                    </tr>    
                                </thead>
                                <tbody>`;

                        excelFullData['columns'] = [
                            'Resp Id',
                            'Full Name',
                            'Classification',
                            'Taxonomy',
                            'Participant Time',
                            'Employee Total Hours',
                            'Survey ID',
                            'Hours',
                            'Percent of Hours'
                        ];

                        excelFullData['data'] = [];
                        
                        res.data.forEach (row => {
                            let exaggeratedStr = 'OK';
                            let excessive_option = 0;
                            if (row.weekly_hours > $('#max_hr').val() || row.weekly_hours < $('#min_hr').val()) {
                                exaggeratedStr = 'FLAG';
                                excessive_option = 1;
                            }
                            strHtml += `<tr>
                                    <td>${ row.resp_id }</td>
                                    <td style="width:150px;">${ row.resp_first + ', ' + row.resp_last }</td>
                                    <td>${ row.classification }</td>
                                    <td>${ row.question_desc }</td>
                                    <td>FLAG</td>
                                    <td>${ row.total_hours.toFixed(2) }</td>
                                    <td>${ row.survey_id }</td>
                                    <td>${ row.hours_value == 0 ? 'Null' : row.hours_value }</td>
                                    <td>${ row.answer_value == 0 ? 'Null' : row.answer_value + '%' }</td>
                                </tr>`;
                            excelFullData['data'].push([
                                row.resp_id,
                                row.resp_first + ', ' + row.resp_last,
                                row.classification,
                                row.question_desc,
                                'FLAG',
                                row.total_hours.toFixed(2),
                                row.survey_id,
                                row.hours_value == 0 ? 'Null' : row.hours_value,
                                row.answer_value == 0 ? 'Null' : row.answer_value + '%'
                            ]);
                        });
                    
                        strHtml += `</tbody>
                            </table></div>`;
                        
                        $fulldata_container.append(`<div>
                                <p>Showing first ${res.data.length} rows</p>
                                <a href="javascript:void(0);" onclick="exportExcelFullData('classification');">Download all rows as a text file</a>
                            </div>`);

                        $fulldata_container.append(strHtml);
                    } else if (res.status == 400) {
                        $fulldata_container = $('#tab02');
                        $fulldata_container.empty();
                        $fulldata_container.append(`<div class="text-center"><p>No data to show</p></div>`)
                    }
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
            $('#detailViewModal').modal('show');            
        }

        function viewOptionsData (id, resp_id) {
            focusSummaryTab();
            $summary_container = $('#tab01');
            $summary_container.empty();
            $summary_container.append(`<div>
                    <p>Showing first 1 row</p>
                    <a href="javascript:void(0);" onclick="exportExcelSummaryData('options');">Download all rows as a text file</a>
                </div>`);
            $summary_container.append(`<div>
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Resp Id</th>    
                                <th>Full Name</th>    
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>${optionsData[id].resp_id}</td>
                                <td>${optionsData[id].resp_first + ', ' + optionsData[id].resp_last}</td>
                                <td>${optionsData[id].count_num}</td>
                            </tr>
                        </tbody>    
                    </table>
                </div>`);
            excelSummaryData['columns'] = [
                'Resp Id',
                'Full Name',
                'Count'];
            excelSummaryData['data'] = [
                optionsData[id].resp_id,
                optionsData[id].resp_first + ', ' + optionsData[id].resp_last,
                optionsData[id].count_num,
            ];

            $fulldata_container = $('#tab02');
            $fulldata_container.empty();
            let strHtml = `<div class="fulldataTableDiv table-responsive"><table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Resp Id</th>
                            <th>Full Name</th>
                            <th>Options</th>
                            <th>Answer Value</th>
                        </tr>    
                    </thead>
                    <tbody>`;
            
            excelFullData['columns'] = [
                'Resp Id',
                'Full Name',
                'Options',
                'Answer Value',
            ];

            excelFullData['data'] = [];

            for (const i in optionsData) {
                if (optionsData[i].resp_id == resp_id) {
                    strHtml += `<tr>
                            <td>${ optionsData[i].resp_id }</td>
                            <td>${ optionsData[i].resp_first + ', ' + optionsData[i].resp_last }</td>
                            <td>${ optionsData[i].question_desc }</td>
                            <td>${ optionsData[i].answer_value }%</td>
                        </tr>`;
                    excelFullData['data'].push([
                        optionsData[i].resp_id,
                        optionsData[i].resp_first + ', ' + optionsData[i].resp_last,
                        optionsData[i].question_desc,
                        optionsData[i].answer_value + '%'
                    ]);
                }
            }
        
            strHtml += `</tbody>
                </table></div>`;
            
            $fulldata_container.append(`<div>
                    <p>Showing first ${optionsData[id].count_num} rows</p>
                    <a href="javascript:void(0);" onclick="exportExcelFullData('options');">Download all rows as a text file</a>
                </div>`);

            $fulldata_container.append(strHtml);

            $('#detailViewModal').modal('show');  
        }

        function viewDeviationData (id, resp_id) {
            focusSummaryTab();
            $fulldata_container = $('#tab02');
            $summary_container = $('#tab01');
            $summary_container.empty();
            $summary_container.append(`<div>
                    <p>Showing first 1 row</p>
                    <a href="javascript:void(0);" onclick="exportExcelSummaryData('deviation');">Download all rows as a text file</a>
                </div>`);
            let tmpHr = (deviationData[id].legal_hours + deviationData[id].support_hours).toFixed(2);
            $summary_container.append(`<div>
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Resp Id</th>    
                                <th>Full Name</th>    
                                <th>Position</th>    
                                <th>Measure Names</th>
                                <th>Measure Values</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>${deviationData[id].resp_id}</td>
                                <td>${deviationData[id].resp_first + ', ' + deviationData[id].resp_last}</td>
                                <td>${deviationData[id].cust_3}</td>
                                <td>Hours</td>
                                <td>${numberWithCommas(tmpHr)}</td>
                            </tr>
                        </tbody>    
                    </table>
                </div>`);
            excelSummaryData['columns'] = [
                'Resp Id',
                'Full Name',
                'Position',
                'Measure Names',
                'Measure Values'];
            excelSummaryData['data'] = [
                deviationData[id].resp_id,
                deviationData[id].resp_first + ', ' + deviationData[id].resp_last,
                deviationData[id].cust_3,
                'Hours',
                numberWithCommas(tmpHr)
            ];

            $.ajax({
                url: "{{ route('getFullDataOfIrregular') }}",
                type: 'POST',
                data: {
                    "_token": '{{ csrf_token() }}',
                    "survey_id": survey_id,
                    "resp_id": resp_id,
                    "sort": 'deviation',
                    "count": $('#max_selection').val(),
                    "max_percent": $('#max_percent').val(),
                    "max_hr": $('#set_max_hours').val(),
                    "min_hr": $('#set_min_hours').val(),
                    "depth": 1,
                },
                dataType: 'json',
                beforeSend: function () {
                    $fulldata_container.empty();
                    $('#tab02').append(`<p class="text-center" style="margin:100px 0;">Loading...</p>`);
                },
                success: function (res) {
                    if (res.status == 200) {
                        $fulldata_container.empty();
                        let strHtml = `<div class="fulldataTableDiv table-responsive"><table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Resp Id</th>
                                        <th>Full Name</th>
                                        <th>Position</th>
                                        <th>Participant Time</th>
                                        <th>Classification</th>
                                        <th>Employee Total Hours</th>
                                        <th>Survey ID</th>
                                        <th>Hours</th>
                                        <th>Percent of Hours</th>
                                        <th>Total Annual Hours of Participants</th>
                                        <th>Weekly Hours</th>
                                    </tr>    
                                </thead>
                                <tbody>`;

                        excelFullData['columns'] = [
                            'Resp Id',
                            'Full Name',
                            'Position',
                            'Participant Time',
                            'Classification',
                            'Employee Total Hours',
                            'Survey ID',
                            'Hours',
                            'Percent of Hours',
                            'Total Annual Hours of Participants',
                            'Weekly Hours'
                        ];

                        excelFullData['data'] = [];
                        res.data.forEach (row => {
                            strHtml += `<tr>
                                    <td>${ row.resp_id }</td>
                                    <td style="width:150px;">${ row.resp_first + ', ' + row.resp_last }</td>
                                    <td>${ row.cust_3 }</td>
                                    <td>FLAG</td>
                                    <td>${ row.question_desc }</td>
                                    <td>${ row.total_hours.toFixed(2) }</td>
                                    <td>${ row.survey_id }</td>
                                    <td>${ row.answer_value == 0 ? 'Null' : numberWithCommas(row.answer_value) }</td>
                                    <td>${ row.hours_percent == 0 ? 'Null' : row.hours_percent + '%' }</td>
                                    <td>${ row.answer_value == 0 ? 'Null' : numberWithCommas(row.answer_value.toFixed(2)) }</td>
                                    <td>${ row.weekly_hours == 0 ? 'Null' : row.weekly_hours.toFixed(4) }</td>
                                </tr>`;
                            excelFullData['data'].push([
                                row.resp_id,
                                row.resp_first + ', ' + row.resp_last,
                                row.cust_3,
                                'FLAG',
                                row.question_desc,
                                row.total_hours.toFixed(2),
                                row.survey_id,
                                row.answer_value == 0 ? 'Null' : numberWithCommas(row.answer_value),
                                row.hours_percent == 0 ? 'Null' : row.hours_percent + '%',
                                row.answer_value == 0 ? 'Null' : numberWithCommas(row.answer_value.toFixed(2)),
                                row.weekly_hours == 0 ? 'Null' : row.weekly_hours.toFixed(4),
                            ]);
                        });
                    
                        strHtml += `</tbody>
                            </table></div>`;
                        
                        $fulldata_container.append(`<div>
                                <p>Showing first ${res.data.length} rows</p>
                                <a href="javascript:void(0);" onclick="exportExcelFullData('deviation');">Download all rows as a text file</a>
                            </div>`);

                        $fulldata_container.append(strHtml);
                    } else if (res.status == 400) {
                        $fulldata_container = $('#tab02');
                        $fulldata_container.empty();
                        $fulldata_container.append(`<div class="text-center"><p>No data to show</p></div>`)
                    }
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
            $('#detailViewModal').modal('show');
        }

        function focusSummaryTab () {            
            $('#tab1-button').addClass('is-active');
            $('#tab2-button').removeClass('is-active');
            $('#tab01').css('display', 'block');
            $('#tab02').css('display', 'none');
        }

        function exportExcelSummaryData (sort) {
            $.ajax({
                url: "{{ route('exportExcelSummaryData') }}",
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'survey_id': survey_id,
                    'columns': excelSummaryData['columns'],
                    'data': excelSummaryData['data'],
                    'sort': sort
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

        function exportExcelFullData (sort) {
            $.ajax({
                url: "{{ route('exportExcelFullData') }}",
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'survey_id': survey_id,
                    'columns': excelFullData['columns'],
                    'data': excelFullData['data'],
                    'sort': sort
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
    </script>
@endsection
