<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="data()">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RevelationLegal') }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('imgs/favicon.png') }}">  
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/additional-styling.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">

    {{-- <link rel="stylesheet" href="{{ asset('css/reset.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/jquery-treegrid@0.3.0/css/jquery.treegrid.css" rel="stylesheet">
    <link href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css" rel="stylesheet">
    @livewireStyles
    <!-- Scripts -->
    <script src="{{ asset('js/alpine.min.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ asset('js/jspdf.min.js') }}"></script>
    <script src="{{ asset('js/jquery.canvasjs.min.js') }}"></script>
    <script src="{{ asset('js/init-alpine.js') }}"></script>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.treegrid.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/sweetalert.min.css') }}">
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">

    <script>
        $(function() {
            // initialize ajax settings for extending pages so we don't have to do it for every jq ajax
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
        })
    </script>
</head>

<body class="font-sans antialiased">

    @include('partials.header')

    <div class="flex dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }" style="min-height: 100vh;">

        <div class="site-sidebar">@include('partials.user-sidebar')</div>

        <div class="site-content-footer">
            <div class="site-content"> @yield('content')</div>
            <div class="site-footer">
                <div class="row footer-inner"> 
                    <div class="col-12 col-md-6"> 
                        <h1>About Us</h1> 
                        <p>Law firms everywhere are seeking a deeper understanding of their operations. Motivated by mergers, relocations, expansions, financial goals, or changes in the practice, firms have a growing imperative for better information to guide their most critical decisions.
                            <br>
                        In response to this need, ofPartner has developed RevelationLegal, a powerful,web-based analysis designed for the legal environment that providesunprecedented vision into a firm's operation.
                            <br>
                        Understanding how people work is the gateway to innovation.
                        RevelationLegal is the key that unlocks it.</p>
                    </div>
                    <div class="col-12 col-md-2 quick-link-sec">
                        <h2>Quick Links</h2>
                        <ul>
                            <li>><a href="#">Project 1</a></li>
                            <li>><a href="#">Project 2</a></li>
                            <li>><a href="#">Project 3</a></li>
                            <li>><a href="#">Project 4</a></li>
                        </ul>

                    </div>
                    <div class="col-12 col-md-5">
                        <img class="" src="{{asset('imgs/logo-foot.png')}}">
                         <p>© ofPartner LLC, All Rights Reserved. 2022</p>

                    </div>

                </div>
                <div class="site-copyright">
                    <p>© ofPartner LLC, All Rights Reserved. 2022</p>
                </div>
            </div>
        </div>

    </div>
</body>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-treegrid@0.3.0/js/jquery.treegrid.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/treegrid/bootstrap-table-treegrid.min.js"></script>

</html>