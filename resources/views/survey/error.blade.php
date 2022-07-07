@extends('layouts.user_survey')

@section('content')
    <div class="container py-5 my-5">
        @if(session('error'))
            <script>
                Swal.fire({
                    title: 'Oops, something went wrong',
                    text: '{{ session('error') }}',
                    icon: 'error',
                }).then(() => window.location.href = '/');
            </script>
        @else
            <script>
                Swal.fire({
                    title: 'Session Timed Out',
                    text: 'It appears you session has timed out, please try the survey link sent to you to reactivate the questionnaire',
                    icon: 'warning',
                }).then(() => window.location.href = '/');
            </script>
        @endif
    </div>
@endsection
