@section('title', $vendor->name ?? 'Vendor Details')
@section('extra-top-content')
    <div class='bg-gray-200'>
        <div class="w-full md:w-10/12 lg:w-8/12 container mx-auto py-6 px-4 md:px-0">
            <div class="block md:flex items-center justify-start space-y-4 md:space-y-0 gap-0 md:gap-8">
                {{-- feature image --}}
                <div class="w-full md:w-5/12">
                    <img src="{{ $vendor->feature_image }}" alt="" class="w-full h-[25vh] object-cover rounded" />
                </div>
                {{-- vendor details --}}
                <div class="w-full md:w-7/12">
                    <div class="flex items-start justify-start">
                        <img src="{{ $vendor->logo }}" alt=""
                            class="w-12 h-12 md:w-20 md:h-20 object-fill rounded shadow-sm border border-gray-200" />
                        <div class="ml-4 w-full">
                            <h1 class="text-lg md:text-2xl font-semibold">{{ $vendor->name }}</h1>
                            <p class="text-gray-500 text-sm line-clamp-2 text-ellipsis">{{ $vendor->description }}</p>
                            {{-- address --}}
                            <p class="mt-1 text-gray-500 text-sm line-clamp-2 text-ellipsis">
                                <x-heroicon-o-location-marker
                                    class="w-4 h-4 inline-block text-primary-500 ltr:mr-1 rtl:ml-1" />
                                {{ $vendor->address }}
                            </p>
                        </div>
                        {{-- other options --}}
                        <div class="w-20 h-12">
                            <a href="{{ route('glover-website.search', ['vendor_id' => $vendor->id, 'type' => 'vendor']) }}"
                                class="shadow hover:shadow-lg bg-primary-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <x-heroicon-o-search class="w-4 h-4 inline-block" />
                            </a>
                        </div>
                    </div>
                    {{-- actions like favourite, open search, location, ratings, reviews count and delivery time --}}
                    <div class="flex justify-start gap-4 md:gap-0 md:space-x-10 flex-wrap mt-4">
                        {{-- rating --}}
                        <div class="justify-center items-center">
                            <p class="items-center justify-center">
                                <x-lineawesome-star-solid class="w-4 h-4 inline-block text-primary-500" />
                                <span class="font-medium text-sm">{{ $vendor->rating }}</span>
                            </p>
                            <p class="text-sm font-semibold"> {{ $vendor->reviews_count }} {{ __('Reviews') }}</p>
                        </div>
                        {{-- prepare time --}}
                        @if (!empty($vendor->prepare_time))
                            <div class="justify-center items-center">
                                <p class="items-center justify-center">
                                    <x-lineawesome-clock-solid class="w-4 h-4 inline-block text-primary-500" />
                                    <span class="font-medium text-sm">{{ $vendor->prepare_time }}(mins)</span>
                                </p>
                                <p class="text-sm font-semibold">{{ __('Prepare Time') }}</p>
                            </div>
                        @endif
                        {{-- delivery time --}}
                        @if (!empty($vendor->delivery_time))
                            <div class="justify-center items-center">
                                <p class="items-center justify-center">
                                    <x-lineawesome-clock-solid class="w-4 h-4 inline-block text-primary-500" />
                                    <span class="font-medium text-sm">{{ $vendor->delivery_time }}(mins)</span>
                                </p>
                                <p class="text-sm font-semibold">{{ __('Delivery Time') }}</p>
                            </div>
                        @endif
                        {{-- min order amount --}}
                        @if (!empty($vendor->min_order))
                            <div class="justify-center items-center">
                                <p class="items-center justify-center">
                                    <x-lineawesome-money-bill-alt-solid class="w-4 h-4 inline-block text-primary-500" />
                                    <span class="font-medium text-sm">{{ currencyFormat($vendor->min_order) }}</span>
                                </p>
                                <p class="text-sm font-semibold">{{ __('Min Order') }}</p>
                            </div>
                        @endif

                        {{-- max order amount --}}
                        @if (!empty($vendor->max_order))
                            <div class="justify-center items-center">
                                <p class="items-center justify-center">
                                    <x-lineawesome-money-bill-alt-solid class="w-4 h-4 inline-block text-primary-500" />
                                    <span class="font-medium text-sm">{{ currencyFormat($vendor->max_order) }}</span>
                                </p>
                                <p class="text-sm font-semibold">{{ __('Max Order') }}</p>
                            </div>
                        @endif


                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
<div>
    {{-- if vendor has menu --}}
    @if (!$vendor->has_sub_categories)
        {{-- menus --}}
        <x-tab.tabview initialTab="0">
            <x-slot name="header">
                <x-tab.header tab="0" title="{{ __('All') }}" />
                @foreach ($vendor->menus as $key => $menu)
                    <x-tab.header tab="{{ $key + 1 }}" name="{{ $menu->id }}" title="{{ $menu->name }}" />
                @endforeach
            </x-slot>

            <x-slot name="body">
                <x-tab.body tab="0">
                    <livewire:extensions.glover-website.components.menu-products vendorId="{{ $vendor->id }}" />
                </x-tab.body>
                @foreach ($vendor->menus as $key => $menu)
                    <x-tab.body tab="{{ $key + 1 }}">
                        <livewire:extensions.glover-website.components.menu-products vendorId="{{ $vendor->id }}"
                            menuId="{{ $menu->id }}" />
                    </x-tab.body>
                @endforeach
            </x-slot>

        </x-tab.tabview>
    @else
        {{-- categories --}}
        <div class="block md:flex justify-start items-start space-y-4 md:space-y-0 md:space-x-4">
            <div class="w-full md:w-3/12 lg:w-4/12 bg-white rounded-sm px-4 py-2 border border-gray-100 shadow-sm">
                <p class="font-medium text-xl"> {{ __('Categories') }} </p>
                {{-- categories --}}
                <div class="mt-2">
                    @foreach ($vendor->categories as $category)
                        @php
                            $active = $category->id == $selectedCategoryId ? 'bg-gray-100' : '';
                        @endphp
                        <div x-data="{ openSubcategory: false }" class="mb-2">
                            <div
                                class="{{ $active }} hover:text-black text-gray-600 flex justify-between items-center cursor-pointer hover:bg-gray-100">
                                <div wire:click="onCategorySelected('{{ $category->id }}')">{{ $category->name }}
                                </div>
                                @if ($category->sub_categories->count() > 0)
                                    <x-heroicon-o-chevron-down x-show="!openSubcategory"
                                        class="w-4 h-4 inline-block text-gray-600" @click="openSubcategory = true" />
                                    <x-heroicon-o-chevron-up x-show="openSubcategory"
                                        class="w-4 h-4 inline-block text-gray-600" @click="openSubcategory = false" />
                                @endif
                            </div>
                            {{-- subcategories if any --}}
                            <div class="ltr:pl-2 rtl:pr-2 mt-1 mb-2" x-show="openSubcategory">
                                @foreach ($category->sub_categories as $subcategory)
                                    @php
                                        $selected = $subcategory->id == $selectedSubcategoryId ? 'text-black underline' : 'text-gray-600';
                                    @endphp
                                    <div class="{{ $selected }} flex items-center space-x-2 mb-1 cursor-pointer hover:underline"
                                        wire:click="onSubcategorySelected('{{ $category->id }}','{{ $subcategory->id }}')">
                                        <x-lineawesome-angle-right-solid class="w-4 h-4 inline-block text-gray-600" />
                                        <span>{{ $subcategory->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="w-full">
                <livewire:extensions.glover-website.components.vendor-category-products
                    vendorId="{{ $vendor->id }}" />
            </div>
        </div>
    @endif

</div>
