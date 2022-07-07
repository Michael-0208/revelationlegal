@extends('layouts.reports')
@section('content')

<div id="HelpContent" class="modal-body" style="display:none;">
<p><strong>Invitations</strong></p>
<p><strong>Within</strong> the Invitations section, there are two sub-sections:<strong> Settings</strong> and <strong>Send Invitations.</strong></p>
<img src="{{asset('imgs/user-guide/image-011.png')}}" alt="img">
<p>In the <strong>Settings </strong>option, you will come to a screen that will allow you to edit each part of the
invitation being sent out. The first section that you will see is “Message Sender”. This is the
name of the individual who will be sending out the invitation to complete the survey. Below that,
you will see “Message Complete-By-Date”. This will tell recipients of the invitation when they
need to have their survey completed by. It is listed in a month-day-year format.</p>

<p>Name of Managing Partner and Firm Domain Name are the name of the individual who is in
charge of the specific project, as well as the firm that the survey is linked to. To be more specific,
<a href="http://ofpartner.com">http://ofpartner.com</a> is an example of a firm domain name that would be listed.</p>

<p>Questions Contact Name is the name of the individual who participants should contact if they
have any questions regarding the survey.</p>

<p>Below that, you will see “PDF Attachment Link”, as well as “Uploaded <strong>PDF</strong> Attachment Link”. These will each be repeated once, giving users the option to attach two PDF files to the survey invitation.</p>

<p>Finally, you will see “Email Subject Line”, and “Template Invitations Letter (HTML)”. The
subject line is where you will let recipients know what the contents of the email will entail. Since
this is an invitation for a survey, the subject line may read “Invitations For Survey”. The
Template Invitations Letter is the contents of your email. This will tell recipients of the invitation
all that they need to know about the survey that they will partake in. It will also include the link
to their surveys.</p>

<p>If you make any changes within these settings, please see the “Save Settings” button at the
bottom right of your screen, and click it. This will solidify any changes that you make.</p>

<p>The second and final subsection to Invitations is titled: <strong>Send Invitations</strong>. In this, you will be able to select who receives the invitations to complete the survey. You will be able to choose
between six different options.</p>
<img src="{{asset('imgs/user-guide/image-012.png')}}" alt="img">
<p>“Send to all participants” allows for you to send the invitation to every single user who has
access to the specific project. “Send only to new participants” means that the invitations will be
sent to users who were newly added to the portal; invitations will not be sent to users who
already exist. “Send only to non-responders” means that invitations will be sent to users who
have not accessed the survey at all, meaning that they haven’t started it. “Send only to partial
completed participants” means that invitations will be sent to individuals who have started the
survey, but have not yet completed it. “Send only to selected participants” means that you have
the ability to hand select who receives the survey invitations. You would select them one by one.
Finally, “Send test email” means that an email will be sent to test if recipients receive it or not.</p>
</div> 


<div class="container-fluid px-3" id="pdfhidden">     
    <div class="cont-mtitle flex flex-wrap justify-between items-center">  
        <h1 class="text-survey">Send Participant Invitation Letters For {{$data['survey']->survey_name}}</h1>
        <button type="button" class="helpguidebtn mx-2" data-toggle="modal" data-target="#helpdetasurvey">         
</button> 
    </div>
    <div class="py-4">
        <p><span id="receiver_num">{{ $data['resp_num'] }}</span> Selected to receive invitations</p>
        <div class="emailFormArea">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="receiverType" id="all_participant" value="0" checked>
                <label class="form-check-label" for="all_participant">
                  <strong>Send to all participants</strong>
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="receiverType" id="new_participant" value="1">
                <label class="form-check-label" for="new_participant">
                  <strong>Send only to new participants</strong>
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="receiverType" id="non_responders" value="2">
                <label class="form-check-label" for="non_responders">
                  <strong>Send only to non-responders</strong>
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="receiverType" id="partial_completed_participants" value="3">
                <label class="form-check-label" for="partial_completed_participants">
                  <strong>Send only to partial completed participants</strong>
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="receiverType" id="selected_participants" value="4">
                <label class="form-check-label" for="selected_participants">
                  <strong>Send only to selected participants</strong>
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="receiverType" id="test_email" value="5">
                <label class="form-check-label" for="test_email">
                  <strong>Send test email</strong>
                </label>
            </div>
            <div class="form-group selectedRespArea">
                <label for="selectedResp"><strong>Enter emails, comma-separated</strong></label>
                <textarea class="form-control" name="selectedResp" id="selectedResp" cols="30" rows="5" style="width: unset;"></textarea>
            </div>
            <p class="alert alert-success" style="display: none;">Emails sent successfully!</p>
            <p class="alert alert-danger" style="display: none;">Error! Please try again later.</p>
            <button data-toggle="modal" data-target="#exampleModal" class="revnor-btn my-2">Send Invitations</button>
            
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
    <p><strong>Invitations</strong></p>
<p><strong>Within</strong> the Invitations section, there are two sub-sections:<strong> Settings</strong> and <strong>Send Invitations.</strong></p>
<img src="{{asset('imgs/user-guide/image-011.png')}}" alt="img">
<p>In the <strong>Settings </strong>option, you will come to a screen that will allow you to edit each part of the
invitation being sent out. The first section that you will see is “Message Sender”. This is the
name of the individual who will be sending out the invitation to complete the survey. Below that,
you will see “Message Complete-By-Date”. This will tell recipients of the invitation when they
need to have their survey completed by. It is listed in a month-day-year format.</p>

<p>Name of Managing Partner and Firm Domain Name are the name of the individual who is in
charge of the specific project, as well as the firm that the survey is linked to. To be more specific,
<a href="http://ofpartner.com">http://ofpartner.com</a> is an example of a firm domain name that would be listed.</p>

<p>Questions Contact Name is the name of the individual who participants should contact if they
have any questions regarding the survey.</p>

<p>Below that, you will see “PDF Attachment Link”, as well as “Uploaded <strong>PDF</strong> Attachment Link”. These will each be repeated once, giving users the option to attach two PDF files to the survey invitation.</p>

<p>Finally, you will see “Email Subject Line”, and “Template Invitations Letter (HTML)”. The
subject line is where you will let recipients know what the contents of the email will entail. Since
this is an invitation for a survey, the subject line may read “Invitations For Survey”. The
Template Invitations Letter is the contents of your email. This will tell recipients of the invitation
all that they need to know about the survey that they will partake in. It will also include the link
to their surveys.</p>

<p>If you make any changes within these settings, please see the “Save Settings” button at the
bottom right of your screen, and click it. This will solidify any changes that you make.</p>

<p>The second and final subsection to Invitations is titled: <strong>Send Invitations</strong>. In this, you will be able to select who receives the invitations to complete the survey. You will be able to choose
between six different options.</p>
<img src="{{asset('imgs/user-guide/image-012.png')}}" alt="img">
<p>“Send to all participants” allows for you to send the invitation to every single user who has
access to the specific project. “Send only to new participants” means that the invitations will be
sent to users who were newly added to the portal; invitations will not be sent to users who
already exist. “Send only to non-responders” means that invitations will be sent to users who
have not accessed the survey at all, meaning that they haven’t started it. “Send only to partial
completed participants” means that invitations will be sent to individuals who have started the
survey, but have not yet completed it. “Send only to selected participants” means that you have
the ability to hand select who receives the survey invitations. You would select them one by one.
Finally, “Send test email” means that an email will be sent to test if recipients receive it or not.</p> 
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Respondent List</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure want to send invitations to the following:</p>
        <ul id="sendInvitationConfirmation">

        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" id="sendInvitation" class="btn btn-primary">Send Invitation</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
         // $('#helpdetasurvey').show();
          $('#copyright_div').removeClass('fixedbottompdf');
          $('#headerDiv').removeClass('fixedtoppdf');
          $(".entrymain-content")[0].style.minHeight = "100vh";   
         /*  }else{ 
              $('#selectRespModal').modal();

          } */

         
      });   




        var survey_id = {{ $data['survey']->survey_id }};
        var receiverType = 0;
        $('.form-check-input').click(function () {
            receiverType = $(this).val();

            if (receiverType == '4') {
                $('.selectedRespArea').slideDown();
                
                $('.revnor-btn').click(function(){
                    var selectedRespondents = $('#selectedResp').val();
                    var selectedRespondentsArray = selectedRespondents.split(',');
                    $('#sendInvitationConfirmation').html('');
                    jQuery.each(selectedRespondentsArray, function(key, respEmail) {
                        $('#sendInvitationConfirmation').append('<li>'+ respEmail +'</li>');
                    });

                })

            } else {
                $('.selectedRespArea').slideUp();
            }

            if (receiverType < 4) {
                $.ajax({
                    url: "{{ route('invitations/get_participants_num') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "survey_id": survey_id,
                        "receiverType": receiverType
                    },
                    dataType: "json",
                    beforeSend: function () {

                    },
                    success: function (res) {
                        console.log(res);
                        //return;
                        $("#sendInvitationConfirmation").html('');
                        $('#receiver_num').html(res.resp_num);
                        
                        jQuery.each(res.resp_emails, function(i, val) {
                            $('#sendInvitationConfirmation').append('<li>'+ val +'</li>');
                        });
                        

                    },
                    error: function(request, error) {
                        alert("Request: " + JSON.stringify(request));
                    }
                });
            }
        });

        $('#sendInvitation').click(function () {
            let receiverList = $('#selectedResp').val();
            
            $.ajax({
                url: "{{ route('invitations/send_emails') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "receiverType": receiverType,
                    "receiverList": receiverList
                },
                dataType: "json",
                beforeSend: function () {

                },
                success: function (res) {
                    if (res.status == 200) {
                        $('.alert-success').slideDown();
                    } else {
                        $('.alert-danger').slideDown();
                    }
                    setTimeout(() => {
                        $('.alert').slideUp();
                        location.reload();
                    }, 3500);
                },
                error: function (request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        });
    </script>
@endsection
