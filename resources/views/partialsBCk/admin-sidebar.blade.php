<!-- Desktop sidebar -->
<div class="admin site-sidebar "> 
<aside class=" z-20 hidden overflow-y-auto shadow-md bg-white dark:bg-gray-800 md:block flex-shrink-0" id="desktop_sidebar">
    <div class="sidebar-menus anchor" style="height:100%;">
        <div class="sidebar-smintxt text-center mb-4">
            <strong class=" h4 text-dark rl-fg-blue ">Admin Panel</strong> 
        </div>

        <ul class="">
            <li class=""> 
                <a class="inline-flex text-gray-500 items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100" href="{{ route('projects') }}">
                    <svg class="" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 16 16">
                        <g fill="currentColor">
                            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
                        </g>
                    </svg>
                    <span class="">Exit Admin</span>
                </a>
            </li>
        </ul>

        @if (\Auth::check() && \Auth::user()->is_admin)
        <ul class="">
            <li class="">
                <a class="@if(Route::currentRouteName() == 'users.all') active @endif inline-flex text-gray-500 items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100" href="{{ route('users.all') }}">
                    <svg class="" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 36 36">
                        <path class="clr-i-solid clr-i-solid-path-1" d="M12 16.14h-.87a8.67 8.67 0 0 0-6.43 2.52l-.24.28v8.28h4.08v-4.7l.55-.62l.25-.29a11 11 0 0 1 4.71-2.86A6.59 6.59 0 0 1 12 16.14z" fill="currentColor" />
                        <path class="clr-i-solid clr-i-solid-path-2" d="M31.34 18.63a8.67 8.67 0 0 0-6.43-2.52a10.47 10.47 0 0 0-1.09.06a6.59 6.59 0 0 1-2 2.45a10.91 10.91 0 0 1 5 3l.25.28l.54.62v4.71h3.94v-8.32z" fill="currentColor" />
                        <path class="clr-i-solid clr-i-solid-path-3" d="M11.1 14.19h.31a6.45 6.45 0 0 1 3.11-6.29a4.09 4.09 0 1 0-3.42 6.33z" fill="currentColor" />
                        <path class="clr-i-solid clr-i-solid-path-4" d="M24.43 13.44a6.54 6.54 0 0 1 0 .69a4.09 4.09 0 0 0 .58.05h.19A4.09 4.09 0 1 0 21.47 8a6.53 6.53 0 0 1 2.96 5.44z" fill="currentColor" />
                        <circle class="clr-i-solid clr-i-solid-path-5" cx="17.87" cy="13.45" r="4.47" fill="currentColor" />
                        <path class="clr-i-solid clr-i-solid-path-6" d="M18.11 20.3A9.69 9.69 0 0 0 11 23l-.25.28v6.33a1.57 1.57 0 0 0 1.6 1.54h11.49a1.57 1.57 0 0 0 1.6-1.54V23.3l-.24-.3a9.58 9.58 0 0 0-7.09-2.7z" fill="currentColor" />
                    </svg>
                    <span class="">All Users</span>
                </a>
            </li>
            <li class="">
                <a class="@if(Route::currentRouteName() == 'projects.index') active @endif inline-flex text-gray-500 items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100" href="{{ route('projects.index') }}">
                    <svg class="" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                        <path d="M11.112 12a4.502 4.502 0 0 0 8.776 0H22v8a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-8h9.112zM5 16h2v2H5v-2zm10.5-2.5a2.5 2.5 0 1 1 0-5a2.5 2.5 0 0 1 0 5zM11.112 10H2V4a1 1 0 0 1 1-1h18a1 1 0 0 1 1 1v6h-2.112a4.502 4.502 0 0 0-8.776 0z" fill="currentColor" />
                    </svg>
                    <span class="">Projects</span>
                </a>
            </li>
        </ul>
        @endif
    </div>
</aside>
</div>
<!-- Mobile sidebar -->
<!-- Backdrop -->
<div x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"></div>
<aside class="mobile-sidebar fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto shadow-md bg-white dark:bg-gray-800 md:hidden" x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150" x-transition:enter-start="opacity-0 transform -translate-x-20" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 transform -translate-x-20" @click.away="closeSideMenu" @keydown.escape="closeSideMenu">
    <div class="py-4 text-gray-500 dark:text-gray-400">

        <ul class="mb-0">
            <li class="">
                <a class="inline-flex text-gray-500 items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100" href="{{ route('projects') }}">
                    <svg class="" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="vertical-align: -0.125em;" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 16 16">
                        <g fill="currentColor">
                            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"></path>
                        </g>
                    </svg> 
                    <span class="">Exit Admin</span>
                </a>
            </li>
        </ul>

        @if(\Auth::check() && \Auth::user()->is_admin) 
        <ul class="">
            <li class="">
                <a class="@if(Route::currentRouteName() == 'users.all') active @endif inline-flex text-gray-500 items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100" href="{{ route('users.all') }}">
                    <svg class="" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 36 36">
                        <path class="clr-i-solid clr-i-solid-path-1" d="M12 16.14h-.87a8.67 8.67 0 0 0-6.43 2.52l-.24.28v8.28h4.08v-4.7l.55-.62l.25-.29a11 11 0 0 1 4.71-2.86A6.59 6.59 0 0 1 12 16.14z" fill="currentColor" />
                        <path class="clr-i-solid clr-i-solid-path-2" d="M31.34 18.63a8.67 8.67 0 0 0-6.43-2.52a10.47 10.47 0 0 0-1.09.06a6.59 6.59 0 0 1-2 2.45a10.91 10.91 0 0 1 5 3l.25.28l.54.62v4.71h3.94v-8.32z" fill="currentColor" />
                        <path class="clr-i-solid clr-i-solid-path-3" d="M11.1 14.19h.31a6.45 6.45 0 0 1 3.11-6.29a4.09 4.09 0 1 0-3.42 6.33z" fill="currentColor" />
                        <path class="clr-i-solid clr-i-solid-path-4" d="M24.43 13.44a6.54 6.54 0 0 1 0 .69a4.09 4.09 0 0 0 .58.05h.19A4.09 4.09 0 1 0 21.47 8a6.53 6.53 0 0 1 2.96 5.44z" fill="currentColor" />
                        <circle class="clr-i-solid clr-i-solid-path-5" cx="17.87" cy="13.45" r="4.47" fill="currentColor" />
                        <path class="clr-i-solid clr-i-solid-path-6" d="M18.11 20.3A9.69 9.69 0 0 0 11 23l-.25.28v6.33a1.57 1.57 0 0 0 1.6 1.54h11.49a1.57 1.57 0 0 0 1.6-1.54V23.3l-.24-.3a9.58 9.58 0 0 0-7.09-2.7z" fill="currentColor" />
                    </svg>
                    <span class="">All Users</span>
                </a>
            </li>
            <li class="">
                <a class="@if(Route::currentRouteName() == 'projects.index') active @endif inline-flex text-gray-500 items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100" href="{{ route('projects.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-width="2" d="M9 15v8H1v-8h8zm14 0v8h-8v-8h8zM9 1v8H1V1h8zm14 0v8h-8V1h8z" />
                    </svg>
                    <span class="">Projects</span>
                </a>
            </li>
        </ul>
        @endif
    </div>
</aside>