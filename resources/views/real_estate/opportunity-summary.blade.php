@extends('layouts.reports')
@section('content')
    <style>
        .virtual-tr {
            background-color: gold;
        }

    </style>

    <div id="HelpContent" class="modal-body" style="display:none;">
        <p><strong>Getting Started</strong></p>
        <p>When you initially click on the Real Estate tab, you will notice that you have a multitude of
            reports to look at. The information you will view may seem confusing at first, so it’s important to
            take note of several points first.</p>

        <p>To start off, you will be seeing the term <strong>‘Proximity Factor</strong>’ quite often. The proximity factor
            is required for each activity within the taxonomy, and each activity will either have a high,
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
        <img src="{{ asset('imgs/user-guide/image-044.png') }}" alt="img">
        <p>You will also see something called RSF Requirement per Participant. This is simply telling you
            how much space an individual takes up. The more important the position, the more space they
            will take up:</p>
        <img src="{{ asset('imgs/user-guide/image-045.png') }}" alt="img">


        <p><strong>Opportunity Summary</strong></p>
        <p>The Opportunity Summary is essentially a visual report of the ‘Opportunity Detail’ report. It
            provides you with a summary view of the potential savings available if activities are moved from
            their current (high) location to an available alternative.</p>

        <p>When you first enter this report, you will see the RSF Rates for each location. Please see the
            section titled ‘Location RSF Rates’ if you need clarification with what you are viewing. The
            default view is unfiltered, however, you may change this by using the set of filters at the top of
            the page. You may sort by Position, Department, Group, Location, or Proximity Factor.</p>

        <p>These filter options can be used to display results for a selected demographic. For example, if
            you use the<strong> Group filter</strong> and select “<strong>Legal Secretary</strong>”, you are easily able to
            see the potential savings if their Medium and Low activities were relocated to alternate locations.</p>

        <p>Below the filters, you will see two small charts that are color coded. The one on the left, titled
            ‘<strong>Current Cost</strong>’, tells you the current cost for all activities across the firm.<strong> The Red
                color means that the activities in this section have a higher proximity factor</strong> and are therefore
            more
            expensive.<strong> The Orange color means that activities in this section have a medium proximity
                factor</strong>, meaning that they are slightly less expensive than the activities in red. Finally,
            the<strong> Blue color means that the activities in this section have a low proximity factor</strong>, meaning
            that they
            are the cheapest.</p>

        <img src="{{ asset('imgs/user-guide/image-049.png') }}" alt="img">

        <p>The second table, on the right, is titled ‘<strong>Potential Savings</strong>’. This table shows you the
            potential savings that you will have for all activities should you move them to a lower proximity factor.
            Please note that <strong>“High” activities can not be moved</strong>. This means that they will display blank in
            the ‘Potential Savings’ columns. “Medium” activities can only be moved to a “Medium”
            location, and “Low” activities can be moved to a Medium, Low, or Low Anywhere location.</p>

        <img src="{{ asset('imgs/user-guide/image-050.png') }}" alt="img">

        <p>You will notice that these columns aren’t labeled by “High”, “Medium”, or “Low” proximity
            factors, but rather labeled by the terms “Adjacent”, “Regional”, and “Other”. These terms are
            simply another way to tell you whether or not an activity has a high proximity factor.
            <strong>A stands for Adjacent</strong>, which means that there is a <strong>medium proximity value</strong>,
            which is slightly less expensive than the higher activities. <strong>R and O stand for Regional and
                Other</strong>, which are<strong> low proximity values</strong>, meaning that they are the cheapest. If you
            need further clarification on what an RSF rate is or what it means, please see the section titled ‘Location RSF
            Rates’.
        </p>

    </div>



    <div class="container-fluid px-3 px-md-5 hideinhelppdf">
        <div class="flex justify-between items-center cont-mtitle  mb-4">
            <h1 class="text-survey">Opportunity Summary Report / {{ $survey->survey_name }}</h1>
            <div class="d-flex py-0 py-md-2">
                @if (\Auth::check() && \Auth::user()->hasPermission('surveyPrint', $survey))
                    <button class="revnor-btn mr-1" id="pdfBtn">Download PDF</button>
                @endif
                <button type="button" class="helpguidebtn mx-1" data-toggle="modal" data-target="#helpdetasurvey"></button>
            </div>
        </div>
        <div id="individualContent">
            <div class="first_part">
                @include('real_estate.partials.opportunity-summary-filter')
            </div>
            <link rel="stylesheet" href="{{ asset('css/report-additional-style.css') }}">
            <div class=" table-txtmid row second_part flex items-center justify-center"
                style="padding:20px 0;border-top:1px solid #dfdfdf;">
                <table id="locationRatesTable" class="table table-sm table-striped m-0 table-responsive">
                    <thead>
                        <tr>
                            <th class="text-right"></th>
                            <th class="text-right">Current</th>
                            <th class="text-right">Adjacent</th>
                            <th class="text-right">Regional</th>
                            <th class="text-right">Other</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['locationRates'] as $location)
                            <tr>
                                <td class="text-right"><b>{{ $location->location }}</b></td>
                                <td class="text-right">${{ number_format($location->location_Current, 2) }}</td>
                                <td class="text-right">${{ number_format($location->location_Adjacent, 2) }}</td>
                                <td class="text-right">${{ number_format($location->location_Regional, 2) }}</td>
                                <td class="text-right">${{ number_format($location->location_OTHER, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="third_part py-4" style="padding-top: 0;border-top: 3px solid #bfbfbf;">
                <div class="tableDiv table-txtmid">
                    <table id="opportunitySummaryTable" data-toggle="table">
                        <thead>
                            <tr>
                                <th class="text-center" colspan="4"><b>Current Cost</b></th>
                                <th class="border-none"></th>
                                <th class="text-center" colspan="6"><b>Potential Savings</b></th>
                            </tr>
                            <tr>
                                <th colspan="4"></th>
                                <th class="border-none"></th>
                                <th class="text-center" colspan="2">Adjacent</th>
                                <th class="text-center" colspan="2">Regional</th>
                                <th class="text-center" colspan="2">Other</th>
                            </tr>
                            <tr>
                                <th>Proximity Factor</th>
                                <th class="text-right" data-field="rsf" data-sortable="false"
                                    data-formatter="table_numberFormatter">RSF</th>
                                <th class="text-right" data-field="blended_rate" data-sortable="false"
                                    data-formatter="table_rateFormatter">Blended Rate*</th>
                                <th class="text-right" data-field="rsf_cost_current" data-sortable="false"
                                    data-formatter="table_costFormatter">RSF Cost(Current)</th>
                                <th class="border-none"></th>
                                <th class="text-right" data-field="rsf_cost_adjacent" data-sortable="false"
                                    data-formatter="table_costFormatter">(M)</th>
                                <th class="text-right" data-field="percent_adjacent" data-sortable="false"
                                    data-formatter="table_percentFormatter">%</th>
                                <th class="text-right" data-field="rsf_cost_regional" data-sortable="false"
                                    data-formatter="table_costFormatter">(L)</th>
                                <th class="text-right" data-field="percent_regional" data-sortable="false"
                                    data-formatter="table_percentFormatter">%</th>
                                <th class="text-right" data-field="rsf_cost_other" data-sortable="false"
                                    data-formatter="table_costFormatter">(LA)</th>
                                <th class="text-right" data-field="percent_other" data-sortable="false"
                                    data-formatter="table_percentFormatter">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['summaryData'] as $prox => $row)
                                    
                                @php

                                    $forExceptionLow = $row['rsf_cost_other'];
                                    
                                    if($prox == 'low'){
                                        // echo "TEST";
                                        $row['rsf_cost_other'] = 0; 
                                    }else{
                                        $row['rsf_cost_other'] =  $forExceptionLow;
                                    }
                                    
                                    $blended_rate = $row['rsf'] > 0 ? $row['rsf_cost_current'] / $row['rsf'] : 0;
                                    $adjacent_percent = $row['rsf_cost_adjacent'] > 0 ? (100 * ($row['rsf_cost_current'] - $row['rsf_cost_adjacent'])) / $row['rsf_cost_current'] : 0;
                                    $regional_percent = $row['rsf_cost_regional'] > 0 ? (100 * ($row['rsf_cost_current'] - $row['rsf_cost_regional'])) / $row['rsf_cost_current'] : 0;
                                    $other_percent = $row['rsf_cost_other'] > 0 ? (100 * ($row['rsf_cost_current'] - $row['rsf_cost_other'])) / $row['rsf_cost_current'] : 0;
                                   

                                    
                                @endphp
                                <tr>
                                    <td><b>{{ ucfirst($prox) }}</b></td>
                                    <td class="text-right {{ $prox }}-tr">{{ $row['rsf'] }}</td>
                                    <td class="text-right {{ $prox }}-tr">{{ round($blended_rate, 2) }}</td>
                                    <td class="text-right {{ $prox }}-tr">{{ $row['rsf_cost_current'] }}</td>
                                    <td style="border: none;"></td>
                                    <td class="text-right {{ $prox }}-tr">
                                        {{ $row['rsf_cost_adjacent'] > 0 ? $row['rsf_cost_current'] - $row['rsf_cost_adjacent'] : 0 }}
                                    </td>
                                    <td class="text-right {{ $prox }}-tr">{{ round($adjacent_percent, 2) }}</td>
                                    <td class="text-right {{ $prox }}-tr">
                                        {{ $row['rsf_cost_regional'] > 0 ? $row['rsf_cost_current'] - $row['rsf_cost_regional'] : 0 }}
                                    </td>
                                    <td class="text-right {{ $prox }}-tr">{{ round($regional_percent, 2) }}</td>
                                    <td class="text-right {{ $prox }}-tr"> {{ $row['rsf_cost_other'] > 0 ? $row['rsf_cost_current'] - $row['rsf_cost_other'] : 0 }}
                                    </td>
                                    
                                    <td class="text-right {{ $prox }}-tr">{{ round($other_percent, 2) }}</td>
                                </tr>
                                
                            @endforeach
                            <tr>

                            </tr>

                        </tbody>
                    </table>
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

        <div id="headerDiv" class="pdfheaderdiv">
            <p class="text-phead">Opportunity Summary Report / {{ $survey->survey_name }}</p>
            <p class="redtext-phead">Confidential</p>
            {{-- <img  src="{{asset('imgs/logo-pdfhead.png')}}"> --}}
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
                        <p><strong>Getting Started</strong></p>
                        <p>When you initially click on the Real Estate tab, you will notice that you have a multitude of
                            reports to look at. The information you will view may seem confusing at first, so it’s important
                            to
                            take note of several points first.</p>

                        <p>To start off, you will be seeing the term <strong>‘Proximity Factor</strong>’ quite often. The
                            proximity factor is required for each activity within the taxonomy, and each activity will
                            either have a high,
                            medium, or low proximity factor. This will determine how costly an activity will be:</p>

                        <p><strong>High: Must be near customer</strong></p>
                        <p>Service provider must have regular personal interaction with the customer or the service provider
                            must have access to physical files etc. that are maintained by the customer. Proximity
                            requirement demands that they reside within the same floor of the same building.</p>

                        <p><strong>Medium: Needs to be relatively close to customer</strong></p>
                        <p>Periodic personal interactions are required for effective delivery of services. Service provider
                            does not need to be in the same space as customer but needs to be in close proximity so periodic
                            personal meetings can occur without requiring travel (so in the same building on a different
                            floor,
                            or same city in a different building).</p>

                        <p><strong>Low: Does not need to be near customer</strong></p>
                        <p>Interfacing with customers using technology (phone, email, etc.) is sufficient. Collaboration
                            tools, web conferencing, and shared access to file systems are adequate to facilitate effective
                            interactions. Service providers can be located anywhere and can work virtually.</p>

                        <p>Another term that you will see is RSF Rates. This is the cost of the square footage that an
                            individual takes up. RSF Rates are required for each location. C stands for Current, which is
                            also
                            a high proximity value. This is the most expensive RSF Rate. A stands for Adjacent, which
                            means that there is a medium proximity value, which is slightly less expensive. R and O stand
                            for Regional and Other, which are low proximity values, meaning that they are the cheapest.</p>
                        <img src="{{ asset('imgs/user-guide/image-044.png') }}" alt="img">
                        <p>You will also see something called RSF Requirement per Participant. This is simply telling you
                            how much space an individual takes up. The more important the position, the more space they
                            will take up:</p>
                        <img src="{{ asset('imgs/user-guide/image-045.png') }}" alt="img">


                        <p><strong>Opportunity Summary</strong></p>
                        <p>The Opportunity Summary is essentially a visual report of the ‘Opportunity Detail’ report. It
                            provides you with a summary view of the potential savings available if activities are moved from
                            their current (high) location to an available alternative.</p>

                        <p>When you first enter this report, you will see the RSF Rates for each location. Please see the
                            section titled ‘Location RSF Rates’ if you need clarification with what you are viewing. The
                            default view is unfiltered, however, you may change this by using the set of filters at the top
                            of
                            the page. You may sort by Position, Department, Group, Location, or Proximity Factor.</p>

                        <p>These filter options can be used to display results for a selected demographic. For example, if
                            you use the<strong> Group filter</strong> and select “<strong>Legal Secretary</strong>”, you are
                            easily able to see the potential savings if their Medium and Low activities were relocated to
                            alternate locations.</p>

                        <p>Below the filters, you will see two small charts that are color coded. The one on the left,
                            titled
                            ‘<strong>Current Cost</strong>’, tells you the current cost for all activities across the
                            firm.<strong> The Red color means that the activities in this section have a higher proximity
                                factor</strong> and are therefore more
                            expensive.<strong> The Orange color means that activities in this section have a medium
                                proximity
                                factor</strong>, meaning that they are slightly less expensive than the activities in red.
                            Finally, the<strong> Blue color means that the activities in this section have a low proximity
                                factor</strong>, meaning that they
                            are the cheapest.</p>

                        <img src="{{ asset('imgs/user-guide/image-049.png') }}" alt="img">
                        <p>The second table, on the right, is titled ‘<strong>Potential Savings</strong>’. This table shows
                            you the potential savings that you will have for all activities should you move them to a lower
                            proximity factor.
                            Please note that <strong>“High” activities can not be moved</strong>. This means that they will
                            display blank in
                            the ‘Potential Savings’ columns. “Medium” activities can only be moved to a “Medium”
                            location, and “Low” activities can be moved to a Medium, Low, or Low Anywhere location.</p>

                        <img src="{{ asset('imgs/user-guide/image-050.png') }}" alt="img">

                        <p>You will notice that these columns aren’t labeled by “High”, “Medium”, or “Low” proximity
                            factors, but rather labeled by the terms “Adjacent”, “Regional”, and “Other”. These terms are
                            simply another way to tell you whether or not an activity has a high proximity factor.
                            <strong>A stands for Adjacent</strong>, which means that there is a <strong>medium proximity
                                value</strong>, which is slightly less expensive than the higher activities. <strong>R and O
                                stand for Regional and Other</strong>, which are<strong> low proximity values</strong>,
                            meaning that they are the cheapest. If you need further clarification on what an RSF rate is or
                            what it means, please see the section titled ‘Location RSF Rates’.
                        </p>
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
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp;
                        Generating PDF...
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
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp;
                        Generating Excel file ...
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
            /*  }else{ 
                 $('#selectRespModal').modal();

             } */


        });



        var survey_id = @php echo $data['survey'] -> survey_id; @endphp;
        var respData = @php echo $data['resps']; @endphp;

        var imgData_1, imgData_2, imgData_3, imgData_4, copyrightData, headerData;

        let formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
            maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
        });

        let numberFormatter = new Intl.NumberFormat('en-US');

        // Handle the pdf button click, generate image data from the body
        $('#pdfBtn').click(function() {
            $('#headerDiv').show();
			$('#copyright_div').addClass('d-flex');
			$('#copyright_div').show();	 
            $('#generatePDFModal').modal('show');
            source = $('#individualContent .first_part');
            html2canvas(source, {
                scale: 3
            }).then(function(canvas) {

                imgData_1 = canvas.toDataURL("image/png", 1.0);

            });
            source = $('#individualContent .second_part');
            html2canvas(source, {
                scale: 3
            }).then(function(canvas) {

                imgData_2 = canvas.toDataURL("image/png", 1.0);

            });
            // Copyright
            source = $('#copyright_div');
            html2canvas(source, {
                scale: 3
            }).then(function(canvas) {

                copyrightData = canvas.toDataURL("image/png", 1.0);

            });
            source = $('#headerDiv');
            html2canvas(source, {
                scale: 3
            }).then(function(canvas) {

                headerData = canvas.toDataURL("image/png", 1.0);

            });
            source = $('#individualContent .third_part');
            html2canvas(source, {
                onrendered: function(canvas) {
                    imgData_3 = canvas.toDataURL('image/jpeg', 1.0);
                }
            }).then(function() {
                $('#generatePDFModal .modal-body').html('Generated a PDF');
                $('#generatePDFModal .btn').attr('disabled', false);
                $('#headerDiv').hide();
					$('#copyright_div').removeClass('d-flex');
			        $('#copyright_div').hide(); 				
            });
        });

        $('#rsf_cost_sort').click(function() {
            val = $(this).val();
            $('.text-Adjacent').hide();
            $('.text-Regional').hide();
            $('.text-OTHER').hide();
            $(`.text-${val}`).show();
        });

        /**
         * Generate pdf document of report
         *
         * @return {void}
         */
        function generatePDF() {
            let imgWidth = $('#individualContent .first_part').outerWidth();
            pdfdoc = new jsPDF('p', 'mm', 'a4');
            imgHeight1 = Math.round($('#individualContent .first_part').outerHeight() * 190 / imgWidth);
            y = 14;
            position = y;
            doc_page = 1;

            /*  pdfdoc.addImage(imgData_1, 'JPEG', 10, y, 190, imgHeight1);
             y += imgHeight1; */

            imgHeight2 = Math.round($('#individualContent .second_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_2, 'JPEG', 10, y, 190, imgHeight2);
            y += imgHeight2;

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
                pdfdoc.setTextColor(111, 107, 107);
                pdfdoc.setFontSize(8);
                pdfdoc.text('Page ' + i + ' of ' + (doc_page - 1), 168, 290, 0, 45);
            }

            pdfdoc.save(`Opportunity Summary Report({{ $data['survey']->survey_name }})`);
            $('#pdfBtn').html('Download PDF');
            $('#pdfBtn').prop('disabled', false);
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .btn').attr('disabled', true);
        }

        function table_numberFormatter(value) {
            return numberFormatter.format(Math.round(value));
        }

        function table_rateFormatter(value) {
            return '$' + value;
        }

        function table_costFormatter(value) {
            if (value == 0) {
                return '';
            }
            return '$' + numberFormatter.format(Math.round(value));
        }

        function table_percentFormatter(value) {
            if (value == 0) {
                return '';
            }
            return value + '%';
        }
    </script>
@endsection
