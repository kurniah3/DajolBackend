<div class="sticky top-0 z-40 bg-primary-500 text-theme" id="header">
    <div class="py-4 px-4 md:px-0 container mx-auto w-full md:w-[80vw] lg:w-[70vw] xl:w-[60vw] flex ">
        {{-- logo section --}}
        <div class="flex flex-wrap items-center ltr:mr-auto rtl:ml-auto">
            <a href="{{ route('glover-website.index') }}" class="flex items-center">
                <img src="{{ setting('website.logo', setting('websiteLogo', asset('images/logo.png'))) }}"
                    alt="{{ setting('websiteName', env('APP_NAME')) }}" class="h-8 w-8 mr-2 object-center" />
                <span class="text-xl font-bold hidden md:block">{{ setting('websiteName', env('APP_NAME')) }}</span>
            </a>
        </div>
        {{-- location component --}}
        <div class="">
            <livewire:extensions.glover-website.components.location-selector />
        </div>
        {{-- search bar --}}
        <div class="hidden md:block w-full md:w-5/12 lg:w-4/12 mx-auto">
            <form action="{{ route('glover-website.search') }}">
                <div class="flex items-center rounded-lg theme-bg px-4 bg-white">
                    <input type="text"
                        class="text-black w-full px-2 border-0 placeholder:text-gray-500 placeholder:opacity-60 placeholder:text-xs placeholder:italic border-none focus:ring-0"
                        name="keyword" placeholder="{{ __('Search your preferred vendor/item/service') }}" />
                    {{-- search icon --}}
                    <button type="submit" class="text-gray-500">
                        <x-heroicon-o-search class="h-5 w-5 text-gray-500" />
                    </button>
                </div>
            </form>
        </div>

        {{-- menu section --}}
        <div class=" items-center gap-2 md:gap-4 justify-end hidden md:flex">

            {{-- language selector --}}
            <div class=''>
                <livewire:extensions.glover-website.components.language-selector />
            </div>


            {{-- cart menu with icon --}}
            <div class="hidden md:block">
                <livewire:extensions.glover-website.components.cart-section />
            </div>

            {{-- if there is no auth --}}

            @if (!auth()->check())
                <a href="{{ route('glover-website.login') }}"
                    class="text-sm font-bold flex gap-2 rounded-lg border px-2 py-1">
                    <x-heroicon-o-login class="h-5 w-5 text-theme" />
                    <p class="hidden md:block">{{ __('Login') }}</p>
                </a>
            @else
                {{-- profile icon with dropdown --}}
                <div class="relative" x-data="{ isMenuOpen: false }">
                    <div x-on:click="isMenuOpen = !isMenuOpen"
                        class="rounded-full p-2 hover:bg-white cursor-pointer hover:text-black ">
                        <x-heroicon-o-user class="h-6 w-6" />
                    </div>
                    <div x-show="isMenuOpen" x-on:click.away="isMenuOpen = false"
                        class="absolute right-0 w-48 bg-white rounded-lg shadow-lg overflow-clip">
                        {{-- profile link with icon --}}
                        <a href="{{ route('glover-website.profile') }}"
                            class="p-2 text-sm text-gray-700 hover:bg-gray-100 flex gap-2">
                            <x-heroicon-o-user class="h-5 w-5" />
                            {{ __('Profile') }}
                        </a>
                        {{-- my orders with icon --}}
                        <a href="{{ route('glover-website.orders') }}"
                            class="p-2 text-sm text-gray-700 hover:bg-gray-100 flex gap-2">
                            <x-heroicon-o-shopping-bag class="h-5 w-5" />
                            {{ __('My Orders') }}
                        </a>
                        {{-- delivery addresses with icon --}}
                        <a href="{{ route('glover-website.addresses') }}"
                            class="p-2 text-sm text-gray-700 hover:bg-gray-100 flex gap-2">
                            <x-heroicon-o-location-marker class="h-5 w-5" />
                            {{ __('Delivery Addresses') }}
                        </a>

                        {{-- logout with icon --}}
                        <a href="{{ route('glover-website.logout') }}"
                            class="p-2 text-sm text-gray-700 hover:bg-gray-100 flex gap-2">
                            <x-heroicon-o-logout class="h-5 w-5" />
                            {{ __('Logout') }}
                        </a>
                    </div>
                </div>
            @endif


        </div>
    </div>


</div>

{{-- navbar mobile --}}
@include('livewire.extensions.glover-website.includes.mobile-menu')
