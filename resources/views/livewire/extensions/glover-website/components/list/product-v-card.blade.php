@php
    $link = route('glover-website.product', [
        'id' => $product->id,
        'slug' => !empty($product->name) ? \Str::slug($product->name) : \Str::random(10),
    ]);
@endphp
<a href="{{ $link }}">
    <div class="rounded overflow-clip border border-gray-200 hover:shadow-lg">
        <div class='relative'>
            <img src="{{ $product->photo }}" alt="Product logo" class="w-full h-24 object-cover" />

            {{-- discount tag --}}
            @if ($product->discount_price > 0)
                <div
                    class="absolute top-5 ltr:left-0 rtl:right-0 bg-primary-500 text-theme px-2 py-1 rounded-md  ltr:rounded-l-none rtl:rounded-r-none">
                    <p class="text-xs font-semibold">
                        ~
                        {{-- calculate the discount percentage between price and discount_price --}}
                        {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                        {{ __('Off') }}
                    </p>
                </div>
            @endif
        </div>

        <div class="w-full p-2">
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
</a>
