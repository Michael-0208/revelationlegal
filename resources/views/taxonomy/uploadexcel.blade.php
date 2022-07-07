<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload Taxanomy</title>
</head>
<body>
    <form enctype="multipart/form-data" method="POST" action="{{route('taxanomy-upload')}}">
        @csrf
        <input type="file" name="exceldata" id="uploadTaxanomy">
        <input type="submit" value="Upload Data">
    </form>
</body>
</html>