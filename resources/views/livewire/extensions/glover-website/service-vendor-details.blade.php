@section('title', $vendor->name ?? __('Vendor Details'))
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
    <p class="font-bold text-xl my-4"> {{ __('Our Services') }}</p>
    {{-- services --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($services as $service)
            @include('livewire.extensions.glover-website.components.list.service-card', [
                'service' => $service,
            ])
        @endforeach
    </div>
    <div> {{ $services->links() }}</div>

</div>
