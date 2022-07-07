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
        <h1 class="text-survey">Respondent Invitation Letters for {{ $survey->survey_name }}</h1>
        <button type="button" class="helpguidebtn mx-2" data-toggle="modal" data-target="#helpdetasurvey">         
</button>
    </div>
    <div class="py-4"> 
        <div class="emailFormArea"> 
            @if (isset($data['updated']) && $data['updated'] == 1)
            <p class="alert alert-success">The settings have been updated successfully</p>
            @endif
            @if (isset($data['inserted']) && $data['inserted'] == 1)
            <p class="alert alert-success">The settings have been saved successfully</p>
            @endif
            <form id="emailForm" method="POST" enctype="multipart/form-data" action="{{ route('invitations/update_settings') }}">
                @csrf
                <div class="form-group flex items-center justify-between">
                    <label for="sender" class="px-0 col-md-5"><span>Message Sender:</span> [sender]</label>
                    <input type="text" class="form-control col-md-7" id="sender" name="sender" value="{{ $data['settings'] !== 404 ? $data['settings']->sender : '' }}">
                </div>
                <div class="form-group flex items-center justify-between">
                    <label for="complete_by_date" class="px-0 col-md-5"><span>Message Complete-By Date:</span> [date]</label>
                    <input type="date" class="form-control col-md-7" id="complete_by_date" name="complete_by_date" value="{{ $data['settings'] !== 404 ? $data['settings']->custom_date : '' }}">
                </div>
                <div class="form-group flex items-center justify-between">
                    <label for="managing_partner_name" class="px-0 col-md-5"><span>Name of Managing Partner:</span> [managing_partner]</label>
                    <input type="text" class="form-control col-md-7" id="managing_partner_name" name="managing_partner_name" value="{{ $data['settings'] !== 404 ? $data['settings']->managing_partner_name : '' }}">
                </div>
                <div class="form-group flex items-center justify-between">
                    <label for="firm_domain_name" class="px-0 col-md-5"><span>Firm Domain Name:</span> [firm]</label>
                    <input type="text" class="form-control col-md-7" id="firm_domain_name" name="firm_domain_name" value="{{ $data['settings'] !== 404 ? $data['settings']->firm_domain_name : '' }}">
                </div>
                <div class="form-group flex items-center justify-between">
                    <label for="questions_contact_name" class="px-0 col-md-5"><span>Questions Contact Name:</span> [questions_contact]</label>
                    <input type="text" class="form-control col-md-7" id="questions_contact_name" name="questions_contact_name" value="{{ $data['settings'] !== 404 ? $data['settings']->questions_contact_name : '' }}">
                </div>
                <div class="form-group flex items-center justify-between">
                    <label for="instructions_pdf_link" class="px-0 col-md-5"><span>PDF Attachment Link:</span> [attachment_link_1]</label>
                    <input type="file" class="form-control col-md-7" id="instructions_pdf_link" name="instructions_pdf_link" value="{{ $data['settings'] !== 404 ? $data['settings']->instructions_pdf_link : '' }}">

                    

                </div>
                <div class="form-group flex items-center justify-between">
                <label for="instructions_pdf_link_2" class="px-0 col-md-5"><span>Uploaded PDF Attachment Link:</span> </label>
                <input type="text"  class="form-control col-md-7" readonly value="{{ $data['settings'] !== 404 ? $data['settings']->instructions_pdf_link : '' }}">

                </div>
                <div class="form-group flex items-center justify-between">
                    <label for="instructions_pdf_link_2" class="px-0 col-md-5"><span>PDF Attachment Link 2:</span> [attachment_link_2]</label>
                    <input type="file" class="form-control col-md-7" id="instructions_pdf_link_2" name="instructions_pdf_link_2" value="{{ $data['settings'] !== 404 ? $data['settings']->instructions_pdf_link_2 : '' }}">

                    
                </div>
                <div class="form-group flex items-center justify-between">
                <label for="instructions_pdf_link_2" class="px-0 col-md-5"><span>Uploaded PDF Attachment Link 2:</span> </label>
                <input type="text"  class="form-control col-md-7" readonly value="{{ $data['settings'] !== 404 ? $data['settings']->instructions_pdf_link_2 : '' }}">

                    
                </div>

                

                <div class="form-group flex items-center justify-between">
                    <label for="email_subject" class="px-0 col-md-5"><span>Email Subject Line:</span></label>
                    <input type="text" class="form-control col-md-7" id="email_subject" name="email_subject" value="{{ $data['settings'] !== 404 ? $data['settings']->email_subject : '' }}">
                </div>
                <div class="form-group">
                    <label for="invitation_letter_template"><span>Template Invitations Letter(HTML):</span></label>
                    <textarea name="invitation_letter_template" id="invitation_letter_template" cols="30" rows="80">
                    {{ $data['settings'] !== 404 ? $data['settings']->invitation_letter_template : '' }}
                    </textarea>
                </div>
                <input type="hidden" id="survey_id" name="survey_id" value="{{ $data['survey']->survey_id }}">
                <p class="text-center"><em>Use the "[field]" labels in your email template and they will be replaced with the value you've saved above.</em></p>
                <button type="submit" class="revnor-btn float-right">Save Settings</button>
                <div class="clear-both"></div>
            </form>
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
<script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>

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

    CKEDITOR.replace('invitation_letter_template');
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert').slideUp();
        }, 2000);
    });
</script>
@endsection