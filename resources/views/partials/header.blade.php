<header class=" site-header" id="header"> 

    <div class="header-inner flex items-center justify-between px-3 mx-auto  ">
        <!-- Mobile hamburger -->
        <button style="color: #fff;" class="menuopen-btn p-1 mr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-purple" @click="toggleSideMenu" aria-label="Menu">
            <svg class="w-8 h-8" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            </svg>
        </button>
        <div class="site-logo  mr-auto"><img class="" src="{{asset('imgs/revel-logo.png')}}"></div>
        <div class="site-menu d-none d-md-block">
            <ul class="menu nav-menu border-b md:border-none flex list-reset  w-full md:w-auto" style="margin-bottom:0;">
                <li class="border-t md:border-none">
                    <a class="block md:inline-block @if(Route::currentRouteName() == 'projects') active @endif" href="{{route('projects')}}">Projects</a>
                </li>
                @if(\Auth::check() && \Auth::user()->is_admin)
                <li class="border-t md:border-none">
                    <a class="block md:inline-block @if(Route::currentRouteName() == 'users.all') active @endif" href="{{ route('users.all') }}">All Users</a>
                </li>
                @endif
                
                <li class="border-t md:border-none">
                    <a id='myaccuser' class="block md:inline-block @if(Route::currentRouteName() == 'profile.show') active @endif" href="{{ route('profile.show') }}">My Account</a>

                </li>
                <li class="dropdown border-t md:border-none">
                    <a class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    User Guides  
                    </a>
                    @php
                        $fileName1 = App\Models\Manageguide::where('id',2)->pluck('file_name')[0];
                        $fileName2 = App\Models\Manageguide::where('id',1)->pluck('file_name')[0]; 
                    @endphp
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="background: #000;" > 
                    <a class="dropdown-item" target="blank" href="{{ url('/public/guides/'.$fileName1.'') }}">User Guide</a>
                    <a class="dropdown-item" target="blank" href="{{ url('/public/guides/'.$fileName2.'') }}">Real Estate Setup</a>
                    </ul>
                </li>  
                <li class="border-t md:border-none">
                    <a class="block md:inline-block @if(Route::currentRouteName() == 'support.index') active @endif" href="{{ route('support.index') }}">Support</a>
                </li>
                <li class="border-t md:border-none">
                    <a class="block md:inline-block @if(Route::currentRouteName() == 'help-desk') active @endif" href="{{ route('help-desk') }}">Manage Help Content</a>
                </li>
                <li class="border-t md:border-none">
                    <a class="block md:inline-block @if(Route::currentRouteName() == 'logout') active @endif" href="{{ route('logout') }}">Log Out({{request()->user()->first_name}})</a>
                </li>
                
            </ul>
        </div> 
    </div>
</header>
<script>
 $(document).ready(()=>{
        CurrentUrl = window.location.href;
        CurrentPage = CurrentUrl.split('/')[5];
        NextParam = CurrentUrl.split('/')[6];

        

        if(CurrentPage == 'user'){
            console.log(CurrentPage);
            //$('#RealEstateButton').click();
            $('#myaccuser').addClass('active');
        }
 })
</script>  
