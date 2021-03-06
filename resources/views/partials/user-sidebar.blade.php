<!-- Desktop sidebar -->
<aside class="z-20 hidden  overflow-y-auto shadow-md  md:block flex-shrink-0" id="desktop_sidebar">
    <div class=" anchor text-gray-500 dark:text-gray-400 sidebar-menus" style="height:100%;">

        @if (\Auth::check() && \Auth::user()->hasPermission('survey', $survey))
            <ul class=" ">
                <li class=" relative ">
                    <!-- <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span> -->
                    <a class="@if (Route::currentRouteName() == 'projects') active @endif inline-flex text-gray-500 items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                        href="{{ route('projects') }}">
                        <i class="fas fa-layer-group"></i>
                        <span class="">Projects</span>
                    </a>
                </li>
            </ul>
        @endif
        @if (\Auth::check() && \Auth::user()->hasPermission('surveyProfile', $survey))
            <ul class="">
                <li class="relative ">
                    <!-- <span class="active absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span> -->
                    <a class=" @if (Route::currentRouteName() == 'survey') active @endif inline-flex text-gray-500 items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                        href="{{ route('survey', $data['survey']->survey_id) }}">
                        <i class="fas fa-check-circle"></i>
                        <span class="">Status</span>
                    </a>
                </li>
            </ul>
        @endif
        @if (\Auth::check() && \Auth::user()->hasPermission('surveyUsers', $survey))
            <ul class="">
                <li class="relative ">
                    <!-- <span class="active absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span> -->
                    <a class="@if (Route::currentRouteName() == 'survey_users') active @endif inline-flex text-gray-500 items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                        href="{{ route('survey_users', $data['survey']->survey_id) }}">
                        <i class="fas fa-users"></i>
                        <span class="">Project Users</span>
                    </a>
                </li>
            </ul>
        @endif
        @if (\Auth::check() && \Auth::user()->hasPermission('surveyTaxonomy', $survey))
            <ul class="">
                <li class="relative ">
                    <!-- <span class="active absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span> -->
                    <a class="@if (Route::currentRouteName() == 'taxonomy.index') active @endif inline-flex text-gray-500 items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                        href="{{ route('taxonomy.index', $data['survey']->survey_id) }}">
                        <i class="fas fa-network-wired"></i>
                        <span class="">Taxonomy</span>
                    </a>
                </li>
            </ul>
        @endif
        @if (\Auth::check() && \Auth::user()->hasPermission('surveySettings', $survey))
            <ul class="">
                <li class="relative ">
                    <button id="settings_togglebtn"
                        class="inline-flex items-center focus:outline-none justify-between w-full text-sm font-semibold duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                        @click="togglePagesMenu6" aria-haspopup="true">
                        <span class="@if (Request::is('settings/*')) active @endif inline-flex items-center">
                            <i class="fas fa-cogs"></i>
                            <span class="">Settings</span>
                        </span>
                        <img class="" src="{{ asset('imgs/downicon.png') }}">
                    </button>
                    <template x-if="isPagesMenuOpen6">
                        <ul x-transition:enter="transition-all ease-in-out duration-300"
                            x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl"
                            x-transition:leave="transition-all ease-in-out duration-300"
                            x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                            class="sub-menu" aria-label="submenu">

                            <li class="submenu-liitem">
                                <a class="w-full  @if (Route::currentRouteName() == 'settings-settings') active @endif"
                                    href="{{ route('settings-settings', $data['survey']->survey_id) }}">Questionnaire
                                    Settings</a>
                            </li>
                            <li class="submenu-liitem">
                                <a class="w-full  @if (Route::currentRouteName() == 'settings-locations') active @endif"
                                    href="{{ route('settings-locations', $data['survey']->survey_id) }}">
                                    Support Locations
                                </a>
                            </li>
                        </ul>
                    </template>
                </li>
            </ul>
        @endif
        @if (\Auth::check() && \Auth::user()->hasPermission('surveyRespondents', $survey))
            <ul class="">
                <li class="relative ">
                    <!-- <span class="active absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span> -->
                    <a class="@if (Route::currentRouteName() == 'Respondents') active @endif inline-flex text-gray-500 items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                        href="{{ route('Respondents', $data['survey']->survey_id) }}">
                        <i class="fas fa-handshake"></i>
                        <span class="">Participants</span>
                    </a>
                </li>
            </ul>
        @endif
        @if (\Auth::check() && \Auth::user()->hasPermission('surveyInvitations', $survey))
            <ul>
                <li class="relative ">
                    <button id="invitation_togglebtn"
                        class="inline-flex items-center focus:outline-none justify-between w-full text-sm font-semibold duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                        @click="togglePagesMenu5" aria-haspopup="true">
                        <span class="@if (Request::is('invitations/*')) active @endif inline-flex items-center">
                            <i class="fas fa-envelope-open-text"></i>
                            <span class="">Invitations</span>
                        </span>
                        <img class="" src="{{ asset('imgs/downicon.png') }}">
                    </button>
                    <template x-if="isPagesMenuOpen5">
                        <ul x-transition:enter="transition-all ease-in-out duration-300"
                            x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl"
                            x-transition:leave="transition-all ease-in-out duration-300"
                            x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                            class="sub-menu" aria-label="submenu">

                            <li class="submenu-liitem">
                                <a class="w-full @if (Route::currentRouteName() == 'invitations-settings') active @endif"
                                    href="{{ route('invitations-settings', $data['survey']->survey_id) }}">Settings</a>
                            </li>
                            <li class="submenu-liitem">
                                <a class="w-full @if (Route::currentRouteName() == 'invitations-send') active @endif"
                                    href="{{ route('invitations-send', $data['survey']->survey_id) }}">
                                    Send Invitations
                                </a>
                            </li>
                        </ul>
                    </template>
                </li>
            </ul>
        @endif
        <ul>
            @if (\Auth::check() && \Auth::user()->hasPermission('surveyReports', $survey))
                <li class="relative ">
                    <button id="reports_togglebtn"
                        class="inline-flex items-center focus:outline-none justify-between w-full text-sm font-semibold duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                        @click="togglePagesMenu" aria-haspopup="true">
                        <span class="@if (Request::is('reports/general/*')) active @endif inline-flex items-center">

                            <i class="fas fa-clipboard"></i>
                            <span class="">Reports</span>
                        </span>
                        <img class="" src="{{ asset('imgs/downicon.png') }}">
                    </button>
                    <template x-if="isPagesMenuOpen">
                        <ul x-transition:enter="transition-all ease-in-out duration-300"
                            x-transition:enter-start="opacity-25 max-h-0"
                            x-transition:enter-end="opacity-100 max-h-xl"
                            x-transition:leave="transition-all ease-in-out duration-300"
                            x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                            class="sub-menu" aria-label="submenu">
                            @if ($survey->survey_id != 54)
                            <li class="submenu-liitem">

                                <a class="w-full @if (Route::currentRouteName() == 'demographic_report') active @endif"
                                    href="{{ route('demographic_report', $data['survey']->survey_id) }}">
                                    Demographic
                                </a>
                            </li>
                            @endif

                            <li class="submenu-liitem">
                                <a class="w-full @if (Route::currentRouteName() == 'individual-report') active @endif"
                                    href="{{ route('individual-report', $data['survey']->survey_id) }}">Individual</a>
                            </li>
                            @if ($survey->survey_id != 54)
                            <li class="submenu-liitem">
                                <a class="w-full @if (Route::currentRouteName() == 'compilation-report') active @endif"
                                    href="{{ route('compilation-report', $data['survey']->survey_id) }}">
                                    Compilation
                                </a>
                            </li>
                            @endif
                            @if ($survey->survey_id != 54)
                            <li class="submenu-liitem">
                                <a class="w-full @if (Route::currentRouteName() == 'individual-crosstabreport') active @endif"
                                    href="{{ route('individual-crosstabreport', $data['survey']->survey_id) }}">Crosstab</a>
                            </li>
                            @endif

                            @if ($survey->survey_id != 54)
                            @if (Auth::user()->id == 11)
                                <li class="submenu-liitem">
                                    <a class="w-full @if (Route::currentRouteName() == 'validation-report') active @endif"
                                        href="{{ route('validation-report', $data['survey']->survey_id) }}">Validation
                                        Reports (Beta)</a>
                                </li>
                            @endif
                            @endif

                        </ul>
                    </template>
                </li>
            @endif
            
            @if ($survey->survey_id != 54)
                

            @if (\Auth::check() && \Auth::user()->hasPermission('surveyNoCompReport', $survey))

                <li class="relative ">
                    <button id="ncreports_togglebtn"
                        class="inline-flex items-center focus:outline-none justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                        @click="togglePagesMenu2" aria-haspopup="true">
                        <span class="@if (Request::is('reports/nc/*')) active @endif inline-flex items-center">
                            <i class="fas fa-poll-h"></i>
                            <span class="">NC Reports</span>
                        </span>
                        <img class="" src="{{ asset('imgs/downicon.png') }}">
                    </button>
                    <template x-if="isPagesMenuOpen2">
                        <ul x-transition:enter="transition-all ease-in-out duration-300"
                            x-transition:enter-start="opacity-25 max-h-0"
                            x-transition:enter-end="opacity-100 max-h-xl"
                            x-transition:leave="transition-all ease-in-out duration-300"
                            x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                            class="sub-menu" aria-label="submenu">
                            <li class="submenu-liitem">
                                <a class="w-full @if (Route::currentRouteName() == 'nc_demographic_report') active @endif"
                                    href="{{ route('nc_demographic_report', $data['survey']->survey_id) }}">
                                    Demographic
                                </a>
                            </li>
                            <li class="submenu-liitem">
                                <a class="w-full @if (Route::currentRouteName() == 'individual-ncreport') active @endif"
                                    href="{{ route('individual-ncreport', $data['survey']->survey_id) }}">
                                    Individual
                                </a>
                            </li>

                            <li class="submenu-liitem">
                                <a class="w-full @if (Route::currentRouteName() == 'compilation-ncreport') active @endif"
                                    href="{{ route('compilation-ncreport', $data['survey']->survey_id) }}">
                                    Compilation
                                </a>
                            </li>
                            {{-- <li class="submenu-liitem">
                            <a class="w-full @if (Route::currentRouteName() == 'individual-crosstabreport') active @endif" href="{{ route('individual-crosstabreport', $data['survey']->survey_id) }}">Crosstab</a>
                        </li> --}}
                        </ul>
                    </template>
                </li>

                @if (\Auth::check() && \Auth::user()->hasPermission('surveyAnalysis', $survey))
                    <li class="relative ">
                        <button id="analysis_togglebtn"
                            class="inline-flex items-center focus:outline-none justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                            @click="togglePagesMenu3" aria-haspopup="true">
                            <span class="@if (Request::is('analysis/*')) active @endif inline-flex items-center">
                                <i class="fas fa-chart-line"></i>
                                <span class="">Analysis</span>
                            </span>
                            <img class="" src="{{ asset('imgs/downicon.png') }}">
                        </button>
                        <template x-if="isPagesMenuOpen3">
                            <ul x-transition:enter="transition-all ease-in-out duration-300"
                                x-transition:enter-start="opacity-25 max-h-0"
                                x-transition:enter-end="opacity-100 max-h-xl"
                                x-transition:leave="transition-all ease-in-out duration-300"
                                x-transition:leave-start="opacity-100 max-h-xl"
                                x-transition:leave-end="opacity-0 max-h-0" class="sub-menu" aria-label="submenu">
                                <li class="submenu-liitem">
                                    <a class="w-full @if (Route::currentRouteName() == 'participant-analysis') active @endif"
                                        href="{{ route('participant-analysis', [$data['survey']->survey_id]) }}">Participant</a>

                                </li>
                                <li class="submenu-liitem">
                                    <a class="w-full @if (Route::currentRouteName() == 'ataglance-analysis') active @endif"
                                        href="{{ route('ataglance-analysis', [$data['survey']->survey_id]) }}">
                                        At A Glance
                                    </a>

                                </li>
                                <li class="submenu-liitem">
                                    <a class="w-full @if (Route::currentRouteName() == 'comparative-glance-analysis') active @endif"
                                        href="{{ route('comparative-glance-analysis', [$data['survey']->survey_id]) }}">
                                        Comparative Glance (Beta)
                                    </a>

                                </li>
                            </ul>
                        </template>
                    </li>
                @endif
                @if (\Auth::check() && \Auth::user()->hasPermission('surveyRealEstate', $survey))
                    <li class="relative ">
                        <button id="realestate_togglebtn"
                            class="@if (Request::is('realestate/*')) active @endif inline-flex items-center focus:outline-none justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none"
                            @click="togglePagesMenu4" aria-haspopup="true">
                            <span class="inline-flex items-center">
                                <i class="fas fa-building"></i>
                                <span class="">Real Estate</span>
                            </span>
                            <img class="" src="{{ asset('imgs/downicon.png') }}">
                        </button>
                        <template x-if="isPagesMenuOpen4">
                            <ul x-transition:enter="transition-all ease-in-out duration-300"
                                x-transition:enter-start="opacity-25 max-h-0"
                                x-transition:enter-end="opacity-100 max-h-xl"
                                x-transition:leave="transition-all ease-in-out duration-300"
                                x-transition:leave-start="opacity-100 max-h-xl"
                                x-transition:leave-end="opacity-0 max-h-0" class="sub-menu" aria-label="submenu">
                                <li class="submenu-liitem">
                                    <a class="w-full @if (Route::currentRouteName() == 'real-estate.location_rsf_rates') active @endif"
                                        @if (Route::currentRouteName() == 'real-estate.location_rsf_rates') onload="ExpandMenu('realestate_togglebtn');" @endif
                                        href="{{ route('real-estate.location_rsf_rates', [$data['survey']->survey_id]) }}">Location
                                        RSF Rates</a>
                                </li>
                                <li class="submenu-liitem">
                                    <a class="w-full @if (Route::currentRouteName() == 'real-estate.opportunity-detail') active @endif"
                                        @if (Route::currentRouteName() == 'real-estate.opportunity-detail') onload='ExpandMenu("realestate_togglebtn");' @endif
                                        href="{{ route('real-estate.opportunity-detail', [$data['survey']->survey_id]) }}">Opportunity
                                        Detail</a>
                                </li>
                                <li class="submenu-liitem">
                                    <a class="w-full @if (Route::currentRouteName() == 'real-estate.opportunity-summary') active @endif"
                                        @if (Route::currentRouteName() == 'real-estate.opportunity-summary') onload='ExpandMenu("realestate_togglebtn");' @endif
                                        href="{{ route('real-estate.opportunity-summary', [$data['survey']->survey_id]) }}">Opportunity
                                        Summary</a>
                                </li>
                                <li class="submenu-liitem">
                                    <a class="w-full @if (Route::currentRouteName() == 'real-estate.individual-proximity') active @endif"
                                        @if (Route::currentRouteName() == 'real-estate.individual-proximity') onload='ExpandMenu("realestate_togglebtn");' @endif
                                        href="{{ route('real-estate.individual-proximity', [$data['survey']->survey_id]) }}">
                                        Individual Proximity
                                    </a>
                                </li>
                                <li class="submenu-liitem">
                                    <a class="w-full @if (Route::currentRouteName() == 'real-estate.participant-proximity') active @endif"
                                        @if (Route::currentRouteName() == 'real-estate.participant-proximity') onload='ExpandMenu("realestate_togglebtn");' @endif
                                        href="{{ route('real-estate.participant-proximity', [$data['survey']->survey_id]) }}">
                                        Participant Proximity
                                    </a>
                                </li>
                                <li class="submenu-liitem">
                                    <a class="w-full @if (Route::currentRouteName() == 'real-estate.activity-by-location') active @endif"
                                        @if (Route::currentRouteName() == 'real-estate.activity-by-location') onload='ExpandMenu("realestate_togglebtn");' @endif
                                        href="{{ route('real-estate.activity-by-location', [$data['survey']->survey_id]) }}">Activity
                                        by Location</a>
                                </li>

                                <li class="submenu-liitem">
                                    <a class="w-full @if (Route::currentRouteName() == 'real-estate.activity-cost-by-location') active @endif"
                                        @if (Route::currentRouteName() == 'real-estate.activity-cost-by-location') onload='ExpandMenu("realestate_togglebtn");' @endif
                                        href="{{ route('real-estate.activity-cost-by-location', [$data['survey']->survey_id]) }}">Activity
                                        Cost by Location</a>
                                </li>
                                <li class="submenu-liitem">
                                    <a class="w-full @if (Route::currentRouteName() == 'real-estate.proximity-by-activity') active @endif"
                                        @if (Route::currentRouteName() == 'real-estate.proximity-by-activity') onload='ExpandMenu("realestate_togglebtn");' @endif
                                        href="{{ route('real-estate.proximity-by-activity', [$data['survey']->survey_id]) }}">Proximity
                                        by Activity(Beta)</a>
                                </li>

                            </ul>
                        </template>
                    </li>
                @endif
            @endif

            @endif
            <!--
            <li class="relative ">
                <button id="reveli_togglebtn" class=" inline-flex items-center focus:outline-none justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none" @click="togglePagesMenu6" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <i class="fa fa-info-circle"></i>
                        <span class="">Revelation Legal-i</span>
                    </span>
                    <img class="" src="{{ asset('imgs/downicon.png') }}">
                </button>
                <template x-if="isPagesMenuOpen6">
                    <ul x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0" class="sub-menu" aria-label="submenu">
                        <li class="submenu-liitem">
                            <a class="w-full @if (Route::currentRouteName() == 'real-estate.opportunity-summary') active @endif" @if (Route::currentRouteName() == 'real-estate.opportunity-summary') onload='ExpandMenu("reveli_togglebtn");' @endif href="#">Opportunity Summary</a>
                        </li>
                        <li class="submenu-liitem">
                            <a class="w-full @if (Route::currentRouteName() == 'real-estate.individual-proximity') active @endif" @if (Route::currentRouteName() == 'real-estate.individual-proximity') onload='ExpandMenu("reveli_togglebtn");' @endif href="#">
                                Individual Proximity
                            </a>
                        </li>
                        <li class="submenu-liitem">
                            <a class="w-full @if (Route::currentRouteName() == 'real-estate.participant-proximity') active @endif" @if (Route::currentRouteName() == 'real-estate.participant-proximity') onload='ExpandMenu("reveli_togglebtn");' @endif href="#">
                                Participant Proximity
                            </a>
                        </li>
                     
                    </ul>
                </template>
            </li> -->


        </ul>
    </div>
</aside>
<!-- Mobile sidebar -->
<!-- Backdrop -->
<div x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"></div>
<aside class="mobile-sidebar fixed inset-y-0 z-20 flex-shrink-0  overflow-y-auto shadow-md  md:hidden"
    x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0 transform -translate-x-20" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0 transform -translate-x-20" @click.away="closeSideMenu"
    @keydown.escape="closeSideMenu">
    <div class="py-4 text-gray-500 dark:text-gray-400">
        <!-- <a class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200" href="#">
            Windmill
        </a> -->
        @if (\Auth::check() && \Auth::user()->is_admin)
            <ul class="">
                <li class="relative ">
                    <a class="@if (Route::currentRouteName() == 'users.all') active @endif inline-flex text-gray-500 items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                        href="{{ route('users.all', $data['survey']->survey_id) }}">
                        <i class="fas fa-users"></i>
                        <span class="">All Users</span>
                    </a>
                </li>
            </ul>
        @endif
        @if (\Auth::check() && \Auth::user()->hasPermission('survey', $survey))
            <ul class="">

                <li class="relative ">
                    <!-- <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span> -->
                    <a class="inline-flex items-center w-full text-sm font-semibold text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                        href="{{ route('projects') }}">
                        <i class="fas fa-layer-group"></i>
                        <span class="">Projects</span>
                    </a>
                </li>
            </ul>
        @endif
        @if (\Auth::check() && \Auth::user()->hasPermission('surveySettings', $survey))
            <ul>
                <li class="relative ">
                    <button
                        class="inline-flex items-center focus:outline-none justify-between w-full text-sm font-semibold duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                        @click="togglePagesMenu6" aria-haspopup="true">
                        <span class="@if (Request::is('settings/*')) active @endif inline-flex items-center">
                            <i class="fas fa-cogs"></i>
                            <span class="">Settings</span>
                        </span>
                        <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <template x-if="isPagesMenuOpen6">
                        <ul x-transition:enter="transition-all ease-in-out duration-300"
                            x-transition:enter-start="opacity-25 max-h-0"
                            x-transition:enter-end="opacity-100 max-h-xl"
                            x-transition:leave="transition-all ease-in-out duration-300"
                            x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                            class="sub-menu" aria-label="submenu">

                            <li class="submenu-liitem">
                                <a class="w-full @if (Route::currentRouteName() == 'settings-settings') active @endif"
                                    href="{{ route('settings-settings', $data['survey']->survey_id) }}">Questionnaire
                                    Settings</a>
                            </li>
                            <li class="submenu-liitem">
                                <a class="w-full @if (Route::currentRouteName() == 'settings-locations') active @endif"
                                    href="{{ route('settings-locations', $data['survey']->survey_id) }}">
                                    Support Locations
                                </a>
                            </li>
                        </ul>
                    </template>
                </li>
            </ul>
        @endif
        @if (\Auth::check() && \Auth::user()->hasPermission('surveyProfile', $survey))
            <ul class="">
                <li class="relative ">
                    <!-- <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span> -->
                    <a class="inline-flex items-center w-full text-sm font-semibold text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                        href="{{ route('survey', $data['survey']->survey_id) }}">
                        <i class="fas fa-check-circle"></i>
                        <span class="">Status</span>
                    </a>
                </li>
            </ul>
        @endif
        @if (\Auth::check() && \Auth::user()->hasPermission('surveyTaxonomy', $survey))
            <ul class="">
                <li class="relative ">
                    <!-- <span class="active absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span> -->
                    <a class="@if (Route::currentRouteName() == 'taxonomy.index') active @endif inline-flex text-gray-500 items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                        href="{{ route('taxonomy.index', $data['survey']->survey_id) }}">
                        <i class="fas fa-network-wired"></i>
                        <span class="">Taxonomy</span>
                    </a>
                </li>
            </ul>
        @endif
        @if (\Auth::check() && \Auth::user()->hasPermission('surveyRespondents', $survey))
            <ul class="">
                <li class="relative ">
                    <!-- <span class="active absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span> -->
                    <a class="@if (Route::currentRouteName() == 'Respondents') active @endif inline-flex text-gray-500 items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                        href="{{ route('Respondents', $data['survey']->survey_id) }}">
                        <i class="fas fa-handshake"></i>
                        <span class="">Participants</span>
                    </a>
                </li>
            </ul>
        @endif
        @if (\Auth::check() && \Auth::user()->hasPermission('surveyInvitations', $survey))
            <ul>
                <li class="relative ">
                    <button
                        class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                        @click="togglePagesMenu5" aria-haspopup="true">
                        <span class="inline-flex items-center">
                            <i class="fas fa-envelope-open-text"></i>
                            <span class="">Invitations</span>
                        </span>
                        <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <template x-if="isPagesMenuOpen5">
                        <ul x-transition:enter="transition-all ease-in-out duration-300"
                            x-transition:enter-start="opacity-25 max-h-0"
                            x-transition:enter-end="opacity-100 max-h-xl"
                            x-transition:leave="transition-all ease-in-out duration-300"
                            x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                            class="sub-menu" aria-label="submenu">
                            <li class="submenu-liitem">
                                <a class="w-full @if (Route::currentRouteName() == 'invitations-settings') active @endif"
                                    href="{{ route('invitations-settings', $data['survey']->survey_id) }}">Settings</a>
                            </li>
                            <li class="submenu-liitem">
                                <a class="w-full @if (Route::currentRouteName() == 'invitations-send') active @endif"
                                    href="{{ route('invitations-send', $data['survey']->survey_id) }}">
                                    Send Invitations
                                </a>
                            </li>
                        </ul>
                    </template>
                </li>
            </ul>
        @endif
        <ul>
            @if (\Auth::check() && \Auth::user()->hasPermission('surveyReports', $survey))
                <li class="relative ">
                    <button
                        class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                        @click="togglePagesMenu" aria-haspopup="true">
                        <span class="inline-flex items-center">
                            <i class="fas fa-clipboard"></i>
                            <span class="">Reports</span>
                        </span>
                        <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <template x-if="isPagesMenuOpen">
                        <ul x-transition:enter="transition-all ease-in-out duration-300"
                            x-transition:enter-start="opacity-25 max-h-0"
                            x-transition:enter-end="opacity-100 max-h-xl"
                            x-transition:leave="transition-all ease-in-out duration-300"
                            x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                            class="sub-menu" aria-label="submenu">
                            @if ($survey->survey_id != 54)
                            <li class="submenu-liitem">
                                <a class="w-full @if (Route::currentRouteName() == 'demographic_report') active @endif"
                                    href="{{ route('demographic_report', $data['survey']->survey_id) }}">
                                    Demographic
                                </a>
                            </li>
                            @endif

                            @if (\Auth::check() && \Auth::user()->hasPermission('surveyIndividual', $survey))
                                <li class="submenu-liitem">
                                    <a class="w-full @if (Route::currentRouteName() == 'individual-report') active @endif"
                                        href="{{ route('individual-report', $data['survey']->survey_id) }}">Individual</a>
                                </li>
                            @endif

                            @if ($survey->survey_id != 54)
                            <li class="submenu-liitem">
                                <a class="w-full @if (Route::currentRouteName() == 'compilation-report') active @endif"
                                    href="{{ route('compilation-report', $data['survey']->survey_id) }}">
                                    Compilation
                                </a>
                            </li>
                            @endif

                            @if ($survey->survey_id != 54)
                                @if (\Auth::check() && \Auth::user()->hasPermission('surveyCrosstab', $survey))
                                    <li class="submenu-liitem">
                                        <a class="w-full @if (Route::currentRouteName() == 'individual-crosstabreport') active @endif"
                                            href="{{ route('individual-crosstabreport', $data['survey']->survey_id) }}">Crosstab</a>
                                    </li>
                                @endif
                            @endif

                            @if ($survey->survey_id != 54)
                                @if (Auth::user()->id == 11)
                                    <li class="submenu-liitem">
                                        <a class="w-full @if (Route::currentRouteName() == 'validation-report') active @endif"
                                            href="{{ route('validation-report', $data['survey']->survey_id) }}">Validation
                                            Reports (Beta)</a>
                                    </li>
                                @endif
                            @endif

                        </ul>
                    </template>
                </li>
            @endif
            @if ($survey->survey_id != 54)
                
                @if (\Auth::check() && \Auth::user()->hasPermission('surveyNoCompReport', $survey))
                    <li class="relative ">
                        <button
                            class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                            @click="togglePagesMenu2" aria-haspopup="true">
                            <span class="inline-flex items-center">
                                <i class="fas fa-poll-h"></i>
                                <span class="">NC Reports</span>
                            </span>
                            <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <template x-if="isPagesMenuOpen2">
                            <ul x-transition:enter="transition-all ease-in-out duration-300"
                                x-transition:enter-start="opacity-25 max-h-0"
                                x-transition:enter-end="opacity-100 max-h-xl"
                                x-transition:leave="transition-all ease-in-out duration-300"
                                x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                                class="sub-menu" aria-label="submenu">
                                <li class="submenu-liitem">
                                    <a class="w-full @if (Route::currentRouteName() == 'nc_demographic_report') active @endif"
                                        href="{{ route('nc_demographic_report', $data['survey']->survey_id) }}">Demographic</a>
                                </li>
                                <li class="submenu-liitem">
                                    <a class="w-full @if (Route::currentRouteName() == 'individual-ncreport') active @endif"
                                        href="{{ route('individual-ncreport', $data['survey']->survey_id) }}">
                                        Individual
                                    </a>
                                </li>
                                <li class="submenu-liitem">
                                    <a class="w-full @if (Route::currentRouteName() == 'compilation-ncreport') active @endif"
                                        href="{{ route('compilation-ncreport', $data['survey']->survey_id) }}">
                                        Compilation
                                    </a>
                                </li>
                                {{-- @if (\Auth::check() && \Auth::user()->hasPermission('surveyCrosstab', $survey))
                            <li class="submenu-liitem">
                                <a class="w-full @if (Route::currentRouteName() == 'individual-crosstabreport') active @endif" href="{{ route('individual-crosstabreport', $data['survey']->survey_id) }}">Crosstab</a>
                            </li>
                            @endif --}}
                            </ul>
                        </template>
                    </li>

                    @if (\Auth::check() && \Auth::user()->hasPermission('surveyAnalysis', $survey))
                        <li class="relative ">
                            <button
                                class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                                @click="togglePagesMenu3" aria-haspopup="true">
                                <span class="inline-flex items-center">
                                    <i class="fas fa-chart-line"></i>
                                    <span class="">Analysis</span>
                                </span>
                                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <template x-if="isPagesMenuOpen3">
                                <ul x-transition:enter="transition-all ease-in-out duration-300"
                                    x-transition:enter-start="opacity-25 max-h-0"
                                    x-transition:enter-end="opacity-100 max-h-xl"
                                    x-transition:leave="transition-all ease-in-out duration-300"
                                    x-transition:leave-start="opacity-100 max-h-xl"
                                    x-transition:leave-end="opacity-0 max-h-0" class="sub-menu" aria-label="submenu">
                                    <li class="submenu-liitem">
                                        <a class="w-full  @if (Route::currentRouteName() == 'participant-analysis') active @endif "
                                            href="{{ route('participant-analysis', [$data['survey']->survey_id]) }}">Participant</a>
                                    </li>
                                    <li class="submenu-liitem">
                                        <a class="w-full  @if (Route::currentRouteName() == 'ataglance-analysis') active @endif"
                                            href="{{ route('ataglance-analysis', [$data['survey']->survey_id]) }}">
                                            At A Glance
                                        </a>
                                    </li>
                                    <li class="submenu-liitem">
                                        <a class="w-full  @if (Route::currentRouteName() == 'comparative-glance-analysis') active @endif"
                                            href="{{ route('comparative-glance-analysis', [$data['survey']->survey_id]) }}">
                                            Comparative Glance (Beta)
                                        </a>
                                    </li>
                                </ul>
                            </template>
                        </li>
                    @endif
                    @if (\Auth::check() && \Auth::user()->hasPermission('surveyRealEstate', $survey))
                        <li class="relative ">
                            <button id="RealEstateButton"
                                class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none"
                                @click="togglePagesMenu4" aria-haspopup="true">
                                <span class="inline-flex items-center">
                                    <i class="fas fa-building"></i>
                                    <span class="">Real Estate</span>
                                </span>
                                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <template x-if="isPagesMenuOpen4">
                                <ul x-transition:enter="transition-all ease-in-out duration-300"
                                    x-transition:enter-start="opacity-25 max-h-0"
                                    x-transition:enter-end="opacity-100 max-h-xl"
                                    x-transition:leave="transition-all ease-in-out duration-300"
                                    x-transition:leave-start="opacity-100 max-h-xl"
                                    x-transition:leave-end="opacity-0 max-h-0" class="sub-menu" aria-label="submenu">
                                    <li class="submenu-liitem">
                                        <a class="w-full @if (Route::currentRouteName() == 'real-estate.location_rsf_rates') active @endif"
                                            @if (Route::currentRouteName() == 'real-estate.location_rsf_rates') onload='ExpandMenu("RealEstateButton");' @endif
                                            href="{{ route('real-estate.location_rsf_rates', [$data['survey']->survey_id]) }}">Location
                                            RSF Rates</a>
                                    </li>
                                    <li class="submenu-liitem">
                                        <a class="w-full @if (Route::currentRouteName() == 'real-estate.opportunity-detail') active @endif"
                                            @if (Route::currentRouteName() == 'real-estate.opportunity-detail') onload='ExpandMenu("RealEstateButton");' @endif
                                            href="{{ route('real-estate.opportunity-detail', [$data['survey']->survey_id]) }}">Opportunity
                                            Detail</a>
                                    </li>
                                    <li class="submenu-liitem">
                                        <a class="w-full @if (Route::currentRouteName() == 'real-estate.opportunity-summary') active @endif"
                                            @if (Route::currentRouteName() == 'real-estate.opportunity-summary') onload='ExpandMenu("RealEstateButton");' @endif
                                            href="{{ route('real-estate.opportunity-summary', [$data['survey']->survey_id]) }}">Opportunity
                                            Summary</a>
                                    </li>
                                    <li class="submenu-liitem">
                                        <a class="w-full @if (Route::currentRouteName() == 'real-estate.individual-proximity') active @endif"
                                            @if (Route::currentRouteName() == 'real-estate.individual-proximity') onload='ExpandMenu("RealEstateButton");' @endif
                                            href="{{ route('real-estate.individual-proximity', [$data['survey']->survey_id]) }}">
                                            Individual Proximity
                                        </a>
                                    </li>
                                    <li class="submenu-liitem">
                                        <a class="w-full @if (Route::currentRouteName() == 'real-estate.participant-proximity') active @endif"
                                            @if (Route::currentRouteName() == 'real-estate.participant-proximity') onload='ExpandMenu("RealEstateButton");' @endif
                                            href="{{ route('real-estate.participant-proximity', [$data['survey']->survey_id]) }}">
                                            Participant Proximity
                                        </a>
                                    </li>
                                    <li class="submenu-liitem">
                                        <a class="w-full @if (Route::currentRouteName() == 'real-estate.activity-by-location') active @endif"
                                            @if (Route::currentRouteName() == 'real-estate.activity-by-location') onload='ExpandMenu("RealEstateButton");' @endif
                                            href="{{ route('real-estate.activity-by-location', [$data['survey']->survey_id]) }}">Activity
                                            by Location</a>
                                    </li>

                                    <li class="submenu-liitem">
                                        <a class="w-full @if (Route::currentRouteName() == 'real-estate.activity-cost-by-location') active @endif"
                                            @if (Route::currentRouteName() == 'real-estate.activity-cost-by-location') onload='ExpandMenu("RealEstateButton");' @endif
                                            href="{{ route('real-estate.activity-cost-by-location', [$data['survey']->survey_id]) }}">Activity
                                            Cost by Location</a>
                                    </li>
                                    <li class="submenu-liitem">
                                        <a class="w-full @if (Route::currentRouteName() == 'real-estate.proximity-by-activity') active @endif"
                                            @if (Route::currentRouteName() == 'real-estate.proximity-by-activity') onload='ExpandMenu("RealEstateButton");' @endif
                                            href="{{ route('real-estate.proximity-by-activity', [$data['survey']->survey_id]) }}">Proximity
                                            by Activity(Beta)</a>
                                    </li>

                                </ul>
                            </template>
                        </li>
                    @endif
                @endif
            @endif
        </ul>

    </div>
</aside>

<script>
    $('#desktop_sidebar a').click(function() {
        mask_height = $('body').height();
        $('.loading-mask').css('height', mask_height);
        $('.loading-mask').show();
    });


    $(document).ready(() => {
        CurrentUrl = window.location.href;
        CurrentPage = CurrentUrl.split('/')[3];
        NextParam = CurrentUrl.split('/')[4];



        if (CurrentPage == 'realestate') {
            console.log(CurrentPage);
            //$('#RealEstateButton').click();
            $('#realestate_togglebtn').click();
        }
        if (CurrentPage == 'analysis') {
            console.log(CurrentPage);
            //$('#RealEstateButton').click();
            $('#analysis_togglebtn').click();
        }
        if (CurrentPage == 'reports' && NextParam == 'nc') {
            console.log(CurrentPage);
            //$('#RealEstateButton').click();
            $('#ncreports_togglebtn').click();
        }
        if (CurrentPage == 'reports' && NextParam != 'nc') {
            console.log(CurrentPage);
            //$('#RealEstateButton').click();
            $('#reports_togglebtn').click();
        }
        if (CurrentPage == 'invitations') {
            console.log(CurrentPage);
            //$('#RealEstateButton').click();
            $('#invitation_togglebtn').click();
        }
        if (CurrentPage == 'settings') {
            console.log(CurrentPage);
            //$('#RealEstateButton').click();
            $('#settings_togglebtn').click();
        }

    })

    /* function ExpandMenu(buttonId){
        alert(buttonId)
        $('#' + buttonId).click();
    } */
</script>
