@extends('layouts.admin')
@section('content')

<div class="container px-3" id="pdfhidden"> 
    <div class="py-4" style="text-align: center;">  
    <div class="tab-pane fade show active help_desk_table" id="nav-active" role="tabpanel" aria-labelledby="nav-active-tab">
                <div class="table-responsive">
                    <table class="table table-striped"> 
                        <thead>
                            <tr>
                                <th style="width: 25%">Page</th>
                                
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach($helpPages as $pages)

                            <tr id="active-row-45">
                                <td>{{$pages['name']}}</td>
                                <td class="my-auto">
                                    <button data-survey_id="45" class="table-smallbtn edit-btn text-white btn-revelation-primary" title="Edit">
                                    <a style="text-decoration:none; color:#fff" href="{{route('edit-helpdesk',$pages['id'])}}">    
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                    
                                            <path d="M18.988 2.012l3 3L19.701 7.3l-3-3zM8 16h3l7.287-7.287l-3-3L8 13z" fill="currentColor"></path>
                                            <path d="M19 19H8.158c-.026 0-.053.01-.079.01c-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .896-2 2v14c0 1.104.897 2 2 2h14a2 2 0 0 0 2-2v-8.668l-2 2V19z" fill="currentColor"></path>
                                        </svg></a></button>
                                    <!-- <button data-survey_id="45" class="table-smallbtn deactivate-btn text-white btn-revelation-primary" title="Deactivate"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                            <g fill="none">
                                                <path d="M5.636 5.636a9 9 0 1 1 12.728 12.728A9 9 0 0 1 5.636 5.636z" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="il-md-length-70 il-md-duration-4 il-md-delay-0"></path>
                                                <path d="M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="il-md-length-25 il-md-duration-2 il-md-delay-5"></path>
                                            </g>
                                        </svg></button>  --> 
                                </td>
                            </tr>
                            @endforeach                                                     
                        </tbody>


                    </table>

                </div> 
        </div>
    </div>  
</div>
<script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<script>
CKEDITOR.replace( 'summary-ckeditor' );
</script>

@endsection

 