<div>
    @if ($showEmpty || $vendors->count() > 0)
        <div class="mt-16">
            <div class="{{ isRTL() ? 'ml-auto' : 'mr-auto' }} my-4">
                <p class="text-start text-2xl font-semibold"> {{ $title ?? __('Campaigns') }}</p>
                <p class="text-start text-sm font-light"> {{ $subtitle ?? __('Services on discount/campaign') }}</p>
            </div>
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 md:gap-4 md:space-y-0 space-y-4 justify-items-stretch">
                @foreach ($vendors as $vendor)
                    @include('livewire.extensions.glover-website.components.list.vendor-info-card', [
                        'vendor' => $vendor,
                    ])
                @endforeach
            </div>
        </div>
    @endif
</div>
