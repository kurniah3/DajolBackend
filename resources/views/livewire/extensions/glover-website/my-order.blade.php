@section('title', __('My Orders'))
<div class="mb-20">
    {{-- breadcrumb --}}
    @include('livewire.extensions.glover-website.components.nav-breadcrumb', [
        'links' => [
            [
                'title' => __('Home'),
                'url' => route('glover-website.index'),
            ],
            [
                'title' => __('My Orders'),
                'url' => '',
            ],
        ],
    ])


    {{-- list of order --}}
    <div class="bg-white rounded-sm p-4 shadow-sm border-gray-100">
        {{-- header --}}
        <div class="flex justify-between ">
            <div class="mt-2 mb-4">
                <div class="font-semibold text-lg">{{ __('My Orders') }}</div>
                <div class="text-gray-500 text-sm">{{ __('List of all orders') }}</div>
            </div>
            <div>
                <select name="status" wire:model="status" class="w-40 p-2 border border-gray-300 rounded">
                    <option value="">{{ __('All') }}</option>
                    @foreach (config('backend.colors') as $key => $color)
                        <option value="{{ $key }}">{{ ucfirst($key) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if ($orders->isEmpty())
            <div class="text-center justify-center items-center py-20">
                <x-heroicon-o-information-circle class="w-16 h-16 text-gray-400 mx-auto" />
                <h3 class="text-2xl font-bold">{{ __('No order found') }}</h3>
            </div>
        @else
            <div>

                @foreach ($orders ?? [] as $order)
                    @php
                        $statusColor = config('backend.colors')[$order->status] ?? '#f0f0f0';
                    @endphp
                    <div x-data="{ openDetails: false }">
                        <div x-bind:class="{ 'mb-4': !openDetails }"
                            class="bg-white p-4 rounded-lg shadow-md border border-gray-100 hover:bg-gray-100">
                            <div class="block md:flex items-center mb-2">
                                <div class="items-center w-full">
                                    <div class="font-semibold text-lg mr-2">#{{ $order->code }}</div>
                                    <div class="text-gray-500 text-sm w-full">{{ __('Placed on') }}
                                        {{ $order->created_at->format('F d, Y') }}
                                    </div>
                                </div>
                                {{-- status --}}
                                <div class="flex md:hidden space-x-4 justify-between">
                                    <div class="justify-start items-center">
                                        <span class="text-sm font-medium"> {{ __('Status') }}:</span>
                                        <p class="text-gray-500 text-sm" style="color: {{ $statusColor }}">
                                            {{ ucfirst($order->status) }}</p>
                                    </div>

                                    <div class="justify-start items-center">
                                        <span class="text-sm font-medium"> {{ __('Payment') }}:</span>
                                        <p class="text-gray-500 text-sm">{{ ucfirst($order->payment_status) }}</p>
                                    </div>
                                </div>

                                {{-- pay button if any --}}
                                @if (
                                    !in_array($order->status, ['failed', 'cancelled']) &&
                                        $order->payment_status == 'pending' &&
                                        !empty($order->payment_link) &&
                                        $order->payment_method->slug != 'cash')
                                    <div class="flex justify-end items-center">
                                        <a href="{{ $order->payment_link }}"
                                            class="bg-primary-500 hover:bg-primary-600 text-white font-semibold p-2 text-sm px-4 rounded-md flex justify-center items-center space-x-2">
                                            <x-heroicon-o-credit-card class="w-4 h-4 inline-block" />
                                            <span>{{ __('Pay') }}</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            {{-- info --}}
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    @if ($order->vendor)
                                        <img src="{{ $order->Vendor->logo }}" alt="" class="w-16 h-16 mr-4">
                                    @else
                                        <img src="{{ $order->taxi_order->vehicle_type->photo }}" alt="Product image"
                                            class="w-16 h-16 mr-4">
                                    @endif
                                    <div>
                                        @if ($order->vendor)
                                            <div class="font-semibold">{{ $order->vendor->name }}</div>
                                            @if ($order->products && $order->products->count() > 0)
                                                <div class="text-gray-500 text-sm line-clamp-1 text-ellipsis">
                                                    @php
                                                        $orderProducts = $order->products;
                                                        $productNames = [];
                                                        foreach ($orderProducts as $orderProduct) {
                                                            $productNames[] = $orderProduct->product->name;
                                                        }
                                                        $flattenProductName = implode(', ', $productNames);
                                                    @endphp

                                                    {{ $flattenProductName }}
                                                </div>
                                            @elseif ($order->order_service)
                                                <div class="text-gray-500 text-sm">
                                                    {{ $order->order_service->service->name }}
                                                </div>
                                            @endif
                                        @else
                                            <div class="font-semibold">{{ $order->taxi_order->vehicle_type->name }}
                                            </div>
                                            <div class="text-gray-500 text-sm">
                                                {{ __('Taxi Order') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex gap-8">
                                    <div class="hidden md:flex space-x-4">
                                        <div class="justify-start items-center">
                                            <span class="text-sm font-medium"> {{ __('Status') }}:</span>
                                            <p style="color: {{ $statusColor }}">{{ ucfirst($order->status) }}</p>
                                        </div>

                                        <div class="justify-start items-center">
                                            <span class="text-sm font-medium"> {{ __('Payment') }}:</span>
                                            <p class="text-gray-500 text-sm">{{ ucfirst($order->payment_status) }}</p>
                                        </div>
                                    </div>
                                    <div class="font-semibold">
                                        <div class="text-sm font-medium">
                                            {{ __('Total') }}
                                        </div>
                                        <div class="text-gray-500 text-sm">
                                            {{ currencyFormat($order->total) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- click to open more details --}}
                            <div class="flex justify-center items-center mt-2 mx-auto cursor-pointer"
                                x-on:click="openDetails = !openDetails">

                                <span x-show="!openDetails">{{ __('More details') }}</span>
                                <span x-show="openDetails"> {{ __('Less details') }}</span>

                                <div class="text-gray-500 text-sm">
                                    <div x-show="!openDetails">
                                        <x-heroicon-o-chevron-down class="w-5 h-5" />
                                    </div>
                                    <div x-show="openDetails">
                                        <x-heroicon-o-chevron-up class="w-5 h-5" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- more details --}}
                        <div x-show="openDetails">
                            <div class="mb-4 border border-t-0 bg-gray-100 rounded-lg rounded-t-none p-4 text-left">
                                @switch($order->order_type)
                                    @case('package')
                                        @include('livewire.order.package_order_details', [
                                            'selectedModel' => $order,
                                        ])
                                    @break

                                    @case('parcel')
                                        @include('livewire.order.package_order_details', [
                                            'selectedModel' => $order,
                                        ])
                                    @break

                                    @case('service')
                                        @include('livewire.order.service_order_details', [
                                            'selectedModel' => $order,
                                        ])
                                    @break

                                    @case('taxi')
                                        @include('livewire.order.taxi_order_details', [
                                            'selectedModel' => $order,
                                        ])
                                    @break

                                    @default
                                        @include('livewire.order.regular_order_details', [
                                            'selectedModel' => $order,
                                        ])
                                    @break
                                @endswitch
                            </div>
                            <hr class="my-4" />
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="my-4">
                {{ $orders->links() }}
            </div>
        @endempty
</div>

</div>
