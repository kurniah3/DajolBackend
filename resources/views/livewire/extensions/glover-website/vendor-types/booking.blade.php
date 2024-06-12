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

            {{-- popular services nearby --}}
            <livewire:extensions.glover-website.components.popular-nearby-services
                vendor_type_id="{{ $vendorType->id }}" title="{{ __('Popular Services') }}" showEmpty="1" />

            {{-- campaign services --}}
            <livewire:extensions.glover-website.components.campaign-services vendor_type_id="{{ $vendorType->id }}"
                title="{{ __('Discounted Service') }}" subtitle="{{ __('Get more done for less') }}" showEmpty="0" />

            {{-- popular vendors --}}
            <livewire:extensions.glover-website.components.top-rated-vendors vendor_type_id="{{ $vendorType->id }}"
                title="{{ __('Top Rated Providers') }}"
                subtitle="{{ __('Rate generally by community base on finished orders') }}" />

            {{-- all vendors --}}
            <livewire:extensions.glover-website.components.all-vendors vendor_type_id="{{ $vendorType->id }}"
                title="{{ __('All Vendors') }}" showEmpty="0" />
        </div>
    </div>
</div>
