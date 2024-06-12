<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Components;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use App\Models\Service;

class PopularNearbyServices extends BaseLivewireComponent
{

    public $vendor_type_id;
    public $title;
    public $subtitle;
    public $showEmpty = false;

    //
    public function render()
    {

        $latitude = null;
        $longitude = null;
        $cLocation = $this->getSavedLocation();
        if ($cLocation) {
            $latitude = $cLocation['geometry']['location']['lat'];
            $longitude = $cLocation['geometry']['location']['lng'];
        }

        return view('livewire.extensions.glover-website.components.popular-nearby-services', [
            'services' => Service::active()
                ->when($latitude, function ($query) use ($latitude, $longitude) {
                    return $query->where(function ($query) use ($latitude, $longitude) {
                        return $query->whereHas('vendor', function ($query) use ($latitude, $longitude) {
                            return $query->active()->within($latitude, $longitude);
                        })->orWhereHas('vendor', function ($query) use ($latitude, $longitude) {
                            return $query->active()->withinrange($latitude, $longitude);
                        });
                    });
                })
                ->when($this->vendor_type_id, function ($query) {
                    return $query->whereHas('vendor', function ($query) {
                        return $query->where('vendor_type_id', $this->vendor_type_id);
                    });
                })
                ->paginate(env('WEBSITE_CAMPAIGNS_COUNT', 12))
        ]);
    }
}
