<div class=" accprofile-infosec">
<x-jet-form-section class="border" submit="updateProfileInformation">
    <x-slot name="title">
        <div class=" cont-mtitle text-main font-bold mt-4 mt-md-5">
            <h1>{{ __('Profile Information') }}</h1>
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="text-ns  text-main">
            {{ __('Update your account\'s profile information and email address.') }}
        </div>
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="sm:col-span-4 ">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden"
                       wire:model="photo"
                       x-ref="photo"
                       x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            "/>

                <x-jet-label for="photo" value="{{ __('Photo') }}"/>

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->first_name }}"
                         class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview">
                    <span class="block rounded-full w-20 h-20"
                          x-bind:style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-jet-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-jet-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-jet-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-jet-secondary-button>
                @endif

                <x-jet-input-error for="photo" class="mt-2"/>
            </div>
        @endif

    <!-- Name -->
        <div>
            <div>
            <label class="proftext-ns" for="first_name" value="{{ __('First Name') }}">First name</label>
            </div>
            <div>
                <x-jet-input id="first_name" type="text" class="mt-1 block w-full" wire:model.defer="state.first_name"
                             autocomplete="firstname" />
                <x-jet-input-error for="first_name" class="mt-2"/>
            </div>
        </div>
        <div>
            <div>
                <label class="proftext-ns" for="last_name" value="{{ __('Last Name') }}">Last Name</label>
            </div>
            <div>
                <x-jet-input id="last_name" type="text" class="mt-1 block w-full" wire:model.defer="state.last_name"
                             autocomplete="lastname" />
                <x-jet-input-error for="last_name" class="mt-2"/>
            </div>
        </div>
        <div>
            <div>
                <label class="proftext-ns" for="username" value="{{ __('Username') }}">Username</label>
            </div>
            <div>
                <x-jet-input id="username" type="text" class="mt-1 block w-full" wire:model.defer="state.username"
                             autocomplete="username" />
                <x-jet-input-error for="username" class="mt-2"/>
            </div>
        </div>
        <!-- Email -->
        <div>
            <div>
                <label class="proftext-ns" for="email" value="{{ __('Email') }}">Email</label>
            </div>
            <div>
                <x-jet-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" />
                <x-jet-input-error for="email" class="mt-2"/>
            </div>
        </div>
    </x-slot>
    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled" wire:target="photo" class="justify-center prosave-btn ">
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section> 
</div>