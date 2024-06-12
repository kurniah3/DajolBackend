<div>

    @switch($vendorType->slug ?? '')
        @case('food')
            <livewire:extensions.glover-website.livewire.vendor-type.food-livewire id="{{ $vendorType->id }}" />
        @break

        @case('grocery')
            <livewire:extensions.glover-website.livewire.vendor-type.grocery-livewire id="{{ $vendorType->id }}" />
        @break

        @case('pharmacy')
            <livewire:extensions.glover-website.livewire.vendor-type.pharmacy-livewire id="{{ $vendorType->id }}" />
        @break

        @case('parcel')
            <livewire:extensions.glover-website.livewire.vendor-type.package-livewire id="{{ $vendorType->id }}" />
        @break

        @case('package')
            <livewire:extensions.glover-website.livewire.vendor-type.package-livewire id="{{ $vendorType->id }}" />
        @break

        @case('commerce')
            <livewire:extensions.glover-website.livewire.vendor-type.commerce-livewire id="{{ $vendorType->id }}" />
        @break

        @case('service')
            <livewire:extensions.glover-website.livewire.vendor-type.service-livewire id="{{ $vendorType->id }}" />
        @break

        @case('booking')
            <livewire:extensions.glover-website.livewire.vendor-type.service-livewire id="{{ $vendorType->id }}" />
        @break

        @case('taxi')
            <livewire:extensions.glover-website.livewire.vendor-type.taxi-livewire id="{{ $vendorType->id }}" />
        @break

        @default
            <livewire:extensions.glover-website.livewire.vendor-type.food-livewire id="{{ $vendorType->id }}" />
        @break
    @endswitch
</div>
