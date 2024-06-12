<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Components;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use App\Models\VendorType;
use App\Models\Category;

class Categories extends BaseLivewireComponent
{

    public $vendorType;
    public $selectedCategoryId;

    public function mount($id)
    {
        $this->vendorType = VendorType::find($id);
    }

    public function render()
    {
        return view('livewire.extensions.glover-website.components.categories', [
            'categories' => Category::active()
                ->when($this->vendorType, function ($query) {
                    return $query->where('vendor_type_id', $this->vendorType->id);
                })
                ->paginate(15)
        ]);
    }


    public function selectedCategory($id)
    {
        $this->selectedCategoryId = $id;
        $this->emit('filterByCategory', $id);
    }
}
