<div class='mt-10'>
    <p class="text-2xl font-medium text-center my-6">{{ __('Featured Vendors') }}</p>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 md:gap-4">
        @foreach ($vendors as $vendor)
            @include('livewire.extensions.glover-website.components.list.vendor', ['vendor' => $vendor])
        @endforeach
    </div>
</div>
