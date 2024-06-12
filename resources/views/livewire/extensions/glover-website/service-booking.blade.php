@section('title', $service->name)
@section('styles')
    {{-- seo links --}}
    <link rel="canonical"
        href="{{ route('glover-website.service', [
            'id' => $service->id,
            'slug' => \Str::slug($service->name),
        ]) }}" />
    <meta name="description" content="{{ $service->description }}" />
    <meta name="keywords" content="{{ implode(',', explode($service->name, '')) }}" />
    <meta name="author" content="{{ $service->vendor->name }}" />
    <meta name="robots" content="index, follow" />
    <meta name="googlebot" content="index, follow" />
    <meta name="google" content="notranslate" />
    {{-- twitter --}}
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="{{ $service->vendor->name }}" />
    <meta name="twitter:title" content="{{ $service->name }}" />
    <meta name="twitter:description" content="{{ $service->description }}" />
    <meta name="twitter:image" content="{{ $service->image }}" />
    {{-- facebook --}}
    <meta property="og:url"
        content="{{ route('glover-website.service', [
            'id' => $service->id,
            'slug' => \Str::slug($service->name),
        ]) }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $service->name }}" />
    <meta property="og:description" content="{{ $service->description }}" />
    <meta property="og:image" content="{{ $service->image }}" />
    <meta property="og:site_name" content="{{ $service->vendor->name }}" />
    <meta property="og:locale"
        content="{{ Session::get('locale', env('WEBSITE_DEFAULT_LANGUAGE', config('app.locale'))) }}" />
@endsection
<div class="mb-20">

    {{-- breadcrumb --}}
    @include('livewire.extensions.glover-website.components.nav-breadcrumb', [
        'links' => [
            [
                'title' => __('Home'),
                'url' => route('glover-website.index'),
            ],
            [
                'title' => $service->vendor->vendor_type->name,
                'url' => route('glover-website.vendor.type', [
                    'id' => $service->vendor->vendor_type->id,
                    'slug' =>
                        $service->vendor->vendor_type->slug ?? \Str::slug($service->vendor->vendor_type->name),
                ]),
            ],
            [
                'title' => __('Service Booking'),
                'url' => url()->current(),
            ],
        ],
    ])

    {{-- details --}}
    <div class="p-2 md:py-4 md:p-8 rounded bg-white">
        <p class="text-xl font-semibold">{{ __('Booking Summray/Checkout') }}</p>
        <p class="text-gray-600 text-sm font-medium">{{ __('Service Details') }}</p>

    </div>
    <div class="block md:flex mt-2 md:gap-4">
        <div class="p-2 md:p-8 rounded bg-white w-full">

            <div class="w-full flex items-center justify-start">
                <img src="{{ $service->photo }}" alt="{{ $service->name }}" class="w-16 h-16 object-cover rounded">
                <div class="w-full">
                    <p class="text-gray-600 text-sm font-semibold">{{ $service->name }}</p>
                    @if (!empty($selectedGroupOptionsFlatten))
                        <p class="text-gray-400 text-xs font-light">{{ $selectedGroupOptionsFlatten ?? '' }}</p>
                    @endif
                    <p class="text-gray-600 text-sm font-light">{{ $service->vendor->name }}</p>
                </div>
                <div class="flex justify-end w-full items-center space-x-4">
                    {{-- price --}}
                    <div class="flex justify-start items-center">
                        @if ($service->discount_price > 0)
                            <p class="text-sm line-through font-normal ltr:mr-2 rtl:ml-2 text-gray-400">
                                {{ currencyFormat($service->price) }}</p>
                            <p class="text-md font-semibold text-primary-600">
                                {{ currencyFormat($service->discount_price) }}
                            </p>
                        @else
                            <p class="text-md font-semibold text-primary-600">
                                {{ currencyFormat($service->price) }}
                            </p>
                        @endif

                        @if ($service->duration != null && $service->duration != 'fixed')
                            <span class="text-gray-400 text-xs font-normal mx-1">
                                / {{ $service->duration }}
                            </span>
                        @endif
                    </div>

                    {{-- add qty selector if the service->duration is not null and not fixed --}}
                    @if ($service->duration != 'fixed')
                        <div class='flex space-x-2'>
                            {{-- - sign --}}
                            <button class="bg-gray-200 rounded-full w-6 h-6 flex items-center justify-center"
                                wire:click="decreaseDuration">
                                <x-heroicon-o-minus class="w-4 h-4 text-gray-600" />
                            </button>
                            {{-- selected duration --}}
                            <span>{{ $duration }}</span>
                            {{-- + sign --}}
                            <button class="bg-gray-200 rounded-full w-6 h-6 flex items-center justify-center"
                                wire:click="increaseDuration">
                                <x-heroicon-o-plus class="w-4 h-4 text-gray-600" />
                            </button>
                        </div>


                        {{-- subtotal
                        <div>
                            <p class="text-gray-600 text-sm font-semibold">{{ __('Subtotal') }}</p>
                            <p class="text-gray-600 text-sm font-light">
                                {{ currencyFormat($subtotal ?? 0.0) }}
                            </p>
                        </div> --}}
                    @endif
                </div>
            </div>

            <hr />
            <div class="my-4">
                {{-- if vendor allow order scheduling --}}
                @if ($service->vendor->allow_schedule_order)
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
                            <p class="text-gray-600 text-sm font-semibold">{{ __('Booking Date') }}</p>
                            <p class="text-gray-600 text-sm font-light">
                                {{ __('When do you want to book this service?') }}
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
                {{-- booking address --}}
                <x-label for="booking_address_id" title="{{ __('Booking Address') }}">
                    <x-select name="delivery_address_id" :options="$deliveryAddresses" :noPreSelect="true" />
                </x-label>

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
                {{-- selectedOptionsTotalPrice --}}
                @if ($selectedOptionsTotalPrice > 0)
                    <div class="flex justify-between items-center">
                        <p class="text-gray-600 text-sm font-semibold">{{ __('Options') }}</p>
                        <p class="text-gray-600 text-sm font-light">
                            {{ currencyFormat($selectedOptionsTotalPrice) }}
                        </p>
                    </div>
                @endif
                {{-- discount --}}
                @if ($discount > 0)
                    <div class="flex justify-between items-center">
                        <p class="text-gray-600 text-sm font-semibold">{{ __('Discount') }}</p>
                        <p class="text-gray-600 text-sm font-light">
                            {{ currencyFormat($discount) }}
                        </p>
                    </div>
                @endif
                {{-- tax --}}
                <div class="flex justify-between items-center">
                    <p class="text-gray-600 text-sm font-semibold">{{ __('Tax') }}({{ $service->vendor->tax }}%)
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
            {{-- place booking --}}
            <div class="my-2">
                <button
                    class="w-full bg-primary-500 text-white rounded py-2 font-semibold flex space-x-2 justify-center items-center"
                    wire:click="placeBooking">
                    {{-- check icon --}}
                    <x-heroicon-o-check class="w-4 h-4 inline-block font-bold" />
                    <span>{{ __('Place Booking') }}</span>
                </button>
            </div>

        </div>



    </div>
    {{-- loading --}}
    @include('livewire.extensions.glover-website.components.loading')
</div>
