@extends('layouts.reports')  
@section('content')
<div id="HelpContent" class="modal-body" style="display:none;">
<p><strong>Questionnaire Settings</strong></p>   
<p>
When you select “Settings”, you are presented with two sub-options: <b>Questionnaire Settings</b>,
and <b>Support Locations</b>. The settings menu is essentially an administrative utility where we can
edit aspects about the questionnaire that is sent to participants.
</p><p>
If you click on <b>Questionnaire Settings</b>, you will see a screen with a multitude of options. Listed
at the top, you will first see “<b>Contact Email</b>”. This is an email that participants can reach out to
should they have any questions while taking the survey. This email is located all throughout the
survey and should be on every page.
</p><p>
Below this, you will see something titled “<b>Splash Page</b>”. This is an introductory page that
participants will first come to after they click on their unique survey link. It provides them with a
brief reminder of where they are at.
</p><p>
“<b>Splash Pages Logo</b>” and “<b>Survey Pages Logo</b>” are simply the logo of RevelationLegal that is
displayed throughout the survey. Occasionally, the client firm, or even a consultant, would like
for their own logo to be incorporated into the screen, so that is what the “<b>Co-Brand Logo</b>” is.
Further down the page, there is a section titled “<b>Instruction Page</b>”. This is the second page that
participants will see after they pass the Splash Page. The instruction page gives each participant
an overview of the survey process. It is essentially a reiteration of information that they will have
already received from the firm via email. In this, there is an option to enable something called
the “<b>Progress Bar</b>”, which is simply a tool that allows users to see how far into the survey they
are.
</p><p>
Below this is the “<b>Footer</b>”. This is seen on every page throughout the questionnaire. It contains a
link to the participant guide incase users need it along the way, as well as information such as the
support email and copyright.
</p><p>
Next, you will see “<b>Weekly Hours Page</b>”. This is one of the first pieces of information that we
ask from each participant. It is the text that’s displayed to help the participant tell us how many
hours they work weekly. Times will vary based on position.
</p><p>
“<b>Legal Services yes/no Page</b>” is the question that pops up that asks participants whether or not
they perform legal services as part of their job. This would include lawyers, paralegals, and
litigation support analysts. It could also include any staff that perform paralegal-like services.
</p>


      </div>   
{{-- @if(!($data['settings']))
<div class="flex justify-between items-center border-b">
    <h3 class="text-survey font-bold py-4 px-8 m-0 text-lg">No settings found for {{ $survey->survey_name }}</h3>
</div>
@else --}}

<div class="container-fluid" id ="pdfhidden"> 
<div class="cont-mtitle flex flex-wrap justify-between items-center"> 
                    <h1 class="text-survey font-bold text-lg">Questionnaire Settings for {{ $survey->survey_name }}</h1>
                    <button type="button" class="helpguidebtn mx-2" data-toggle="modal" data-target="#helpdetasurvey">         
</button> 
                </div>
    <div class="emailFormArea my-4"> 
        @if (isset($data['updated']) && $data['updated'] == 1)
        <p class="alert alert-success">The settings have been updated successfully</p>
        @endif
        <form id="emailForm" method="POST" action="{{ ($data['settings'] == null)?route('settings/add_settings'):route('settings/update_settings')}}" enctype="multipart/form-data"> 
            @csrf
            <div class="form-group flex items-center justify-between">
                <label for="contact_email" class="col-md-5"><span>Contact E-mail:</span></label>
                <input type="text" class="form-control col-md-7" id="contact_email" name="contact_email" value="{{ isset($data['settings']->contact_email)?$data['settings']->contact_email : '' }}">
            </div> 
            <div class="form-group flex items-center justify-between">
                <label for="contact_phone" class="col-md-5"><span>Contact Phone:</span></label>
                <input type="text" class="form-control col-md-7" id="contact_phone" name="contact_phone" value="{{ isset($data['settings']->contact_phone)?$data['settings']->contact_phone : '' }}">
            </div>
            <div class="form-group flex">
                <label for="contact_email" class="col-md-5">
                    <span>Splash Page:</span> <br>
                    <div class="form-check p-0 mt-2" style="color:#337ab7;">
                        <input type="checkbox" id="show_splash_page" name="show_splash_page" class="fomr-check-input" {{(isset($data['settings']->show_splash_page) && $data['settings']->show_splash_page == 1) ? 'checked' : ""}}>
                        <label for="show_splash_page" class="form-check-label">Enable Splash Page</label>
                    </div>
                </label>
                <textarea class="form-control" name="splash_page" id="splash_page" cols="30" rows="7">{{ isset($data['settings']->splash_page)?$data['settings']->splash_page : '' }}</textarea>
            </div>
            <div class="form-group flex">
                <label for="logo_splash" class="col-md-5"><span>Splash Page Logo:</span></label>
                <div class="col-md-7">
                    <div class="flex items-center splashimgupload justify-between">
                        <img style="height:80px;" src="{{ isset($data['settings']->logo_splash)?$data['settings']->logo_splash : '' }}" alt="">
                        <input type="file" class="form-control" id="logo_splash_file" name="logo_splash">
                    </div>
                    <label for="logo_splash" class="mt-2 font-bold">Choose logo splash file</label>
                    <input type="text" class="form-control" id="logo_splash" name="logo_splash" value="{{ isset($data['settings']->logo_splash)?$data['settings']->logo_splash : '' }}">
                </div>
            </div>
            <div class="form-group flex">
                <label for="logo_survey" class="col-md-5"><span>Survey Pages Logo:</span></label>
                <div class="col-md-7">
                    <div class="flex items-center splashimgupload justify-between">
                        <img style="height:80px;" src="{{ isset($data['settings']->logo_survey)?$data['settings']->logo_survey : '' }}" alt="">
                        <input type="file" class="form-control" id="logo_survey_file" name="logo_survey">
                    </div>
                    <label for="logo_survey" class="mt-2 font-bold">Choose logo survey file</label>
                    <input type="text" class="form-control" id="logo_survey" name="logo_survey" value="{{ isset($data['settings']->logo_survey)?$data['settings']->logo_survey : '' }}">
                </div>
            </div>
            <div class="form-group flex">
                <label for="cobrand_logo" class="col-md-5"><span>Co-Brand Logo:</span></label>
                <div class="col-md-7">
                    <div class="flex items-center splashimgupload justify-between">
                        <img style="height:80px;" src="{{ isset($data['settings']->cobrand_logo)?$data['settings']->cobrand_logo : '' }}" alt="">
                        <input type="file" class="form-control" id="cobrand_logo_file" name="cobrand_logo">
                    </div>
                    <label for="cobrand_logo" class="mt-2 font-bold">Choose logo co-brand file</label>
                    <input type="text" class="form-control" id="cobrand_logo" name="cobrand_logo" value="{{ isset($data['settings']->cobrand_logo)?$data['settings']->cobrand_logo : '' }}">
                </div>
            </div>
            <div class="form-group flex">
                <label for="begin_page" class="col-md-5">
                    <span>Instruction Page:</span> <br>
                    <div class="form-check p-0 mt-2" style="color:#337ab7;">
                        <input type="checkbox" id="show_progress_bar" name="show_progress_bar" class="fomr-check-input" @if ( isset($data['settings']->show_progress_bar) && $data['settings']->show_progress_bar == 1)
                        checked
                        @endif>
                        <label for="show_progress_bar" class="form-check-label">Enable Progress Bar</label>
                    </div>
                </label>
                <textarea class="form-control" name="begin_page" id="begin_page" cols="30" rows="7">{{ isset($data['settings']->begin_page)?$data['settings']->begin_page : ''}}</textarea>
            </div>
            <div class="form-group flex">
                <label for="footer" class="col-md-5">
                    <span>Footer:</span>
                </label>
                <textarea class="form-control" name="footer" id="footer" cols="30" rows="7">{{ isset($data['settings']->footer)?$data['settings']->footer:'' }}</textarea>
            </div>
            <div class="form-group flex">
                <label for="weekly_hours_text" class="col-md-5">
                    <span>Weekly Hours Page:</span>
                </label>
                <textarea class="form-control" name="weekly_hours_text" id="weekly_hours_text" cols="30" rows="7">{{ isset($data['settings']->weekly_hours_text)?$data['settings']->weekly_hours_text:''}}</textarea>
            </div>
            <div class="form-group flex">
                <label for="legal_yn_text" class="col-md-5">
                    <span>Legal Services Yes/No Page:</span>
                </label>
                <textarea class="form-control" name="legal_yn_text" id="legal_yn_text" cols="30" rows="7">{{ isset($data['settings']->legal_yn_text)?$data['settings']->legal_yn_text:''}}</textarea>
            </div>
            <div class="form-group flex">
                <label for="annual_legal_hours_text" class="col-md-5">
                    <span>Annual Legal Hours Page:</span>
                </label>
                <textarea class="form-control" name="annual_legal_hours_text" id="annual_legal_hours_text" cols="30" rows="7">{{ isset($data['settings']->annual_legal_hours_text)?$data['settings']->annual_legal_hours_text:''}}</textarea>
            </div>
            <div class="form-group flex">
                <label for="location_dist_text" class="col-md-5">
                    <span>Support Locations:</span> <br>
                    <div class="form-check p-0 mt-2" style="color:#337ab7;">
                        <input type="checkbox" id="show_location_dist" name="show_location_dist" class="fomr-check-input" @if (isset($data['settings']->show_location_dist) && $data['settings']->show_location_dist == 1)
                        checked 
                        @endif>
                        <label for="show_location_dist" class="form-check-label">Enable Support Locations</label>
                    </div>
                </label>
                <textarea class="form-control" name="location_dist_text" id="location_dist_text" cols="30" rows="7">{{ isset($data['settings']->location_dist_text)?$data['settings']->location_dist_text:''}}</textarea>
            </div>
            <div class="form-group flex">
                <label for="end_page" class="col-md-5">
                    <span>End Page:</span> <br>
                    <div class="form-check p-0 mt-2" style="color:#337ab7;">
                        <input type="checkbox" id="show_summary" name="show_summary" class="fomr-check-input" @if (isset($data['settings']->show_summary) && $data['settings']->show_summary == 1)
                        checked
                        @endif>
                        <label for="show_summary" class="form-check-label">Enable Print Summary</label>
                    </div>
                </label>
                <textarea class="form-control" name="end_page" id="end_page" cols="30" rows="7">{{ isset($data['settings']->end_page)?$data['settings']->end_page:'' }}</textarea>
            </div>
            <div class="form-group flex">
                <label for="copyright" class="col-md-5">
                    <span>Copyright:</span>
                </label>
                <textarea class="form-control" name="copyright" id="copyright" cols="30" rows="3">{{ isset($data['settings']->copyright)?$data['settings']->copyright:'' }}</textarea>
            </div>
            @if (isset($data['settings']->show_legal_services) && $data['settings']->show_legal_services == 1)
            <div class="form-group flex items-center justify-between">
                <label for="contact_email" class="col-md-5"><span>Participant's Guide:</span></label>
                <div class="col-md-7">
                    <a href="{{ ($survey->survey_id == 54)?asset('REV_LEGAL-i_GUIDE.pdf'):asset('Participants_Guide.pdf') }}" target="_blank">View Current Participant Guide</a>
                </div>
            </div>
            @endif
            <input type="hidden" id="survey_id" name="survey_id" value="{{ isset($data['survey']->survey_id)?$data['survey']->survey_id :'' }}">
            <button type="submit" class="btn btn-revelation-primary float-right">{{isset($data['settings']->contact_phone)? 'Save' : 'Add'}} Settings</button>
            <div class="clear-both"></div>
        </form>
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
      <p>
When you select “Settings”, you are presented with two sub-options: <b>Questionnaire Settings</b>,
and <b>Support Locations</b>. The settings menu is essentially an administrative utility where we can
edit aspects about the questionnaire that is sent to participants.
</p><p>
If you click on <b>Questionnaire Settings</b>, you will see a screen with a multitude of options. Listed
at the top, you will first see “<b>Contact Email</b>”. This is an email that participants can reach out to
should they have any questions while taking the survey. This email is located all throughout the
survey and should be on every page.
</p><p>
Below this, you will see something titled “<b>Splash Page</b>”. This is an introductory page that
participants will first come to after they click on their unique survey link. It provides them with a
brief reminder of where they are at.
</p><p>
“<b>Splash Pages Logo</b>” and “<b>Survey Pages Logo</b>” are simply the logo of RevelationLegal that is
displayed throughout the survey. Occasionally, the client firm, or even a consultant, would like
for their own logo to be incorporated into the screen, so that is what the “<b>Co-Brand Logo</b>” is.
Further down the page, there is a section titled “<b>Instruction Page</b>”. This is the second page that
participants will see after they pass the Splash Page. The instruction page gives each participant
an overview of the survey process. It is essentially a reiteration of information that they will have
already received from the firm via email. In this, there is an option to enable something called
the “<b>Progress Bar</b>”, which is simply a tool that allows users to see how far into the survey they
are.
</p><p>
Below this is the “<b>Footer</b>”. This is seen on every page throughout the questionnaire. It contains a
link to the participant guide incase users need it along the way, as well as information such as the
support email and copyright.
</p><p>
Next, you will see “<b>Weekly Hours Page</b>”. This is one of the first pieces of information that we
ask from each participant. It is the text that’s displayed to help the participant tell us how many
hours they work weekly. Times will vary based on position.
</p><p>
“<b>Legal Services yes/no Page</b>” is the question that pops up that asks participants whether or not
they perform legal services as part of their job. This would include lawyers, paralegals, and
litigation support analysts. It could also include any staff that perform paralegal-like services.
</p> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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


    $(document).ready(function() {
        setTimeout(function() {
            $('.alert').slideUp();
        }, 2000);
    });
</script>
@endsection