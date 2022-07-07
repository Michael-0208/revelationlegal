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
    <script src="{{ asset('js/jquery-3.6.0.js') }}"></script>

    <!-- Styles -->

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <script src="{{ asset('js/jquery-3.2.1.slim.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>



    @livewireStyles

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/jquery.canvasjs.min.js')}}"></script>
    
    @livewireScripts
    <script src="{{ asset('js/init-alpine.js') }}"></script>
</head>

<body class="font-sans antialiased">
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        <!-- Desktop sidebar-->
        <aside style="display: none;" class="z-20 w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0">
            <div class="py-4 text-gray-500 dark:text-gray-400">
                <ul class="mt-20">
                    <li class="">
                        <a class="inline-flex items-center w-full text-sm font-semibold text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100" href="index.html">
                            <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="ml-4">Projects</span>
                        </a>
                    </li>
                </ul>

            </div> 
        </aside>
        <!-- Mobile sidebar --> 
        <!-- Backdrop -->
        <div x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"></div>
        <aside class="admin-sidebar mobile-sidebar fixed   md:hidden" x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150" x-transition:enter-start="opacity-0 transform -translate-x-20" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 transform -translate-x-20" @click.away="closeSideMenu" @keydown.escape="closeSideMenu">
            <div class="py-4 text-gray-500 dark:text-gray-400">
                <ul class="">
                    <li class="">
                        <!-- <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span> 
                        <a class="inline-flex items-center w-full text-sm font-semibold text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100" href="index.html">
                            <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="ml-4">Projects</span>
                        </a>-->
                        @if(\Auth::check() && \Auth::user()->is_admin)
                        <li class="border-b md:border-none">
                            <a class="block md:inline-block @if(Route::currentRouteName() == 'users.all') active @endif" href="{{ route('users.all') }}">All Users</a>
                        </li>
                        @endif
                        
                        <li class="border-b md:border-none">
                            <a id='myaccuser' class="block md:inline-block  @if(Route::currentRouteName() == 'profile.show') active @endif" href="{{ route('profile.show') }}">My Account</a>
        
                        </li>
                        <li class="border-b md:border-none">
                            <!-- <a class="block md:inline-block  @if(Route::currentRouteName() == 'user-guide') active @endif" href="{{ route('user-guide')}}">User Guide</a> -->
                            <a class="block md:inline-block hhhhh" download="Revelation Legal Documentation( User Guide).pdf" href="{{url('/public/user-guide.pdf')}}">User Guide</a> 
        
                        </li>
                        <li class="border-b md:border-none">
                            <a class="block md:inline-block @if(Route::currentRouteName() == 'support.index') active @endif" href="{{ route('support.index') }}">Support</a>
                        </li>
                        <li class=" md:border-none">
                            <a class="block md:inline-block @if(Route::currentRouteName() == 'logout') active @endif" href="{{ route('logout') }}">Log Out({{request()->user()->first_name}})</a>
                        </li>
                    </li>
                </ul>

            </div>
        </aside>
        <div class="indexwelcome-page flex flex-col flex-1 w-full  ">
            <header class="site-header">
                <div class="header-inner flex items-center justify-between px-3 mx-auto">
                    <!-- Mobile hamburger -->
                    <button style="color: #fff;" class="menuopen-btn p-1 mr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-purple" @click="toggleSideMenu" aria-label="Menu">
                        <svg class="w-8 h-8" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div class="site-logo mr-auto"><img class="" src="{{asset('imgs/revel-logo.png')}}"></div>
                    <div class="site-menu d-none d-md-block">
                        <ul class="menu border-b md:border-none flex justify-end items-center list-reset w-full md:w-auto" style="margin-bottom: 0;">
                            <li class="border-t md:border-none">
                                <a class="block md:inline-block @if(Route::currentRouteName() == 'projects') active @endif" href="{{route('projects')}}">Projects</a>
                            </li>
                            @if(\Auth::check() && \Auth::user()->is_admin)
                            <li class="border-t md:border-none">
                                <a class="block md:inline-block @if(Route::currentRouteName() == 'users.all') active @endif hover:text-gray-800" href="{{ route('users.all') }}">
                                    All Users
                                </a>
                            </li>
                            @endif
                            
                            <li class="border-t md:border-none">
                                <a id='myaccuser' class="block md:inline-block  @if(Route::currentRouteName() == 'profile.show') active @endif" href="{{ route('profile.show') }}">My Account </a>

                            </li>
                            <li class="border-b md:border-none">
                            <!-- <a class="block md:inline-block  @if(Route::currentRouteName() == 'user-guide') active @endif" href="{{ route('user-guide')}}">User Guide</a> -->
                            <a class="block md:inline-block hhhhh" download="Revelation Legal Documentation( User Guide).pdf" href="{{url('/public/user-guide.pdf')}}">User Guide</a> 
        
                        </li>
                            <li class="border-t md:border-none">
                                <a class="block md:inline-block @if(Route::currentRouteName() == 'support.index') active @endif" href="{{ route('support.index') }}">Support </a>
                            </li>
                            <li class="border-t md:border-none">
                                <a class="block md:inline-block @if(Route::currentRouteName() == 'logout') active @endif" href="{{ route('logout') }}">Log Out({{request()->user()->first_name}})</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
            <main class="overflow-visible">
                {{ $slot }}
            </main>
            <div class="site-footer">
                <div class="row footer-inner">
                    <div class="col-12 col-md-9">
                        <h1>About Us</h1>
                        <p>Law firms everywhere are seeking a deeper understanding of their operations. Motivated by mergers, relocations, expansions, financial goals, or changes in the practice, firms have a growing imperative for better information to guide their most critical decisions.
                            
                        In response to this need, ofPartner has developed RevelationLegal, a powerful,web-based analysis designed for the legal environment that providesunprecedented vision into a firm's operation.
                        
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
                    <div class="col-12 col-md-3">
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
    <script>
 $(document).ready(()=>{
        CurrentUrl = window.location.href;
        CurrentPage = CurrentUrl.split('/')[3];
        NextParam = CurrentUrl.split('/')[4];

        

        if(CurrentPage == 'user'){
            console.log(CurrentPage);
            //$('#RealEstateButton').click();
            $('body #myaccuser').css('color','rgb(0, 140, 194)');
        }
 })
</script>

</html>