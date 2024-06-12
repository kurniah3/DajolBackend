@section('title', __('Search'))
<div>
    {{-- breadcrumb --}}
    @include('livewire.extensions.glover-website.components.nav-breadcrumb', [
        'links' => [
            [
                'title' => __('Home'),
                'url' => route('glover-website.index'),
            ],
            [
                'title' => __('Search'),
                'url' => '',
            ],
        ],
    ])

    <div class='bg-white p-2 px-4 rounded-lg shadow'>

        <div class="blck md:flex gap-2 md:justify-between">
            <div class="w-full">
                <x-label for="keyword" title="{{ __('Enter keyword to search') }}" />
                <input wire:model="keyword" type="text" class="w-full border border-gray-400 rounded pr-4 p-2"
                    placeholder="{{ __('Search') }}" />

            </div>

            {{-- sort by asc/desc --}}
            <div>
                <x-label for="orderBy" title="{{ __('Sort') }}" />
                <select wire:model="orderBy" class="w-full md:w-32 border border-gray-400 rounded pr-4 p-2">
                    <option value="asc">{{ __('asc') }}</option>
                    <option value="desc">{{ __('desc') }}</option>
                </select>
            </div>

            {{-- filter per page --}}
            <div>
                <x-label for="perPage" title="{{ __('Per Page') }}" />
                <select wire:model="perPage" class="w-full md:w-32 border border-gray-400 rounded pr-4 p-2">
                    <option value="12">12</option>
                    <option value="24">24</option>
                    <option value="36">36</option>
                    <option value="48">48</option>
                    <option value="60">60</option>
                </select>
            </div>
        </div>

        {{--  --}}
        <x-tab.tabview>
            <x-slot name="header">
                @if ($showProducts)
                    <x-tab.header tab="1" title="{{ __('Products') }}" />
                @endif

                @if ($showServices)
                    <x-tab.header tab="2" title="{{ __('Services') }}" />
                @endif
                <x-tab.header tab="3" title="{{ __('Vendors') }}" />
            </x-slot>
            <x-slot name="body">
                <x-tab.body tab="1">
                    {{-- products --}}
                    <div wire:init="getProducts()">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                            @forelse ($products as $product)
                                @include(
                                    'livewire.extensions.glover-website.components.list.product-v-card',
                                    [
                                        'product' => $product,
                                    ]
                                )
                            @empty
                                <div class="text-center">
                                    <x-heroicon-o-information-circle class="text-4xl text-gray-400" />
                                    <p class="text-gray-400">{{ __('No products found') }}</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="my-4">
                            {{ $products->links() }}
                        </div>
                    </div>
                </x-tab.body>
                <x-tab.body tab="2">
                    {{-- services --}}
                    <div wire:init="getServices()">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                            @forelse ($services as $service)
                                @include(
                                    'livewire.extensions.glover-website.components.list.service-card',
                                    [
                                        'service' => $service,
                                    ]
                                )
                            @empty
                                <div class="text-center">
                                    <x-heroicon-o-information-circle class="text-4xl text-gray-400" />
                                    <p class="text-gray-400">{{ __('No services found') }}</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="my-4">
                            {{ $services->links() }}
                        </div>
                    </div>
                </x-tab.body>
                <x-tab.body tab="3">
                    <div wire:init="getVendors()">
                        {{-- vendors --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                            @forelse ($vendors as $vendor)
                                @include('livewire.extensions.glover-website.components.list.vendor-card', [
                                    'vendor' => $vendor,
                                ])
                            @empty
                                <div class="text-center">
                                    <x-heroicon-o-information-circle class="text-4xl text-gray-400" />
                                    <p class="text-gray-400">{{ __('No vendors found') }}</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="my-4">
                            {{ $vendors->links() }}
                        </div>
                    </div>
                </x-tab.body>
            </x-slot>

        </x-tab.tabview>

    </div>
    {{-- loading --}}
    @include('livewire.extensions.glover-website.components.loading')
</div>
