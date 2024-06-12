<div x-data="{ isNavMenuOpen: false }">
    <div
        class="fixed bottom-0 left-0 right-0 bg-gray-100 md:hidden shadow-2xl shadow-black border-t border-gray-300 z-50 h-12">
        {{-- get the active menu via route request --}}
        @php
            $activeMenu = request()
                ->route()
                ->getName();
            $homeUrl = route('glover-website.index');
            $ordersUrl = route('glover-website.orders');
            $searchUrl = route('glover-website.search');
            $cartUrl = route('glover-website.cart');
            //
            $isHomeActive = $activeMenu == 'glover-website.index';
            $isOrdersActive = $activeMenu == 'glover-website.orders';
            $isSearchActive = $activeMenu == 'glover-website.search';
            $isCartActive = $activeMenu == 'glover-website.cart';
            //active class
            $activeClass = 'text-primary-500 border border-white';
            $inactiveClass = 'text-grey-500 border border-transparent';
        @endphp
        {{-- navbar --}}
        <div class="flex items-center space-x-2 justify-evenly justify-items-center my-1 p-2">
            {{-- home icon --}}
            <a href="{{ $homeUrl }}" class="{{ $isHomeActive ? $activeClass : $inactiveClass }}">
                <x-heroicon-s-home class="h-6 w-6" />
            </a>

            {{-- orders icon --}}
            @auth
                <a href="{{ $ordersUrl }}" class="{{ $isOrdersActive ? $activeClass : $inactiveClass }}">
                    <x-heroicon-s-shopping-bag class="h-6 w-6" />
                </a>
            @endauth

            {{-- search icon --}}
            <a href="{{ $searchUrl }}" class="{{ $isSearchActive ? $activeClass : $inactiveClass }}">
                <x-heroicon-s-search class="h-6 w-6" />
            </a>

            {{-- cart icon --}}
            <div>
                <livewire:extensions.glover-website.components.cart-section
                    styles="{{ $isCartActive ? $activeClass : $inactiveClass }}" />
            </div>

            {{-- more/profile icon --}}
            <div class="" x-on:click="isNavMenuOpen = true">
                <x-heroicon-s-menu class="h-6 w-6 " />
            </div>

        </div>

    </div>

    {{-- menu popup --}}
    <div x-show="isNavMenuOpen">
        <div class="fixed top-0 bottom-0 left-0 right-0 z-50">
            <div class='bg-black opacity-30 h-full w-full' x-on:click="isNavMenuOpen = false"></div>
            <div>
                <div class="absolute w-full bottom-0 bg-white rounded-t-lg shadow-lg overflow-clip py-4 px-4">
                    @auth
                        {{-- profile link with icon --}}
                        <a href="{{ route('glover-website.profile') }}"
                            class="p-2 text-sm text-gray-700 hover:bg-gray-100 flex gap-2 justify-between">
                            <p> {{ __('Profile') }}</p>
                            <x-heroicon-o-user class="h-5 w-5" />
                        </a>
                        {{-- my orders with icon --}}
                        <a href="{{ route('glover-website.orders') }}"
                            class="p-2 text-sm text-gray-700 hover:bg-gray-100 flex gap-2 justify-between">
                            <p>{{ __('My Orders') }}</p>
                            <x-heroicon-o-shopping-bag class="h-5 w-5" />
                        </a>
                        {{-- delivery addresses with icon --}}
                        <a href="{{ route('glover-website.addresses') }}"
                            class="p-2 text-sm text-gray-700 hover:bg-gray-100 flex gap-2 justify-between">
                            <p>{{ __('Delivery Addresses') }}</p>
                            <x-heroicon-o-location-marker class="h-5 w-5" />
                        </a>

                        {{-- logout with icon --}}
                        <a href="{{ route('glover-website.logout') }}"
                            class="p-2 text-sm text-gray-700 hover:bg-gray-100 flex gap-2 justify-between">
                            <p>{{ __('Logout') }}</p>
                            <x-heroicon-o-logout class="h-5 w-5" />
                        </a>
                    @else
                        {{-- login --}}
                        <a href="{{ route('glover-website.login') }}"
                            class="p-2 text-sm text-gray-700 hover:bg-gray-100 flex gap-2  justify-between">
                            <p class="">{{ __('Login') }}</p>
                            <x-heroicon-o-logout class="h-5 w-5" />
                        </a>
                        {{-- register --}}
                        <a href="{{ route('glover-website.register') }}"
                            class="p-2 text-sm text-gray-700 hover:bg-gray-100 flex gap-2  justify-between">
                            <p class="">{{ __('Register') }}</p>
                            <x-heroicon-o-user-add class="h-5 w-5" />
                        </a>
                    @endauth
                    <hr class="my-2" />
                    <div class="p-2 text-sm text-gray-700 hover:bg-gray-100 flex gap-2  justify-between">
                        <p class="">{{ __('Language') }}</p>
                        <div class="border-primary-500 rounded border overflow-clip">
                            <livewire:extensions.glover-website.components.language-selector />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
