@extends('layouts.admin')
@section('content')
<div class="w-100 d-flex justify-content-center my-5">
	@if (isset($data['success']))
		<p class="alert alert-success">{{$data['success']}}</p>
	@endif
    <form action="{{route('update-help-guide')}}" method="POST" enctype="multipart/form-data">
		@csrf
        <div class="form-group">
            <label for="exampleInputEmail1">Real Estate Setup</label>
            <input type="file" class="form-control" id="exampleInputEmail1" name="1" aria-describedby="emailHelp"
                placeholder="Enter email">
            <small id="emailHelp" class="form-text text-muted">Upload Real estate setup file</small>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">User Guide</label>
            <input type="file" name="2" class="form-control" id="exampleInputPassword1" placeholder="Password">
			<small id="emailHelp" class="form-text text-muted">Upload User Guide</small>
        </div>
        <div class="form-group">
            <input type="submit" class="form-control" style="    background-color: #008cc2;" id="exampleInputPassword1" value="Upload File">
        </div>
    </form>
</div>
@endsection
