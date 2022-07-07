@extends('layouts.nonav')
@section('content')
    {{--    <div class="login-container">--}}
    {{--    <div class="row">--}}
    {{--        <div class="col-md-8 login-wrapper bg-blue corp-image">--}}
    {{--            <div class="login-wrapper bg-blue">--}}
    {{--                <div class="bg-pic">--}}
    {{--            <h1>Welcome to the RevelationLegal</h1>--}} 
    {{--            <p>Our unique analysis tool provides unprecedented vision into a law firm's operations. </p>--}}
    {{--                    <img src="{{asset('imgs/corporate.jpg')}}" class="corp-image" />--}}
    {{--                    <div class="welcome-message">--}}

    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    </div>--}} 
    <div class="login-wrapper main-loginpage">
        <div class="container-new"> 
        <div class="row">
            <div class="col-md-8 bg-blue">
                <div class="bg-pic">
                    <div class="welcome-message">
                        <h1>Welcome to RevelationLegal</h1>
                        <p>Our unique analysis tool provides unprecedented vision into a law firm's operations. </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
{{--                <x-auth-card>--}}
<div class="mainloginpopup">
                    <x-guest-layout>
                        <x-jet-authentication-card>
                            <header class="flex items-center justify-center leading-tight p-2 md:p-4">
                                <img src="{{asset('imgs/logo-pdfhead.png')}}"/><br>

                            </header>
                            <x-jet-validation-errors class="mb-4" />
<h2>Login your Account</h2>
                            @if (session('status'))
                                <div class="mb-4 font-medium text-sm text-green-600">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div>
                                    {{-- <x-jet-label for="email" value="{{ __('Username or Email') }}" /> --}}
                                    <x-jet-input class="block mt-1 w-full" type="text" placeholder="Username" name="username" :value="old('username')" required autofocus />
                                </div>

                                <div class="mt-4">
                                    {{-- <x-jet-label for="password" value="{{ __('Password') }}" /> --}}
                                    <x-jet-input id="password" placeholder="Password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                                </div>

                                <div class="loginremember-sec block mt-4">
                                    <p class="text-sm text-white d-flex mb-2">
                                        <i class="fas fa-shield-alt"></i> 
                                        <span class="pl-2">Your passwords at company are encrypted and secured</span></p>
                                    <label for="remember_me" class="flex items-center">
                                        <x-jet-checkbox id="remember_me" name="remember" />
                                        <span class="ml-2 text-sm text-white">{{ __('Remember me') }}</span>
                                    </label>
                                </div>
                                <div class="text-center">
                                    <x-jet-button class="ml-4" style="background-color: #008EC1">
                                        {{ __('Log in') }}
                                    </x-jet-button>


                                </div>
                                <div class="flex items-center justify-center my-2">
                                    @if (Route::has('password.request'))
                                        <a class="underline d-block text-sm text-white hover:text-gray-900" href="{{ route('password.request') }}">
                                            {{ __('Forgot your password?') }}
                                        </a>
                                    @endif


                                </div>

                            </form>
                            <div class="login-cardfooter">Copyright© 2022 Revelation Legal<br>All Rights Reserved</div>
                        </x-jet-authentication-card> 
                    </x-guest-layout>
                </div>
            </div>
        </div>
        </div> 
@endsection

