@section('title', __('Cart'))
<div class="mb-20">

    {{-- breadcrumb --}}
    @include('livewire.extensions.glover-website.components.nav-breadcrumb', [
        'links' => [
            [
                'title' => __('Home'),
                'url' => route('glover-website.index'),
            ],
            [
                'title' => __('Cart'),
                'url' => url()->current(),
            ],
        ],
    ])

    @empty($cartItems)
        <div class="bg-white p-4 py-12 rounded-sm shadow-sm">
            <div class="flex flex-col items-center justify-center">
                <x-lineawesome-shopping-cart-solid class="w-20 h-20 text-gray-400" />
                <p class="text-gray-400 text-center mt-2 mb-4">{{ __('Your cart is empty') }}</p>
                {{-- home button --}}
                <a href="{{ route('glover-website.index') }}"
                    class="mt-4 px-4 py-2 bg-primary-500 text-white rounded-sm hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-opacity-50">
                    {{ __('Go to Home') }}
                </a>
            </div>
        </div>
    @else
        {{-- if cart items --}}
        <div class="block lg:flex gap-4">
            <div class="p-4 bg-white w-full rounded-lg border border-gray-100 shadow-sm">
                <div class="flex justify-start">
                    <p class="font-light text-lg border-b-2 pb-1">{{ __('Cart Items') }}</p>
                </div>
                {{-- large screen --}}
                <div class="hidden lg:block">
                    <table class="w-full mt-4">
                        {{-- header for Product, price qty subtotal --}}
                        <thead class="">
                            <tr class="border-b">
                                <th class=" py-2 font-medium text-sm text-left"></th>
                                <th class="py-2 font-medium text-sm text-left">{{ __('Product') }}</th>
                                <th class=" py-2 font-medium text-sm text-left">{{ __('Price') }}</th>
                                <th class=" py-2 font-medium text-sm text-left">{{ __('Options Price') }}</th>
                                <th class="py-2 font-medium text-sm text-left px-4">{{ __('Qty') }}</th>
                                <th class=" py-2 font-medium text-sm text-left">{{ __('Subtotal') }}</th>
                                <th class=""></th>
                            </tr>
                        </thead>
                        {{-- body --}}
                        <tbody>
                            @foreach ($cartItems as $cartItem)
                                <tr class="  border-b">
                                    <td class="py-2 ">
                                        <img src="{{ $cartItem['photo'] }}" alt="{{ $cartItem['name'] }}"
                                            class="w-10 h-10 object-cover rounded-lg" />
                                    </td>
                                    <td class="py-2 text-sm">
                                        <p>{{ $cartItem['name'] }} </p>
                                        <p class="text-xs text-gray-400">
                                            {{ $cartItem['extra_data']['selectedOptionFlatten'] ?? '' }}</p>
                                    </td>
                                    <td class="py-2 text-sm justify-start items-start">
                                        {{ currencyFormat($cartItem['sell_price']) }}
                                    </td>
                                    <td class="py-2 text-sm justify-start items-start">
                                        {{ currencyFormat($cartItem['options_total_price'] ?? 0.0) }}
                                    </td>
                                    <td class="py-2 px-4 text-sm">
                                        <div class="flex items-center">
                                            <button wire:click="decrementItem('{{ $cartItem['code'] }}')"
                                                class="p-2 bg-gray-300 w-8 h-8 rounded-full flex justify-center items-center text-black hover:text-white focus:outline-none focus:ring-2">
                                                <x-lineawesome-minus-solid class="w-3 h-3 font-bold" />
                                            </button>
                                            <span class="px-4">{{ $cartItem['quantity'] }}</span>
                                            <button wire:click="incrementItem('{{ $cartItem['code'] }}')"
                                                class="p-2 bg-gray-300 w-8 h-8 rounded-full flex justify-center items-center text-black hover:text-white focus:outline-none focus:ring-2">
                                                <x-lineawesome-plus-solid class="w-3 h-3 font-bold" />
                                            </button>
                                        </div>
                                    </td>
                                    <td class="py-2  text-sm">
                                        {{ currencyFormat(($cartItem['sell_price'] + ($cartItem['options_total_price'] ?? 0)) * $cartItem['quantity']) }}
                                    </td>
                                    <td class="py-2 px-2">
                                        <button wire:click="removeItem('{{ $cartItem['code'] }}')"
                                            class="text-xl text-red-500 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-opacity-50">
                                            <x-lineawesome-times-solid class="w-5 h-5 font-bold" />
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- small screen --}}
                <div class="block lg:hidden">
                    <table class="w-full mt-4">
                        {{-- header for Product, price qty subtotal --}}
                        <thead class="">
                            <tr class="border-b">
                                <th class="py-2 font-medium text-sm text-left">{{ __('Product') }}</th>
                                <th class="py-2 font-medium text-sm text-left px-4">{{ __('Qty') }}</th>
                                <th class=""></th>
                            </tr>
                        </thead>
                        {{-- body --}}
                        <tbody>
                            @foreach ($cartItems as $cartItem)
                                <tr class="  border-b">
                                    <td class="py-2 text-sm"> {{ $cartItem['name'] }} </td>
                                    <td class="py-2 px-4 text-sm">
                                        <div class="flex items-center">
                                            <button wire:click="decrementItem('{{ $cartItem['code'] }}')"
                                                class="bg-gray-300 w-5 h-5 rounded-full flex justify-center items-center text-black hover:text-white focus:outline-none focus:ring-2">
                                                <x-lineawesome-minus-solid class="w-4 h-4 font-bold" />
                                            </button>
                                            <span class="px-2">{{ $cartItem['quantity'] }}</span>
                                            <button wire:click="incrementItem('{{ $cartItem['code'] }}')"
                                                class="bg-gray-300 w-5 h-5 rounded-full flex justify-center items-center text-black hover:text-white focus:outline-none focus:ring-2">
                                                <x-lineawesome-plus-solid class="w-4 h-4 font-bold" />
                                            </button>
                                        </div>
                                    </td>
                                    <td class="py-2 px-2">
                                        <button wire:click="removeItem('{{ $cartItem['code'] }}')"
                                            class="text-xl text-red-500 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-opacity-50">
                                            <x-lineawesome-times-solid class="w-5 h-5 font-bold" />
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- clear all --}}
                <div class="flex justify-end p-4">
                    <button wire:click="clearCart"
                        class="text-sm text-red-500 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-opacity-50">
                        {{ __('Clear All') }}
                    </button>
                </div>
            </div>
            <div class="p-4 bg-white w-full lg:w-4/12 rounded-lg border border-gray-100 shadow-sm">
                <p class="font-light text-lg"> {{ __('Checkout Summary') }}</p>
                {{-- subtotal --}}
                <div class="flex justify-between mt-4">
                    <p class="text-sm">{{ __('Subtotal') }}</p>
                    <p class="text-sm">{{ currencyFormat($subtotal) }}</p>
                </div>
                <div class="py-2 border-t border-b my-2">
                    {{-- apply coupon --}}
                    <div class="flex justify-start items-start space-x-1">
                        <x-input type="text" name="coupon_code" class="mt-0 w-2/3"
                            placeholder="{{ __('Enter Coupon Code') }}" />
                        <button wire:click="applyCoupon"
                            class="bg-primary-500 w-1/3 h-9 mt-1 rounded flex justify-center items-center text-theme hover:shadow-sm text-sm hover:text-white focus:outline-none focus:ring-2">
                            {{ __('Apply') }}
                        </button>
                    </div>

                    {{-- discount --}}
                    <div class="flex justify-between mt-4">
                        <p class="text-sm">{{ __('Discount') }}</p>
                        <p class="text-sm">{{ currencyFormat($discount) }}</p>
                    </div>
                </div>
                {{-- total --}}
                <div class="flex justify-between mt-4">
                    <p class="text-sm">{{ __('Total') }}</p>
                    <p class="text-sm">{{ currencyFormat($total) }}</p>
                </div>

                <div class="mt-4">
                    <button wire:click="proceedToCheckout"
                        class="bg-primary-500 w-full h-9 rounded flex justify-center items-center text-theme hover:shadow-sm text-sm hover:text-white focus:outline-none focus:ring-2">
                        {{ __('Proceed to Checkout') }}
                    </button>
                </div>
            </div>

        </div>

    @endempty

    {{-- loading --}}
    @include('livewire.extensions.glover-website.components.loading')
</div>
