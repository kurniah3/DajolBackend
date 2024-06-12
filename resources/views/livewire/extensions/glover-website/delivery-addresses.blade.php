@section('title', __('My Addresses'))
<div class="mb-20">
    {{-- breadcrumb --}}
    @include('livewire.extensions.glover-website.components.nav-breadcrumb', [
        'links' => [
            [
                'title' => __('Home'),
                'url' => route('glover-website.index'),
            ],
            [
                'title' => __('My Addresses'),
                'url' => '',
            ],
        ],
    ])

    {{-- list of addresses --}}
    <div class="bg-white rounded-sm p-4 shadow-sm border-gray-100">
        {{-- header --}}
        <div class="flex justify-between items-start">
            <div class="mt-2 mb-4">
                <div class="font-semibold text-lg">{{ __('My Addresses') }}</div>
                <div class="text-gray-500 text-sm">{{ __('List of all delivery/booking addresses') }}</div>
            </div>
            {{-- new button --}}
            <button type="button"
                class="flex items-center justify-center w-auto px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 border border-transparent rounded-lg bg-primary-600 active:bg-primary-600 hover:bg-primary-700 focus:outline-none focus:shadow-outline-primary"
                wire:click="showCreateModal">
                <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                <span>{{ __('New Address') }}</span>
            </button>

        </div>

        @if ($delivery_addresses->isEmpty())
            <div class="text-center justify-center items-center py-20">
                <x-heroicon-o-information-circle class="w-16 h-16 text-gray-400 mx-auto" />
                <h3 class="text-2xl font-bold">{{ __('No Address') }}</h3>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach ($delivery_addresses as $deliveryAddress)
                    <div class="rounded border border-gray-100 p-2 shadow">
                        <p>{{ $deliveryAddress->name }}</p>
                        <p class="text-sm">
                            <span class="font-semibold text-xs uppercase">{{ __('Address') }}</span> :
                            <span class="text-sm text-gray-600">{{ $deliveryAddress->address }}</span>
                        </p>
                        <p>
                            <span class="font-semibold text-xs uppercase">{{ __('city') }}</span> :
                            <span class="text-sm text-gray-600">{{ $deliveryAddress->city }}</span>
                        </p>
                        <p>
                            <span class="font-semibold text-xs uppercase">{{ __('state') }}</span> :
                            <span class="text-sm text-gray-600">{{ $deliveryAddress->state }}</span>
                        </p>
                        <p>
                            <span class="font-semibold text-xs uppercase">{{ __('country') }}</span> :
                            <span class="text-sm text-gray-600">{{ $deliveryAddress->country }}</span>
                        </p>
                        <hr class="mt-2" />
                        {{-- action edit and delete --}}
                        <div class="flex
                            justify-end">
                            <button type="button"
                                class="flex items-center justify-center w-auto px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 border border-transparent rounded-lg bg-primary-600 active:bg-primary-600 hover:bg-primary-700 focus:outline-none focus:shadow-outline-primary"
                                wire:click="$emitSelf('initiateEdit', '{{ $deliveryAddress->id }}')">
                                <x-heroicon-o-pencil class="w-4 h-4 mr-2" />
                                <span>{{ __('Edit') }}</span>
                            </button>
                            <button type="button"
                                class="flex items-center justify-center w-auto px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 border border-transparent rounded-lg bg-red-600 active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-primary ml-2"
                                wire:click="deleteAddress({{ $deliveryAddress->id }})">
                                <x-heroicon-o-trash class="w-4 h-4 mr-2" />
                                <span>{{ __('Delete') }}</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="my-4"> {{ $delivery_addresses->links() }} </div>
        @endif
    </div>

    {{-- new form --}}
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Save') }}" action="save">
            <p class="text-xl font-semibold">{{ __('New Delivery Address') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="" />
            <x-input title="{{ __('Description') }}" name="description" placeholder="" />
            <livewire:component.autocomplete-address title="{{ __('Address') }}" name="address"
                address="{{ $address ?? '' }}" />
            <div class="grid grid-cols-2 space-x-4">
                <x-input title="{{ __('Latitude') }}" name="latitude" :disable="true" />
                <x-input title="{{ __('Longitude') }}" name="longitude" :disable="true" />
            </div>
        </x-modal>
    </div>

    {{--  update form  --}}
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Update') }}" action="update">
            <p class="text-xl font-semibold">{{ __('Update Delivery Address') }}</p>
            <x-input title="{{ __('Name') }}" name="name" placeholder="" />
            <x-input title="{{ __('Description') }}" name="description" placeholder="" />
            <livewire:component.autocomplete-address title="{{ __('Address') }}" name="address"
                address="{{ $address ?? '' }}" />
            <div class="grid grid-cols-2 space-x-4">
                <x-input title="{{ __('Latitude') }}" name="latitude" :disable="true" />
                <x-input title="{{ __('Longitude') }}" name="longitude" :disable="true" />
            </div>

        </x-modal>
    </div>
    {{-- loading --}}
    @include('livewire.extensions.glover-website.components.loading')
</div>
