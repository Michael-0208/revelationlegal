@extends('layouts.reports')
@section('content')
{{-- {{dd($data['questions'])}} --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ asset('css/jquerysctipttop.css') }}">

<style>
    .node {
        cursor: pointer;
    }

    .node circle {
        fill: #fff;
        stroke: steelblue;
        stroke-width: 1.5px;
    }

    .node text {
        font: 10px sans-serif; 
        font-size:16px; 
    }

    .link {
        fill: none;
        stroke: #ccc;
        stroke-width: 1.5px;
    }

    #export-loading {
        display: inline-block;
        width: 50px;
        height: 50px;
        margin-top: 10px;
        border: 3px solid rgba(136, 194, 252, .3);
        border-radius: 50%;
        border-top-color: #367BC1;
        animation: spin 1s ease-in-out infinite;
        -webkit-animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to {
            -webkit-transform: rotate(360deg);
        }
    }

    @-webkit-keyframes spin {
        to {
            -webkit-transform: rotate(360deg);
        }
    }
</style>
<div id="HelpContent" class="modal-body" style="display:none;">
<p>
The taxonomy is essentially the database of activities. This is a very important part of the system,
because it’s the format that we use to create the questions within the survey for people to
describe their jobs. Users describing their jobs with consistent terminology is absolutely essential
to any analysis.
</p><p>
When you select this tab, you may be presented with a warning. This is telling you that the
specific project is active, which means that if the taxonomy gets messed with, the entire analysis
gets messed with. For example, if a user has already completed this survey, and you change the
taxonomy, you are essentially changing the questions of a test, and now that person’s results are
not going to be consistent with the rest of the data collected. This warning is essentially telling
you that since the project is active, you should not be changing anything.
</p>
<img src="{{ asset('imgs/user-guide/image-003.png') }}" alt="tag"> 
<p>
If you have rights, you will also see that there is an option to add another function, as well as
delete and add one. This is<b> very dangerous</b>; if the project is active, and the taxonomy is messed
with, it will alter the results of the entire analysis.
</p><p>
The taxonomy is broken into two main components:<b> Support Activities</b> and<b> Legal Services</b>.
These represent everything that everybody does within a law firm. If you are practicing law, or
directly supporting the practice of law, those activities will be a part of Legal Services. If you are
supporting the practice, all of those activities, such as finance, business department, and
administrative work, will all be a part of Support Activities.
</p>
<img src="{{ asset('imgs/user-guide/image-004.png') }}" alt="tag">
<p>
On this screen, you will see a Hide Definitions button. Each activity has a definition to help users
understand what it is exactly that they are looking at. This feature allows for you to hide or
display the definitions of each activity, so if you don’t need the definitions, you can hide them
and have a much cleaner view.
</p><p>
You will notice that each activity has a plus sign. This is how you view the taxonomy; it is
essentially a hierarchical structure of all of the activities that occur at a law firm that are either
needed to support the practice, or that are actually the practice of law.
</p><p>
Each activity has a name, but it also has a code that correlates to it. The code isn’t really used by
anybody in the system; it is moreso used behind the scenes for us to manage the taxonomy. The
codes are fixed– they do not change. If you have rights, you can change the heading and the
descriptions of the activities.
</p><p>
In the Legal Services category, you have a description within each activity, but also have an
alternate name of the description. The reason for this is that it differentiates between who is
supporting, and who is actually doing, the activity. The description would tell you who is doing
the activity (such as a lawyer), while the alternate description would tell you who is supporting
the activity (such as a paralegal who is doing discovery activities to support a lawyer).
</p>
<p>
For each activity, we assign what is called a “Proximity Factor.” The proximity factor can be
high, medium, or low. This essentially determines where the survey must be conducted. If the
proximity factor is high, the survey has to be done where the practice is occurring, and where the
lawyers are. If it is medium, the survey has to be done nearby, but the participant doesn’t have to
be directly there. This means that it can be conducted in a neighboring building, or a lower floor
of the same building. If the proximity factor is low, this means that the survey doesn’t have to be
done anywhere near the practice; it can be conducted virtually, in another city, or even in another
country.
</p>
<p>
You will also see an option to select what is called a Tree Chart. This is just a visualization tool
to see the taxonomy in electronic tree format. You are able to click on it, and it will display all of
the details for each activity. This is another way to see the hierarchical structure of the taxonomy.
Users also have the option to print this out.
</p>

   
</div> 

<div class="container" id="pdfhidden">
    <div class="cont-mtitle mt-8 flex flex-wrap justify-between items-center  ">
        <h1 class="">Taxonomy / {{ $survey->survey_name }} </h1>
        <button type="button" class="helpguidebtn mx-2" data-toggle="modal" data-target="#helpdetasurvey">         
</button>
    </div>
    <div class="tax-maincont  mt-8 ">
        <div id="taxonomyTable" class="treetable"></div>
        <div class="treeArea">
            <div>
                <button class="revnor-btn" onclick="viewTaxonomy();">Taxonomy</button>
                {{-- <button class="revnor-btn">Tree Chart</button> --}}
                @if (\Auth::check() && \Auth::user()->hasPermission('surveyExport', $survey))
                <button class="revnor-btn" onclick="exportExcel();"><i class="fa fa-download"></i>&nbsp;Export</button>
                @endif
            </div>
            <div id="treemain">
                <div id="node_0" class="window hidden" data-id="0" data-parent="" data-first-child="{{ $data['questions'][0]->id }}" data-next-sibling="">
                    Root
                </div>
                @foreach ($data['questions'] as $question)
                <div id="node_{{ $question->id }}" class="window hidden" data-id="{{ $question->data_id }}" data-parent="{{ $question->data_parent }}" data-first-child="{{ $question->data_first_child }}" data-next-sibling="{{ $question->data_next_sibling }}">
                    {{ $question->name }}
                </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- Edit Taxonomy Modal --}}

    
<!-- Modal -->

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
The taxonomy is essentially the database of activities. This is a very important part of the system,
because it’s the format that we use to create the questions within the survey for people to
describe their jobs. Users describing their jobs with consistent terminology is absolutely essential
to any analysis.
</p><p>
When you select this tab, you may be presented with a warning. This is telling you that the
specific project is active, which means that if the taxonomy gets messed with, the entire analysis
gets messed with. For example, if a user has already completed this survey, and you change the
taxonomy, you are essentially changing the questions of a test, and now that person’s results are
not going to be consistent with the rest of the data collected. This warning is essentially telling
you that since the project is active, you should not be changing anything.
</p>
<img src="{{ asset('imgs/user-guide/image-003.png') }}" alt="tag"> 
<p>
If you have rights, you will also see that there is an option to add another function, as well as
delete and add one. This is<b> very dangerous</b>; if the project is active, and the taxonomy is messed
with, it will alter the results of the entire analysis.
</p><p>
The taxonomy is broken into two main components:<b> Support Activities</b> and<b> Legal Services</b>.
These represent everything that everybody does within a law firm. If you are practicing law, or
directly supporting the practice of law, those activities will be a part of Legal Services. If you are
supporting the practice, all of those activities, such as finance, business department, and
administrative work, will all be a part of Support Activities.
</p>
<img src="{{ asset('imgs/user-guide/image-004.png') }}" alt="tag">
<p>
On this screen, you will see a Hide Definitions button. Each activity has a definition to help users
understand what it is exactly that they are looking at. This feature allows for you to hide or
display the definitions of each activity, so if you don’t need the definitions, you can hide them
and have a much cleaner view.
</p><p>
You will notice that each activity has a plus sign. This is how you view the taxonomy; it is
essentially a hierarchical structure of all of the activities that occur at a law firm that are either
needed to support the practice, or that are actually the practice of law.
</p><p>
Each activity has a name, but it also has a code that correlates to it. The code isn’t really used by
anybody in the system; it is moreso used behind the scenes for us to manage the taxonomy. The
codes are fixed– they do not change. If you have rights, you can change the heading and the
descriptions of the activities.
</p><p>
In the Legal Services category, you have a description within each activity, but also have an
alternate name of the description. The reason for this is that it differentiates between who is
supporting, and who is actually doing, the activity. The description would tell you who is doing
the activity (such as a lawyer), while the alternate description would tell you who is supporting
the activity (such as a paralegal who is doing discovery activities to support a lawyer).
</p>
<p>
For each activity, we assign what is called a “Proximity Factor.” The proximity factor can be
high, medium, or low. This essentially determines where the survey must be conducted. If the
proximity factor is high, the survey has to be done where the practice is occurring, and where the
lawyers are. If it is medium, the survey has to be done nearby, but the participant doesn’t have to
be directly there. This means that it can be conducted in a neighboring building, or a lower floor
of the same building. If the proximity factor is low, this means that the survey doesn’t have to be
done anywhere near the practice; it can be conducted virtually, in another city, or even in another
country.
</p>
<p>
You will also see an option to select what is called a Tree Chart. This is just a visualization tool
to see the taxonomy in electronic tree format. You are able to click on it, and it will display all of
the details for each activity. This is another way to see the hierarchical structure of the taxonomy.
Users also have the option to print this out.
</p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!--- help data popup end ---->
    <div class="modal fade" tabindex="-1" role="dialog" id="editWarningModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong >Edit Warning</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>This survey is currently active and/or has responses. Making edits to an active survey with responses is strongly discouraged. If edits are absolutely necessary, please ensure changes are minor, such as spelling corrections.</p>
                    <p>Restructuring the survey, adding, deleting, or significantly rewording questions may cause the existing survey data to become skewed and possibly unusable. Please proceed with caution.</p>
                    <p>It is further recommended to deactivate the survey while performing edits.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="revnor-btn" data-dismiss="modal">Understood</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="editTaxonomyModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong>Edit Item</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="item_enabled">
                        <label for="form-check-label" for="item_enabled">Visible On Survey</label>
                    </div>
                    <div class="form-group">
                        <label for="sort_id">Sort Order:</label> 

                        <input type="text" class="form-control" id="sort_id" style="width: 50%;" required>
                    </div>
                    <div class="form-group">
                        <label for="sort_id">LEDE Code:</label> 

                        <input type="text" class="form-control" id="lead_code" style="width: 50%;">
                    </div>
                    <div class="form-group">
                        <label for="item_code">Item Code:</label>
                        <input type="text" class="form-control" id="item_code"  required>
                    </div>
                    <div class="form-group">
                        <label for="item_desc">Item Name:</label>
                        <input type="text" class="form-control" id="item_desc" required>
                    </div>
                    <div class="form-group">
                        <label for="item_extra">Item Description:</label>
                        <textarea class="form-control" id="item_extra" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="item_desc_alt">Alternate Item Name (optional):</label>
                        <input type="text" class="form-control" id="item_desc_alt">
                    </div>
                    <div class="form-group">
                        <label for="item_extra_alt">Alternate Item Description (optional):</label>
                        <textarea class="form-control" id="item_extra_alt"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="item_prox">Proximity Factor:</label>
                        <select class="form-control" name="item_prox" id="item_prox">
                            <option value="1">Low</option>
                            <option value="2">Medium</option>
                            <option value="3">High</option>
                            <option value="4">Virtual</option>
                        </select>
                    </div>
                    <input type="hidden" id="item_id">
                    <div class="alert alert-success" role="alert">
                        Updated successfully!
                    </div>
                    <div class="alert alert-danger alert-error" role="alert">
                        Error, Try again later!
                    </div>
                    <div class="alert alert-danger alert-validation" role="alert">
                        Item Name is required!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="revnor-btn" id="updateItem">Save</button>
                    <button type="button" class="revnor-btn" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Add Taxonomy Modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="createTaxonomyModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong>Add Item</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="additem_enabled">
                        <label for="form-check-label" for="additem_enabled">Visible On Survey</label>
                    </div>
                    <div class="form-group">
                        <label for="sort_id">Sort Order:</label>
                        <input type="text" class="form-control" id="sort_id" style="width: 50%;" required>
                    </div>
                    <div class="form-group">
                        <label for="sort_id">LEDE Code</label>
                        <input type="text" class="form-control" id="lead_code" style="width: 50%;">
                    </div>
                    <div class="form-group">
                        <label for="additem_code">Item Code:</label>
                        <input type="text" class="form-control" id="additem_code" style="width: 50%;" required>
                    </div>
                    <div class="form-group">
                        <label for="additem_desc">Item Name:</label>
                        <input type="text" class="form-control" id="additem_desc" required>
                    </div>
                    <div class="form-group">
                        <label for="additem_extra">Item Description:</label>
                        <textarea class="form-control" id="additem_extra" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="additem_desc_alt">Alternate Item Name (optional):</label>
                        <input type="text" class="form-control" id="additem_desc_alt">
                    </div>
                    <div class="form-group">
                        <label for="additem_extra_alt">Alternate Item Description (optional):</label>
                        <textarea class="form-control" id="additem_extra_alt"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="additem_prox">Proximity Factor:</label>
                        <select class="form-control" id="additem_prox">
                            <option value="1">Low</option>
                            <option value="2">Medium</option>
                            <option value="3">High</option>
                            <option value="4">Virtual</option>
                        </select>
                    </div>
                    <input type="hidden" id="additem_pid">
                    <div class="alert alert-success" role="alert">
                        Saved successfully!
                    </div>
                    <div class="alert alert-danger alert-error" role="alert">
                        Error, Try again later!
                    </div>
                    <div class="alert alert-danger alert-validation" role="alert">
                        Item Name is required!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-revelation-primary" id="addItem">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="addPageModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong>Add Page</strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="addpage_title">Page Title:</label>
                        <input type="text" class="form-control" id="addpage_title" required>
                    </div>
                    <div class="form-group">
                        <label for="addpage_description">Page Description:</label>
                        <textarea class="form-control" name="addpage_description" id="addpage_description" cols="30" rows="10"><p>Of the time you devote to <strong>[SURVEY POSITION]</strong>, indicate the percentage dedicated to these categories of activities. Your responses must total 100%.</p></textarea>
                    </div>
                    <input type="hidden" id="page_pid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-revelation-primary" id="addPage">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="exportModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h4>Exporting PDF...</h4>
                    <div id="export-loading"></div>
                </div>
            </div>
        </div>
    </div>
    <img id="test" src="" alt="">
</div>
<script>
    var permissionCreate = "{{$data['permissionCreate']}}";
    var permissionUpdate = "{{$data['permissionUpdate']}}";
    var permissionDelete = "{{$data['permissionDelete']}}";
    var permissionExport = "{{$data['permissionExport']}}";
    var moreInfo = 0;
</script>
<script src="{{ asset('js/jquery.edittreetable.js') }}"></script>
<script > 


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

           
        }); </script>
<script>
    var questionData = @json($data['questions']);
    var survey_id = "{{$data['survey']->survey_id}}";
    var checkPageUrl = "{{ route('checkTaxonomyPage') }}";
    var treechartData = [];
    for (let p in questionData) {
        if (questionData[p].pid == 0 || questionData[p].pid == null) {
            let tmpNode = {
                "name": questionData[p].name,
                "children": getChildrenData(questionData, questionData[p].id)
            };
            treechartData.push(tmpNode);
        }
    }
    $(document).ready(function() {
        $table = $('#taxonomyTable');
        $table.bstreetable({
            data: questionData,
            maintitle: "Taxonomy",
            maxlevel: 6,
            nodeaddCallback: function(data, callback) {
                // $('#additem_desc').val(data.name);
                // console.log(data);
                $('#additem_pid').val(data.pid);
                $.ajax({
                    url: "{{ route('checkTaxonomyPage') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'survey_id': survey_id,
                        'pid': data.pid
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 400) {
                            $('#addPageModal').modal('show');
                            $('#addpage_title').val(res.parent_question.question_desc);
                            $('#page_pid').val(data.pid);
                        } else {
                            $('#createTaxonomyModal').modal('show');
                        }
                    }
                });
            },
            noderemoveCallback: function(data, callback) {
                if (!data) {
                    callback();
                } else {
                    $.ajax({
                        url: "{{ route('deleteTaxonomy') }}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "survey_id": survey_id,
                            "question_id": data,
                        },
                        dataType: 'json',
                        beforeSend: function() {},
                        success: function(res) {
                            if (res.status == 200) {
                                alert('Successfully removed!');
                                callback();
                            } else {
                                alert('Error!');
                            }
                        },
                        error: function(request, error) {
                            alert("Request: " + JSON.stringify(request));
                        }
                    });
                }
            },
            nodeupdateCallback: function(data, callback) {
                if (data.name == "") {
                    alert('You need to fill the input field.');
                    $(`.j-expend[data-id="${data.id}"] input`).val(data.oldname);
                }
                let confirm_edit = confirm("Are you sure to update this question?");
                if (confirm_edit === true) {
                    $.ajax({
                        url: '{{ route("updateTaxonomy") }}',
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "survey_id": survey_id,
                            "question_id": data.id,
                            "update_desc": data.name,
                        },
                        dataType: 'json',
                        beforeSend: function() {},
                        success: function(res) {
                            if (res.status == 200) {
                                alert('Successfully saved!')
                            } else {
                                alert('Error!');
                                let editedNode = questionData.find((node, index) => {
                                    if (node.id == data.id)
                                        return true;
                                })
                                $(`.j-expend[data-id="${data.id}"] input`).val(editedNode.name);
                            }
                        },
                        error: function(request, error) {
                            alert("Request: " + JSON.stringify(request));
                        }
                    });
                }
                callback();
            }
        });

        $('#editWarningModal').modal('show');
        $('.alert').fadeOut();
        $('body .j-moreinfo').click();
    });

    function getChildrenData(data, pid) {
        let existChild = 0
        let retData = [];
        for (let q in data) {
            // console.log(data);
            if (data[q].pid == pid) {
                let tmpNode = {
                    "name": data[q].name,
                    "children": getChildrenData(data, data[q].id)
                };
                retData.push(tmpNode);
            }
        }

        return retData;
    }

    function viewTaxonomy() {
        $('.treeArea').hide();
        $('#taxonomyTable').show();
    }

    $('#updateItem').click(function() {
        let question_id = $('#item_id').val(),
            question_desc = $('#item_desc').val(),
            question_extra = $('#item_extra').val(),
            question_enabled = $('#item_enabled').prop('checked') == true ? 1 : 0,
            question_code = $('#item_code').val(),
            question_desc_alt = $('#item_desc_alt').val(),
            question_extra_alt = $('#item_desc_alt').val(),
            question_sortorder_id = $('#sort_id').val(),
            question_lead_code = $('#lead_code').val(),
            question_prox = $('#item_prox').val();

        if (question_desc == '') {
            $('#editTaxonomyModal .alert').fadeOut();
            $('#editTaxonomyModal .alert-validation').fadeIn();
        } else {
            $.ajax({
                url: '{{ route("updateTaxonomy") }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "question_id": question_id,
                    "update_flag": "AjaxUpdate",
                    "question_enabled": question_enabled,
                    "question_code": question_code,
                    "question_desc": question_desc,
                    "question_desc_alt": question_desc_alt,
                    "question_extra": question_extra,
                    "question_extra_alt": question_extra_alt,
                    "question_proximity_factor": question_prox,
                    "question_sortorder_id": question_sortorder_id,
                    "lead_codes": question_lead_code ,
                },
                dataType: 'json',
                beforeSend: function() {},
                success: function(resp) {
                    if (resp.status == '200') {
                        $('#editTaxonomyModal .alert').fadeOut();
                        $('#editTaxonomyModal .alert-success').fadeIn();
                        $(`.j-expend[data-id="${question_id}"]`).find('input').val(question_desc);
                        var this_pid;
                        // console.log(resp);
                        for (var i in questionData) {
                            if (questionData[i].id == question_id) {
                                questionData[i].name = question_desc;
                                questionData[i].question_code = question_code;
                                questionData[i].question_desc_alt = question_desc_alt;
                                questionData[i].question_enabled = question_enabled;
                                questionData[i].question_extra = question_extra;
                                questionData[i].question_extra_alt = question_extra_alt;
                                questionData[i].question_proximity_factor = question_prox;
                                questionData[i].lead_codes = question_lead_code;
                                this_pid = questionData[i].pid;
                            }
                        }
                        let parentCode = $(`li[data-id="${this_pid}"]`).find('.code_str').html();
                        let nodeCode = parentCode && parentCode != '' ? parentCode + '.' + question_code : question_code;
                        $updatedNode = $(`ul[data-id="${question_id}"]`);
                        $updatedNode.attr('data-id', question_id);
                        $updatedNode.find('.j-expend').attr('data-id', question_id);
                        $updatedNode.find('.input-sm').val(question_desc);
                        $updatedNode.find('.code_str').html(nodeCode);
                        $updatedNode.find('.code').html(question_code);
                        $updatedNode.find('.desc').html(question_extra);
                        setTimeout(() => {
                            $('#editTaxonomyModal').modal('toggle');
                            $('#editTaxonomyModal .alert').fadeOut();
                        }, 1500);
                    } else if (resp.status == '400') {
                        $('#editTaxonomyModal .alert').fadeOut();
                        $('#editTaxonomyModal .alert-error').fadeIn();
                    }
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        }
    });

    $('#addItem').click(function() {
        let question_id = $('#additem_id').val(),
            question_desc = $('#additem_desc').val(),
            question_extra = $('#additem_extra').val(),
            question_enabled = $('#additem_enabled').prop('checked') == true ? 1 : 0,
            question_code = $('#additem_code').val(),
            question_desc_alt = $('#additem_desc_alt').val(),
            question_extra_alt = $('#additem_desc_alt').val(),
            question_prox = $('#additem_prox').val(),
            question_lead_code = $('#lead_code').val();
        if (question_desc == '') {
            $('#addTaxonomyModal .alert').fadeOut();
            $('#addTaxonomyModal .alert-validation').fadeIn();
        } else {
            $.ajax({
                url: '{{ route("createTaxonomy") }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "survey_id": survey_id,
                    "question_id_parent": $('#additem_pid').val(),
                    "question_enabled": question_enabled,
                    "question_code": question_code,
                    "question_desc": question_desc,
                    "question_desc_alt": question_desc_alt,
                    "question_extra": question_extra,
                    "question_extra_alt": question_extra_alt,
                    "question_proximity_factor": question_prox,
                    "lead_codes": question_lead_code ,
                },
                dataType: 'json',
                beforeSend: function() {},
                success: function(resp) {
                    if (resp.status == '200') {
                        $('#createTaxonomyModal .alert').fadeOut();
                        $('#createTaxonomyModal .alert-success').fadeIn();
                        questionData = resp.updatedData;
                        setTimeout(() => {
                            $('#createTaxonomyModal').modal('toggle');
                            $('#createTaxonomyModal .alert').fadeOut();
                            $('#createTaxonomyModal').find('input').val('');
                        }, 1500);
                        let parentCode = $(`li[data-id="${resp.quesiton_id_parent}"]`).find('.code_str').html();
                        let nodeCode = parentCode && parentCode != '' ? parentCode + '.' + resp.question_code : resp.question_code;
                        $newNode = $('ul[data-id=""]');
                        $newNode.attr('data-id', resp.question_id);
                        $newNode.find('.j-expend').attr('data-id', resp.question_id);
                        $newNode.find('.input-sm').val(resp.question_desc);
                        $newNode.find('.code_str').html(nodeCode);
                        $newNode.find('.code').html(resp.question_code);
                        $newNode.find('.desc').html(resp.question_extra);
                    } else if (resp.status == '400') {
                        $('#createTaxonomyModal .alert').fadeOut();
                        $('#createTaxonomyModal .alert-error').fadeIn();
                    }
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            });
        }
    });

    $('#addPage').click(function() {
        var page_title = $('#addpage_title').val();
        var page_desc = $('#addpage_description').val();
        var pid = $('#page_pid').val();
        $.ajax({
            url: "{{ route('createPageForTaxonomy') }}",
            type: "POST",
            data: {
                '_token': '{{ csrf_token() }}',
                'survey_id': survey_id,
                'page_title': page_title,
                'page_desc': page_desc,
                'pid': pid,
            },
            dataType: 'json',
            success: function(res) {
                if (res == 200) {
                    $('#addPageModal').modal('hide');
                    $('#createTaxonomyModal').modal('show');
                }
            }
        });
    });

    $('#createTaxonomyModal').on('hidden.bs.modal', function() {
        $('input.input-sm').each(function() {
            if ($(this).val() == "") {
                $(this).parent().parent().parent().remove();
                console.log('hello')
            }
        });
    });

    function exportExcel() {
        $('#exportModal').modal('show');
        if ($('#taxonomyTable').css('display') == 'none') {
            $('#exportModal h4').html('Exporting Taxonomy Tree...')
            $('.treeArea svg').css('background', 'white');
            source = $('.treeArea svg');
            source.find('.link').css('fill', 'none');
            source.find('.link').css('stroke', '#ccc');
            source.find('.link').css('stroke-width', '1.5px');
            source.find('.node circle').css('stroke', 'steelblue');
            source.find('.node circle').css('stroke-width', '1.5px');
            source.find('.node text').css('font', '10px sans-serif');
            html2canvas(source).then(function(canvas) {
                var a = document.createElement("a");
                a.href = canvas.toDataURL('image/jpeg', 1.0);
                a.download = "Revelation Legal Taxonomy Tree({{ $data['survey']->survey_name }}).png";
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                $('#exportModal').modal('hide');
            });
        } else {
            $('#exportModal h4').html('Exporting Taxonomy Data...')
            $.ajax({
                url: `{{ route('export-taxonomy') }}`,
                method: 'POST',
                data: {
                    "_token": '{{ csrf_token() }}',
                    "survey_id": survey_id,
                    "data_show": moreInfo
                },
                dataType: 'json',
                success: function(res) {
                    var a = document.createElement("a");
                    a.href = res.url;
                    a.download = "Revelation Legal Taxonomy Data({{ $data['survey']->survey_name }}).xlsx";
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    $('#exportModal').modal('hide');
                },
                error: function(request, error) {
                    alert("Request: " + JSON.stringify(request));
                }
            })
        }
    }
</script>
{{-- Taxonomy Tree Chart --}}
<script src="{{ asset('js/d3.v3.min.js') }}"></script>
<script>
    var margin = {
            top: 20,
            right: 120,
            bottom: 20,
            left: 120
        },
        width = 1640 - margin.right - margin.left,
        height = 800 - margin.top - margin.bottom;

    var i = 0,
        duration = 750,
        root;

    var tree = d3.layout.tree()
        .size([height, width]);

    var diagonal = d3.svg.diagonal()
        .projection(function(d) {
            return [d.y, d.x];
        });

    var svg = d3.select("#treemain").append("svg")
        .attr("width", width + margin.right + margin.left)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    flare = {
        "name": "Root",
        "children": treechartData
    }

    root = flare;
    root.x0 = height / 2;
    root.y0 = 0;

    function collapse(d) {
        if (d.children) {
            d._children = d.children;
            d._children.forEach(collapse);
            d.children = null;
        }
    }

    root.children.forEach(collapse);
    update(root);

    d3.select(self.frameElement).style("height", "800px");

    function update(source) {

        // Compute the new tree layout.
        var nodes = tree.nodes(root).reverse(),
            links = tree.links(nodes);

        // Normalize for fixed-depth.
        nodes.forEach(function(d) {
            d.y = d.depth * 300;
        });

        // Update the nodes…
        var node = svg.selectAll("g.node")
            .data(nodes, function(d) {
                return d.id || (d.id = ++i);
            });

        // Enter any new nodes at the parent's previous position.
        var nodeEnter = node.enter().append("g")
            .attr("class", "node")
            .attr("transform", function(d) {
                return "translate(" + source.y0 + "," + source.x0 + ")";
            })
            .on("click", click);

        nodeEnter.append("circle")
            .attr("r", 1e-6)
            .style("fill", function(d) {
                return d._children ? "lightsteelblue" : "#fff";
            });

        nodeEnter.append("text")
            .attr("x", function(d) {
                return d.children || d._children ? -10 : 10;
            })
            .attr("dy", ".35em")
            .attr("text-anchor", function(d) {
                return d.children || d._children ? "end" : "start";
            })
            .text(function(d) {
                return d.name;
            })
            .style("fill-opacity", 1e-6);

        // Transition nodes to their new position.
        var nodeUpdate = node.transition()
            .duration(duration)
            .attr("transform", function(d) {
                return "translate(" + d.y + "," + d.x + ")";
            });

        nodeUpdate.select("circle")
            .attr("r", 4.5)
            .style("fill", function(d) {
                return d._children ? "lightsteelblue" : "#fff";
            });

        nodeUpdate.select("text")
            .style("fill-opacity", 1);

        // Transition exiting nodes to the parent's new position.
        var nodeExit = node.exit().transition()
            .duration(duration)
            .attr("transform", function(d) {
                return "translate(" + source.y + "," + source.x + ")";
            })
            .remove();

        nodeExit.select("circle")
            .attr("r", 1e-6);

        nodeExit.select("text")
            .style("fill-opacity", 1e-6);

        // Update the links…
        var link = svg.selectAll("path.link")
            .data(links, function(d) {
                return d.target.id;
            });

        // Enter any new links at the parent's previous position.
        link.enter().insert("path", "g")
            .attr("class", "link")
            .attr("d", function(d) {
                var o = {
                    x: source.x0,
                    y: source.y0
                };
                return diagonal({
                    source: o,
                    target: o
                });
            });

        // Transition links to their new position.
        link.transition()
            .duration(duration)
            .attr("d", diagonal);

        // Transition exiting nodes to the parent's new position.
        link.exit().transition()
            .duration(duration)
            .attr("d", function(d) {
                var o = {
                    x: source.x,
                    y: source.y
                };
                return diagonal({
                    source: o,
                    target: o
                });
            })
            .remove();

        // Stash the old positions for transition.
        nodes.forEach(function(d) {
            d.x0 = d.x;
            d.y0 = d.y;
        });
    }

    // Toggle children on click.
    function click(d) {
        if (d.children) {
            d._children = d.children;
            d.children = null;
        } else {
            d.children = d._children;
            d._children = null;
        }
        update(d);
    }
</script>


@endsection