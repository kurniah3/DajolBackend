<div class='bg-white p-2 px-4 rounded-lg shadow'>
    <p class="">
        {{ __('Total Items/Products') }} : <span class="font-bold text-lg">{{ $products->total() }}</span>
    </p>
    {{-- products --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2 mt-4">
        @forelse ($products as $product)
            <div class="">
                @include('livewire.extensions.glover-website.components.list.product-v-card', [
                    'product' => $product,
                ])
            </div>
        @empty
            <div class="text-center">
                <p class="text-lg font-semibold">{{ __('No Products Found') }}</p>
            </div>
        @endforelse
    </div>

    <div class="mt-3 mb-4">
        {{ $products->links() }}
    </div>
</div>
