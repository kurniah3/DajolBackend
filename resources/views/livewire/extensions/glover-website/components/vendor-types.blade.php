<div class="mt-20 mb-10">
    <p class="text-center text-2xl font-semibold my-4">{{ __('What we offer') }}</p>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @foreach ($vendorTypes as $vendorType)
            @php
                $link = route('glover-website.vendor.type', ['slug' => $vendorType->slug, 'id' => $vendorType->id]);
            @endphp
            <a href="{{ $link }}">
                <div
                    class="rounded border hover:skew-y-1 hover:scale-110 hover:z-50 z-10 hover:shadow-xl border-gray-300 shadow-md flex items-center px-1 lg:px-2">
                    <img src="{{ $vendorType->logo }}" class="w-12 h-12 lg:w-20 lg:h-20 object-cover rounded-l p-2" />
                    <div class="p-2 w-full overflow-hidden">
                        <p class="text-xl font-semibold line-clamp-1">{{ $vendorType->name }}</p>
                        <p class="text-sm text-gray-500 text-ellipsis line-clamp-2">{{ $vendorType->description }}</p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
