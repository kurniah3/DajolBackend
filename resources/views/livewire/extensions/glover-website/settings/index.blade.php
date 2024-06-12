@section('title', __('Website Setting'))
<div>
    <x-baseview title="{{ __('Website Setting') }}">
        <div class="w-full md:w-4/12 lg:w-3/12">
            <x-form action='save'>
                <x-input name="websiteDomain" title="{{ __('Website Domain') }}" />
                <x-input name="bannerHeight" title="{{ __('Website Banner Height') }}(px)" />
                <x-input name="popularVendorCount" title="{{ __('Max Number of popular vendor') }}" />
                <x-input name="campaignProductsCount" title="{{ __('Max Number of campaign product') }}" />
                <x-select title="{{ __('Default Language') }}" name="defaultLanguage" :options="$languages" />
                <x-buttons.primary type="submit" title="{{ __('Save') }}" />
            </x-form>
        </div>

    </x-baseview>
</div>
