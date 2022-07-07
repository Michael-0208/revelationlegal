<x-app-layout>
    <x-slot name="header">
        <h2 class="py-2 px-5">
            {{ __('Your Projects') }}
        </h2>
    </x-slot>

    <div>
        <div>
            @livewire('user-survey')
        </div>
    </div>
</x-app-layout>
