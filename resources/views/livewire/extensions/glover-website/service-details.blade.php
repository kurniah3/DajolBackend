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
                'title' => $service->vendor->name,
                'url' => route('glover-website.vendor', [
                    'id' => $service->vendor->id,
                    'slug' => \Str::slug($service->vendor->name),
                ]),
            ],
            [
                'title' => $service->name,
                'url' => '',
            ],
        ],
    ])



    {{-- details --}}
    <div class="p-2 md:p-8 rounded bg-white">
        <p class="text-xl font-semibold">{{ $service->name }}</p>
        <div class="flex justify-start items-center my-1">
            {{-- vendor --}}
            <div class="flex items-center text-xs">
                <span class="text-gray-400">{{ __('Provider') }}:</span>
                <a href="{{ route('glover-website.vendor', [
                    'id' => $service->vendor->id,
                    'slug' => \Str::slug($service->vendor->name),
                ]) }}"
                    class="ml-2 text-gray-700 hover:underline">
                    {{ $service->vendor->name }}
                </a>
            </div>
        </div>


        {{-- imgs, details --}}
        <div class="w-full block md:flex md:gap-8 md:space-y-0 space-y-4 mt-6">
            <div class="w-full md:w-6/12 lg:w-5/12">
                {{-- main selected image --}}
                <div>
                    <a data-fancybox="gallery" data-src="{{ $service->photo }}">
                        <img src="{{ $service->photo }}" class="w-full h-[30vh] object-cover rounded" />
                    </a>
                </div>
                {{-- images previews --}}
                <div class="mt-1">
                    <div class="flex flex-wrap gap-2" id="image-container">
                        @foreach ($service->photos as $key => $image)
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
                    @if ($service->discount_price > 0)
                        <p class="text-lg line-through font-normal ltr:mr-2 rtl:ml-2 text-gray-400">
                            {{ currencyFormat($service->price) }}</p>
                        <p class="text-2xl font-semibold text-primary-600">
                            {{ currencyFormat($service->discount_price) }}
                        @else
                        <p class="text-2xl font-semibold text-primary-600">
                            {{ currencyFormat($service->price) }}
                    @endif

                    @if ($service->duration != null && $service->duration != 'fixed')
                        <span class="text-gray-400 text-sm font-normal">
                            / {{ $service->duration }}
                        </span>
                    @endif
                    </p>
                </div>

                {{-- short description, cut to max of 4lines --}}
                <div class="mt-4">
                    <p class="text-gray-600 text-sm leading-6 line-clamp-3 text-ellipsis">
                        {{ strip_tags($service->description) }}
                    </p>
                </div>

                {{-- options --}}
                <div>
                    {{-- option groups if any --}}
                    @if ($service->option_groups->count() > 0)
                        <div class="mt-4">
                            @foreach ($service->option_groups as $option_group)
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
                </div>

                <div class="border-b py-6 block md:flex justify-start md:space-x-4 space-y-2 md:space-y-0">


                    {{-- book now button --}}
                    <button
                        class="flex items-center justify-center bg-primary-600 text-white rounded px-4 py-2 text-sm font-semibold ml-2"
                        wire:click="bookNow">
                        <x-heroicon-o-credit-card class="h-5 w-5" />
                        <span class="ltr:ml-2 rtl:mr-2">{{ __('Book') }}</span>
                    </button>
                </div>

                {{-- categories --}}
                @if (!empty($service->categories))
                    <div class="mt-4">
                        <p class="text-gray-600 text-sm font-semibold">{{ __('Categories') }}</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach ($service->categories as $category)
                                <a href="{{ route('glover-website.search', [
                                    'category_id' => $category->id,
                                    'type' => 'category',
                                ]) }}"
                                    class="text-gray-600 text-sm hover:underline">{{ $category->name }}</a>
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

    {{-- service details, reviews tab --}}
    <div class="rounded mt-10">
        <x-tab.tabview>
            <x-slot name="header">
                <x-tab.header tab="1" title="{{ __('Description') }}" />
            </x-slot>
            <x-slot name="body">
                <x-tab.body tab="1">
                    <div class="p-4">
                        <p class="text-gray-600 text-sm leading-6 line-clamp-4 text-ellipsis">
                            {!! $service->description !!}
                        </p>
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
