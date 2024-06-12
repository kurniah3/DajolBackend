@php
    $link = route('glover-website.product', [
        'id' => $product->id,
        'slug' => !empty($product->name) ? \Str::slug($product->name) : \Str::random(10),
    ]);
@endphp
<a href="{{ $link }}">
    <div class='relative'>
        <div class="flex justify-start items-center gap-2 rounded-md border border-gray-300 shadow">
            <img src="{{ $product->photo }}" alt="Product logo" class="w-20 h-20 object-cover rounded-md" />
            <div class="w-full">
                <p class="line-clamp-1 text-ellipsis text-sm font-medium">{{ $product->name }}</p>
                <p class="text-xs text-gray-500 line-clamp-1 text-ellipsis">
                    {{-- strip description of html tags --}}
                    {{ strip_tags($product->vendor->name) }}
                </p>
                {{-- price --}}
                <p class="font-semibold text-sm">
                    @if ($product->discount_price > 0)
                        <span class="font-medium line-through text-xs">{{ currencyFormat($product->price) }}</span>
                    @endif
                    {{ currencyFormat($product->sell_price) }}
                </p>
            </div>
        </div>

        {{-- discount tag --}}
        @if ($product->discount_price > 0)
            <div class="absolute top-0 ltr:left-0 rtl:right-0 bg-primary-500 text-theme px-2 py-1 rounded-md">
                <p class="text-xs font-semibold">
                    ~
                    {{-- calculate the discount percentage between price and discount_price --}}
                    {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                    {{ __('Off') }}
                </p>
            </div>
        @endif
    </div>
</a>
