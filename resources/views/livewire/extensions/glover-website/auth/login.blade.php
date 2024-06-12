@section('title', __('Login'))
<div class="w-full md:w-6/12 lg:w-5/12 mx-auto bg-white rounded border border-gray-200 p-4 shadow mt-10 mb-20">
    <div class="mb-2">
        <p class="font-bold text-2xl">{{ __('Login') }}</p>
        <p>
            {{ __("Don't have an account?") }}
            <a href="{{ route('glover-website.register') }}"
                class="text-primary-500 hover:text-primary-700">{{ __('Register') }}</a>
        </p>
    </div>
    <form class="">
        {{-- email input --}}
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
            <div class="mt-1">
                <input type="email" name="email" id="email" autocomplete="email" wire:model.defer="email"
                    class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md" />
                @error('email')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
        </div>
        {{-- password input with show/hide value using alphine js --}}
        <div class="mb-4" x-data="{ showPassword: false }">
            <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
            <div class="mt-1 relative">
                <input x-bind:type="showPassword ? 'text' : 'password'" type="password" name="password" id="password"
                    autocomplete="current-password" wire:model.defer="password"
                    class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md" />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                    <button type="button"
                        class="text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700"
                        x-on:click="showPassword = !showPassword">
                        <span x-show="!showPassword">
                            <x-heroicon-o-eye class="h-5 w-5" />
                        </span>
                        <span x-show="showPassword">
                            <x-heroicon-o-eye-off class="h-5 w-5" />
                        </span>
                    </button>
                </div>
                @error('password')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
        </div>
        {{-- forgot passord link to the end --}}
        <div class="mb-4 flex justify-end">
            <a href="{{ route('glover-website.password.forgot') }}"
                class="text-primary-500 hover:text-primary-700">{{ __('Forgot your password?') }}</a>
        </div>


        {{-- login button --}}
        <div class="my-4">
            <button type="submit" wire:click.prevent="emaillogin"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                {{ __('Login') }}
            </button>
        </div>
    </form>

    {{-- loading --}}
    @include('livewire.extensions.glover-website.components.loading')
</div>
