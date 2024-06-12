<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Components;

use App\Http\Controllers\API\GeocoderController;
use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use Illuminate\Support\Facades\Session;

class LocationSelector extends BaseLivewireComponent
{

    public $currentLocation = null;
    public $addresses = [];
    public $search = null;


    public function getListeners()
    {
        return [
            "selectedLocation" => 'selectedLocation',
            "mapSelectedLocation" => 'mapSelectedLocation',
        ];
    }

    public function render()
    {
        return view('livewire.extensions.glover-website.components.location-selector');
    }


    public function mount()
    {
        $this->currentLocation = $this->getSavedLocation();
    }

    public function updatedSearch()
    {
        $geocontroller = new GeocoderController();
        $request = new \Illuminate\Http\Request();
        $request->replace(['keyword' => $this->search]);
        $response = $geocontroller->reverse($request);
        $this->addresses = $response->getData(true)['data'];
    }

    public function selectedAddress($index)
    {
        $this->currentLocation = $this->addresses[$index];
        Session::put('location', $this->currentLocation);
        $this->addresses = [];
        //toast('Location Selected', 'success');
        $this->toast(__('Location Selected'));
        //emit reload page
        $this->emit('reloadpage');
    }

    public function selectedLocation($lat, $lng)
    {
        $geocontroller = new GeocoderController();
        $request = new \Illuminate\Http\Request();
        $request->replace(['lat' => $lat, 'lng' => $lng]);
        $response = $geocontroller->forward($request);
        $this->addresses = $response->getData(true)['data'];
        //if not empty
        if (count($this->addresses) > 0) {
            $this->selectedAddress(0);
        }
    }

    public function mapSelectedLocation($address)
    {
        $this->currentLocation = $address;
        Session::put('location', $this->currentLocation);
        $this->addresses = [];
        $this->toast(__('Location Selected'));
        //emit reload page
        $this->emit('reloadpage');
    }
    //
}
