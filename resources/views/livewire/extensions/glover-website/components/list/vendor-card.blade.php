@php
    $link = route('glover-website.vendor', [
        'id' => $vendor->id,
        'slug' => \Str::slug($vendor->name),
    ]);
@endphp
<a href="{{ $link }}">
    <div x-data="{ imgClass: '' }" class="items-stretch min-h-full">
        <div class="rounded-lg overflow-clip shadow border border-gray-100" x-on:mouseover="imgClass = 'scale-110'"
            x-on:mouseout="imgClass = ''">
            {{-- stack section --}}
            <div class="relative w-full h-48 overflow-clip">
                <img src="{{ $vendor->feature_image }}" alt="{{ $vendor->name }}"
                    class="w-full h-48 object-cover ease-in-out duration-500" :class="imgClass" />
                {{-- bg if vendor is open --}}
                @if (!$vendor->is_open)
                    <div
                        class="flex items-end justify-center w-full h-48 left-0 right-0 z-10 absolute top-0 bottom-0 bg-red-500 bg-opacity-50">
                    </div>
                @else
                    <div
                        class="w-full h-48 items-center justify-center left-0 right-0 z-10 absolute top-0 bottom-0 bg-black bg-opacity-30">
                        <div class="mx-auto my-auto">

                        </div>
                    </div>
                @endif
                <div class="flex items-center justify-center left-0 right-0 z-10 absolute top-0 bottom-0 ">
                    <div>
                        <p class="mx-1 text-xl font-bold text-white drop-shadow-xl shadow-black text-center">
                            {{ $vendor->name }}</p>

                        @if ($vendor->is_open)

                            {{-- categories --}}
                            <div class="flex flex-wrap justify-center gap-1">
                                @foreach ($vendor->categories as $category)
                                    <div class="">
                                        <span
                                            class="text-xs font-medium text-white bg-gray-600 rounded px-2 py-1 shadow-md bg-opacity-50">{{ $category->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="">
                                <span
                                    class="text-xs font-medium text-white bg-red-600 rounded px-2 py-1 shadow-md bg-opacity-90">{{ __('Closed') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- bottom part --}}
            <div class="h-16 p-2 flex flex-wrap justify-start items-center gap-2 text-sm lg:text-xs font-medium">
                {{-- indicators for ratings, number of reviews, delivery time, min & max order amount --}}

                {{-- ratings --}}
                <div class="flex items-center space-x-1">
                    <div class="flex items-center">
                        <x-lineawesome-star-solid class="w-4 h-4 text-yellow-500" />
                        <span>{{ $vendor->rating }}</span>
                    </div>
                    <span class="text-gray-400">({{ $vendor->reviews->count() ?? 0 }})</span>
                </div>
                {{-- dot --}}
                <div class="flex items-center space-x-1">
                    <span class="text-gray-400">•</span>
                </div>
                {{-- delivery fee --}}
                @if (!$vendor->charge_per_km)
                    <div class="flex items-center space-x-1">
                        <x-lineawesome-motorcycle-solid class="w-4 h-4" />
                        <span>{{ currencyFormat($vendor->delivery_fee) }}</span>
                    </div>

                    {{-- dot --}}
                    <div class="flex items-center space-x-1">
                        <span class="text-gray-400">•</span>
                    </div>
                @endif
                {{-- delivery time --}}
                @if (!empty($vendor->delivery_time))
                    <div class="flex items-center space-x-1">
                        <x-heroicon-o-clock class="w-4 h-4 text-gray-600" />
                        <span class="font-semibold ">{{ $vendor->delivery_time }}min(s)</span>
                    </div>

                    {{-- dot --}}
                    <div class="flex items-center space-x-1">
                        <span class="text-gray-400">•</span>
                    </div>
                @endif
                {{-- order amount limit --}}
                <div class="flex items-center space-x-1">
                    {{-- min order amount --}}
                    @if (!empty($vendor->min_order))
                        <span>{{ currencyFormat($vendor->min_order) }}</span>
                    @endif
                    {{-- max order amount --}}
                    @if (!empty($vendor->max_order))
                        @if (!empty($vendor->min_order))
                            {{-- dash --}}
                            <p class="">-</p>
                        @endif
                        <span>{{ currencyFormat($vendor->max_order) }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</a>
