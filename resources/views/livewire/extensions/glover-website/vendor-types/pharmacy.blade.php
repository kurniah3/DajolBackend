@section('title', $vendorType->name)
<div class="mb-20">
    {{-- breadcrumb --}}
    @include('livewire.extensions.glover-website.components.nav-breadcrumb', [
        'links' => [
            [
                'title' => __('Home'),
                'url' => route('glover-website.index'),
            ],
            [
                'title' => $vendorType->name,
                'url' => '',
            ],
        ],
    ])

    <div class="block md:flex md:gap-4 space-y-6 md:space-y-0">
        <div class="w-full md:w-4/12 lg:w-3/12">
            {{-- banner for small screen --}}
            <div class="block md:hidden">
                <livewire:extensions.glover-website.components.banner-slider vendor_type_id="{{ $vendorType->id }}"
                    eId="sm-slider" />
            </div>
            {{-- categories --}}
            <livewire:extensions.glover-website.components.categories id="{{ $vendorType->id }}" />
        </div>
        <div class="w-full md:px-4">
            {{-- banners --}}
            <div class="hidden md:block">
                <livewire:extensions.glover-website.components.banner-slider vendor_type_id="{{ $vendorType->id }}"
                    eId="lg-slider" />
            </div>

            {{-- new products --}}
            <livewire:extensions.glover-website.components.newest-products vendor_type_id="{{ $vendorType->id }}"
                title="{{ __('New Arrivals') }} 🛬" subtitle="{{ __('New products with updated stocks.') }}"
                showEmpty="0" />

            {{-- campaign products --}}
            <livewire:extensions.glover-website.components.campaign-products vendor_type_id="{{ $vendorType->id }}"
                title="{{ __('Discount Products') }}" />

            {{-- popular foods nearby --}}
            <livewire:extensions.glover-website.components.popular-vendors vendor_type_id="{{ $vendorType->id }}"
                title="{{ __('Popular Nearby Vendors') }}" showEmpty="1" />


        </div>
    </div>
</div>
