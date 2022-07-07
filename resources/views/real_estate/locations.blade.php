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


<p><strong>Location RSF Rates</strong></p>
<p>The first section within the Real Estate window is called Location RSF Rates. This just simply
shows you the locations that the activities take place in, and it tells you their proximity rate. As
mentioned above,<strong> C stands for Current</strong>, which is also a<strong> high proximity value</strong>. This is the most expensive RSF Rate. <strong>A stands for Adjacent</strong>, which means that there is a <strong>medium proximity value</strong>, which is slightly less expensive. <strong>R and O stand for Regional and Other</strong>, which are <strong>low proximity values</strong>, meaning that they are the cheapest.</p>
<img src="{{asset('imgs/user-guide/image-046.png')}}" alt="img">
<p>You will see that each proximity factor rate here has a number listed. This is just telling you that
in each location, there is an activity in a high factor, medium factor, and low factor. The numbers
you are seeing are the rates for those activities.</p>

      </div> 



<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> 
<script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<div class="container-fluid px-3  hideinhelppdf"> 
<div class="flex justify-between items-center cont-mtitle  mb-4">
        <h1 class="text-survey">Location RSF Rates / {{ $survey->survey_name }}</h1>
        <div class="d-flex py-0 py-md-2">
        @if (\Auth::check() && \Auth::user()->hasPermission('surveyPrint', $survey))
            <button class="revnor-btn mr-1" id="pdfBtn">Download PDF</button>              
        @endif
        <button type="button" class="helpguidebtn mx-1" data-toggle="modal" data-target="#helpdetasurvey"></button>
    </div>
    </div>   
    <div class=" py-2">
        <button id="addLocationBtn" class="revnor-btn flex items-center justify-end mb-2"><svg
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                style="vertical-align: -0.125em;display: inline;" width="1em" height="1em"
                preserveAspectRatio="xMidYMid meet" viewBox="0 0 16 16">
                <g fill="currentColor">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z" />
                </g>
            </svg> New Location</button>
        <table class="table table-txtmid realtable-responsive" id="location_table"> 
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Location Name</th>
                    <th>Current</th>
                    <th>Adjacent</th>
                    <th>Regional</th>
                    <th>Other</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['locations'] as $location)
                    <tr id="row-{{ $location->location_id }}">
                        <td>
                            <button data-id="{{ $location->location_id }}"
                                class="table-smallbtn edit-btn btn-revelation-primary"><svg xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                    style="vertical-align: -0.125em;" width="1em" height="1em"
                                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                    <path d="M18.988 2.012l3 3L19.701 7.3l-3-3zM8 16h3l7.287-7.287l-3-3L8 13z"
                                        fill="currentColor" />
                                    <path
                                        d="M19 19H8.158c-.026 0-.053.01-.079.01c-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .896-2 2v14c0 1.104.897 2 2 2h14a2 2 0 0 0 2-2v-8.668l-2 2V19z"
                                        fill="currentColor" />
                                </svg></button>
                            <button data-id="{{ $location->location_id }}"
                                class="table-smallbtn remove-btn btn-revelation-danger"><svg xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                    style="vertical-align: -0.125em;" width="1em" height="1em"
                                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20">
                                    <path
                                        d="M12 4h3c.6 0 1 .4 1 1v1H3V5c0-.6.5-1 1-1h3c.2-1.1 1.3-2 2.5-2s2.3.9 2.5 2zM8 4h3c-.2-.6-.9-1-1.5-1S8.2 3.4 8 4zM4 7h11l-.9 10.1c0 .5-.5.9-1 .9H5.9c-.5 0-.9-.4-1-.9L4 7z"
                                        fill="currentColor" />
                                </svg></button>
                        </td>
                        <td>{{ $location->location }}</td>
                        <td>{{ ($location->location_Current == 0)?'':number_format((float)$location->location_Current, 2) }}</td>
                        <td>{{ ($location->location_Adjacent == 0)?'':number_format((float)$location->location_Adjacent,2) }}</td>
                        <td>{{ ($location->location_Regional == 0)?'':number_format((float)$location->location_Regional,2) }}</td>
                        <td>{{ ($location->location_OTHER == 0)?'':number_format((float)$location->location_OTHER,2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
    <p class="text-phead">Location RSF Rates / {{ $survey->survey_name }}</p> 
    <p class="redtext-phead">Confidential</p>
    {{-- <img  src="{{asset('imgs/logo-pdfhead.png')}}">   --}}
</div>
    </div>

    <div id="editLocationModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong>Add New Location</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="location_id">
                    <div class="form-group">
                        <label for="addpage_title">Location Name</label>
                        <input type="text" class="form-control" id="location" placeholder="Location Name" required>
                    </div>
                    <div class="form-group">
                        <label for="addpage_title">Current Rate</label>
                        <input type="number" class="form-control" id="location_Current" step="0.01"
                            placeholder="Current Rate" required>
                    </div>
                    <div class="form-group">
                        <label for="addpage_title">Adjacent Rate</label>
                        <input type="number" class="form-control" id="location_Adjacent" step="0.01"
                            placeholder="Adjacent Rate" required>
                    </div>
                    <div class="form-group">
                        <label for="addpage_title">Regional Rate</label>
                        <input type="number" class="form-control" id="location_Regional" step="0.01"
                            placeholder="Regional Rate" required>
                    </div>
                    <div class="form-group">
                        <label for="addpage_title">Other Rate</label>
                        <input type="number" class="form-control" id="location_OTHER" step="0.01" placeholder="Other Rate"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-revelation-primary" id="saveLocation">Save Changes</button>
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


<p><strong>Location RSF Rates</strong></p>
<p>The first section within the Real Estate window is called Location RSF Rates. This just simply
shows you the locations that the activities take place in, and it tells you their proximity rate. As
mentioned above,<strong> C stands for Current</strong>, which is also a<strong> high proximity value</strong>. This is the most expensive RSF Rate. <strong>A stands for Adjacent</strong>, which means that there is a <strong>medium proximity value</strong>, which is slightly less expensive. <strong>R and O stand for Regional and Other</strong>, which are <strong>low proximity values</strong>, meaning that they are the cheapest.</p>
<img src="{{asset('imgs/user-guide/image-046.png')}}" alt="img">
<p>You will see that each proximity factor rate here has a number listed. This is just telling you that
in each location, there is an activity in a high factor, medium factor, and low factor. The numbers
you are seeing are the rates for those activities.</p>
 
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



        var imgData_1, copyrightData, headerData;
        $(document).ready(() => {
            $('#location_table').DataTable({
                "bLengthChange": true,
                'pageLength': 25,
                'lengthMenu': [[25, 50, 100], [25, 50, 100]],
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5'
                ],
                "fnDrawCallback": function(oSettings) {
                    if ($('#location_table tr').length < 25) {
                        $('#location_table_info').hide();
                        $('#location_table_paginate').hide();
                        $('#location_table_length').hide();
                    }
                }
            });

            $('input[name="table_survey_users_length"]').css('width','3.5rem');

            $('#pdfBtn').on('click',function(){
                $('#headerDiv').show();
            $('#copyright_div').addClass('d-flex');
			$('#copyright_div').show();	 				
                $('#generatePDFModal').modal('show');

                source = $('#location_table');
                html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    imgData_1 = canvas.toDataURL("image/png", 1.0);
                        
                });
                // Copyright
                source = $('#copyright_div');
                html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    copyrightData = canvas.toDataURL("image/png", 1.0);
                        
                });
                source = $('.pdfheaderdiv'); 
                html2canvas(source, {
                    scale:3
                    }).then(function(canvas) {
                        
                    headerData = canvas.toDataURL("image/png", 1.0); 
                        
                }).then(function () {
                    $('#generatePDFModal .modal-body').html(`<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="24" height="24" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path d="M30 18v-2h-6v10h2v-4h3v-2h-3v-2h4z" fill="currentColor"/><path d="M19 26h-4V16h4a3.003 3.003 0 0 1 3 3v4a3.003 3.003 0 0 1-3 3zm-2-2h2a1.001 1.001 0 0 0 1-1v-4a1.001 1.001 0 0 0-1-1h-2z" fill="currentColor"/><path d="M11 16H6v10h2v-3h3a2.003 2.003 0 0 0 2-2v-3a2.002 2.002 0 0 0-2-2zm-3 5v-3h3l.001 3z" fill="currentColor"/><path d="M22 14v-4a.91.91 0 0 0-.3-.7l-7-7A.909.909 0 0 0 14 2H4a2.006 2.006 0 0 0-2 2v24a2 2 0 0 0 2 2h16v-2H4V4h8v6a2.006 2.006 0 0 0 2 2h6v2zm-8-4V4.4l5.6 5.6z" fill="currentColor"/></svg> &nbsp; Generated a PDF`);
                    $('#generatePDFModal .btn').attr('disabled', false);
                    $('.pdfheaderdiv').hide();
					$('#copyright_div').removeClass('d-flex');
			        $('#copyright_div').hide();		
				    $('#headerDiv').hide(); 
                });
            })

        })


        function generatePDF(){
            let imgWidth = $('#location_table').outerWidth();
            pdfdoc = new jsPDF('p', 'mm', 'a4');
            imgHeight1 = Math.round($('#location_table').outerHeight() * 190 / imgWidth);
            y = 16;  
            position = y;  
            doc_page = 1;

            pdfdoc.addImage(imgData_1, 'JPEG', 10, y, 190, imgHeight1);
            y += imgHeight1;

           /*  imgHeight2 = Math.round($('#individualContent .second_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_2, 'JPEG', 10, y, 190, imgHeight2); 
            y += imgHeight2;

            imgHeight4 = Math.round($('#individualContent .fourth_part').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_4, 'JPEG', 10, y, 190, imgHeight4);
            y += imgHeight4;
            /* imgHeight5 = Math.round($('#copyright_div').outerHeight() * 190 / imgWidth);
            pdfdoc.addImage(imgData_4, 'JPEG', 10, y, 190, imgHeight5);
            y += imgHeight5; 

            imgHeight3 = Math.round($('#individualContent .third_part').outerHeight() * 190 / imgWidth);

            pdfdoc.addPage();
            doc_page++;
            pdfdoc.addImage(imgData_3, 'JPEG', 10, 15, 190, imgHeight3); */

            pageHeight = pdfdoc.internal.pageSize.height - 20;
            heightLeft = y - pageHeight;

            while (heightLeft >= -pageHeight) {
                position = heightLeft - imgHeight1;
                pdfdoc.addPage();
                doc_page++;
                pdfdoc.addImage(imgData_1, 'JPEG', 10, position, 190, imgHeight1);
                heightLeft -= pageHeight;
            }

            pdfdoc.deletePage(doc_page); 

            for (i = 1; i < doc_page; i++) {
                pdfdoc.setPage(i);

                pdfdoc.addImage(headerData, 'JPEG', 10, 0, 190, 14); 
                pdfdoc.addImage(copyrightData, 'JPEG', 10, 280, 190, 14);
                pdfdoc.setTextColor(111,107,107); 
                pdfdoc.setFontSize(8); 
                pdfdoc.text('Page ' + i + ' of ' + (doc_page-1) , 168, 285, 0, 45);
            }

            pdfdoc.save(`{{$data['survey']->survey_name}}`);
            $('#generatePDFModal').modal('hide');
            $('#generatePDFModal .btn').attr('disabled', true);
        }

        var survey_id = {{ $data['survey']->survey_id }};
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
        });

        function init() {
            $('#addLocationBtn').click(function() {
                $('#editLocationModal input').val('');
                $('#editLocationModal').modal('show');
                $('#editLocationModal .modal-header strong').html('Add New Location');
            });

            $('.edit-btn').click(function() {
                $('#editLocationModal input').val('');
                let location_id = $(this).attr('data-id');
                $('#location_id').val(location_id);
                $('#location').val($(`#row-${location_id} td:eq(1)`).text());
                $('#location_Current').val($(`#row-${location_id} td:eq(2)`).text());
                $('#location_Adjacent').val($(`#row-${location_id} td:eq(3)`).text());
                $('#location_Regional').val($(`#row-${location_id} td:eq(4)`).text());
                $('#location_OTHER').val($(`#row-${location_id} td:eq(5)`).text());
                $('#editLocationModal').modal('show');
                $('#editLocationModal .modal-header strong').html('Edit Location');
            });

            $('.remove-btn').click(function() {
                let location_id = $(this).attr('data-id');
                removeLocation(location_id);
            });

            $('#saveLocation').click(function() {
                let location_id = $('#location_id').val();
                if (location_id != '') {
                    updateLocation();
                } else {
                    createLocation();
                }
            });
        }

        $(document).ready(function() {
            init();
        });

        function createLocation() {
            $.ajax({
                url: '{{ route("real-estate.create-location") }}',
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'survey_id': survey_id,
                    'location': $('#location').val(),
                    'location_Current': $('#location_Current').val(),
                    'location_Adjacent': $('#location_Adjacent').val(),
                    'location_Regional': $('#location_Regional').val(),
                    'location_OTHER': $('#location_OTHER').val(),
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status == 200) {
                        $('#location_table tbody').append(`
                            <tr id="row-${res.new.location_id}">
                                <td>
                                    <button data-id="${res.new.location_id}" class="btn btn-sm edit-btn btn-revelation-primary"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path d="M18.988 2.012l3 3L19.701 7.3l-3-3zM8 16h3l7.287-7.287l-3-3L8 13z" fill="currentColor"/><path d="M19 19H8.158c-.026 0-.053.01-.079.01c-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .896-2 2v14c0 1.104.897 2 2 2h14a2 2 0 0 0 2-2v-8.668l-2 2V19z" fill="currentColor"/></svg></button>
                                    <button data-id="${res.new.location_id}" class="btn btn-sm remove-btn btn-revelation-danger"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><path d="M12 4h3c.6 0 1 .4 1 1v1H3V5c0-.6.5-1 1-1h3c.2-1.1 1.3-2 2.5-2s2.3.9 2.5 2zM8 4h3c-.2-.6-.9-1-1.5-1S8.2 3.4 8 4zM4 7h11l-.9 10.1c0 .5-.5.9-1 .9H5.9c-.5 0-.9-.4-1-.9L4 7z" fill="currentColor"/></svg></button>
                                </td>
                                <td>${res.new.location}</td>
                                <td>${res.new.location_Current}</td>
                                <td>${res.new.location_Adjacent}</td>
                                <td>${res.new.location_Regional}</td>
                                <td>${res.new.location_OTHER}</td>
                            </tr>`);
                        $('#editLocationModal').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: 'A new location created successfully.'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'An error has been occured while creating.'
                        });
                    }
                    init();
                },
                error: function(response) {
                    let errors = response.responseJSON.errors;

                    errorHtml = '<ul style="list-style:unset;">';
                    for (const i in errors) {
                        errorHtml += `<li>${errors[i]}</li>`;
                    }
                    errorHtml += '</ul>';

                    if (response.responseJSON.message.indexOf('1062') > -1) {
                        title = 'Duplication Error';
                        errorHtml = 'The same location already exists';
                    } else {
                        title = response.responseJSON.message
                    }
                    Toast.fire({
                        icon: 'error',
                        title: title,
                        html: errorHtml,
                        customClass: {
                            icon: 'toast-icon'
                        }
                    })
                }
            });
        }

        function updateLocation() {
            $.ajax({
                url: '{{ route("real-estate.update-location") }}',
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'location_id': $('#location_id').val(),
                    'location': $('#location').val(),
                    'location_Current': $('#location_Current').val(),
                    'location_Adjacent': $('#location_Adjacent').val(),
                    'location_Regional': $('#location_Regional').val(),
                    'location_OTHER': $('#location_OTHER').val(),
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status == 200) {
                        $(`#row-${res.updated.location_id}`).html(`
                            <td>
                                <button data-id="${res.updated.location_id}" class="btn btn-sm edit-btn btn-revelation-primary"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path d="M18.988 2.012l3 3L19.701 7.3l-3-3zM8 16h3l7.287-7.287l-3-3L8 13z" fill="currentColor"/><path d="M19 19H8.158c-.026 0-.053.01-.079.01c-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .896-2 2v14c0 1.104.897 2 2 2h14a2 2 0 0 0 2-2v-8.668l-2 2V19z" fill="currentColor"/></svg></button>
                                <button data-id="${res.updated.location_id}" class="btn btn-sm remove-btn btn-revelation-danger"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><path d="M12 4h3c.6 0 1 .4 1 1v1H3V5c0-.6.5-1 1-1h3c.2-1.1 1.3-2 2.5-2s2.3.9 2.5 2zM8 4h3c-.2-.6-.9-1-1.5-1S8.2 3.4 8 4zM4 7h11l-.9 10.1c0 .5-.5.9-1 .9H5.9c-.5 0-.9-.4-1-.9L4 7z" fill="currentColor"/></svg></button>
                            </td>
                            <td>${res.updated.location}</td>
                            <td>${res.updated.location_Current}</td>
                            <td>${res.updated.location_Adjacent}</td>
                            <td>${res.updated.location_Regional}</td>
                            <td>${res.updated.location_OTHER}</td>`);
                        $('#editLocationModal').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: 'A location has been updated successfully.'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'An error has been occured while updating.'
                        });
                    }
                    init();
                },
                error: function(response) {
                    let errors = response.responseJSON.errors;
                    errorHtml = '<ul style="list-style:unset;">';
                    for (const i in errors) {
                        errorHtml += `<li>${errors[i]}</li>`;
                    }
                    errorHtml += '</ul>';
                    Toast.fire({
                        icon: 'error',
                        title: response.responseJSON.message,
                        html: errorHtml,
                        customClass: {
                            icon: 'toast-icon'
                        }
                    })
                }
            });
        }

        function removeLocation(location_id) {
            Swal.fire({
                title: 'Remove Survey',
                text: 'Are you sure to remove this survey?',
                icon: 'warning',
                showDenyButton: true,
                confirmButtonText: 'OK',
                denyButtonText: 'Cancel'
            }).then(function(isConfirmed) {
                if (isConfirmed.isConfirmed) {
                    $.ajax({
                        url: '{{ route("real-estate.destroy-location") }}',
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'location_id': location_id
                        },
                        dataType: 'json',
                        success: function(res) {
                            if (res.status == 200) {
                                $(`#row-${location_id}`).remove();
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Removed successfully'
                                })
                            } else if (res.status == 400) {
                                Swal.fire({
                                    title: 'Error',
                                    text: res.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'An error has been occured while removing.'
                                })
                            }
                            init();
                        },
                        error: function(response) {
                            let errors = response.responseJSON.errors;
                            errorHtml = '<ul style="list-style:unset;">';
                            for (const i in errors) {
                                errorHtml += `<li>${errors[i]}</li>`;
                            }
                            errorHtml += '</ul>';
                            Toast.fire({
                                icon: 'error',
                                title: response.responseJSON.message,
                                html: errorHtml,
                                customClass: {
                                    icon: 'toast-icon'
                                }
                            })
                        }
                    });
                }
            });
        }
    </script>
@endsection
