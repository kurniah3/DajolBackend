<div class="my-auto flex space-x-2 items-center mx-4 px-1 md:p-1" x-data="{ open: false }"
    @setCloseModal.window="open = false">
    {{-- location icon --}}

    {{-- <div class="flex cursor-pointer items-center" x-on:click="open = true">
        <x-heroicon-o-location-marker class="h-5 w-5 text-theme" />
        <div class="w-full ">
            @empty($currentLocation)
                <p class="text-xs md:text-base">{{ __('Set Location') }}</p>
            @else
                <p class="mx-1 w-full md:max-w-[80px] lg:max-w-[120px] line-clamp-1">
                    {{ $currentLocation['formatted_address'] }}</p>
            @endempty
        </div>
        <div class="items-center">
            <x-heroicon-o-chevron-down class="h-5 w-5 text-theme" />
        </div>
    </div> --}}
    <div class="flex cursor-pointer items-center space-x-1" x-on:click="open = true">
        {{-- <x-heroicon-o-location-marker class="h-5 w-5 text-theme" /> --}}
        <div class="w-full">
            <p class="text-xs">{{ __('Location') }}</p>
            <div class="">
                @empty($currentLocation)
                    <p class="text-xs font-bold">
                        {{ __('Click to set location') }}
                    </p>
                @else
                    <p class="mx-1 w-full md:max-w-[80px] lg:max-w-[120px] line-clamp-1 text-xs font-bold">
                        {{ $currentLocation['formatted_address'] }}
                    </p>
                @endempty
            </div>
        </div>
        <x-heroicon-o-chevron-down class="h-5 w-5 text-theme" />
    </div>


    {{-- modal to show --}}
    <div x-cloak x-show="open" class="fixed inset-0 z-20 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div @if ($clickAway ?? true) @click.away="open = false" @endif
                class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle w-11/12 md:w-6/12 lg:w-4/12 p-4 text-black"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                {{-- content --}}
                <div class="h-[70vh]" x-data="{ useMap: false }">
                    {{-- title --}}
                    <p class="text-2xl font-bold">{{ __('Select delivery address') }}</p>
                    {{-- toggle between useMap for search/map --}}
                    <div class="mx-auto mt-4 mb-2 w-fit">
                        <div class="flex border rounded-lg w-fit">
                            <button @click="useMap = false" :class="{ 'bg-primary-500 text-white': !useMap }"
                                class="py-2 px-4 rounded-l">
                                {{ __('Search') }}
                            </button>
                            <button @click="useMap = true" :class="{ 'bg-primary-500 text-white': useMap }"
                                class="py-2 px-4 rounded-r">
                                {{ __('Map') }}
                            </button>
                        </div>
                    </div>

                    {{-- search --}}
                    <div x-show="!useMap">
                        {{-- search input --}}
                        <div class="flex items-center">
                            <input type="text" wire:model.debounce.500ms="search" class="w-full border rounded p-2"
                                placeholder="{{ __('Search for location/address') }}">

                            {{-- spinner --}}
                            <div wire:loading wire:target="search">
                                <x-lineawesome-spinner-solid class="animate-spin h-5 w-5 mx-2" />
                            </div>
                        </div>
                        {{-- search results --}}
                        <div class="border border-t-none rounded rounded-t-none max-h-[40vh] overflow-auto">

                            @forelse ($addresses as $key => $address)
                                <div class="w-full border-b p-2 cursor-pointer"
                                    wire:click="selectedAddress('{{ $key }}')">
                                    <p class="text-sm">{{ $address['formatted_address'] }}</p>
                                </div>

                            @empty
                                @if (!empty($search) && empty($currentLocation))
                                    <p class="text-center font-semibold p-4">{{ __('No results found') }}</p>
                                @endif
                            @endforelse

                        </div>

                        {{-- use current location --}}
                        <div class="flex justify-center mt-4">
                            <button x-on:click="window.useCurrentLocation()"
                                class="w-full bg-primary-500 text-white px-4 py-2 rounded">{{ __('Use current location') }}</button>
                        </div>
                    </div>
                    {{-- map --}}
                    <div x-show="useMap">
                        <div id="map" class="w-full h-[50vh]" wire:ignore>

                        </div>
                        <div class="flex justify-center mt-4">
                            <button x-on:click="window.selectLocation()"
                                class="bg-primary-500 text-white px-4 py-2 rounded">{{ __('Use Selected Location') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- close button invisible --}}
        <button x-on:click="open = false" class="absolute top-0 right-0" id="close-loc-modal">
            <x-heroicon-o-x class="h-5 w-5 text-theme" />
        </button>
    </div>


    <div wire:ignore>
        @push('scripts')
            {{-- google map js --}}
            <script src="https://maps.googleapis.com/maps/api/js?key={{ env('googleMapKey') }}&libraries=places&callback=initMap"
                async defer></script>
            <script>
                //
                var currentLocation = @json($currentLocation);
                var map;
                var marker = null;
                var markerLocation = null;
                var infowindow = null;

                function initMap() {

                    // Create the map.
                    map = new google.maps.Map(document.getElementById("map"), {
                        center: {
                            lat: currentLocation['geometry']['location']['lat'] ?? 0,
                            lng: currentLocation['geometry']['location']['lng'] ?? 0
                        },
                        zoom: 1,
                        mylocation: true,
                        mylocationControl: true,
                        // mapTypeControl: false,

                    });

                    //add the marker to the map
                    marker = new google.maps.Marker({
                        position: {
                            lat: currentLocation['geometry']['location']['lat'] ?? 0,
                            lng: currentLocation['geometry']['location']['lng'] ?? 0
                        },
                        map: map,
                        draggable: true,
                        animation: google.maps.Animation.DROP,
                    });


                    // Create the My Location button and add it to the map
                    var myLocationButton = document.createElement('button');
                    myLocationButton.textContent = "{{ __('Use current location') }}";
                    myLocationButton.classList.add('bg-primary-500', 'text-white', 'px-4', 'py-2',
                        'rounded', 'shadow', 'outline-none', 'focus:outline-none', 'my-2', 'mx-1',
                        'ease-linear', 'transition-all', 'duration-150');
                    map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(myLocationButton);

                    // Add click event listener to the My Location button
                    myLocationButton.addEventListener('click', function() {
                        // Try to get the user's location
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function(position) {
                                // Set the map center to the user's location
                                var pos = {
                                    lat: position.coords.latitude,
                                    lng: position.coords.longitude
                                };
                                map.setCenter(pos);
                                map.setZoom(16);
                                moveMarker(pos);
                            }, function() {
                                // Handle geolocation errors
                                alert('Error: The Geolocation service failed.');
                            });
                        } else {
                            // Handle geolocation not supported
                            alert('Error: Your browser doesn\'t support geolocation.');
                        }
                    });


                    //listen to the marker position change
                    google.maps.event.addListener(marker, 'dragend', function() {
                        //update the position of the marker
                        moveMarker(marker.getPosition());
                    });


                    //listen to the map click event
                    google.maps.event.addListener(map, 'click', function(event) {
                        //update the position of the marker
                        moveMarker(event.latLng);
                    });

                }


                function moveMarker(location) {
                    marker.setPosition(location);
                    map.panTo(location);
                    fetchLocationPlaceInfo(location);
                }

                function fetchLocationPlaceInfo(position) {

                    //get the place info
                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode({
                        'latLng': position
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {

                                //get city, state/reigon, country
                                var city = '';
                                var state = '';
                                var country = '';
                                var country_code = '';
                                var address = '';
                                //iterate through address_component array
                                for (var i = 0; i < results[0].address_components.length; i++) {
                                    var addressType = results[0].address_components[i].types[0];
                                    if (addressType == 'locality') {
                                        city = results[0].address_components[i]['long_name'];
                                    }
                                    if (addressType == 'administrative_area_level_1') {
                                        state = results[0].address_components[i]['long_name'];
                                    }
                                    if (addressType == 'country') {
                                        country = results[0].address_components[i]['long_name'];
                                    }
                                    // country code
                                    if (addressType == 'country') {
                                        country_code = results[0].address_components[i][
                                            'short_name'
                                        ];
                                    }
                                }

                                //get address
                                address = results[0].formatted_address;

                                //format the address
                                markerLocation = {
                                    'address': address,
                                    'formatted_address': results[0].formatted_address,
                                    'geometry': {
                                        'location': {
                                            'lat': results[0].geometry.location.lat(),
                                            'lng': results[0].geometry.location.lng()
                                        }
                                    },
                                    'country': country,
                                    'country_code': country_code,
                                    'postal_code': '',
                                    'subLocality': city,
                                    'administrative_area_level_1': state,
                                    'locality': city,
                                    'administrative_area_level_2': state,
                                };

                                //remove the previous info window if any
                                if (infowindow) {
                                    infowindow.close();
                                }
                                //set the marker info window
                                infowindow = new google.maps.InfoWindow({
                                    content: address
                                });
                                infowindow.open(map, marker);

                            }
                        }
                    });
                }


                function useCurrentLocation() {

                    // Check if the Geolocation API is available
                    if ('geolocation' in navigator) {
                        // Request permission to access the user's location
                        navigator.permissions
                            .query({
                                name: 'geolocation'
                            })
                            .then(permissionStatus => {
                                if (permissionStatus.state === 'granted') {
                                    // If permission is granted, get the user's current position
                                    navigator.geolocation.getCurrentPosition(
                                        position => {
                                            const {
                                                latitude,
                                                longitude
                                            } = position.coords;

                                            window.livewire.emit('selectedLocation', latitude,
                                                longitude);
                                            closeModal();
                                        },
                                        error => {
                                            console.error(error);
                                            //show sweet alert center toast
                                            showErrorMessage(error.message);
                                        }
                                    );
                                } else if (permissionStatus.state === 'prompt') {
                                    // If permission is not yet granted, show a permission prompt
                                    navigator.geolocation.getCurrentPosition(
                                        position => {
                                            const {
                                                latitude,
                                                longitude
                                            } = position.coords;
                                            window.livewire.emit('selectedLocation', latitude,
                                                longitude);
                                            closeModal();
                                        },
                                        error => {
                                            console.error(error);
                                            //show sweet alert center toast
                                            const messageError =
                                                "You have blocked website from tracking your location. To allow it, change your browser's location settings.";
                                            showErrorMessage(error.message ?? messageError);
                                        }
                                    );
                                } else if (permissionStatus.state === 'denied') {
                                    // If permission is denied, show an error message
                                    console.error('Geolocation permission denied');
                                    //show sweet alert center toast
                                    showErrorMessage(error.message);
                                }
                            });
                    } else {
                        // If Geolocation API is not available, show an error message
                        console.error('Geolocation is not supported by this browser');
                        showErrorMessage(error.message);
                    }
                }

                function selectLocation() {
                    //get the selected location
                    var selectedLocation = markerLocation;
                    if (selectedLocation) {
                        //emit the selected location to the parent component
                        window.livewire.emit('mapSelectedLocation', selectedLocation);
                        closeModal();
                    } else {
                        //show sweet alert center toast
                        showErrorMessage("{{ __('Please select a location') }}");
                    }
                }

                function closeModal() {
                    //perform click on the close button
                    document.getElementById('close-loc-modal').click();
                }

                function showErrorMessage(message) {
                    //show sweet alert center toast
                    window.Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        text: message,
                        icon: 'error',
                        customClass: {
                            text: 'text-xs text-red-500',
                            title: 'text-xs text-red-500',
                        }
                    });
                }
            </script>
        @endpush
    </div>


</div>
