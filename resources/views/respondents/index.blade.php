@extends('layouts.reports')
@section('content')
@if($errors->any())
<h4>{{$errors->first()}}</h4>
@endif
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
@if (\Auth::check() && \Auth::user()->hasPermission('surveyRespondents', $survey))
<div id="HelpContent" class="modal-body" style="display:none;">
<p></strong>Participants</strong></p> 
<p>When you select the <b>Participants button</b>, you are brought to a screen that lists all users who have
participated in a particular survey. On the top of the page, you are given a number of options to choose
from, such as “<b>New Participant</b>”, “<b>Upload Participants</b>”, “<b>Download Participants</b>”, “Download All
Data”, “Reset All”, and “Delete All”.
</p><p>
<b>New Participant</b> allows for you to add a new user to the survey. When you click on this option,
you are provided with a pop-up that allows for you to edit the information of the individual that
you are adding. Each user has a unique access code that is specific to their individual survey.
</p>
<img src="{{asset('imgs/user-guide/image-006.png')}}" alt="img">
<p>
<b>Upload Participants</b> is another option that you will see on the top of your screen. When
selected, you will be able to create a CSV file for the participants listed. Once you have the file,
you can select Choose File, and click on the CSV in your computer. Finally, you will be able to
click the Upload button, and it will upload your data for you. This feature also has the option for
you to Check For Errors within your file.
</p> 
<img src="{{asset('imgs/user-guide/image-007.png')}}" alt="img"> 
<p>
Should you click on ‘<b>Download Participants</b>’, all of the data within this screen will be put into
a spreadsheet and downloaded onto your computer. This includes participant’s names, emails,
and specific access codes.
</p><p>
<b>Download All Data</b> is similar to Download Participants, but there is more information that gets
put into the spreadsheet for you. For example, Download All Data includes support activities for
each individual, as well as the rentable square footage that they have.
</p><p>
<b>Reset All</b> and <b>Delete All</b> are options that are dangerous to mess around with, and they should
rarely ever be clicked on. Reset All does exactly that; it will reset all of the data for every single
participant listed. Essentially, it will wipe any record of the user taking the survey. All of their
data will be clean, and it will show as though they never participated. Delete All will delete every
user from the particular project; there will be no users listed in the survey.
</p><p>
Below these options, and slightly to the side, you will notice a <b>Search bar</b>. This allows you to
search for a particular participant, should the amount of users be extensive. You will also notice
another horizontal menu below this, with sections titled <b>“Utilities”, “Access Code”, “Name”,
“Email”, “Last Updated”, and “Status”.</b>
</p>

<p>
There are four utilities within this section: Edit Participant, Reset Participant, Delete
Participant, and Survey URL.
</p>
<img src="{{asset('imgs/user-guide/image-008.png')}}" alt="img"> 
<p>
Edit Participant will allow for you to change information about a particular user. This includes
their name, employee ID, Title, Location, and more. Reset Participant will reset all of the data
relating to that specific user. As mentioned above, it will wipe the results of their survey clean,
which will essentially show that the individual never participated. Delete Participant will wipe
the user from the project completely.
</p><p>
Survey URL is unique, because it allows for you to access a specific individual’s actual survey.
Like Access Codes, the Survey URL is specific to each user. This is a useful tool; in the event
that a user is not able to find their survey, you are able to copy the url and send it to them
directly.
</p><p>
Each user who partakes in the survey has a unique Access Code. This code is specific to each
individual, and is essentially like an I.D. for the survey. It is moreso used behind the scenes, but
is essential to the analysis.
</p><p>
Name and Email show the first and last name of the user who is partaking in the survey, as well
as the email that was used for it. Since it is through RevelationLegal, the email address will
always be <a href="mailto:support@revelationlegal.com">support@revelationlegal.com.</a>
</p><p>
<b>Last Updated</b> and <b>Status</b> tell you when the user last accessed the survey and made any changes
to it. It is listed in year-month-day format, and also shows the specific time. Status will tell you
whether or not the individual has participated, and, if they have, it will tell you if they completed
the survey, or merely started it. A green check mark means that the user has completed the
survey. An orange clock means that the user has started the survey, but not completed it. Finally,
no icon means that the user has not participated in the survey at all.
</p><p class="d-flex justify-between">
<img src="{{asset('imgs/user-guide/image-009.png')}}" alt="img"> 
<img src="{{asset('imgs/user-guide/image-010.png')}}" alt="img"> 
</p> 
      </div> 
<div class="container-fluid px-3" id="pdfhidden"> 
    <div class="row alert alert-dismissible" id="message_flash_row" style="display: none;">
        <div class="col-12">
            <h3 id="message_flash_text"></h3> 
        </div>
    </div>
    <div class="cont-mtitle mt-8 flex flex-wrap justify-between items-center"> 
        <h1 class="text-survey w-95 mw-95">
            {{$data['survey']->survey_name}} / 
            Participants: {{ $respondents->count() }} Total,
            {{ $respondents->whereNotNull('last_dt')->count() }} Responded,
            {{ $respondents->whereIn('survey_completed', [1,2])->count() }} Completed
        </h1>
        <button type="button" class="helpguidebtn mx-2" data-toggle="modal" data-target="#helpdetasurvey">         
</button>
    </div>

    <div class="participant-topbtns py-3">  
        <button id="new_participant" class=""><span class="fas fa-user"></span> New Participant</button>
        <button id="upload_participants_btn" class=""><span class="fas fa-upload"></span> Upload Participants</button>
        @if (\Auth::check() && \Auth::user()->hasPermission('surveyExport', $survey))
        <a target="_blank" href="{{ action('App\Http\Controllers\RespondentController@downloadRespondents', $survey) }}" class=""><span class="fas fa-download"></span> Download
            Participants</a>
        <a target="_blank" href="{{ action('App\Http\Controllers\RespondentController@downloadParticipantAllData', $survey) }}" class=""><span class="fas fa-cloud-download-alt"></span>
            Download All Data</a> 
        <button class="btn-revelation-warning" id="resetAllBtn"><span class="fas fa-sync-alt"></span> Reset All</button>
        <button id="delete_all_respondents_btn" class="btn-revelation-danger"><span class="fas fa-trash"></span> Delete All</button>
        @endif
        <!--<button class="" id="editFieldLabelsBtn"><span class="fas fa-tag"></span> Field Labels</button> -->
        
    </div>
</div>
<div class="container-fluid px-3 hideinpdf">

    @if ($respondents->count())
    <div class="table-responsive">
        <table id="respondants_table" class="table table-striped table-txtmid" id="respondent_table"> 
            <thead>
                <tr>
                    <th>Utilities</th>
                    <th>Access Code</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Last Updated<span class="d-block" style="font-size:13px;color: #008cc2;line-height: 1;">(Central Standard Time)</span></th> 
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="respondent_body">
                @foreach ($respondents as $respondent)
                <tr id="respondent_row_{{ $respondent->resp_id }}">
                    <td class="rl-fg-blue">
                        <button title="Edit Participant" id="edit_respondent_{{ $respondent->resp_id }}" data-respondent_id="{{ $respondent->resp_id }}" class="fas fa-edit mr-1"></button>
                        <button title="Reset Participant" id="reset_respondent_{{ $respondent->resp_id }}" data-respondent_id="{{ $respondent->resp_id }}" data-resp_name="{{ $respondent->resp_first . ' ' . $respondent->resp_last }}" class="fas fa-sync-alt mr-1"></button>
                        <button title="Delete Participant" id="delete_respondent_{{ $respondent->resp_id }}" data-respondent_id="{{ $respondent->resp_id }}" class="fas fa-trash mr-1"></button>
                        <a title="Survey URL" target="_blank" href="{{ route('survey.start') }}?sv={{ $respondent->hashed_survey }}&ac={{ $respondent->hashed_code }}" id="link_respondent_{{ $respondent->resp_id }}" data-respondent_id="{{ $respondent->resp_id }}" class="fas fa-link mr-1 rl-fg-blue"></a>
                    </td>
                    <td>{{ $respondent->resp_access_code }}</td>
                    <td>{{ $respondent->resp_first . ' ' . $respondent->resp_last }}</td>
                    <td>{{ $respondent->resp_email }}</td>
                    <td>{{ $respondent->last_dt ? $respondent->last_dt : $respondent->start_dt }}</td>
                    @if(empty($respondent->last_dt))
                    <td><span title="" class="d-block text-center"></span>  
                    </td>
                    @else
                        @if($respondent->survey_completed == 1)
                        <td><span title="Particpant Completed Survey" class="d-block text-center fas fa-check text-success"></span>  
                        </td>
                        @endif
                        @if($respondent->survey_completed == 2)
                        <td><span title="Report Has been sent" class="d-block text-center fas fa-check text-primary"></span>  
                        </td>
                        @endif
                        @if($respondent->survey_completed == 0)
                        <td><span title="Survey In Progress" class="d-block text-center fas text-warning fa-clock"></span>  
                        </td>
                        @endif
                    @endif

                    
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <h4>There are no respondents for this survey</h4>
    @endif
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
      <p></strong>Participants</strong></p> 
    <p>When you select the <b>Participants button</b>, you are brought to a screen that lists all users who have
participated in a particular survey. On the top of the page, you are given a number of options to choose
from, such as “<b>New Participant</b>”, “<b>Upload Participants</b>”, “<b>Download Participants</b>”, “Download All
Data”, “Reset All”, and “Delete All”.
</p><p>
<b>New Participant</b> allows for you to add a new user to the survey. When you click on this option,
you are provided with a pop-up that allows for you to edit the information of the individual that
you are adding. Each user has a unique access code that is specific to their individual survey.
</p>
<img src="{{asset('imgs/user-guide/image-006.png')}}" alt="img">
<p>
<b>Upload Participants</b> is another option that you will see on the top of your screen. When
selected, you will be able to create a CSV file for the participants listed. Once you have the file,
you can select Choose File, and click on the CSV in your computer. Finally, you will be able to
click the Upload button, and it will upload your data for you. This feature also has the option for
you to Check For Errors within your file.
</p> 
<img src="{{asset('imgs/user-guide/image-007.png')}}" alt="img"> 
<p>
Should you click on ‘<b>Download Participants</b>’, all of the data within this screen will be put into
a spreadsheet and downloaded onto your computer. This includes participant’s names, emails,
and specific access codes.
</p><p>
<b>Download All Data</b> is similar to Download Participants, but there is more information that gets
put into the spreadsheet for you. For example, Download All Data includes support activities for
each individual, as well as the rentable square footage that they have.
</p><p>
<b>Reset All</b> and <b>Delete All</b> are options that are dangerous to mess around with, and they should
rarely ever be clicked on. Reset All does exactly that; it will reset all of the data for every single
participant listed. Essentially, it will wipe any record of the user taking the survey. All of their
data will be clean, and it will show as though they never participated. Delete All will delete every
user from the particular project; there will be no users listed in the survey.
</p><p>
Below these options, and slightly to the side, you will notice a <b>Search bar</b>. This allows you to
search for a particular participant, should the amount of users be extensive. You will also notice
another horizontal menu below this, with sections titled <b>“Utilities”, “Access Code”, “Name”,
“Email”, “Last Updated”, and “Status”.</b>  
</p>

<p>
There are four utilities within this section: Edit Participant, Reset Participant, Delete
Participant, and Survey URL.
</p>
<img src="{{asset('imgs/user-guide/image-008.png')}}" alt="img"> 
<p>
Edit Participant will allow for you to change information about a particular user. This includes
their name, employee ID, Title, Location, and more. Reset Participant will reset all of the data
relating to that specific user. As mentioned above, it will wipe the results of their survey clean,
which will essentially show that the individual never participated. Delete Participant will wipe
the user from the project completely.
</p><p>
Survey URL is unique, because it allows for you to access a specific individual’s actual survey.
Like Access Codes, the Survey URL is specific to each user. This is a useful tool; in the event
that a user is not able to find their survey, you are able to copy the url and send it to them
directly.
</p><p>
Each user who partakes in the survey has a unique Access Code. This code is specific to each
individual, and is essentially like an I.D. for the survey. It is moreso used behind the scenes, but
is essential to the analysis.
</p><p>
Name and Email show the first and last name of the user who is partaking in the survey, as well
as the email that was used for it. Since it is through RevelationLegal, the email address will
always be <a href="mailto:support@revelationlegal.com">support@revelationlegal.com.</a>
</p><p>
<b>Last Updated</b> and <b>Status</b> tell you when the user last accessed the survey and made any changes
to it. It is listed in year-month-day format, and also shows the specific time. Status will tell you
whether or not the individual has participated, and, if they have, it will tell you if they completed
the survey, or merely started it. A green check mark means that the user has completed the
survey. An orange clock means that the user has started the survey, but not completed it. Finally,
no icon means that the user has not participated in the survey at all.
</p><p class="d-flex justify-between">
<img src="{{asset('imgs/user-guide/image-009.png')}}" alt="img"> 
<img src="{{asset('imgs/user-guide/image-010.png')}}" alt="img"> 
</p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div> 


<!--- help data popup end ---->
<div class="modal fade" tabindex="-1" role="dialog" id="respondent_upload_modal" aria-labelledby="upload_participant_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-notify modal-warning" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header text-center" style="background-color: #286090;">
                <h4 id="upload_participant_title" class="modal-title text-white w-100 font-weight-bold py-2">Upload
                    Participants</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body" style="height: 50vh; overflow-y: auto;">
                <form id="respondent_upload_form" action="{{ action([App\Http\Controllers\RespondentController::class, 'uploadRespondents'], $survey) }}" method="POST" enctype="multipart/form-data">
                    <div class="row border-bottom">
                        <div class="col-12">
                            <p>
                                In this area you can upload an entire list of respondents at once.
                                Click the <b>Browse</b> button below to search for the CSV (Comma Separated Values)
                                file on your computer.
                            </p>
                            <p>
                                Click the <b>Check For Errors</b> button before uploading your file.
                                This will help identify any issues in your file before uploading the entire list.
                            </p>
                            <p>
                                Need a CSV file? <a target="_blank" href="{{ action([App\Http\Controllers\RespondentController::class, 'downloadExampleCsv'], $survey) }}">Click
                                    here to download</a> and fill in the template.
                                Once filled in, save the CSV file to your computer and continue with the upload
                                process.
                            </p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <input type="file" accept=".csv" name="file_respondent_upload" id="file_respondent_upload" class="form-control" />
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 text-right">
                            @csrf
                            <button type="button" class="btn rounded btn-revelation-cancel">Cancel</button>
                            <button type="button" id="respondent_upload_check_btn" class="btn rounded btn-revelation-warning">Check For Errors</button>
                            <button type="submit" class="btn rounded btn-revelation-primary">Upload</button>
                        </div>
                    </div>
                </form>
            </div>

            <!--Footer-->
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <h3 id="status_text"></h3>
                        </div>
                    </div>
                </div>
                <div class="container-fluid" style="max-height: 150px; overflow-y: auto;" id="respondent_upload_errors">
                    </h3>
                </div>
            </div>
            <!--/.Content-->
        </div>
    </div>
</div>

<div class="modal fade" tabindex="0" role="dialog" id="respondent_edit_modal" aria-labelledby="edit_respondent_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-notify modal-warning" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header text-center" style="background-color: #286090;">
                <h4 id="edit_respondent_title" class="modal-title text-white w-100 font-weight-bold py-2">Edit
                    Participants</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body" style="height: 50vh; overflow-y: auto;">
                <form id="respondent_edit_form" action="{{ action([App\Http\Controllers\RespondentController::class, 'saveRespondent'], $survey) }}" method="POST" enctype="multipart/form-data">
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Access Code</strong>
                            <span id="ac_indicator"></span><input type="text" name="resp_access_code" id="resp_access_code" class="form-control d-inline" style="width: 90%;" readonly />
                            {{--<button id="resetAccessCode" class="btn rounded btn-revelation-primary" style="width: 9%;"><span class="fas fa-sync-alt"></span></button>--}}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>First Name</strong>
                            <input type="text" name="resp_first" id="resp_first" class="form-control" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Last Name</strong>
                            <input type="text" name="resp_last" id="resp_last" class="form-control" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Email Address</strong>
                            <input type="text" name="resp_email" id="resp_email" class="form-control" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label>
                                <input type="checkbox" name="alternate_text" /> <strong>Receives Alternate
                                    Text</strong>
                            </label>
                        </div>
                    </div>
                    @foreach ($labels as $column => $label)
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>{{ $label }}</strong>
                            <input type="text" name="{{ $column }}" id="{{ $column }}" placeholder="{{ 'Custom ' . $loop->iteration }}" class="form-control" />
                        </div>
                    </div>
                    @endforeach

                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Rentable Sq. Ft.</strong>
                            <input type="text" name="rentable_square_feet" id="rentable_square_feet" class="form-control" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>FTE (1.0 = Full time)</strong>
                            <input type="text" name="fte" id="fte" class="form-control" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Compensation</strong>
                            <input type="text" name="resp_compensation" id="resp_compensation" class="form-control" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Benefits Percentage</strong>
                            <input type="text" name="resp_benefit_pct" id="resp_benefit_pct" class="form-control" />
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 text-right">
                            <input type="hidden" name="participant_id" id="participant_id" />
                            <input type="hidden" name="survey_id" value="{{ $survey->survey_id }}" />
                            @csrf
                            <button class="btn rounded btn-revelation-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--Footer-->
        <div class="modal-footer">
        </div>
    </div>
    <!--/.Content-->
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="edit_field_labels_modal" aria-labelledby="edit_field_labels_modal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-notify modal-warning" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header text-center" style="background-color: #286090;">
                <h4 id="edit_field_labels_modal_title" class="modal-title text-white w-100 font-weight-bold py-2">
                    Edit Field Labels</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body" style="height: 50vh; overflow-y: auto;">
                <form id="editFieldLabelsForm">

                    @foreach (\App\Http\Controllers\RespondentController::getLabels($survey->survey_id) as $count => $label)
                    <div class="row mt-3">
                        <div class="col-12 col-md-4 my-auto">
                            <label for="custom_{{ $count }}">Custom
                                {{ preg_replace('/cust_/i', '', $count) }}: </label>
                        </div>

                        <div class="col-12 col-md-8 my-auto">
                            <input type="text" id="custom_{{ $count }}" name="{{ $count }}_label" value="{{ $label }}" class="form-control" />
                        </div>
                    </div>
                    @endforeach

                    <div class="row mt-3">
                        <div class="col-12 text-right">
                            @csrf
                            <button type="button" class="btn rounded btn-revelation-cancel">Cancel</button>
                            <button type="submit" class="btn rounded btn-revelation-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>

            <!--Footer-->
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <h3 id="status_text"></h3>
                        </div>
                    </div>
                </div>
                <div class="container-fluid" style="max-height: 150px; overflow-y: auto;" id="respondent_upload_errors">
                    </h3>
                </div>
            </div>
            <!--/.Content-->
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
          $('.hideinpdf').hide();
          // $('#helpdetasurvey').hide();
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
          $('.hideinpdf').show(); 
         // $('#helpdetasurvey').show();
          $('#copyright_div').removeClass('fixedbottompdf');
          $('#headerDiv').removeClass('fixedtoppdf');
          $(".entrymain-content")[0].style.minHeight = "100vh";   
         /*  }else{ 
              $('#selectRespModal').modal();

          } */

         
      }); 

</script>
@else
<script>
    Swal.fire({
        title: 'Permissions Required',
        text: 'You do not have permissions to view this page.',
        icon: 'error',
        confirmButtonText: 'OK',
    });
</script>

@endif

<script>
    $(document).ready(() => {
        $('#respondants_table').DataTable({
            searching: true,
            "bLengthChange": true,
            'pageLength': 25,
            'lengthMenu': [[25, 50, 100, -1], [25, 50, 100,'All']],
        });
    })
</script>

@if (\Auth::check() && \Auth::user()->hasPermission('surveyRespondents', $survey))
<script type="text/javascript">
    const survey_id = "{{ $survey->survey_id }}";
    let access_code_validated = false;

    $(function() {

        $('#upload_participants_btn').on('click', function() {
            $('#respondent_upload_modal').modal('show');
        });

        $('#respondent_upload_check_btn').on('click', function() {

            validateCSV($('#file_respondent_upload').get(0).files[0]);
        });

        $('#new_participant').on('click', function() {
            clearForm();
            fetchAccessCode();
            $('#respondent_edit_modal').modal('show');
        });

        $('#resp_access_code').on('change', validateAccessCode);
        $('#resetAccessCode').on('click', fetchAccessCode);

        $('#respondants_table').on('submit','#respondent_edit_form', function() {

            $('.form-control').css('border-color', ''); // reset all the errored inputs

            /* if (!access_code_validated) {
                setErrorField('Invalid Access Code',
                    'Please select a unique access code for this respondent.', $(
                        '#resp_access_code'));
                return false;
            } */

            if (!$('#resp_first').val()) {
                setErrorField('Invalid Data', 'Please specify a first name for this respondent.', $(
                    '#resp_first'));
                return false;
            }

            if (!$('#resp_last').val()) {
                setErrorField('Invalid Data', 'Please specify a last name for this respondent.', $(
                    '#resp_last'));
                return false;
            }

            if (!$('#resp_email').val()) {
                setErrorField('Invalid Data',
                    'Please specify a valid email address for this respondent.', $('#resp_email'));
                return false;
            }

            if (!$('#cust_1').val()) {
                setErrorField('Invalid Data', 'Please specify a value for this field.', $('#cust_1'));
                return false;
            }

            if (!$('#cust_2').val()) {
                setErrorField('Invalid Data', 'Please specify a value for this field.', $('#cust_2'));
                return false;
            }

            if (!$('#cust_3').val()) {
                setErrorField('Invalid Data', 'Please specify a value for this field.', $('#cust_3'));
                return false;
            }

            if (!$('#cust_4').val()) {
                setErrorField('Invalid Data', 'Please specify a value for this field.', $('#cust_4'));
                return false;
            }

            if (!$('#cust_5').val()) {
                setErrorField('Invalid Data', 'Please specify a value for this field.', $('#cust_5'));
                return false;
            }

            if (!$('#cust_6').val()) {
                setErrorField('Invalid Data', 'Please specify a value for this field.', $('#cust_6'));
                return false;
            }

            if (!$('#rentable_square_feet').val() || isNaN($('#rentable_square_feet').val())) {
                setErrorField('Invalid Data', 'Please specify a numeric value for this field.', $(
                    '#rentable_square_feet'));
                return false;
            }

            if (!$('#fte').val()/*  || isNaN($('#fte').val()) || ($('#fte').val() < 0 || $('#fte').val() >
                    1) */) {
                setErrorField('Invalid Data',
                    'Please specify valid numeric value between 0.00 and 1.0 for this field.', $(
                        '#fte'));
                return false;
            }

            if (!$('#resp_compensation').val()  /* || isNaN($('#resp_compensation').val()) */) {
                setErrorField('Invalid Data', 'Please specify a valid numeric value for this field.', $(
                    '#resp_compensation'));
                return false;
            }

            if (!$('#resp_benefit_pct').val() /* || isNaN($('#resp_benefit_pct').val()) */) {
                setErrorField('Invalid Data', 'Please specify a valid numeric value for this field.', $(
                    '#resp_benefit_pct'));
                return false;
            }

            return true;

        });

        $('#respondants_table').on('click','[id^="edit_respondent_"]', function() {
            loadParticipant($(this).data('respondent_id'));
        });

        $('#respondants_table').on('click', '[id^="delete_respondent_"]',function() {
            deleteParticipant($(this).data('respondent_id'));
        });

        $('#delete_all_respondents_btn').on('click', function() {

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-revelation-primary rounded',
                    denyButton: 'btn btn-revelation-cancel rounded mx-1',
                    cancelButton: 'btn btn-revelation-danger rounded',
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Make a backup first?',
                text: "You will not be able to restore respondents unless you make a copy first.",
                icon: 'warning',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Backup First',
                cancelButtonText: 'Continue Delete',
                denyButtonText: 'Cancel Delete',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const printWindow = window.open(
                        "{{ action('App\Http\Controllers\RespondentController@downloadRespondents', $survey) }}",
                        'Participant Download');
                    printWindow.addEventListener('beforeunload', function() {
                        deleteAllParticipants();
                    })
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    deleteAllParticipants();
                }
            })
        });

        $('#editFieldLabelsBtn').on('click', function() {
            $('#edit_field_labels_modal').modal('show');
        });

        $('#editFieldLabelsForm').on('submit', saveFieldLabels);

        $('#resetAllBtn').on('click', resetAll);

        $('#respondants_table').on('click','[id^="reset_respondent_"]', resetSingle);

    });

    function setErrorField(title, msg, input) {
        Swal.fire({
            title: title,
            text: msg,
            icon: "error",
            button: "OK",
        }, function() {
            // give the error input a red border, and focus it on next tick
            setTimeout(function() {
                input.css('border-color', 'red').get(0).focus();
            }, 1)
        });
    }

    function fetchAccessCode() {

        $('#ac_indicator').removeClass(
            'mr-2 fas fa-times fa-check text-danger text-success'); // get rid of the final state classes
        $('#ac_indicator').addClass('mr-2 fas fa-spin fa-spinner text-warning'); // add the intermediate state classes

        $.get('{{url("/")}}/survey/${survey_id}/access-codes/generate')
            .done(function(code) {
                $('#ac_indicator').removeClass('mr-2 fas fa-spin fa-spinner text-warning');
                $('#ac_indicator').addClass('mr-2 fas fa-check text-success');
                $('#resp_access_code').val(code);
                access_code_validated = true;
            })
            .fail(function(data) {
                $('#ac_indicator').removeClass('mr-2 fas fa-spin fa-spinner text-warning');
                $('#ac_indicator').removeClass('mr-2 fas fa-times text-danger');
                access_code_validated = false;
            });

        return false; // make sure form doesn't submit
    }

    function validateAccessCode() {

        $('#ac_indicator').removeClass('mr-2 fas fa-check text-success');
        $('#ac_indicator').removeClass('mr-2 fas fa-times text-danger');
        $('#ac_indicator').addClass('mr-2 fas fa-spin fa-spinner text-warning');

        $.post('{{url("/")}}/survey/${survey_id}/access-codes/validate', {
                code: $(this).val()
            })
            .done(function(results) {

                $('#ac_indicator').removeClass('mr-2 fas fa-spin fa-spinner text-warning');
                if (!results.count) {
                    access_code_validated = true;
                    $('#ac_indicator').addClass('mr-2 fas fa-check text-success');
                } else {
                    access_code_validated = false;
                    $('#ac_indicator').addClass('mr-2 fas fa-times text-danger');
                }
            })
            .fail(function(data) {
                $('#ac_indicator').removeClass('mr-2 fas fa-spin fa-spinner text-warning');
            });

        return false; // disable form submission
    }

    function saveFieldLabels() {
        showLoader();
        $.post('{{url("/")}}/survey/{{ $survey->survey_id }}/save-field-labels', $(this).serialize())
            .done(function(data) {
                hideLoader();
                $('#edit_field_labels_modal').modal('hide');
                Swal.fire({
                    title: 'Saved',
                    text: '',
                    icon: 'success',
                    confirmButtonText: 'OK',
                });
            })
            .catch(function(data) {
                hideLoader();
                console.log();
                Swal.fire({
                    title: "Oops, something went wrong",
                    text: str_msg,
                    icon: "error",
                    button: "OK",
                });
            });

        return false;
    }

    function handleError(data, text, error) {

        hideLoader();
        console.log(data, text, error);
        let messages = JSON.parse(data.responseText);
        let str_msg = 'The following errors occured. Please adjust and try again.\r\n';
        for (const message in messages.errors) {
            str_msg += messages.errors[message][0] + '\r\n';
        }

        Swal.fire({
            title: "Oops, something went wrong",
            text: str_msg,
            icon: "error",
            button: "OK",
        });

    }

    async function validateCSV(file) {
        if (!(file instanceof Blob)) {
            Swal.fire('You must select a csv file to continue.');
            return;
        }

        $('#respondent_upload_errors').empty();
        const csv = new CsvFile();
        const reader = new LocalFileReader(file);
        const validator = new CsvValidator([{
                name: 'Access Code',
                regex: ''
            },
            {
                name: 'Email Address',
                regex: "email"
            },
            {
                name: 'First Name',
                regex: "string"
            },
            {
                name: 'Last Name',
                regex: "string"
            },
            {
                name: 'Rentable Square Feet',
                regex: "decimal"
            },
            {
                name: 'Alt Text',
                regex: ""
            },
            {
                name: 'Employee Id',
                regex: "string"
            },
            {
                name: 'Group',
                regex: ""
            },
            {
                name: 'Title',
                regex: ""
            },
            {
                name: 'Department',
                regex: ""
            },
            {
                name: 'Category',
                regex: ""
            },
            {
                name: 'Location',
                regex: ""
            },
            {
                name: 'FTE',
                regex: ""
            },
            {
                name: 'Compensation',
                regex: "decimal"
            },
            {
                name: 'Benfits Percentage',
                regex: "decimal"
            },
        ]);
        reader.readFile()
            .then(results => {
                csv.loadCsv(results);
                csv.parseCsv();
                return csv.validateCsv(validator);
            })
            .then(errors => {
                if (errors.length) {
                    for (error of errors) {
                        $('#respondent_upload_errors').append(`
                                    <div class="row">
                                        <div class="col-12">
                                            <h5>${error.error}</h5>
                                            <p>${error.msg}</p>
                                        </div>
                                    </div>
                                `);
                    }
                    $('#status_text').removeClass('text-success').addClass('text-danger').text(errors.length +
                        ' Errors Found. ');
                } else {
                    $('#status_text').removeClass('text-danger').addClass('text-success').text('No Errors!');
                }
            })
            .catch(handleError);
    }

    function clearForm() {
        $('#respondent_edit_form').find('input[type="text"]').val('');
        $('#respondent_edit_form').find('input[type="text"]').val('');
        $('#respondent_edit_form').find('input[type="checkbox"]').prop('checked', false);
        $('#participant_id').val('');
    }

    function loadParticipant(participant_id) {
        clearForm();
        showLoader();

        $.get(`{{url("/")}}/survey/${survey_id}/respondents/fetch/${participant_id}`)
            .done(function(data) {
                hideLoader();
                console.log(data);
                $('#resp_access_code').val(data.resp_access_code);
                $('#resp_first').val(data.resp_first);
                $('#resp_last').val(data.resp_last);
                $('#resp_email').val(data.resp_email);
                $('#alternate_text').val(data.alternate_text); //FIXME: What's this supposed to be?
                $('#cust_1').val(data.cust_1);
                $('#cust_2').val(data.cust_2);
                $('#cust_3').val(data.cust_3);
                $('#cust_4').val(data.cust_4);
                $('#cust_5').val(data.cust_5);
                $('#cust_6').val(data.cust_6);
                $('#rentable_square_feet').val(data.rentable_square_feet);
                $('#fte').val(data.fte); //FIXME: Where does this come from? // fixed 
                $('#resp_compensation').val(data.resp_compensation);
                $('#resp_benefit_pct').val((data.resp_benefit_pct) * 100 + "%");
                $('#participant_id').val(data.resp_id);
                $('#respondent_edit_modal').modal('show');
            })
            .catch(function(data) {
                hideLoader();
                Swal.fire('Could not find participant');
            })
    }

    function deleteParticipant(participant_id) {
        Swal.fire({
                title: 'Are you sure you want to delete this participant?',
                showCancelButton: true,
                confirmButtonText: `Continue`,
                reverseButtons: true,
                cancelButtonColor: '#6E7D88', 
            })
            .then(function(result) {
                if (result.isConfirmed) {
                    showLoader();
                    $.get(`{{url("/")}}/survey/${survey_id}/respondents/remove/${participant_id}`)
                        .done(function() {
                            $('#respondent_row_' + participant_id).remove();
                            hideLoader();
                            Swal.fire({
                                title: 'Success',
                                text: 'The user has been successfully removed.',
                                icon: 'success',
                                confirmButtonText: 'OK',
                            });
                        })
                        .fail(function(data) {

                        });
                }
            });
    }

    function deleteAllParticipants() {
        Swal.fire({
            title: `<h3 class="text-danger">Cofirm Delete All Respondents</h3>`,
            html: `<h6>You are about to <strong><u>PERMANENTLY DELETE</u></strong> all survey answers for <i>${$(this).data('resp_name')}</i></h6><br><h6>Are you sure you want to continue?</h6>`,
            icon: 'warning',
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: `Continue`,
            cancelButtonText: `Cancel`,
            reverseButtons: true,
            cancelButtonColor: '#6E7D88',
        }).then(function(result) {
            if (result.isConfirmed) {
                showLoader();
                $.post(`{{url("/")}}/survey/${survey_id}/respondents/remove/all`, {
                        resp_ids: $('[id^="reset_respondent_"]').get().map(el => $(el).data(
                            'respondent_id'))
                    })
                    .done(function() {
                        hideLoader();
                        Swal.fire({
                            title: 'Success',
                            text: 'All respondents removed.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                        });
                        window.location.reload();
                    })
                    .fail(function(data) {

                    });
            }
        }.bind(this));
    }

    function resetAll() {
        const respIds = $('[id^="reset_respondent_"]').get().map(el => $(el).data('respondent_id'));
        Swal.fire({
            title: `<h3 class="text-danger">Cofirm Respondent Reset</h3>`,
            html: `<h6>You are about to <strong>RESET ALL SURVEY DATA</strong> for <i>${respIds.length}</i> respondents. </h6><br><h6>Are you sure you want to continue?</h6>`,
            showCancelButton: true,
            confirmButtonText: `Continue`,
            reverseButtons: true,
            cancelButtonColor: '#6E7D88',
        }).then(function(result) {
            if (result.isConfirmed) {
                resetRespondents(respIds);
            }
        });
    }

    function resetSingle() {
        Swal.fire({
                title: `<h3 class="text-danger">Cofirm Respondent Reset</h3>`,
                html: `<h6 class="my-3">You are about to <strong><u>PERMANENTLY DELETE</u></strong> all survey answers for <i>${$(this).data('resp_name')}</i></h6><h6  class="mb-1">Are you sure you want to continue?</h6>`,
                showCancelButton: true,
                confirmButtonText: `Continue`,
                reverseButtons: true,
                cancelButtonColor: '#6E7D88',
            })
            .then((result) => {
                if (result.isConfirmed)
                    resetRespondents([$(this).data('respondent_id')])
            });
    }

    function resetRespondents(respIds) {
        showLoader();
        $.post(`{{url("/")}}/survey/${survey_id}/respondents/reset`, {
                resp_ids: respIds
            })
            .done(function(data) {
                Swal.fire({
                    title: 'Survey Data Removed!',
                    text: '',
                    icon: 'success',
                    confirmButtonText: 'OK',
                });
                hideLoader();
                window.location.reload();
            })
            .fail(function(data) {
                console.log(data);
                hideLoader();
            });
    }
</script>


<script src="{{ asset('js/CsvFile.js') }}"></script>
<script src="{{ asset('js/LocalFileReader.js') }}"></script>
<script src="{{ asset('js/CsvValidator.js') }}"></script>
@endif

@include('partials.loader')
@endsection