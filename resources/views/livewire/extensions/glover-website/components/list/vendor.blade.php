@php
    $link = route('glover-website.vendor', [
        'id' => $vendor->id,
        'slug' => \Str::slug($vendor->name),
    ]);
@endphp
<a href="{{ $link }}">
    <div class="border border-gray-200 shadow-md hover:shadow-lg cursor-pointer bg-white  rounded flex gap-4">

        <div class="w-24">
            <img src="{{ $vendor->logo }}" alt="{{ $vendor->name }}" class="w-24 h-24 object-full rounded-l" />
        </div>
        <div class="w-full items-center my-auto">
            <p class="font-medium text-lg">{{ $vendor->name ?? '' }}</p>
            <p class="text-sm text-gray-400 text-ellipsis line-clamp-1">{{ $vendor->address ?? '' }}</p>
            {{-- rating bar --}}
            <div class="flex gap-2 items-center">
                <div class="flex gap-1 items-center">
                    @for ($i = 1; $i <= setting('defaultVendorRating', 5); $i++)
                        <span class="text-sm {{ $i <= $vendor->rating ? 'text-yellow-400' : 'text-gray-400' }}">
                            <i class="fas fa-star"></i>
                        </span>
                    @endfor
                </div>
                <p class="text-sm text-gray-400">({{ $vendor->reviews->count() }})</p>
            </div>
        </div>

    </div>
</a>
