
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>
    <div class="container">
    <div> 
        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
            @livewire('profile.update-profile-information-form')
        @endif
    </div>
    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
    <div>
        @livewire('profile.update-password-form')
    </div>
    @endif
    <div class="">
        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <div>
                @livewire('profile.two-factor-authentication-form') 
            </div>

            
        @endif

        <div>
            @livewire('profile.logout-other-browser-sessions-form')
        </div>

        @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
            

            <div>
                @livewire('profile.delete-user-form')
            </div>
        @endif
    </div>
    </div>
</x-app-layout> 
