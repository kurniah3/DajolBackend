<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use App\Models\Category;
use App\Models\VendorType;


class CategoriesLivewire extends BaseLivewireComponent
{

    public $vendor_type_id;
    public $vendor_type;

    public function mount()
    {
        $this->vendor_type_id = request()->vendor_type_id;
        $this->vendor_type = VendorType::find($this->vendor_type_id);
    }

    public function render()
    {
        return view('livewire.extensions.glover-website.categories-full', [
            'categories' => Category::active()
                ->when($this->vendor_type_id, function ($query) {
                    return $query->where('vendor_type_id', $this->vendor_type_id);
                })
                ->paginate(30)
        ])->layout('livewire.extensions.glover-website.layouts.app');
    }
}
