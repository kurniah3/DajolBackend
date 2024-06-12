@section('title', __('Profile'))
<div class="mb-20">
    {{-- breadcrumb --}}
    @include('livewire.extensions.glover-website.components.nav-breadcrumb', [
        'links' => [
            [
                'title' => __('Home'),
                'url' => route('glover-website.index'),
            ],
            [
                'title' => __('Profile'),
                'url' => '',
            ],
        ],
    ])


    <div class="bg-white rounded-sm p-4 shadow-sm border-gray-100">
        {{-- header --}}
        <div class="mt-2 mb-4">
            <div class="font-semibold text-lg">{{ __('My Profile') }}</div>
            <div class="text-gray-500 text-sm">{{ __('Account Details') }}</div>
        </div>
        {{-- tab for info and edit --}}
        <x-tab.tabview>

            <x-slot name="header">
                <x-tab.header tab="1" title="{{ __('Profile Details') }}" />
                <x-tab.header tab="2" title="{{ __('Edit Profile') }}" />
                <x-tab.header tab="3" title="{{ __('Change Password') }}" />
            </x-slot>

            <x-slot name="body">
                <x-tab.body tab="1">
                    <div class="w-full md:w-6/12 lg:w-4/12">
                        <div class="flex flex-col space-y-2">
                            <div class="flex flex-row">
                                <div class="w-1/3">
                                    <div class="font-semibold">{{ __('Name') }}</div>
                                </div>
                                <div class="w-2/3">
                                    <div class="text-gray-500">{{ $user->name }}</div>
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="w-1/3">
                                    <div class="font-semibold">{{ __('Email') }}</div>
                                </div>
                                <div class="w-2/3">
                                    <div class="text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="w-1/3">
                                    <div class="font-semibold">{{ __('Phone') }}</div>
                                </div>
                                <div class="w-2/3">
                                    <div class="text-gray-500">{{ $user->phone }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-tab.body>
                <x-tab.body tab="2">
                    <div class="w-full md:w-6/12 lg:w-4/12">
                        <form wire:submit.prevent="updateProfile">
                            <x-input name="name" title="{{ __('Name') }}" />
                            <x-input name="email" title="{{ __('Email Address') }}" />
                            <x-phoneselector />
                            <div class="flex justify-end mt-4">
                                <x-buttons.primary title="{{ __('Update Profile') }}" />
                            </div>
                        </form>
                    </div>
                </x-tab.body>
                <x-tab.body tab="3">
                    <div class="w-full md:w-6/12 lg:w-4/12">
                        <form wire:submit.prevent="changePassword">
                            <x-input type="password" name="current_password" title="{{ __('Current Password') }}" />
                            <x-input type="password" name="new_password" title="{{ __('New Password') }}" />
                            <x-input type="password" name="new_password_confirmation"
                                title="{{ __('Confirm New Password') }}" />

                            <div class="flex justify-end mt-4">
                                <x-buttons.primary title="{{ __('Change Password') }}" />
                            </div>
                        </form>
                    </div>
                </x-tab.body>
            </x-slot>
        </x-tab.tabview>

    </div>

</div>
@include('layouts.partials.phoneselector')
@push('styles')
    <style>
        .iti {
            width: 100%;
        }

        .intl-tel-input {
            width: 100%;
        }
    </style>
@endpush
