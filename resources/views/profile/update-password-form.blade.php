<div class=" accupdatepas-sec">
<x-jet-form-section class="border" submit="updatePassword">
    <x-slot name="title">
        <div class="cont-mtitle text-main font-bold mt-4 mt-md-5">
            <h1>{{ __('Update Password') }}</h1>
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="text-ns ">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </div>
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <label class="proftext-ns" for="current_password" value="{{ __('Current Password') }}" >Current Password</label>
            <x-jet-input id="current_password" type="password" class="mt-1 block w-full" wire:model.defer="state.current_password" autocomplete="current-password" />
            <x-jet-input-error for="current_password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <label class="proftext-ns" for="password" value="{{ __('New Password') }}">New Password</label>
            <x-jet-input id="password" type="password" class="mt-1 block w-full" wire:model.defer="state.password" autocomplete="new-password" />
            <x-jet-input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
        <label class="proftext-ns" for="password_confirmation" value="{{ __('Confirm Password') }}" >Confirm Password</label>
            <x-jet-input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model.defer="state.password_confirmation" autocomplete="new-password" />
            <x-jet-input-error for="password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button class="prosave-btn" >
                {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section> 
</div> 
