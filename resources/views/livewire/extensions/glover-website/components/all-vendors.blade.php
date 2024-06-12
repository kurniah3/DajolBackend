<div>
    @if ($showEmpty || $vendors->count() > 0)
        <div class="mt-16">
            <p class="text-start text-2xl font-semibold my-4"> {{ $title ?? __('Popular Vendors') }}</p>
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 md:gap-4 md:space-y-0 space-y-4 justify-items-stretch">
                @foreach ($vendors as $vendor)
                    @include('livewire.extensions.glover-website.components.list.vendor-card', [
                        'vendor' => $vendor,
                    ])
                @endforeach
            </div>
        </div>

        {{-- show more --}}
        @if ($vendors->hasMorePages())
            <div class="flex justify-center mt-4">
                <button wire:click="loadMore"
                    class="bg-primary-500 rounded shadow-sm text-theme px-2 py-1 flex space-x-2 items-center">
                    <span>{{ __('Show More') }}</span>
                    <x-heroicon-o-arrow-circle-down class="w-5 h-5" />
                </button>
            </div>
        @endif
    @endif
</div>
