<div>
    <div class='grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 mb-2'>
        @foreach ($products as $product)
            @include('livewire.extensions.glover-website.components.list.product-card', [
                'product' => $product,
            ])
        @endforeach
    </div>
    {{ $products->links() }}
</div>
