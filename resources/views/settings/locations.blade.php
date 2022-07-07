@extends('layouts.reports')
@section('content')

<style>
    .table-wrapper {
        background: #fff;
        padding: 20px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    }

    .table-title h2 {
        margin: 6px 0 0;
        font-size: 22px;
    }

    .table-title .add-new {
        float: right;
        height: 30px;
        font-weight: bold;
        font-size: 12px;
        text-shadow: none;
        min-width: 100px;
        border-radius: 50px;
        line-height: 13px;
    }

    .table-title .add-new i {
        margin-right: 4px;
    }

    table.table {
        table-layout: fixed;
    }

    table.table tr th,
    table.table tr td {
        border-color: #e9e9e9;
    }

    table.table th i {
        font-size: 13px;
        margin: 0 5px;
        cursor: pointer;
    }

    table.table th:last-child {
        width: 100px;
    }

    table.table td a {
        cursor: pointer;
        display: inline-block;
        margin: 0 5px;
        min-width: 24px;
    }

    table.table .form-control {
        height: 32px;
        line-height: 32px;
        box-shadow: none;
        border-radius: 2px;
    }

    table.table .form-control.error {
        border-color: #f50000;
    }

    table.table td .add {
        display: none;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>


<div id="HelpContent" class="modal-body" style="display:none;">
<p><strong>Support Locations</strong></p> 
<p>
You will then see what is called “<b>Support Locations</b>”. This simply tells us what locations of the
firm participants provide support to. This is <b>NOT</b> where you see where participants live. That is
provided in their demographic report.
</p><p>
After this, you will see “<b>End Page</b>”. This is the final page that a participant will see after they’ve
completed their survey. It essentially just tells users that they’re finished, and that they may close their browser. There is an option in this to enable “Print Summary”, which allows for participants
to be given a recap of their survey responses, and print them if they choose to do so.
</p><p>
“Copyright” is simply the ofpartner <b>copyright</b>. It tells users that this survey is copyrighted
material.
</p><p>
Lastly, you will see “Participants Guide”. This is located in the footer throughout the entire
questionnaire, and it is a document that guides participants through their surveys. You are able to
view the guide here, as well as upload a new version of it.
</p><p>
Should you make any changes to the survey, and wish to save them, please click on the “Save
All” button. This will solidify any changes you may make.
</p><p>
The second aspect of “<b>Settings</b>” is called “<b>Support Locations</b>”. This is an optional question on the survey, but it tells us what locations of the firm participants provide support to. This is <b>NOT</b> where you see where participants live. That is provided in their demographic report. This also
does <b>NOT </b>apply to any legal personnel, just support personnel. An example of this would be a
global tech help desk worker.</p>

<img src="{{asset('imgs/user-guide/image-005.png')}}">

</div>
<div class="container location-page" id="pdfhidden">
<div class="cont-mtitle cont-mtitle flex flex-wrap justify-between items-center"> 
                    <h1 class="text-survey font-bold text-lg">Support Location Settings for {{ $survey->survey_name }}</h1>
                    <button type="button" class="helpguidebtn mx-2" data-toggle="modal" data-target="#helpdetasurvey">         
</button>
                </div>
    <div class="table-responsive mt-4 border-none"> 
        <div class="table-wrapper">
            <div class="location-cont mt-4"> 
                <div class="" id="searchnewbtn"> <button type="button" class="add-new"><i class="fa fa-plus"></i> Add New</button>
                </div>
                <div class="setlocations-outer "> 
                    <table id="setting_locations sl" class="table table-bordered setlocations table-txtmid">
                        <thead> 
                            <tr>
                                <th>Location</th> 
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['locations'] as $location)

                            <tr class="data-tr" data-id="{{ $location->location_id }}">
                                <td data-desc="location">{{ $location->location }}</td>
                                {{-- {{--<td data-desc="location_OTHER">{{ $location->location_OTHER }}</td>
                                <td data-desc="location_Regional">{{ $location->location_Regional }}</td>
                                <td data-desc="location_Adjacent">{{ $location->location_Adjacent }}</td>
                                <td data-desc="location_Current">{{ $location->location_Current }}</td>--}}
                                <td>
                                    <a class="add font-revelation toolstip">
                                        <i class="fa fa-save" title="Add"></i>
                                        </a>
                                    <a class="edit font-revelation toolstip">
                                        <i class="fa fa-edit" title="Edit"></i> 
                                         </a>
                                    <a class="delete font-revelation toolstip">
                                        <i class="fa fa-trash" title="Remove"></i></a>
                                </td>
                            </tr> 
                            @endforeach
                            <tr class="data-tr" hidden>
                                <td data-desc="location"></td>
                                <td>
                                    <a class="add font-revelation toolstip">
                                        <i class="fa fa-save" title="Add"></i>
                                        </a>
                                    <a class="edit font-revelation toolstip">
                                        <i class="fa fa-edit" title="Edit"></i> 
                                         </a>
                                    <a class="delete font-revelation toolstip">
                                        <i class="fa fa-trash" title="Remove"></i></a>
                                </td>
                            </tr> 
                        </tbody>
                    </table>
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
      
    <p>
You will then see what is called “<b>Support Locations</b>”. This simply tells us what locations of the
firm participants provide support to. This is <b>NOT</b> where you see where participants live. That is
provided in their demographic report.
</p><p>
After this, you will see “<b>End Page</b>”. This is the final page that a participant will see after they’ve
completed their survey. It essentially just tells users that they’re finished, and that they may close their browser. There is an option in this to enable “Print Summary”, which allows for participants
to be given a recap of their survey responses, and print them if they choose to do so.
</p><p>
“Copyright” is simply the ofpartner <b>copyright</b>. It tells users that this survey is copyrighted
material.
</p><p>
Lastly, you will see “Participants Guide”. This is located in the footer throughout the entire
questionnaire, and it is a document that guides participants through their surveys. You are able to
view the guide here, as well as upload a new version of it.
</p><p>
Should you make any changes to the survey, and wish to save them, please click on the “Save
All” button. This will solidify any changes you may make.
</p><p>
The second aspect of “<b>Settings</b>” is called “<b>Support Locations</b>”. This is an optional question on the survey, but it tells us what locations of the firm participants provide support to. This is <b>NOT</b> where you see where participants live. That is provided in their demographic report. This also
does <b>NOT </b>apply to any legal personnel, just support personnel. An example of this would be a
global tech help desk worker.</p>

<img src="{{asset('imgs/user-guide/image-005.png')}}">

    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>


<!--- help data popup end ---->
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




    var survey_id = "{{$data['survey']->survey_id}}";
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
    $(document).ready(function() {

        $('#setting_locations').DataTable({
            searching: true,
            "bLengthChange": false,
        });

        var actions = $("table td:last-child").html();
        // Append table with add row form on add new button click
        $(".add-new").click(function() {
           // alert('location');
            //alert(actions);
            $(this).attr("disabled", "disabled");
            var index = $("table tbody tr:last-child").index();
            var row = '<tr class="data-tr" data-id="">' +
                '<td data-desc="location"><input type="text" class="form-control" name="location" id="location"></td>' +
                '<td>' + actions + '</td>' +
                '</tr>';
            $("table").append(row);
            $("table tbody tr").eq(index + 1).find(".add, .edit").toggle();
        });
        // Add row on add button click
        $(document).on("click", ".add", function() {
            var empty = false;
            var input = $(this).parents("tr").find('input');
            var location_id = $(this).parents("tr").attr('data-id');
            input.each(function() {
                if (!$(this).val()) {
                    $(this).addClass("error");
                    empty = true;
                } else {
                    $(this).removeClass("error");
                }
            });
            $(this).parents("tr").find(".error").first().focus();
            if (!empty) {
                if (!location_id) {
                    $.ajax({
                        url: '{{ route("settings/add_location") }}',
                        type: 'POST',
                        data: {
                            '_token': "{{ csrf_token() }}",
                            'survey_id': survey_id,
                            'location': $('#location').val(),
                            'location_OTHER':0,
                            'location_Regional':0,
                            'location_Adjacent':0,
                            'location_Current':0,
                        },
                        dataType: 'json',
                        beforeSend: function() {

                        },
                        success: function(res) {
                            if (res.status == 200) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Created successfully.'
                                });
                                input.each(function() {
                                    $(this).parent("td").html($(this).val());
                                });
                                parentTR = $('tr.data-tr').filter(function() {
                                    return !$(this).attr('data-id');
                                });
                                parentTR.attr('data-id', res.id);
                                parentTR.find(".add, .edit").toggle();
                            } else if (res.status == 400) {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'This location already exists.'
                                });
                            }
                            $(".add-new").removeAttr("disabled");
                        },
                        error: function(request, error) {
                            alert("Request: " + JSON.stringify(request));
                        }
                    });
                } else {
                    $.ajax({
                        url: '{{ route("settings/update_location") }}',
                        type: 'POST',
                        data: {
                            '_token': "{{ csrf_token() }}",
                            'survey_id': survey_id,
                            'location_id': location_id,
                            'location':0,
                            'location_OTHER':0,
                            'location_Regional':0,
                            'location_Adjacent':0,
                            'location_Current':0,
                        },
                        dataType: 'json',
                        beforeSend: function(res) {},
                        success: function(res) {
                            if (res == 200) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Updated successfully.'
                                });
                                input.each(function() {
                                    $(this).parent("td").html($(this).val());
                                });
                                $(`tr[data-id=${location_id}]`).find(".add, .edit")
                                    .toggle();
                                $(".add-new").removeAttr("disabled");
                            } else if (res == 400) {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'The same location already exists.'
                                });
                            }
                        },
                        error: function(request, error) {
                            alert("Request: " + JSON.stringify(request));
                        }
                    });
                }
            }
        });
        // Edit row on edit button click
        $(document).on("click", ".edit", function() {
            $(this).parents("tr").find("td:not(:last-child)").each(function() {
                $(this).html('<input type="text" class="form-control" id="' + $(this).attr(
                    'data-desc') + '" value="' + $(this).text() + '">');
            });
            $(this).parents("tr").find(".add, .edit").toggle();
            $(".add-new").attr("disabled", "disabled");
        });
        // Delete row on delete button click
        $(document).on("click", ".delete", function() {
            let location_id = $(this).parents('tr').attr('data-id');
            let location_desc = $(this).parents('tr').find('td[data-desc="location"]').html();
            if (location_id == "") {
                $(this).parents('tr').remove();
                $(".add-new").removeAttr("disabled");
            } else {
                $.ajax({
                    url: '{{ route("settings/delete_location") }}',
                    type: 'POST',
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'location_id': location_id,
                        'location_desc': location_desc,
                        'survey_id': survey_id,
                    },
                    dataType: 'json',
                    beforeSend: function() {

                    },
                    success: function(res) {
                        if (res == 200) {
                            $(`tr[data-id='${location_id}']`).remove();
                            Toast.fire({
                                icon: 'success',
                                title: location_desc +
                                    ' has been deleted successfully.'
                            });
                        } else if (res == 400) {
                            Toast.fire({
                                icon: 'error',
                                title: location_desc +
                                    ' has been used already, cannot delete.'
                            });
                        }
                        $(".add-new").removeAttr("disabled");
                    },
                    error: function(request, error) {
                        alert("Request: " + JSON.stringify(request));
                    }
                });
            }
        });
    });
</script>
@endsection