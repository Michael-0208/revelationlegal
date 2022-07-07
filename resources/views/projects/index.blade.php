@extends('layouts.admin')
@section('content')
<style>
    th {
        text-align: center;
    }

    td {
        text-align: center;
    }
</style>
<div class="container-fluid">
<div class="project-tab mt-3">
    <nav>
        <div class="nav nav-tabs nav-fill" id="nav-tab">
            <a class="nav-item nav-link active" id="nav-active-tab" data-toggle="tab" href="#nav-active" role="tab" aria-controls="nav-active" aria-selected="true">Currently Active Projects</a>
            <a class="nav-item nav-link" id="nav-inactive-tab" data-toggle="tab" href="#nav-inactive" role="tab" aria-controls="nav-inactive" aria-selected="false">Currently Inactive Projects</a>
        </div>
    </nav>
    <div class="tab-content" id=" nav-tabContent">
        <div class="tab-pane fade show active" id="nav-active" role="tabpanel" aria-labelledby="nav-active-tab">
            <div class="">
                <div class="mb-2">
                    <button class="revnor-btn flex items-center" id="btnAddProject">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;display:inline;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024">
                            <defs />
                            <path d="M464 144H160c-8.8 0-16 7.2-16 16v304c0 8.8 7.2 16 16 16h304c8.8 0 16-7.2 16-16V160c0-8.8-7.2-16-16-16zm-52 268H212V212h200v200zm452-268H560c-8.8 0-16 7.2-16 16v304c0 8.8 7.2 16 16 16h304c8.8 0 16-7.2 16-16V160c0-8.8-7.2-16-16-16zm-52 268H612V212h200v200zm52 132H560c-8.8 0-16 7.2-16 16v304c0 8.8 7.2 16 16 16h304c8.8 0 16-7.2 16-16V560c0-8.8-7.2-16-16-16zm-52 268H612V612h200v200zM424 712H296V584c0-4.4-3.6-8-8-8h-48c-4.4 0-8 3.6-8 8v128H104c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h128v128c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V776h128c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8z" fill="currentColor" />
                        </svg> Add Project
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped"> 
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Cust_1_Label</th>
                                <th>Cust_2_Label</th>
                                <th>Cust_3_Label</th>
                                <th>Cust_4_Label</th>
                                <th>Cust_5_Label</th>
                                <th>Cust_6_Label</th>
                                <th>Survey Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="active_projects_table">
                            @foreach($active_surveys as $survey)
                            <tr id="active-row-{{ $survey->survey_id }}">
                                <td>{{ $survey->survey_name }}</td>
                                <td>{{ $survey->cust_1_label }}</td>
                                <td>{{ $survey->cust_2_label }}</td>
                                <td>{{ $survey->cust_3_label }}</td>
                                <td>{{ $survey->cust_4_label }}</td>
                                <td>{{ $survey->cust_5_label }}</td>
                                <td>{{ $survey->cust_6_label }}</td>
                                <td>{{ $survey->survey_created_dt }}</td>
                                <td class="my-auto">
                                    <button data-survey_id="{{ $survey->survey_id }}" class="table-smallbtn edit-btn text-white btn-revelation-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                            <path d="M18.988 2.012l3 3L19.701 7.3l-3-3zM8 16h3l7.287-7.287l-3-3L8 13z" fill="currentColor" />
                                            <path d="M19 19H8.158c-.026 0-.053.01-.079.01c-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .896-2 2v14c0 1.104.897 2 2 2h14a2 2 0 0 0 2-2v-8.668l-2 2V19z" fill="currentColor" />
                                        </svg></button>
                                    <button data-survey_id="{{ $survey->survey_id }}" class="table-smallbtn deactivate-btn text-white btn-revelation-primary" title="Deactivate"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                            <g fill="none">
                                                <path d="M5.636 5.636a9 9 0 1 1 12.728 12.728A9 9 0 0 1 5.636 5.636z" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="il-md-length-70 il-md-duration-4 il-md-delay-0" />
                                                <path d="M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="il-md-length-25 il-md-duration-2 il-md-delay-5" />
                                            </g>
                                        </svg></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $active_surveys->links() }}
                </div> 
            </div>
        </div>
        <div class="tab-pane fade" id="nav-inactive" role="tabpanel" aria-labelledby="nav-inactive-tab">
            <div class="my-3">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Cust_1_Label</th>
                                <th>Cust_2_Label</th>
                                <th>Cust_3_Label</th>
                                <th>Cust_4_Label</th>
                                <th>Cust_5_Label</th>
                                <th>Cust_6_Label</th>
                                <th>Survey Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="inactive_projects_table">
                            @foreach($inactive_surveys as $survey)
                            <tr id="inactive-row-{{ $survey->survey_id }}">
                                <td>{{ $survey->survey_name }}</td>
                                <td>{{ $survey->cust_1_label }}</td>
                                <td>{{ $survey->cust_2_label }}</td>
                                <td>{{ $survey->cust_3_label }}</td>
                                <td>{{ $survey->cust_4_label }}</td>
                                <td>{{ $survey->cust_5_label }}</td>
                                <td>{{ $survey->cust_6_label }}</td>
                                <td>{{ $survey->survey_created_dt }}</td>
                                <td class="my-auto">
                                    <button data-survey_id="{{ $survey->survey_id }}" class="table-smallbtn activate-btn text-white btn-revelation-primary" title="Activate"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 27 24">
                                            <path d="M24 24H0V0h18.4v2.4h-16v19.2h20v-8.8h2.4V24zM4.48 11.58l1.807-1.807l5.422 5.422l13.68-13.68L27.2 3.318L11.709 18.809z" fill="currentColor" />
                                        </svg></button>
                                    <button data-survey_id="{{ $survey->survey_id }}" class="table-smallbtn remove-btn text-white btn-revelation-primary" title="Remove"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20">
                                            <path d="M12 4h3c.6 0 1 .4 1 1v1H3V5c0-.6.5-1 1-1h3c.2-1.1 1.3-2 2.5-2s2.3.9 2.5 2zM8 4h3c-.2-.6-.9-1-1.5-1S8.2 3.4 8 4zM4 7h11l-.9 10.1c0 .5-.5.9-1 .9H5.9c-.5 0-.9-.4-1-.9L4 7z" fill="currentColor" />
                                        </svg></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $inactive_surveys->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div id="addProjectModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="New Project" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-notify" role="document">
        <div class="modal-content">
            <div class="modal-header text-center rl-modal-header">
                <h5 id="manage_project_title" class="modal-title white-text w-100 font-weight-bold py-2">Add Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 50vh; overflow-y: auto;">
                <input type="hidden" id="survey_id" name="survey_id" value="">
                <div class="md-form mb-2">
                    <label data-error="wrong" data-success="right" for="survey_name">Survey Name</label>
                    <input type="text" id="survey_name" class="form-control validate" placeholder="Survey Name">
                </div>
                <div class="md-form mb-2">
                    <label data-error="wrong" data-success="right" for="cust_1_label">Cust_1_Label</label>
                    <input type="text" id="cust_1_label" class="form-control validate">
                </div>
                <div class="md-form mb-2">
                    <label data-error="wrong" data-success="right" for="cust_2_label">Cust_2_Label</label>
                    <input type="text" id="cust_2_label" class="form-control validate">
                </div>
                <div class="md-form mb-2">
                    <label data-error="wrong" data-success="right" for="cust_3_label">Cust_3_Label</label>
                    <input type="text" id="cust_3_label" class="form-control validate">
                </div>
                <div class="md-form mb-2">
                    <label data-error="wrong" data-success="right" for="cust_4_label">Cust_4_Label</label>
                    <input type="text" id="cust_4_label" class="form-control validate">
                </div>
                <div class="md-form mb-2">
                    <label data-error="wrong" data-success="right" for="cust_5_label">Cust_5_Label</label>
                    <input type="text" id="cust_5_label" class="form-control validate">
                </div>
                <div class="md-form mb-2">
                    <label data-error="wrong" data-success="right" for="cust_6_label">Cust_6_Label</label>
                    <input type="text" id="cust_6_label" class="form-control validate">
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button class="btn rounded waves-effect text-white" id="saveProject" style="background: #008EC1;">Save <i class="fa fa-paper-plane ml-1"></i></button>
            </div>
        </div>
    </div>
</div>
<script>
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
        $('#btnAddProject').click(function() {
            $('#addProjectModal input').val('');
            $('#manage_project_title').html('Add Project');
            $('#addProjectModal').modal('show');
        });

        $('.edit-btn').click(function() {
            let survey_id = $(this).attr('data-survey_id');
            $('#addProjectModal input').val('');
            $('#manage_project_title').html('Edit Project');
            $('#survey_id').val(survey_id);
            $('#survey_name').val($(`#active-row-${survey_id} td:eq(0)`).text()),
                $('#cust_1_label').val($(`#active-row-${survey_id} td:eq(2)`).text()),
                $('#cust_2_label').val($(`#active-row-${survey_id} td:eq(3)`).text()),
                $('#cust_3_label').val($(`#active-row-${survey_id} td:eq(4)`).text()),
                $('#cust_4_label').val($(`#active-row-${survey_id} td:eq(5)`).text()),
                $('#cust_5_label').val($(`#active-row-${survey_id} td:eq(6)`).text()),
                $('#cust_6_label').val($(`#active-row-${survey_id} td:eq(7)`).text()),
                $('#addProjectModal').modal('show');
        });

        $('.deactivate-btn').click(function() {
            let survey_id = $(this).attr('data-survey_id');
            deactivateProject(survey_id);
        });

        $('.activate-btn').click(function() {
            let survey_id = $(this).attr('data-survey_id');
            activateProject(survey_id);
        });

        $('.remove-btn').click(function() {
            let survey_id = $(this).attr('data-survey_id');
            removeProject(survey_id);
        });

        $('#saveProject').click(function() {
            let survey_id = $('#survey_id').val();
            if (survey_id != '') {
                updateProject();
            } else {
                createProject();
            }
        });
    }

    $(document).ready(function() {
        init();
    });

    function createProject() {
        $.ajax({
            url: "{{ route('projects.create') }}",
            type: 'POST',
            data: {
                '_token': '{{ csrf_token() }}',
                'survey_name': $('#survey_name').val(),
                'cust_1_label': $('#cust_1_label').val(),
                'cust_2_label': $('#cust_2_label').val(),
                'cust_3_label': $('#cust_3_label').val(),
                'cust_4_label': $('#cust_4_label').val(),
                'cust_5_label': $('#cust_5_label').val(),
                'cust_6_label': $('#cust_6_label').val(),
            },
            datatType: 'json',
            success: function(res) {
                if (res.status == 200) {
                    $('#active_projects_table').append(`
                            <tr id="active-row-${res.survey_id}">
                                <td>${res.survey_name}</td>
                                <td>${res.creator}</td>
                                <td>${res.cust_1_label}</td>
                                <td>${res.cust_2_label}</td>
                                <td>${res.cust_3_label}</td>
                                <td>${res.cust_4_label}</td>
                                <td>${res.cust_5_label}</td>
                                <td>${res.cust_6_label}</td>
                                <td>${res.created_dt}</td>
                                <td class="my-auto">
                                    <button data-survey_id="${res.survey_id}" class="btn edit-btn text-white btn-revelation-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path d="M18.988 2.012l3 3L19.701 7.3l-3-3zM8 16h3l7.287-7.287l-3-3L8 13z" fill="currentColor"/><path d="M19 19H8.158c-.026 0-.053.01-.079.01c-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .896-2 2v14c0 1.104.897 2 2 2h14a2 2 0 0 0 2-2v-8.668l-2 2V19z" fill="currentColor"/></svg></button>
                                    <button data-survey_id="${res.survey_id}" class="btn deactivate-btn text-white btn-revelation-primary" title="Deactivate"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none"><path d="M5.636 5.636a9 9 0 1 1 12.728 12.728A9 9 0 0 1 5.636 5.636z" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="il-md-length-70 il-md-duration-4 il-md-delay-0"/><path d="M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="il-md-length-25 il-md-duration-2 il-md-delay-5"/></g></svg></button>
                                </td>
                            </tr>`);
                    $('#addProjectModal').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: 'A new survey created successfully.'
                    })
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'An error has been occured while creating.'
                    })
                }
                init();
            },
            error: function(request, error) {
                let errors = request.responseJSON.errors;
                errorHtml = '<ul style="list-style:unset;">';
                for (const i in errors) {
                    errorHtml += `<li>${i}: ${errors[i]}</li>`;
                }
                errorHtml += '</ul>';
                Toast.fire({
                    icon: 'error',
                    title: request.responseJSON.message,
                    html: errorHtml,
                    customClass: {
                        icon: 'toast-icon'
                    }
                })
            }
        });
    }

    function updateProject() {
        $.ajax({
            url: "{{ route('projects.update') }}",
            type: 'POST',
            data: {
                '_token': '{{ csrf_token() }}',
                'survey_id': $('#survey_id').val(),
                'survey_name': $('#survey_name').val(),
                'cust_1_label': $('#cust_1_label').val(),
                'cust_2_label': $('#cust_2_label').val(),
                'cust_3_label': $('#cust_3_label').val(),
                'cust_4_label': $('#cust_4_label').val(),
                'cust_5_label': $('#cust_5_label').val(),
                'cust_6_label': $('#cust_6_label').val(),
            },
            datatType: 'json',
            success: function(res) {
                if (res.status == 200) {
                    $(`#active-row-${res.survey_id}`).html(`
                            <td>${res.survey_name}</td>
                            <td>${res.creator}</td>
                            <td>${res.cust_1_label}</td>
                            <td>${res.cust_2_label}</td>
                            <td>${res.cust_3_label}</td>
                            <td>${res.cust_4_label}</td>
                            <td>${res.cust_5_label}</td>
                            <td>${res.cust_6_label}</td>
                            <td>${res.created_dt}</td>
                            <td class="my-auto">
                                <button data-survey_id="${res.survey_id}" class="btn edit-btn text-white btn-revelation-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path d="M18.988 2.012l3 3L19.701 7.3l-3-3zM8 16h3l7.287-7.287l-3-3L8 13z" fill="currentColor"/><path d="M19 19H8.158c-.026 0-.053.01-.079.01c-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .896-2 2v14c0 1.104.897 2 2 2h14a2 2 0 0 0 2-2v-8.668l-2 2V19z" fill="currentColor"/></svg></button>
                                <button data-survey_id="${res.survey_id}" class="btn deactivate-btn text-white btn-revelation-primary" title="Deactivate"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none"><path d="M5.636 5.636a9 9 0 1 1 12.728 12.728A9 9 0 0 1 5.636 5.636z" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="il-md-length-70 il-md-duration-4 il-md-delay-0"/><path d="M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="il-md-length-25 il-md-duration-2 il-md-delay-5"/></g></svg></button>
                            </td>`);
                    $('#addProjectModal').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: 'A new survey updated successfully.'
                    })
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'An error has been occured while updating.'
                    })
                }
                init();
            },
            error: function(request, error) {
                let errors = request.responseJSON.errors;
                errorHtml = '<ul style="list-style:unset;">';
                for (const i in errors) {
                    errorHtml += `<li>${i}: ${errors[i]}</li>`;
                }
                errorHtml += '</ul>';
                Toast.fire({
                    icon: 'error',
                    title: request.responseJSON.message,
                    html: errorHtml,
                    customClass: {
                        icon: 'toast-icon'
                    }
                })
            }
        });
    }

    function deactivateProject(survey_id) {
        $.ajax({
            url: "{{ route('projects.deactivate') }}",
            type: 'POST',
            data: {
                '_token': '{{ csrf_token() }}',
                'survey_id': survey_id,
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == 200) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'A survey has been deactivated successfully.'
                    });
                    $(`#active-row-${res.survey.survey_id}`).remove();
                    $('#inactive_projects_table').append(`
                            <tr id="inactive-row-${res.survey.survey_id}">
                                <td>${res.survey.survey_name}</td>
                                <td>${res.survey.creator}</td>
                                <td>${res.survey.cust_1_label}</td>
                                <td>${res.survey.cust_2_label}</td>
                                <td>${res.survey.cust_3_label}</td>
                                <td>${res.survey.cust_4_label}</td>
                                <td>${res.survey.cust_5_label}</td>
                                <td>${res.survey.cust_6_label}</td>
                                <td>${res.survey.survey_created_dt}</td>
                                <td class="my-auto">
                                    <button data-survey_id="${res.survey.survey_id}" class="btn activate-btn text-white btn-revelation-primary" title="Activate"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 27 24"><path d="M24 24H0V0h18.4v2.4h-16v19.2h20v-8.8h2.4V24zM4.48 11.58l1.807-1.807l5.422 5.422l13.68-13.68L27.2 3.318L11.709 18.809z" fill="currentColor"/></svg></button>
                                    <button data-survey_id="${res.survey.survey_id}" class="btn remove-btn text-white btn-revelation-primary" title="Remove"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><path d="M12 4h3c.6 0 1 .4 1 1v1H3V5c0-.6.5-1 1-1h3c.2-1.1 1.3-2 2.5-2s2.3.9 2.5 2zM8 4h3c-.2-.6-.9-1-1.5-1S8.2 3.4 8 4zM4 7h11l-.9 10.1c0 .5-.5.9-1 .9H5.9c-.5 0-.9-.4-1-.9L4 7z" fill="currentColor"/></svg></button>
                                </td>
                            </tr>`);
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'An error has been occured while deactivating.'
                    });
                }
                init();
            },
            error: function(request, error) {
                let errors = request.responseJSON.errors;
                errorHtml = '<ul style="list-style:unset;">';
                for (const i in errors) {
                    errorHtml += `<li>${i}: ${errors[i]}</li>`;
                }
                errorHtml += '</ul>';
                Toast.fire({
                    icon: 'error',
                    title: request.responseJSON.message,
                    html: errorHtml,
                    customClass: {
                        icon: 'toast-icon'
                    }
                })
            }
        });
    }

    function activateProject(survey_id) {
        $.ajax({
            url: "{{ route('projects.activate') }}",
            type: 'POST',
            data: {
                '_token': '{{ csrf_token() }}',
                'survey_id': survey_id,
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == 200) {
                    Toast.fire({
                        icon: 'info',
                        title: 'A survey has been activated successfully.'
                    });
                    $(`#inactive-row-${res.survey.survey_id}`).remove();
                    $('#active_projects_table').append(`
                            <tr id="active-row-${res.survey.survey_id}">
                                <td>${res.survey.survey_name}</td>
                                <td>${res.survey.creator}</td>
                                <td>${res.survey.cust_1_label}</td>
                                <td>${res.survey.cust_2_label}</td>
                                <td>${res.survey.cust_3_label}</td>
                                <td>${res.survey.cust_4_label}</td>
                                <td>${res.survey.cust_5_label}</td>
                                <td>${res.survey.cust_6_label}</td>
                                <td>${res.survey.survey_created_dt}</td>
                                <td class="my-auto">
                                    <button data-survey_id="${res.survey.survey_id}" class="btn edit-btn text-white btn-revelation-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path d="M18.988 2.012l3 3L19.701 7.3l-3-3zM8 16h3l7.287-7.287l-3-3L8 13z" fill="currentColor"/><path d="M19 19H8.158c-.026 0-.053.01-.079.01c-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .896-2 2v14c0 1.104.897 2 2 2h14a2 2 0 0 0 2-2v-8.668l-2 2V19z" fill="currentColor"/></svg></button>
                                    <button data-survey_id="${res.survey.survey_id}" class="btn deactivate-btn text-white btn-revelation-primary" title="Deactivate"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none"><path d="M5.636 5.636a9 9 0 1 1 12.728 12.728A9 9 0 0 1 5.636 5.636z" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="il-md-length-70 il-md-duration-4 il-md-delay-0"/><path d="M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="il-md-length-25 il-md-duration-2 il-md-delay-5"/></g></svg></button>
                                </td>
                            </tr>`);
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'An error has been occured while activating.'
                    });
                }
                init();
            },
            error: function(request, error) {
                let errors = request.responseJSON.errors;
                errorHtml = '<ul style="list-style:unset;">';
                for (const i in errors) {
                    errorHtml += `<li>${i}: ${errors[i]}</li>`;
                }
                errorHtml += '</ul>';
                Toast.fire({
                    icon: 'error',
                    title: request.responseJSON.message,
                    html: errorHtml,
                    customClass: {
                        icon: 'toast-icon'
                    }
                })
            }
        });
    }

    function removeProject(survey_id) {
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
                    url: "{{ route('projects.delete') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'survey_id': survey_id,
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 200) {
                            $(`#inactive-row-${survey_id}`).remove();
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
                    error: function(request, error) {
                        let errors = request.responseJSON.errors;
                        errorHtml = '<ul style="list-style:unset;">';
                        for (const i in errors) {
                            errorHtml += `<li>${i}: ${errors[i]}</li>`;
                        }
                        errorHtml += '</ul>';
                        Toast.fire({
                            icon: 'error',
                            title: request.responseJSON.message,
                            html: errorHtml,
                            customClass: {
                                icon: 'toast-icon'
                            }
                        })
                    }
                });
            }
        })
    }
</script>
@endsection