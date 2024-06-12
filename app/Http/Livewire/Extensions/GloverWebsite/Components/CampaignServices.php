<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Components;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use App\Models\Service;

class CampaignServices extends BaseLivewireComponent
{

    public $vendor_type_id;
    public $selectedCategoryId;
    public $title;
    public $subtitle;
    public $showEmpty = false;


    public function render()
    {
        return view('livewire.extensions.glover-website.components.campaign-services', [
            'services' => Service::active()
                ->where('discount_price', ">", 0)
                ->when($this->vendor_type_id, function ($query) {
                    return $query->whereHas('vendor', function ($query) {
                        return $query->where('vendor_type_id', $this->vendor_type_id);
                    });
                })->orderBy('discount_price', 'DESC')
                ->paginate(env('WEBSITE_CAMPAIGNS_COUNT', 12))
        ]);
    }
}
