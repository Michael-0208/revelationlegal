@extends('layouts.admin')
@section('content')

<div class="container-fluid px-3" id="pdfhidden"> 
    <div class="cont-mtitle flex flex-wrap justify-between items-center">
        <h1 class="text-survey">Edit Help Content </h1>     
</button>
    </div>
    <div class="py-4"> 
        <div class="emailFormArea"> 
           
            <form action="{{route('update-help-data',$helpEdit['id'])}}" method="POST" enctype="multipart/form-data" >
                @csrf
                <div class="form-group flex items-center justify-between">
                    <label for="sender" class="px-0 col-md-5"><span>Page Title</span></label>
                    <input type="text" class="form-control col-md-7" value="{{$helpEdit['name']}}" id="sender" name="name"  >
                </div> 
                <div class="form-group flex items-center">
                    <label for="sender" class="px-0 col-md-5"><span>Add Image [help_image_1]</span></label>
                    <input type="file" name="help_image_1" value="{{(isset($helpImages['helpImage_1'])) ? $helpImages['helpImage_1'] : ''}}" id="helpImage_1">
                </div> 
                <div class="form-group flex items-center">
                    <label for="sender" class="px-0 col-md-5"><span>Add Image [help_image_2]</span></label>
                    <input type="file" name="help_image_2" value="{{(isset($helpImages['helpImage_2']) > 0) ? $helpImages['helpImage_2'] : ''}}" id="helpImage_2">
                </div> 
                
                <div class="form-group">
                    <textarea class="form-control" id="summary-ckeditor" name="course">{{$helpEdit['course']}}</textarea>
                </div>  
              
                <button type="submit" class="revnor-btn float-right">Save</button> 
                <div class="clear-both"></div>
                <p hidden class="alert alert-success">The Content have been updated successfully</p>
            </form>
        </div>
    </div>  
</div>
<script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<script>
CKEDITOR.replace( 'summary-ckeditor' );
CKEDITOR.config.height = '40rem';
</script>

@endsection

 