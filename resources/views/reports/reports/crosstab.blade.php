@extends('layouts.reports')
@section('content')
    {{-- {{dd($data['resps'][0])}} --}}

    <div id="HelpContent" class="modal-body" style="display:none;">
        <p><b>Reports</b></p>
        <p>When you click on Reports, you will see four different report options listed: <b>Demographic,Individual,
                Compilation, and Crosstab</b>.</p>
        <p><strong>Crosstab Report</strong></p>
        <p>The Crosstab Report is a visual report that allows for you to study whole groups of employees, or
            tasks, at a glance. It uses heat map viewing, which means that it applies a range of colors ranging
            from green, to tan, to red, to visually show you the data. Green represents a lower side of the
            spectrum of data, while tan is in the middle, and red is a higher side. Essentially, the darker a
            cell, the greater the activity.</p>

        <p>The default view for the Crosstab Report is sorted in terms of hours; however, you may change
            this to view the report in a way that is more convenient for you by using the filters at the top of
            the page.</p>
        <img src="{{ asset('imgs/user-guide/image-026.png') }}" alt="img">
        <p>You also have an option to download this dataset into Microsoft Excel, as well as download it as
            a PDF file. Both options are listed at the top of the page, above the sorting filters.</p>

    </div>

    <link rel="stylesheet" href="{{ asset('css/bootstrap-table.min.css') }}">
    <script src="{{ asset('js/bootstrap-table.min.js') }}"></script>
    <div class="container-fluid px-3 hideinhelppdf">
        <div class="flex justify-between items-center cont-mtitle  mb-4" id="hideinpdf">
            <h1 class="text-survey">Crosstab Report / {{ $survey->survey_name }}</h1>
            <div class="d-flex py-0 py-md-2">
                @if (\Auth::check() && \Auth::user()->hasPermission('surveyExport', $survey))
                    <button class="revnor-btn mr-1" id="excelBtn">Export to Excel</button>
                @endif
                @if (\Auth::check() && \Auth::user()->hasPermission('surveyPrint', $survey))
                    <!-- <button class="revnor-btn mr-md-2 my-md-1" id="pdfPrint">Download PDF</button>  -->
                    <button class="revnor-btn mr-1" id="pdfPrint">Download
                        PDF</button>
                @endif
                <button type="button" class="helpguidebtn mx-1" data-toggle="modal" data-target="#helpdetasurvey"></button>
            </div>
        </div>
        <div id="headerDiv" class="pdfheaderdiv">
            <p class="text-phead">Crosstab Report / {{ $survey->survey_name }}</p>
            <p class="redtext-phead">Confidential</p>
            {{-- <img  src="{{asset('imgs/logo-pdfhead.png')}}"> --}}
        </div>
        <div id="individualContent" class="">
            <div class="first_part border-bottom">
                <div class="row">
                    <div class="col-12 mt-2">
                        <div class="lead_in">
                            <h5 class="text-lg">{{ $data['survey']->survey_name }}</h5>
                            <h5 class="text-lg">Crosstab Breakdown</h5>
                        </div>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-6 col-md-2 px-0">
                        <div class="crosstab-txt ">Classification</div>
                        <select name="taxonomy" id="taxonomy" class="form-control filter-btn">
                            <option value="">(All)</option>
                            @foreach ($data['taxonomy'] as $tax)
                                <option value="{{ $tax->question_desc }}{{ $tax->question_desc == 'Legal Services' ? '17371' : '17370' }}">
                                    {{ $tax->question_desc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3 px-0">
                        <div class="crosstab-txt ">Metric</div>
                        <select class="form-control" id="metric">
                            @foreach (App\Http\Controllers\CrosstabReportController::getMetricMap() as $metric => $title)
                                <option value="{{ $metric }}">{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3 px-0">
                        <div class="crosstab-txt ">Veritcal Breakdown</div>
                        <select class="form-control" id="vertical_breakdown">
                            <option value="0">Support or Legal</option>
                            <option value="1" selected>Classification</option>
                            <option value="2">Substantive Area</option>
                            <option value="3">Category</option>
                            <option value="4">Process</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2 px-0">
                        <div class="crosstab-txt ">Horizontal Breakdown</div>
                        <select class="form-control" id="horizontal_breakdown">
                            <option value="group">Group</option>
                            <option value="position" selected>Position</option>
                            <option value="department">Department</option>
                            <option value="category">Category</option>
                            <option value="location">Location</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2 px-0">
                        <div class="crosstab-txt ">Location</div>
                        <select class="form-control" id="location">
                            <option value="">(All)</option>
                            @foreach ($data['location'] as $location)
                                @if ($location != '')
                                    <option value="{{ $location }}">{{ $location }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="second_part container-fluid">
            <div class="row">
                <div class="col-12 px-0">
                    <div class="table-crosstab-pro" style="overflow-x: scroll;">
                        <table id="reportBreakdownChart" class="table table-sm text-sm">
                            <thead></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" tabindex="-1" role="dialog" id="generateExcelModal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body flex items-center justify-center" style="height: 150px;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp;
                        Generating Excel file ...
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-revelation-primary disabled" href="javascript:void(0);">Download</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="generatePDFModal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body flex items-center justify-center" style="height: 150px;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp;
                        Generating PDF file ...
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-revelation-primary" disabled onclick="generatePDF();"
                            href="javascript:void(0);">Download</a>
                    </div>
                </div>
            </div>
        </div>


        <div id="copyright_div" class="flex justify-content-between items-end" style="">
            <div>
                <img src="{{ asset('imgs/logo-pdfhead.png') }}">
            </div>
            <div class="text-center">
                <a href="{{ url('/') }}">{{ url('/') }}</a> <br>
                <span>© ofPartner LLC {{ date('Y') }}, All Rights Reserved.</span>
            </div>
            <div>

                <span>Report Generated @php echo date('m/d/Y h:i:s') @endphp</span>
            </div>
        </div>

        <div class="modal fade" id="helpdetasurvey" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header align-items-center">
                        <h5 class="modal-title" id="exampleModalCenterTitle">User Guide</h5>
                        <button class="revnor-btn ml-auto mr-2 mb-3 mb-md-0 bg-white text-dark"
                            id="printHelp">Print</button>
                        <button type="button" class="close ml-0" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><b>Reports</b></p>
                        <p>When you click on Reports, you will see four different report options listed:
                            <b>Demographic,Individual, Compilation, and Crosstab</b>.</p>
                        <p><strong>Crosstab Report</strong></p>
                        <p>The Crosstab Report is a visual report that allows for you to study whole groups of employees, or
                            tasks, at a glance. It uses heat map viewing, which means that it applies a range of colors
                            ranging
                            from green, to tan, to red, to visually show you the data. Green represents a lower side of the
                            spectrum of data, while tan is in the middle, and red is a higher side. Essentially, the darker
                            a
                            cell, the greater the activity.</p>

                        <p>The default view for the Crosstab Report is sorted in terms of hours; however, you may change
                            this to view the report in a way that is more convenient for you by using the filters at the top
                            of
                            the page.</p>
                        <img src="{{ asset('imgs/user-guide/image-026.png') }}" alt="img">
                        <p>You also have an option to download this dataset into Microsoft Excel, as well as download it as
                            a PDF file. Both options are listed at the top of the page, above the sorting filters.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <script>
        $('#printHelp').on('click', function() {

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
            const hideElements = ['#desktop_sidebar', '#hideinpdf', '.site-footer', '.site-header', '.first_part',
                '#pdfPrint', 'header > div > ul'
            ];

            $.each(hideElements, function(_, el) {
                $(el).hide();
            });

            window.print();

            $.each(hideElements, function(_, el) {
                $(el).show();
            });

            $('#headerDiv').hide();
            $('#HelpContent').hide();
            $('#hiddenprint').show();
            $('#pdfhidden').show();
            $('.hideinhelppdf').show();
            $('#copyright_div').removeClass('fixedbottompdf');
            $('#headerDiv').removeClass('fixedtoppdf');
            $(".entrymain-content")[0].style.minHeight = "100vh";
        });






        const survey_id = <?= $data['survey']->survey_id ?>;
        const survey = @JSON($data['survey']);
        const $table = $('#report_breakdown_table');

        $(init);

        function init() {
            $('#taxonomy').trigger(
            'change'); // trigger a change event on the selectors for initial page load so the user doesn't have to
        }

        $('#taxonomy, #metric, #vertical_breakdown, #horizontal_breakdown, #location').on('change', function() {
            getFilteredData(
                $('#taxonomy').val(),
                $('#metric').val(),
                $('#vertical_breakdown').val(),
                $('#horizontal_breakdown').val(),
                $('#location').val(),
                survey.survey_id
            );
        });


        /* $('#pdfPrint').on('click', function(){
            $('#headerDiv').show();
            const hideElements = ['#desktop_sidebar','#hideinpdf', '.site-footer','.site-header','.first_part', '#pdfPrint', 'header > div > ul'];

            $.each(hideElements, function(_, el){ $(el).hide(); });

            window.print();

            $.each(hideElements, function(_, el){ $(el).show(); });
            $('#headerDiv').hide();
        }); */

        $(document).ready(function() {
            $('#pdfPrint').on('click', function() {
                $(".table-responsive").css('overflow-x', 'visible');
                $('#generatePDFModal').modal('show');
				 $('#copyright_div').addClass('d-flex');
				 $('#copyright_div').show();
                $('#headerDiv').show();
                source = $('#individualContent .first_part');
                html2canvas(source, {
                    onrendered: function(canvas) {
                        imgData_1 = canvas.toDataURL('image/jpeg', 1.0);
                    }
                });

                source = $('.second_part');
                /* console.log(source);
                return; */
                html2canvas(source, {
                    scale: 3
                }).then(function(canvas) {

                    imgData_2 = canvas.toDataURL("image/png", 1.0);

                });
                source = $('#headerDiv');
                /* console.log(source);
                return; */
                html2canvas(source, {
                    scale: 3
                }).then(function(canvas) {

                    headerData = canvas.toDataURL("image/png", 1.0);

                });

                // Copyright copyrightData
                source = $('#copyright_div');
                html2canvas(source, {
                    scale: 3
                }).then(function(canvas) {

                    copyrightData = canvas.toDataURL("image/png", 1.0);

                }).then(function() {
                    $('#generatePDFModal .modal-body').html(
                        `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="24" height="24" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path d="M30 18v-2h-6v10h2v-4h3v-2h-3v-2h4z" fill="currentColor"/><path d="M19 26h-4V16h4a3.003 3.003 0 0 1 3 3v4a3.003 3.003 0 0 1-3 3zm-2-2h2a1.001 1.001 0 0 0 1-1v-4a1.001 1.001 0 0 0-1-1h-2z" fill="currentColor"/><path d="M11 16H6v10h2v-3h3a2.003 2.003 0 0 0 2-2v-3a2.002 2.002 0 0 0-2-2zm-3 5v-3h3l.001 3z" fill="currentColor"/><path d="M22 14v-4a.91.91 0 0 0-.3-.7l-7-7A.909.909 0 0 0 14 2H4a2.006 2.006 0 0 0-2 2v24a2 2 0 0 0 2 2h16v-2H4V4h8v6a2.006 2.006 0 0 0 2 2h6v2zm-8-4V4.4l5.6 5.6z" fill="currentColor"/></svg> &nbsp; Generated a PDF`
                    );
                    $('#generatePDFModal .btn').attr('disabled', false);
                    $(".table-responsive").css('overflow-x', 'scroll');
					$('#copyright_div').removeClass('d-flex');
					$('#copyright_div').hide();
					$('#headerDiv').hide(); 	 				
                });


            });
        });

        function generatePDF() {
            // var respondent_name = $('#respondent_data').find('h3').text();

            let imgWidth = $('.second_part').outerWidth();

            pdfdoc = new jsPDF('l', 'mm', 'a4');
            //pdfdoc.internal.scaleFactor = 20;
            imgHeight1 = Math.round($('#individualContent .first_part').outerHeight() * 190 / imgWidth);
            //imgHeight1 = Math.round($('#individualContent .first_part').outerHeight() * 190 / imgWidth);
            y = 14;
            position = y;
            doc_page = 1;

            /*  pdfdoc.addImage(imgData_1, 'JPEG', 10, y, 190, imgHeight1);
             y += imgHeight1; */
            //console.log(imgData_2);
            imgHeight2 = Math.round($('.second_part').outerHeight() * 200 / imgWidth);
            pdfdoc.addImage(imgData_2, 'JPEG', 12, y, 270, imgHeight2);
            y += imgHeight2;

            /* imgHeight4 = Math.round($('#individualContent .fourth_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_4, 'JPEG', 10, y, 190, imgHeight4);
            y += imgHeight4;

            imgHeight3 = Math.round($('#individualContent .third_part').outerHeight() * 190 / imgWidth);

            pdfdoc.addPage();
            doc_page++;
            pdfdoc.addImage(imgData_3, 'JPEG', 10 , 20, 190, imgHeight3); */

            pageHeight = pdfdoc.internal.pageSize.height - 20;
            heightLeft = imgHeight2 - pageHeight + 10;

            while (heightLeft >= -pageHeight) {
                position = heightLeft - imgHeight2;
                pdfdoc.addPage();
                doc_page++;
                pdfdoc.addImage(imgData_2, 'JPEG', 10, position, 270, imgHeight2);
                heightLeft -= pageHeight;
            }

            pdfdoc.deletePage(doc_page);

            for (i = 1; i < doc_page; i++) {
                pdfdoc.setPage(i);
                pdfdoc.addImage(headerData, 'JPEG', 55, 0, 190, 14);
                pdfdoc.addImage(copyrightData, 'JPEG', 55, 190, 190, 14.5);
                // pdfdoc.text('Page ' + i + ' of ' + (doc_page-1) , 8, 275, 90.916667, 52.916667, null, null, 45);
                pdfdoc.setTextColor(111, 107, 107);
                pdfdoc.setFontSize(8);
                pdfdoc.text('Page ' + i + ' of ' + (doc_page - 1), 168, 195, 0, 45);
            }

            pdfdoc.save(`{{ $data['survey']->survey_name }} (Crosstab Report)`);
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .btn').attr('disabled', true);
            $('#headerDiv').hide();

        }

        function getFilteredData(taxonomy, metric, vertical_breakdown, horizontal_breakdown, location, survey_id) {
            // taxonomy = [];

            // alert(taxonomy);
            showLoader();
            $.post('{{ url('/') }}/reports/crosstab/individual', {
                    taxonomy,
                    metric,
                    vertical_breakdown,
                    horizontal_breakdown,
                    location,
                    survey_id,
                })
                .done(function(data) {
                    hideLoader();
                    loadResults(data);
                })
                .catch(function(data) {
                    hideLoader();
                    alert('An error occured while fetching report data');
                });
        }

        function loadResults(results) {
            // console.log(results);

            const {
                breakdown,
                headings,
                max,
                min,
                average
            } = results;

            const aboveAverage = average + (average * .75);


            $('#reportBreakdownChart > thead').empty();
            $.each(headings, function(_, heading) {
                $("#reportBreakdownChart > thead").append(
                    `<th class="text-right">${heading == 'EMPTY' ? '' : heading}</th>`);
            });

            $('#reportBreakdownChart > tbody').empty();
            // console.log(breakdown);
            $.each(breakdown, function(question, columns) {
                // console.log(,columns.question_parent_id);
                var row;

                if ($('#vertical_breakdown').val() == 1) {

                    if (columns.question_parent_id == $('#taxonomy').val() || $('#taxonomy').val() == '') {
                        row = $('<tr class="' + columns.question_parent_id + '"></tr>');
                    } else {
                        row = $('<tr hidden class="' + columns.question_parent_id + '"></tr>');
                    }

                } else {
                    row = $('<tr class="' + columns.question_parent_id + '"></tr>');
                }



                row.append(`<td>${question}</td>`);

                $.each(headings, function(_, column) {
                    if (column != 'EMPTY') {

                        const answer = columns[column] && columns[column] >= 1 ? Math.floor(columns[
                            column]) : 0;
                        const answerToShow = columns[column] && columns[column] >= 1 ? addComma(Math.floor(
                            columns[column])) : 0;

                        const percentage = (answer / max);

                        const bg = getBackground(percentage, aboveAverage);

                        row.append(
                            `<td class="text-right border" style="color: #373a3c; background-color: ${bg}; ">${(answerToShow)}</td>`
                            );
                    }

                });

                $('#reportBreakdownChart > tbody').append(row);
            });
        }



        const supportColor = "#82BD5E"; // green 82BD5E
        const legalColor = "#367BC1"; // blue 367BC1
        const highColor = "#f6022f"; // red f6022f
        function getBackground(percentage, max) {

            // let color = supportColor; // initialize color
            // if(percentage >= max)
            //     color = highColor;
            // else
            //     color = legalColor;

            /* color_red = 130 + 125 * percentage;
            color_green = 189 - 189 * percentage;

            color_red = 450 * percentage;
            color_green = 195 - 35 * percentage;   */
            color_red = 350 * percentage;
            color_green = 150 - 35 * percentage;

            /* const bg = 'rgba(' + parseInt(color.slice(-6,-4),16)
                                + ',' + parseInt(color.slice(-4,-2),16)
                                + ',' + parseInt(color.slice(-2),16)
                                +',' + (percentage <= 0 ? 0.01 : percentage) + ')';
            
            For green -> 3 param would be 13

            */

            const bg = `rgba(${color_red}, ${color_green}, 40, 0.8)`;

            return bg;
        }

        function formatNumber(number) {
            return number.toLocaleString('en-US');
        }

        // Handle the event of excel button click
        $('#excelBtn').click(function() {
            let headers = $("#reportBreakdownChart th").map(function() {
                return this.innerHTML;
            }).get();

            let rows = $("#reportBreakdownChart tbody tr").map(function() {
                return [$("td", this).map(function() {
                    return this.innerHTML;
                }).get()];
            }).get();

            $.ajax({
                url: '{{ route('individual-crosstabreport.export-excel') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "headers": JSON.stringify(headers),
                    "rows": JSON.stringify(rows),
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#generateExcelModal').modal('show');
                },
                success: function(res) {
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

        $('#generateExcelModal').on('hidden.bs.modal', function() {
            $('#generateExcelModal').modal('hide');
            $('#generateExcelModal .modal-body').html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp; Generating Excel file...`
                );
            $('#generateExcelModal .btn').attr('href', 'javascript:void(0);');
            $('#generateExcelModal .btn').addClass('disabled');
        });

        $('#generateExcelModal .btn').click(function() {
            $('#generateExcelModal').modal('hide');
        });

        function reverseString(str) {
            return str.split("").reverse().join("");
        }


        function addComma(num) {
            const emptyStr = '';
            const group_regex = /\d{3}/g;

            // delete extra comma by regex replace.
            const trimComma = str => str.replace(/^[,]+|[,]+$/g, emptyStr)


            const str = num + emptyStr;
            const [integer, decimal] = str.split('.')

            const conversed = reverseString(integer);

            const grouped = trimComma(reverseString(
                conversed.replace(/\d{3}/g, match => `${match},`)
            ));

            return !decimal ? grouped : `${grouped}.${decimal}`;
        }
    </script>

    @include('partials.loader')
@endsection
