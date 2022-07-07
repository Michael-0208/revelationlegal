@extends('layouts.reports')
@section('content')

<style>
    .vitual-bar{
        background-color: gold;
        border: 1px solid #000;

    }


</style>

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


<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<div class="container-fluid px-3 hideinhelppdf"> 
    <div class="flex justify-between items-center cont-mtitle  mb-4">
        <h1 class="text-survey w-100">Participant Proximity Report / {{ $survey->survey_name }}
        </h1>
        <div class="d-flex py-0 py-md-2"> 
            @if (\Auth::check() && \Auth::user()->hasPermission('surveyExport', $survey))                
                <button class="revnor-btn mr-1" id="pdfBtn">Download PDF</button>                
            @endif
            <button type="button" class="helpguidebtn mx-1" data-toggle="modal" data-target="#helpdetasurvey"></button> 
        </div>   
    </div>
    <div id="participantProximityContent">
        <div class="first_part" style="background: white;" id="pdfhidden">
            @include('real_estate.partials.participantfilter')
          
        </div>
        <div class="second_part  pt-4" style="background: white;">
            <h5>Hours by Proximity Factor</h5>
            <div id="proximity_bar" class="flex w-full">
                <div class="high-bar text-center" style="width: {{ $data['rsf_percent_data']['high_percent'] }}%;">
                    <div class="title font-bold">High</div>
                    <div class="value">{{ number_format($data['rsf_percent_data']['high_hours']) }}</div>
                </div>
                <div class="med-bar text-center" style="width: {{ $data['rsf_percent_data']['med_percent'] }}%;">
                    <div class="title font-bold">Med</div>
                    <div class="value">{{ number_format($data['rsf_percent_data']['med_hours']) }}</div>
                </div>
                <div class="low-bar text-center" style="width: {{ $data['rsf_percent_data']['low_percent'] }}%;">
                    <div class="title font-bold">Low</div>
                    <div class="value">{{ number_format($data['rsf_percent_data']['low_hours']) }}</div>
                </div>
                <div class="vitual-bar text-center text-white" style="width: 10%; border: 1px solid black;">
                    <div class="title font-bold">Virtual</div>
                    <div class="value">{{ number_format($data['rsf_percent_data']['virtual_hours']) }}</div>
                </div>
            </div>
        </div>
        <div class="third_part" style="background: white;">
            <div class="toolbar">
                @if (\Auth::check() && \Auth::user()->hasPermission('surveyExport', $survey))
                    {{-- <button class="btn btn-revelation-primary" id="excelBtn">Export to Excel</button> --}}
                @endif
            </div>
            <!--   -->
            <div class=" table-txtmid">
                <table data-search="true" data-search-highlight="true" data-toggle="table" data-button-class="btn-revelation-primary" data-toolbar=".toolbar"
                    data-pagination="true" data-toolbar-align="right" id="respTable" class="table table-striped table-sm text-xs w-full table-bordered table-hover"
                    cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="th-sm" data-sortable="true" data-field="employee_id" data-width="50"
                                data-halign="center">Emp ID</th>
                            <th class="th-sm" data-sortable="true" data-field="name" data-halign="center">Full Name
                            </th>
                            <th class="th-sm" data-sortable="true" data-field="position" data-halign="center">
                                Position</th>
                            <th class="th-sm text-right" data-sortable="true" data-field="rsf_cost"
                                data-formatter="table_costFormatter" data-halign="center">Total RSF Cost</th>
                            <th class="th-sm text-right" data-sortable="true" data-field="total_hours"
                                data-formatter="table_numberFormatter" data-halign="center">Total Hours</th>
                            <th class="th-sm pl-0" data-sortable="true" data-field="high_hours"
                                data-formatter="table_highFormatter" data-width="150" data-halign="center">High</th>
                            <th class="th-sm pl-0" data-sortable="true" data-field="med_hours"
                                data-formatter="table_medFormatter" data-width="150" data-halign="center">Med</th>
                            <th class="th-sm pl-0" data-sortable="true" data-field="low_hours"
                                data-formatter="table_lowFormatter" data-width="150" data-halign="center">Low</th>
                            <th class="th-sm pl-0" data-sortable="true" data-field="virtual_hours"
                                data-formatter="table_virtualFormatter" data-width="150" data-halign="center">Virtual</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['resps'] as $resp)
                            <tr>
                                <td>{{ $resp->cust_1 }}</td>
                                <td>{{ $resp->resp_last }}, {{ $resp->resp_first }}</td>
                                <td>{{ $resp->cust_3 }}</td>
                                <td>{{ $resp->rsf_cost }}</td>
                                <td>{{ $resp->total_hours }}</td>
                                <td>{{ $resp->prox_high_hours }}</td>
                                <td>{{ $resp->prox_medium_hours }}</td>
                                <td>{{ $resp->prox_low_hours }}</td>
                                <td>{{ $resp->prox_virtual_hours }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
 
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
            <p class="text-phead">Participant Proximity Report / {{ $survey->survey_name }}</p> 
            <p class="redtext-phead">Confidential</p>
            {{-- <img  src="{{asset('imgs/logo-pdfhead.png')}}">   --}}
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

        let numberFormatter = new Intl.NumberFormat('en-US');

        $(document).ready(function() {

            // $('#respTable').DataTable({
            //     searching: true,
            //     "bLengthChange": true,
            //     'pageLength': 25,
            //     'lengthMenu': [[25, 50, 100], [25, 50, 100]],
            //     dom: 'Bfrtip',
            //     buttons: [
            //         {
            //             extend:'pdfHtml5',
            //             orientation : 'landscape',
            //             pageSize : 'LEGAL',
            //         }
                    
            //     ],
            //     "fnDrawCallback": function(oSettings) {
            //         if ($('#respTable tr').length < 25) {
            //             $('#respTable_info').hide();
            //             $('#respTable_paginate').hide();
            //             $('#respTable_length').hide();
            //         }
            //     }
            // });
        });

        // Handle the event of excel button click
        $('#excelBtn').click(function() {
            let tableData = $('#respTable').DataTable('getData');
            $.ajax({
                url: '{{ route("realestate.exportParticipantExcel") }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "tableData": JSON.stringify(tableData)
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

        // Handle the pdf button click, generate image data from the body
        $('#pdfBtn').click(function() {
            $('.fixed-table-toolbar').hide();
            $('.fixed-table-pagination').hide(); 
            $('#generatePDFModal').modal('show');
            $('#headerDiv').show();
            $('#copyright_div').addClass('d-flex');
			$('#copyright_div').show();	 			
            source = $('#participantProximityContent .first_part');
            html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    imgData_1 = canvas.toDataURL("image/jpeg", 1.0);
                        
                });
            source = $('#participantProximityContent .second_part');
            html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    imgData_2 = canvas.toDataURL("image/jpeg", 1.0);
                        
                });
            // Copyright
            source = $('#copyright_div');
            html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    copyrightData = canvas.toDataURL("image/jpeg", 1.0);
                        
                });
            source = $('#headerDiv');
            html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    headerData = canvas.toDataURL("image/jpeg", 1.0);
                        
                });
            source = $('#participantProximityContent .third_part');
            html2canvas(source, {
                onrendered: function(canvas) {
                    imgData_3 = canvas.toDataURL('image/jpeg', 1.0);
                }
            }).then(function() {
                $('#generatePDFModal .modal-body').html('Generated a PDF');
                $('#generatePDFModal .btn').attr('disabled', false);
                $('#headerDiv').hide();
                $('.fixed-table-toolbar').show();
                $('.fixed-table-pagination').show();
					$('#copyright_div').removeClass('d-flex');
			        $('#copyright_div').hide();	 				
            });
        });

        $('#generatePDFModal').on('hidden.bs.modal', function() {
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .modal-body').html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> &nbsp;&nbsp; Generating PDF...`
            );
            $('#generatePDFModal .btn').attr('disabled', true);
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

        /**
         * Generate pdf document of report
         *
         * @return {void}
         */
        function generatePDF() {
            let imgWidth = $('#participantProximityContent .first_part').outerWidth();
            pdfdoc = new jsPDF('p', 'mm', 'a4');
            imgHeight1 = Math.round($('#participantProximityContent .first_part').outerHeight() * 190 / imgWidth);
            y = 13;
            position = y; 
            doc_page = 1;

            // pdfdoc.addImage(imgData_1, 'JPEG', 10, y, 190, imgHeight1);
            // y += imgHeight1;

           /*  imgHeight2 = Math.round($('#participantProximityContent .second_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_2, 'JPEG', 10, y, 190, imgHeight2);
            y += imgHeight2; */ 

            imgHeight3 = Math.round($('#participantProximityContent .third_part').outerHeight() * 170 / imgWidth);
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
                pdfdoc.addImage(headerData, 'JPEG', 10, 0, 190, 13);  
                pdfdoc.addImage(copyrightData, 'JPEG', 10, 284, 190, 13); 
                // pdfdoc.text('Page ' + i + ' of ' + (doc_page-1) , 8, 275, 90.916667, 52.916667, null, null, 45);
                pdfdoc.setTextColor(111,107,107); 
                pdfdoc.setFontSize(8); 
                pdfdoc.text('Page ' + i + ' of ' + (doc_page-1) , 172, 290, 0, 45);
            } 

            pdfdoc.save(`Participant Proximity Report({{ $data['survey']->survey_name }})`);
            $('#pdfBtn').html('Download PDF');
            $('#pdfBtn').prop('disabled', false);
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .btn').attr('disabled', true);
        }

        function searchRespName(data, text) {
            return data.filter(function(row) {
                return row.name.indexOf(text) > -1;
            });
        }

        function table_numberFormatter(value) {
            return numberFormatter.format(value);
        }

        function table_costFormatter(value) {
            return '$' + numberFormatter.format(value);
        }

        function table_highFormatter(value, row) {
            if (value > 0) {
                let width = Math.round(50 * value / max_hours);
                let show = numberFormatter.format(Math.round(value));
                let html = `<div class="flex items-center">
                        <div class="high-bar" style="width: ${width}%;height:15px;margin-right:1px;"></div>
                        <div>${show}</div>
                    </div>`;
                return html;
            } else {
                return '';
            }
        }

        function table_medFormatter(value, row) {
            if (value > 0) {
                let width = Math.round(50 * value / max_hours);
                let show = numberFormatter.format(Math.round(value));
                let html = `<div class="flex items-center">
                        <div class="med-bar" style="width: ${width}%;height:15px;margin-right:1px;"></div>
                        <div>${show}</div>
                    </div>`;
                return html;
            } else {
                return '';
            }
        }

        function table_lowFormatter(value, row) {
            if (value > 0) {
                let width = Math.round(50 * value / max_hours);
                let show = numberFormatter.format(Math.round(value));
                let html = `<div class="flex items-center">
                        <div class="low-bar" style="width: ${width}%;height:15px;margin-right:1px;"></div>
                        <div>${show}</div>
                    </div>`;
                return html;
            } else {
                return '';
            }
        }

        function table_virtualFormatter(value, row) {
            if (value > 0) {
                let width = Math.round(50 * value / max_hours);
                let show = numberFormatter.format(Math.round(value));
                let html = `<div class="flex items-center">
                        <div class="virtual-bar" style="width: 5%;height:15px;margin-right:1px; background-color:gold;"></div>
                        <div>${show}</div>
                    </div>`;
                return html;
            } else {
                return '';
            }
        }
    </script>

@endsection
