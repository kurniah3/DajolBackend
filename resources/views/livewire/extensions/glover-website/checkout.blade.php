@section('title', __('Checkout'))
<div class="mb-20">

    {{-- breadcrumb --}}
    @include('livewire.extensions.glover-website.components.nav-breadcrumb', [
        'links' => [
            [
                'title' => __('Home'),
                'url' => route('glover-website.index'),
            ],
            [
                'title' => __('Checkout'),
                'url' => url()->current(),
            ],
        ],
    ])


    {{-- details --}}
    <div class="p-2 md:py-4 md:p-8 rounded bg-white">
        <p class="text-xl font-semibold">{{ __('Checkout') }}</p>
        <p class="text-gray-600 text-sm font-medium">{{ __('Delivery Address, Time/Date, Payment Method and More') }}</p>

    </div>
    <div class="block md:flex mt-2 md:gap-4">
        <div class="p-2 md:p-8 rounded bg-white w-full">
            <div x-data="{ showData: false }" class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-8">
                        <p class="text-xl font-semibold">{{ __('Order Details') }}</p>
                        <a href="{{ route('glover-website.cart') }}"
                            class="text-sm text-primary-500 hover:text-primary-600">{{ __('Edit Cart') }}</a>
                    </div>
                    <x-heroicon-o-chevron-down class="w-6 h-6" x-on:click="showData = true" x-show="!showData" />
                    <x-heroicon-o-chevron-up class="w-6 h-6" x-on:click="showData = false" x-show="showData" />


                </div>
                <div x-show="showData" class="border-b pb-4 mb-2">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2 text-left">{{ __('Item/Product') }}</th>
                                <th class="py-2 text-left">{{ __('Subtotal') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $cartItem)
                                <tr>
                                    <td class="text-sm flex flex-wrap py-2">
                                        {{ $cartItem['name'] }}
                                        <span class="mx-2 font-bold text-sm">x {{ $cartItem['quantity'] }}</span>
                                    </td>
                                    <td class="text-sm  py-2">
                                        {{ currencyFormat(($cartItem['sell_price'] + ($cartItem['options_total_price'] ?? 0.0)) * $cartItem['quantity']) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="my-4">
                {{-- if vendor allow order scheduling --}}
                @if ($vendor->allow_schedule_order)
                    {{-- toggle option to enable the schedule order selection --}}
                    <x-checkbox name="schedule_order" :defer="false">
                        <x-slot name="title">
                            {{ __('Schedule Order') }}
                        </x-slot>
                        <x-slot name="description">
                            {{ __('Do you want to schedule this order?') }}
                        </x-slot>
                    </x-checkbox>

                    @if ($schedule_order)
                        {{-- booking date --}}
                        <div class="mt-4">
                            <p class="text-gray-600 text-sm font-semibold">{{ __('Order Date') }}</p>
                            <p class="text-gray-600 text-sm font-light">
                                {{ __('When do you want to schedule order for?') }}
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-select title="{{ __('Schedule Date') }}" name="schedule_date" :options="$datesSlot"
                                    :defer="false" :noPreSelect="true" />
                                <x-select title="{{ __('Schedule Time') }}" name="schedule_time" :options="$timesSlot"
                                    :defer="false" :noPreSelect="true" />
                            </div>
                        </div>
                    @endif
                    <hr class="mt-4" />
                @endif

                {{-- pickup --}}
                @if ($vendor->pickup)
                    <x-checkbox name="is_pickup" :defer="false">
                        <x-slot name="title">
                            {{ __('Pickup') }}
                        </x-slot>
                        <x-slot name="description">
                            {{ __('Do you want to pickup this order?') }}
                        </x-slot>
                    </x-checkbox>
                @endif
                {{-- delivery address --}}
                @if ($vendor->delivery && $is_pickup == false)
                    <x-label for="delivery_address_id" title="{{ __('Delivery Address') }}">
                        <x-select name="delivery_address_id" :options="$deliveryAddresses" :noPreSelect="true" :defer="false" />
                    </x-label>
                @endif
                <hr class="my-4" />
                {{-- payment methods --}}
                <div class="py-4">
                    <p class="font-semibold">{{ __('Payment Methods') }}</p>
                    <p class="text-sm">{{ __('How do you want to pay for this order?') }}</p>
                    <x-input-error message="{{ $errors->first('payment_method_id') }}" class="mt-2" />
                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($paymentMethods ?? [] as $paymentMethod)
                            @php
                                $selectedStyle = '';
                                if ($paymentMethod->id == $payment_method_id) {
                                    $selectedStyle = 'border-2 border-primary-500';
                                }
                            @endphp
                            <div class="{{ $selectedStyle }} border rounded flex items-center cursor-pointer"
                                wire:click="onPaymentMethodSelected('{{ $paymentMethod->id }}')">
                                <img src="{{ $paymentMethod->photo }}" alt="{{ $paymentMethod->name }}"
                                    class="w-16 h-16 object-cover" />
                                <div class="p-2">
                                    <p class="font-semibold">{{ $paymentMethod->name }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <hr />

        </div>


        <div class="w-full md:w-5/12 p-2 md:p-4 rounded bg-white">
            <p class="font-bold text-2xl">{{ __('Order Summary') }}</p>
            <hr class="my-2" />
            {{-- subtotal --}}
            <div class="flex justify-between items-center my-2">
                <p class="text-gray-600 text-sm font-semibold">{{ __('Subtotal') }}</p>
                <p class="text-gray-600 text-sm font-light">
                    {{ currencyFormat($subtotal ?? 0.0) }}
                </p>
            </div>
            <hr class="my-2" />
            <div class="space-y-2">
                {{-- discount --}}
                @if ($discount > 0)
                    <div class="flex justify-between items-center">
                        <p class="text-gray-600 text-sm font-semibold">{{ __('Discount') }}</p>
                        <p class="text-gray-600 text-sm font-light">
                            {{ currencyFormat($discount) }}
                        </p>
                    </div>
                @endif
                {{-- delivery fee --}}
                @if ($delivery_fee > 0)
                    <div class="flex justify-between items-center">
                        <p class="text-gray-600 text-sm font-semibold">{{ __('Delivery Fee') }}</p>
                        <p class="text-gray-600 text-sm font-light">
                            {{ currencyFormat($delivery_fee) }}
                        </p>
                    </div>
                @endif
                {{-- tax --}}
                <div class="flex justify-between items-center">
                    <p class="text-gray-600 text-sm font-semibold">{{ __('Tax') }}({{ $vendor->tax }}%)
                    </p>
                    <p class="text-gray-600 text-sm font-light">
                        {{ currencyFormat($tax) }}
                    </p>
                </div>

                {{-- fees if any --}}
                @if ($fees)
                    @foreach ($fees as $fee)
                        <div class="flex justify-between items-center">
                            <p class="text-gray-600 text-sm font-semibold">{{ $fee['name'] }}</p>
                            <p class="text-gray-600 text-sm font-light">
                                {{ currencyFormat($fee['amount']) }}
                            </p>
                        </div>
                    @endforeach
                @endif
            </div>
            <hr class="my-2" />
            {{-- total --}}
            <div class="flex justify-between items-center my-2">
                <p class="text-gray-600 text-sm font-semibold">{{ __('Total') }}</p>
                <p class="text-gray-600 text-sm font-light">
                    {{ currencyFormat($total) }}
                </p>
            </div>
            <hr class="my-2" />
            <x-textarea name="note" title="{{ __('Note') }}" :defer="false" h="h-24" />
            <hr class="my-2" />
            {{-- place booking --}}
            <div class="my-2">
                <button
                    class="w-full bg-primary-500 text-white rounded py-2 font-semibold flex space-x-2 justify-center items-center"
                    wire:click="placeOrder">
                    {{-- check icon --}}
                    <x-heroicon-o-check class="w-4 h-4 inline-block font-bold" />
                    <span>{{ __('Place Order') }}</span>
                </button>
            </div>

        </div>



    </div>
    {{-- loading --}}
    @include('livewire.extensions.glover-website.components.loading')
</div>
