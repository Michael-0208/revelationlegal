@extends('layouts.admin')
@section('content')
<!--  -->
{{-- {{dd($active_users)}} --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @if(\Auth::check() && \Auth::user()->is_admin)
    <div class="container-fluid">
        <div class="mt-2" style="display: none;">
            <div class="row alert alert-dismissible" id="message_flash_row" style="display: none;">
                <div class="col-12">
                    <h3 id="message_flash_text"></h3>
                </div>
            </div>
        </div>
        <div class="project-tab mt-3"> 
            <nav>
                <div class="nav nav-tabs nav-fill" id="nav-tab">
                    <a class="nav-item nav-link active" id="nav-active-tab" data-toggle="tab" href="#nav-active" role="tab" aria-controls="nav-active" aria-selected="true">All Currently Active Users</a>
                    <a class="nav-item nav-link" id="nav-inactive-tab" data-toggle="tab" href="#nav-inactive" role="tab" aria-controls="nav-inactive" aria-selected="false">Currently Inactive Users</a>
                </div>
            </nav>
            <div class="tab-content" id=" nav-tabContent">
                <div class="tab-pane fade show active" id="nav-active" role="tabpanel" aria-labelledby="nav-active-tab">
                    <div class="    ">
                        <div class="mb-2">
                            <button class="revnor-btn flex items-center" id="btnAddUser">
                                <svg style="display: inline;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><rect x="0" y="0" width="20" height="20" fill="none" stroke="none" /><path d="M15.989 19.129C16 17 13.803 15.74 11.672 14.822c-2.123-.914-2.801-1.684-2.801-3.334c0-.989.648-.667.932-2.481c.12-.752.692-.012.802-1.729c0-.684-.313-.854-.313-.854s.159-1.013.221-1.793c.064-.817-.398-2.56-2.301-3.095c-.332-.341-.557-.882.467-1.424c-2.24-.104-2.761 1.068-3.954 1.93c-1.015.756-1.289 1.953-1.24 2.59c.065.78.223 1.793.223 1.793s-.314.17-.314.854c.11 1.718.684.977.803 1.729c.284 1.814.933 1.492.933 2.481c0 1.65-.212 2.21-2.336 3.124C.663 15.53 0 17 .011 19.129C.014 19.766 0 20 0 20h16s-.014-.234-.011-.871zM17 10V7h-2v3h-3v2h3v3h2v-3h3v-2h-3z" fill="currentColor"/></svg> Add User
                            </button>
                        </div>
                        <div class="table-responsive admin-tables">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Last Login</th>
                                        <th>Project Access</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="active_user_table">
                                    @foreach($active_users as $user)
                                        <tr>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->first_name }}</td>
                                            <td>{{ $user->last_name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ Carbon\Carbon::parse($user->last_login)->format('F d, Y h:ma') }}</td>
                                            @if($user->is_admin ==1)
                                            <td>All Projects</td>
                                            @else
                                            {{-- <td>{!! $user->allowedProjects !!}</td> --}}
                                            @php 
                                                $assignedSurvey = [];
                                                if($user->survey_assign != ''){
                                                    $assignedSurvey = explode(',',$user->survey_assign);
                                                    
                                                    $surveyNameArr = App\Models\Survey::whereIn('survey_id',$assignedSurvey)->pluck('survey_name')->toArray();
                                                    // dd($surveyNameArr)
                                                    $surveyName = implode(",",$surveyNameArr);


                                                    

                                                }else{
                                                    $surveyName = 'No survey assigned';
                                                }
                                                // dd($assignedSurvey);
                                            @endphp
                                                
                                                    
                                                    <td>{{$surveyName}}</td>
                                            @endif
                                           
                                            <td class="my-auto">
                                                <button id="active_user_{{ $user->id }}" data-user_id="{{ $user->id }}" class=" table-smallbtn deactivate-btn text-white btn-revelation-primary" title="Deactivate"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                                    <g fill="none">
                                                        <path d="M5.636 5.636a9 9 0 1 1 12.728 12.728A9 9 0 0 1 5.636 5.636z" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="il-md-length-70 il-md-duration-4 il-md-delay-0" />
                                                        <path d="M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="il-md-length-25 il-md-duration-2 il-md-delay-5" />
                                                    </g>
                                                </svg></button>
                                                <button id="edit_user_{{ $user->id }}" data-user_id="{{ $user->id }}" class=" fas fa-edit mx-auto table-midbtn btn-revelation-primary" title="Edit User"></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $active_users->links() }}
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-inactive" role="tabpanel" aria-labelledby="nav-inactive-tab">
                    <div class="m-3">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Deactivated At</th>
                                        <th>Project Access</th>
                                        <th colspan="2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="inactive_user_table">
                                    @foreach($inactive_users as $user)
                                        <tr>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->first_name }}</td>
                                            <td>{{ $user->last_name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ Carbon\Carbon::parse($user->deleted_at)->format('F d, Y h:ma') }}</td>
                                            <td>{!! $user->allowedProjects !!}</td>
                                            <td>
                                                <button id="inactive_user_{{ $user->id }}" data-user_id="{{ $user->id }}" class="table-smallbtn activate-btn text-white btn-revelation-primary" title="Activate"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 27 24">
                                                    <path d="M24 24H0V0h18.4v2.4h-16v19.2h20v-8.8h2.4V24zM4.48 11.58l1.807-1.807l5.422 5.422l13.68-13.68L27.2 3.318L11.709 18.809z" fill="currentColor" />
                                                </svg></button>
                                            </td class="my-auto">
                                            <td class="my-auto">
                                                <button id="delete_user_{{ $user->id }}" data-user_id="{{ $user->id }}" class="delete-btn clred text-white btn-revelation-primary table-smallbtn" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20">
                                                    <path d="M12 4h3c.6 0 1 .4 1 1v1H3V5c0-.6.5-1 1-1h3c.2-1.1 1.3-2 2.5-2s2.3.9 2.5 2zM8 4h3c-.2-.6-.9-1-1.5-1S8.2 3.4 8 4zM4 7h11l-.9 10.1c0 .5-.5.9-1 .9H5.9c-.5 0-.9-.4-1-.9L4 7z" fill="currentColor" />
                                                </svg></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- {{ $inactive_users->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" tabindex="-1" role="dialog" id="addNewUser" aria-labelledby="New User" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-notify modal-warning" role="document">
                <!--Content-->
                <div class="modal-content">
                    <!--Header-->
                    <div class="modal-header text-center rl-modal-header">
                        <h4 id="manage_user_title" class="modal-title white-text w-100 font-weight-bold py-2">Add User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="white-text">&times;</span>
                        </button>
                    </div>

                    <!--Body-->
                    <div class="modal-body" style="height: 50vh; overflow-y: auto;">
                        <input type="hidden" id="user_id" name="user_id" value="">
                        <div class="md-form mb-2">
                            <label data-error="wrong" data-success="right" for="first_name">First Name</label>
                            <input type="text" id="first_name" class="form-control validate" placeholder="First Name">
                        </div>
                        <div class="md-form mb-2">
                            <label data-error="wrong" data-success="right" for="last_name">Last Name</label>
                            <input type="text" id="last_name" class="form-control validate" placeholder="Last Name">
                        </div>
                        <div class="md-form mb-2">
                            <label data-error="wrong" data-success="right" for="email">Email</label>
                            <input type="email" id="email" class="form-control validate" placeholder="Email">
                        </div>
                        <div class="md-form mb-2">
                            <label data-error="wrong" data-success="right" for="username">Username</label>
                            <input type="text" id="username" class="form-control validate" autocomplete="off" placeholder="Username">
                        </div>
                        <div class="md-form mb-2">
                            <label data-error="wrong" data-success="right" for="password">Password</label>
                            <input type="password" id="password" class="form-control validate" autocomplete="off" placeholder="Password - Leave empty to keep current password">
                        </div>
                        <hr>

                        <h5>Projects (optional)</h5>
                        <select id="projects" name="projects[]" multiple="multiple" class="form-control mb-1" style="display: block;">
                            @foreach(App\Models\Survey::all()->sortBy('survey_name') as $project)
                                <option value="{{ $project->survey_id }}">{{ $project->survey_name }}</option>
                            @endforeach
                        </select>

                       {{--  <h5 class="mt-2">Allowed Departments</h5>
                        <select id="departments" name="departments[]" multiple="multiple" class="form-control" style="display: block;">
                            @foreach(App\Models\Department::all()->unique('name')->sortBy('name') as $department)
                                <option value="{{ $department->name }}">{{ $department->name }}</option>
                            @endforeach
                        </select>

                        <h5 class="mt-2">Allowed Locations</h5>
                        <select id="locations" name="locations[]" multiple="multiple" class="form-control" style="display: block;">
                            @foreach(App\Models\SupportLocation::all()->unique('support_location_desc')->sortBy('support_location_desc') as $location)
                                <option value="{{ $location->support_location_desc }}">{{ $location->support_location_desc }}</option>
                            @endforeach
                        </select>

                        <h5 class="mt-2">Project Permissions</h5>
                        <select id="permissions" name="permissions[]" multiple="multiple" class="form-control" style="display: block;">
                            @foreach(App\Models\Permission::all()->unique('name')->sortBy('name') as $permission)
                                <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                            @endforeach
                        </select> --}} 
                    </div>

                    <!--Footer-->
                    <div class="modal-footer justify-content-center">
                        <button class="btn rounded waves-effect text-white" id="saveUser" style="background: #008EC1;">Save <i class="fa fa-paper-plane ml-1"></i></button>
                    </div>
                </div>
                <!--/.Content-->
            </div>
        </div>
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

    @if(\Auth::check() && \Auth::user()->is_admin)
        <script type="text/javascript">
             $(document).on('click', '[id^="edit_user_"]', function() {
                $('#manage_user_title').text('Edit User');
                loadUser($(this).data('user_id'));
            });

            function loadUser(userId) {
                showLoader();
                clearUserForm();
                $.get('{{url("/")}}/users/fetchForAdmin/' + userId)
                    .done(function(data) {
                        console.log(data.surveys);
                        $('#first_name').val(data.first_name);
                        $('#last_name').val(data.last_name);
                        $('#email').val(data.email);
                        $('#username').val(data.username);
                        $('#password').val(data.password);
                        $('#user_id').val(data.id);
                        $("#addNewUser").modal('show');
                        // Initialize select2
                        $('#departments').empty();
                        $('#permissions').empty();
                        $('#locations').empty();
                        $('#projects').empty();
                        
                        $('#projects').select2({
                            data: data.surveys,
                            width: '100%'
                        });
                        
                        /* $('#departments').select2({
                            data: data.departments,
                            width: '100%'
                        });

                        $('#permissions').select2({
                            data: data.permissions,
                            width: '100%'
                        });

                        $('#locations').select2({
                            data: data.locations,
                            width: '100%'
                        }); */

                        hideLoader();
                    })
                    .fail(handleError);
            }

            function clearUserForm() {
                $('#projects').val(null).trigger('change')
                $('#first_name').val('');
                $('#last_name').val('');
                $('#email').val('');
                $('#username').val('');
                $('#password').val('');
                $('#user_id').val('');
            }


            var projects_data ;
            $(function(){
                projects_data    = $('#projects').html();
               /*  departments_data = $('#departments').html();
                locations_data   = $('#locations').html();
                permissions_data = $('#permissions').html(); */

                $('#btnAddUser').on('click', function () {
                    $('#addNewUser').modal('show');
                    $('#addNewUser input').val('');
                    
                    // Initialize select2
                    //$('#projects').html(projects_data);
                   /*  $('#departments').html(departments_data);
                    $('#permissions').html(permissions_data);
                    $('#locations').html(locations_data); */

                    $('#projects').select2({ width: '100%'});
                   /*  $('#departments').select2({ width: '100%'});
                    $('#permissions').select2({ width: '100%'});
                    $('#locations').select2({ width: '100%'}); */
                });

                $('#saveUser').on('click', function(){
                    saveUser();
                });

                //** DEACTIVATE USER CODE **//
                $(document).on('click', '.deactivate-btn', function(){
                    const id = $(this).data('user_id');
                    showLoader();
                    $.post('{{url("/")}}/all-users/deactivate', { user_id: id })
                        .done(function(data) {
                            hideLoader();

                            if(data.error) {

                                Swal.fire({
                                    title: 'Permissions Required',
                                    text: 'You do not have permission to perform this action.',
                                    icon: 'error',
                                    confirmButtonText: 'OK',
                                });

                            } else {
                                $('#active_user_' + id).parents('tr').fadeOut(300, 'linear', function(){
                                    $(this).remove();

                                    const lastLogin = new Date(data.deleted_at);
                                    let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(lastLogin);
                                    let mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(lastLogin);
                                    let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(lastLogin);
                                    let time = new Intl.DateTimeFormat('en', { hour: 'numeric', minute: 'numeric' }).format(lastLogin);

                                    $('#inactive_user_table').append(`
                                        <tr>
                                            <td>${data.username}</td> 
                                            <td>${data.first_name}</td>
                                            <td>${data.last_name}</td>
                                            <td>${data.email}</td>
                                            <td>${mo} ${da}, ${ye} ${time}</td>
                                            <td>${data.allowedProjects}</td>
                                            <td class="my-auto">
                                                <button id="inactive_user_${data.id}" data-user_id="${data.id}" class="table-smallbtn activate-btn text-white btn-revelation-primary" title="Activate"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 27 24">
                                                    <path d="M24 24H0V0h18.4v2.4h-16v19.2h20v-8.8h2.4V24zM4.48 11.58l1.807-1.807l5.422 5.422l13.68-13.68L27.2 3.318L11.709 18.809z" fill="currentColor" />
                                                </svg></button>
                                            </td>
                                            <td class="my-auto">
                                                <button id="delete_user_${data.id}" data-user_id="${data.id}" class="delete-btn clred text-white btn-revelation-primary table-smallbtn" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20">
                                                    <path d="M12 4h3c.6 0 1 .4 1 1v1H3V5c0-.6.5-1 1-1h3c.2-1.1 1.3-2 2.5-2s2.3.9 2.5 2zM8 4h3c-.2-.6-.9-1-1.5-1S8.2 3.4 8 4zM4 7h11l-.9 10.1c0 .5-.5.9-1 .9H5.9c-.5 0-.9-.4-1-.9L4 7z" fill="currentColor" />
                                                </svg></button>
                                            </td>
                                        </tr>
                                    `);
                                })
                            }
                        })
                        .catch(function(data){
                            console.log(data);
                            hideLoader();
                        })
                });

                //** ACTIVATE USER CODE **//
                $(document).on('click', '.activate-btn', function(){
                    const id = $(this).data('user_id');
                    showLoader();
                    $.post(`{{url("/")}}/all-users/activate`, { user_id: id })
                        .done(function(data){
                            hideLoader();

                            if(data.error) {

                            Swal.fire({
                                title: 'Permissions Required',
                                text: 'You do not have permission to perform this action.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                            });

                            } else {
                                $('#inactive_user_' + id).parents('tr').fadeOut(300, 'linear', function(){
                                    $(this).remove();


                                    const lastLogin = new Date(data.last_login);
                                    let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(lastLogin);
                                    let mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(lastLogin);
                                    let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(lastLogin);
                                    let time = new Intl.DateTimeFormat('en', { hour: 'numeric', minute: 'numeric' }).format(lastLogin);

                                    $('#active_user_table').append(`
                                        <tr>
                                            <td>${data.username}</td>
                                            <td>${data.first_name}</td>
                                            <td>${data.last_name}</td>
                                            <td>${data.email}</td>
                                            <td>${mo} ${da}, ${ye} ${time}</td>
                                            <td>${data.allowedProjects}</td>
                                            <td class="my-auto">
                                                <button id="active_user_${data.id}" data-user_id="${data.id}" class="btn deactivate-btn text-white btn-revelation-primary">Deactivate</button>
                                            </td>
                                        </tr>
                                    `);
                                })
                            }
                        })
                        .catch(function(data){
                            console.log(data);
                            hideLoader();
                        })
                });

                //** DELETE USER CODE **//
                $(document).on('click', '.delete-btn', function() {
                    const id = $(this).data('user_id');
                    
                    //showLoader();
                    Swal.fire({
                        title: 'Do you want to Delete this User?',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Delete',
                        denyButtonText: `No`,
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            
                            $.post(`{{url('/')}}/all-users/delete`, {user_id: id}).done(function(data) {
                                

                                if (data.error) {

                                    Swal.fire({
                                        title: 'Permissions Required',
                                        text: 'You do not have permission to perform this action.',
                                        icon: 'error',
                                        confirmButtonText: 'OK',
                                    });

                                } else {
                                    $('#delete_user_' + id).parents('tr').fadeOut(300, 'linear', function() {
                                        $(this).remove();
                                    })
                                }
                            })
                            
                        } else if (result.isDenied) {
                            Swal.fire('Changes are not saved', '', 'info')
                        }
                    })

                    .catch(function(data) {
                        console.log(data);
                        //hideLoader();
                    })
                });               

                $('#projects').select2({ width: '100%'});
                $('#departments').select2({ width: '100%'});
                $('#permissions').select2({ width: '100%'});
                $('#locations').select2({ width: '100%'});

            });

            function saveUser()
            {
                const first_name = $('#first_name').val();
                const last_name  = $('#last_name').val();
                const email      = $('#email').val();
                const username   = $('#username').val();
                const password   = $('#password').val();
                let projects     = [];
               /*  let permissions  = [];
                let departments  = [];
                let locations    = []; */
                const userId     = $('#user_id').val();
                const fromAdmin = 1;


                $.each($('#projects').select2('data'), function(){
                    projects.push(this.id);
                });

                /* $.each($('#departments').select2('data'), function(){
                    departments.push(this.text);
                });

                $.each($('#permissions').select2('data'), function(){
                    permissions.push(this.text);
                });

                $.each($('#locations').select2('data'), function(){
                    locations.push(this.text);
                }); */

                if(!first_name) {
                    Swal.fire({ title: 'Missing Field', text: 'First name is required.' }, function(){ setTimeout(function(){ $('#first_name').focus(); }) });
                    return;
                } else if(!last_name) {
                    Swal.fire({ title: 'Missing Field', text: 'Last name is required.' }, function(){ setTimeout(function(){ $('#last_name').focus(); }) });
                    return;
                } else if(!first_name) {
                    Swal.fire({ title: 'Missing Field', text: 'Email address is required.' }, function(){ setTimeout(function(){ $('#email').focus(); }) });
                    return;
                } else if(!username) {
                    Swal.fire({ title: 'Missing Field', text: 'Username is required.' }, function(){ setTimeout(function(){ $('#username').focus(); }) });
                    return;
                } else if(!password) {
                    if (!userId) {
                        Swal.fire({ title: 'Missing Field', text: 'Password is required.' }, function(){ setTimeout(function(){ $('#password').focus(); }) });
                        return;
                    }
                };

                showLoader();

                if(userId)
                    updateUser(first_name, last_name, email, password, username, projects, userId, fromAdmin);
                else
                    createUser(first_name, last_name, email, password, username, projects, fromAdmin);
            }

            function createUser(first_name, last_name, email, password, username, projects, fromAdmin)
            {
                $.post('{{url("/")}}/users/create', { first_name, last_name, email, password, username,fromAdmin, projects, 'page': 'all_users' })
                    .done(function(data){
                        hideLoader();
                        $("#addNewUser").modal('hide');
                        Swal.fire("User Created", "This user is registered successfully." , "success");
                        addUserToTable(data)
                    })
                    .fail(handleError);
            }

            function handleError(data, text, error)
            {

                hideLoader();
                let messages = JSON.parse(data.responseText);
                let str_msg = 'The following errors occured. Please adjust and try again.\r\n';
                for(const message in messages.errors)
                {
                    str_msg += messages.errors[message][0] + '\r\n';
                }

                Swal.fire({
                    title: "Oops, something went wrong",
                    text: str_msg,
                    icon: "error",
                    button: "OK",
                });
            }

            function addUserToTable(data)
            {
                const lastLogin = new Date(data.last_login);
                let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(lastLogin);
                let mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(lastLogin);
                let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(lastLogin);
                let time = new Intl.DateTimeFormat('en', { hour: 'numeric', minute: 'numeric' }).format(lastLogin);

                $('#active_user_table').append(`
                    <tr>
                        <td>${data.username}</td>
                        <td>${data.first_name}</td>
                        <td>${data.last_name}</td>
                        <td>${data.email}</td>
                        <td>${mo} ${da}, ${ye} ${time}</td>
                        <td>${data.allowedProjects}</td>
                        <td class="my-auto">
                            <button id="active_user_${data.id}" data-user_id="${data.id}" class="btn deactivate-btn text-white btn-revelation-primary">Deactivate</button>
                        </td>
                    </tr>
                `);
            }

        function updateUser(first_name, last_name, email, password, username, survey_id, userId, fromAdmin) {
        $.post('{{url("/")}}/users/update/' + userId, {
                first_name,
                last_name,
                email,
                password,
                username,
                survey_id,
                fromAdmin
            })
            .done(function(data) {
                hideLoader();
                $("#addNewUser").modal('hide');
                Swal.fire({
                        title: "User Updated",
                        text: "Press OK to reload changes.",
                        icon: "success"
                    })
                    .then(function() {
                        window.location.reload();
                    }, 100)
            })
            .fail(handleError);

    }
        </script>
    @endif

    @include('partials.loader')
@endsection
