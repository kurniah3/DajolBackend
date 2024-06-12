@section('title', $product->name)
@section('styles')
    {{-- seo links --}}
    <link rel="canonical"
        href="{{ route('glover-website.product', [
            'id' => $product->id,
            'slug' => \Str::slug($product->name),
        ]) }}" />
    <meta name="description" content="{{ $product->description }}" />
    <meta name="keywords" content="{{ implode(',', explode($product->name, '')) }}" />
    <meta name="author" content="{{ $product->vendor->name }}" />
    <meta name="robots" content="index, follow" />
    <meta name="googlebot" content="index, follow" />
    <meta name="google" content="notranslate" />
    {{-- twitter --}}
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="{{ $product->vendor->name }}" />
    <meta name="twitter:title" content="{{ $product->name }}" />
    <meta name="twitter:description" content="{{ $product->description }}" />
    <meta name="twitter:image" content="{{ $product->photo }}" />
    {{-- facebook --}}
    <meta property="og:url"
        content="{{ route('glover-website.product', [
            'id' => $product->id,
            'slug' => \Str::slug($product->name),
        ]) }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $product->name }}" />
    <meta property="og:description" content="{{ $product->description }}" />
    <meta property="og:image" content="{{ $product->photo }}" />
    <meta property="og:site_name" content="{{ $product->vendor->name }}" />
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
                'title' => $product->vendor->vendor_type->name,
                'url' => route('glover-website.vendor.type', [
                    'id' => $product->vendor->vendor_type->id,
                    'slug' =>
                        $product->vendor->vendor_type->slug ?? \Str::slug($product->vendor->vendor_type->name),
                ]),
            ],
            [
                'title' => $product->vendor->name,
                'url' => route('glover-website.vendor', [
                    'id' => $product->vendor->id,
                    'slug' => \Str::slug($product->vendor->name),
                ]),
            ],
            [
                'title' => $product->name,
                'url' => '',
            ],
        ],
    ])



    {{-- details --}}
    <div class="p-2 md:p-8 rounded bg-white">
        <p class="text-xl font-semibold">{{ $product->name }}</p>
        <div class="flex justify-start items-center my-1">
            {{-- vendor --}}
            <div class="flex items-center text-xs">
                <span class="text-gray-400">{{ __('Vendor') }}:</span>
                <a href="{{ route('glover-website.vendor', [
                    'id' => $product->vendor->id,
                    'slug' => \Str::slug($product->vendor->name),
                ]) }}"
                    class="ml-2 text-gray-700 hover:underline">
                    {{ $product->vendor->name }}
                </a>
            </div>
            {{-- sku if any --}}
            @if ($product->sku)
                <div class="ltr:border-l ltr:pl-5 rtl:border-r rtl:pr-5 flex items-center text-xs ml-4">
                    <span class="text-gray-400">{{ __('SKU') }}:</span>
                    <span class="ml-2 text-gray-700">{{ $product->sku }}</span>
                </div>
            @endif

            {{-- barcode if any --}}
            @if ($product->barcode)
                <div class="ltr:border-l ltr:pl-5 rtl:border-r rtl:pr-5 flex items-center text-xs ml-4">
                    <span class="text-gray-400">{{ __('Barcode') }}:</span>
                    <span class="ml-2 text-gray-700">{{ $product->barcode }}</span>
                </div>
            @endif
        </div>


        {{-- imgs, details --}}
        <div class="w-full block md:flex md:gap-8 md:space-y-0 space-y-4 mt-6">
            <div class="w-full md:w-6/12 lg:w-5/12">
                {{-- main selected image --}}
                <div>
                    <a data-fancybox="gallery" data-src="{{ $product->photo }}">
                        <img src="{{ $product->photo }}" class="w-full h-[30vh] object-cover rounded" />
                    </a>
                </div>
                {{-- images previews --}}
                <div class="mt-1">
                    <div class="flex flex-wrap gap-2" id="image-container">
                        @foreach ($product->photos as $key => $image)
                            <a data-fancybox="gallery" data-src="{{ $image }}">
                                <img src="{{ $image }}" alt="{{ $key }}"
                                    class="w-20 h-20 object-cover rounded border border-gray-400" />
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="w-full md:w-6/12 lg:w-7/12">
                {{-- price --}}
                <div class="flex justify-start items-center">
                    @if ($product->discount_price > 0)
                        <p class="text-lg line-through font-normal ltr:mr-2 rtl:ml-2 text-gray-400">
                            {{ currencyFormat($product->price) }}</p>
                    @endif
                    <p class="text-2xl font-semibold text-primary-600">{{ currencyFormat($product->sell_price) }}</p>
                </div>

                {{-- in stock or not --}}
                @if ($product->available_qty !== null)
                    <div class="flex items-center justify-start mt-2">
                        {{-- no stock if zero --}}
                        @if ($product->available_qty == 0)
                            <p class="bg-red-100 p-2 rounded-2xl px-4 text-red-500 uppercase text-xs font-medium">
                                {{ __('Out of stock') }}
                            </p>
                        @else
                            <p class="bg-green-100 p-2 rounded-2xl px-4 text-green-500 uppercase text-xs font-medium">
                                {{ __('In stock') }}</p>
                        @endif

                    </div>

                @endif

                {{-- short description, cut to max of 4lines --}}
                <div class="mt-4">
                    <p class="text-gray-600 text-sm leading-6 line-clamp-3 text-ellipsis">
                        {{ strip_tags($product->description) }}
                    </p>
                </div>


                {{-- if stock is avaiblable --}}
                @if ($product->available_qty > 0 || $product->available_qty === null)
                    {{-- option groups if any --}}
                    @if ($product->option_groups->count() > 0)
                        <div class="mt-4">
                            @foreach ($product->option_groups as $option_group)
                                <div class="mb-4">
                                    <p class="text-gray-600 text-sm font-semibold">{{ $option_group->name }}</p>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach ($option_group->options as $option)
                                            <div>
                                                <div
                                                    class="flex items-center justify-start border border-gray-400 rounded px-2 py-1 cursor-pointer">

                                                    @if (!$option_group->multiple)
                                                        <input type="radio"
                                                            wire:model="selectedGroupOptions.{{ $option_group->id }}"
                                                            value="{{ $option->id }}" class="" />
                                                    @else
                                                        <input type="checkbox"
                                                            wire:model="selectedGroupOptions.{{ $option_group->id }}"
                                                            value="{{ $option->id }}" class="" />
                                                    @endif
                                                    {{-- image is its not empty --}}
                                                    @if ($option->hasMedia())
                                                        <img src="{{ $option->photo }}"
                                                            class="mx-2 w-8 h-8 object-cover" />
                                                    @endif
                                                    <p
                                                        class="h-8 flex text-gray-600 text-sm ltr:ml-2 rtl:mr-2 items-center justify-center">
                                                        {{ $option->name }}
                                                        {{-- if price is not 0 or null --}}
                                                        @if ($option->price != 0 && $option->price != null)
                                                            <span class="mx-1 text-primary-600 font-bold">
                                                                ({{ currencyFormat($option->price) }})
                                                            </span>
                                                        @endif
                                                    </p>
                                                </div>

                                            </div>
                                        @endforeach
                                    </div>
                                    {{-- error message --}}
                                    @error('option_group.' . $option_group->id)
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="border-t border-b py-6 block md:flex justify-start md:space-x-4 space-y-2 md:space-y-0">
                        {{-- qty selector --}}
                        <div class="flex items-center justify-start gap-2">
                            <button class="rounded-full w-10 h-6 border flex justify-center items-center"
                                wire:click="decreaseQty">
                                <x-heroicon-o-minus class="h-5 w-5 text-gray-400" />
                            </button>
                            <span class="px-3 font-bold text-lg">{{ $selectedQty ?? 1 }}</span>
                            <button class="rounded-full w-10 h-6 border flex justify-center items-center"
                                wire:click="increaseQty">
                                <x-heroicon-o-plus class="h-5 w-5 text-gray-400" />
                            </button>
                        </div>

                        {{-- add to cart button --}}
                        <button
                            class="flex items-center justify-center bg-primary-600 text-white rounded px-4 py-2 text-sm font-semibold"
                            wire:click="addToCart">
                            <x-heroicon-o-shopping-cart class="h-5 w-5" />
                            <span class="ltr:ml-2 rtl:mr-2">{{ __('Add to cart') }}</span>
                        </button>

                        {{-- buy now button --}}
                        <button
                            class="flex items-center justify-center bg-primary-600 text-white rounded px-4 py-2 text-sm font-semibold ml-2"
                            wire:click="buyNow">
                            <x-heroicon-o-shopping-bag class="h-5 w-5" />
                            <span class="ltr:ml-2 rtl:mr-2">{{ __('Buy now') }}</span>
                        </button>
                    </div>

                @endif


                {{-- categories --}}
                @if (!empty($product->categories))
                    <div class="mt-4">
                        <p class="text-gray-600 text-sm font-semibold">{{ __('Categories') }}</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach ($product->categories as $category)
                                <a href="{{ route('glover-website.search', [
                                    'category_id' => $category->id,
                                    'type' => 'category',
                                ]) }}"
                                    class="text-gray-600 text-sm hover:underline">{{ $category->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- tags --}}
                @if (!empty($product->tags))
                    <div class="mt-4">
                        <p class="text-gray-600 text-sm font-semibold">{{ __('Tags') }}</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach ($product->tags as $tag)
                                <a href="{{ route('glover-website.search', [
                                    'tag_id' => $tag->id,
                                    'type' => 'tag',
                                ]) }}"
                                    class="text-gray-600 text-sm hover:underline">{{ $tag->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- share current url, use social icons from line awesome --}}
                <div class="mt-4">
                    <p class="text-gray-600 text-sm font-semibold">{{ __('Share') }}</p>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                            target="_blank" class="text-gray-600 text-sm hover:underline">
                            <x-lineawesome-facebook class="h-5 w-5" />
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}"
                            target="_blank" class="text-gray-600 text-sm hover:underline">
                            <x-lineawesome-twitter class="h-5 w-5" />
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}"
                            target="_blank" class="text-gray-600 text-sm hover:underline">
                            <x-lineawesome-linkedin class="h-5 w-5" />
                        </a>
                        <a href="https://wa.me/?text={{ urlencode(url()->current()) }}" target="_blank"
                            class="text-gray-600 text-sm hover:underline">
                            <x-lineawesome-whatsapp class="h-5 w-5" />
                        </a>
                    </div>
                </div>


            </div>
        </div>




    </div>

    {{-- product details, reviews tab --}}
    <div class="rounded mt-10">
        <x-tab.tabview>
            <x-slot name="header">
                <x-tab.header tab="1" title="{{ __('Description') }}" />
                <x-tab.header tab="2" title="{{ __('Reviews') }}({{ $product->reviews_count }})" />
            </x-slot>
            <x-slot name="body">
                <x-tab.body tab="1">
                    <div class="p-4">
                        <p class="text-gray-600 text-sm leading-6 line-clamp-4 text-ellipsis">
                            {!! $product->description !!}
                        </p>
                    </div>
                </x-tab.body>
                <x-tab.body tab="2">
                    <div class="p-4">
                        @foreach ($product->reviews as $review)
                            <div class="flex justify-start items-start space-x-4">
                                <img src="{{ $review->user->photo }}" class="w-12 h-12 object-cover rounded-full" />
                                <div>
                                    {{-- rating stars --}}
                                    <div class="flex">
                                        @for ($i = 1; $i <= setting('defaultVendorRating', 5); $i++)
                                            @if ($review->rating >= $i)
                                                <x-heroicon-s-star class="h-5 w-5 text-yellow-400" />
                                            @else
                                                <x-heroicon-s-star class="h-5 w-5 text-gray-400" />
                                            @endif
                                        @endfor
                                    </div>
                                    {{-- name and date --}}
                                    <div class="flex justify-start items-center">
                                        @php
                                            $text = $review->user->name;
                                            $start = 4;
                                            $length = 8;
                                            $mask = '*';
                                            
                                            $maskedName = substr($text, 0, $start) . str_repeat($mask, $length) . substr($text, $start + $length);
                                            
                                        @endphp
                                        <p class="text-gray-600 text-sm font-semibold">
                                            {{ $maskedName }}
                                        </p>
                                        <p class="text-gray-600 text-xs mx-2">-</p>
                                        <p class="text-gray-600 text-xs">
                                            {{ $review->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    {{-- review text --}}
                                    <p class="text-gray-600 text-sm leading-6">
                                        {{ $review->review }}
                                    </p>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-tab.body>
            </x-slot>
        </x-tab.tabview>
    </div>

</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/extensions/glover-website/fancybox.css') }}" />
@endpush
@push('scripts')
    <script src="{{ asset('js/extensions/glover-website/fancybox.umd.js') }}"></script>
    <script>
        // on page finish loading
        window.addEventListener('load', function() {
            // init fancybox
            Fancybox.bind('[data-fancybox="gallery"]', {
                loop: true,
                buttons: [
                    "zoom",
                    "slideShow",
                    "fullScreen",
                    "download",
                    "thumbs",
                    "close",
                ],
            });
        });
    </script>
@endpush
