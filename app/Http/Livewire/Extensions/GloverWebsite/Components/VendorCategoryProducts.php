<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Components;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use App\Models\Product;

class VendorCategoryProducts extends BaseLivewireComponent
{

    public $vendorId;
    public $categoryId;
    public $subcategoryId;

    public function getListeners()
    {
        return $this->listeners + [
            'filterProducts' => 'filterProducts',
        ];
    }


    public function render()
    {
        return view('livewire.extensions.glover-website.components.vendor-category-products', [
            'products' => Product::active()
                ->where('vendor_id', $this->vendorId)
                ->when($this->categoryId, function ($query) {
                    return $query->whereHas('categories', function ($query) {
                        return $query->where('categories.id', $this->categoryId);
                    });
                })
                ->when($this->subcategoryId, function ($query) {
                    return $query->whereHas('sub_categories', function ($query) {
                        return $query->where('id', $this->subcategoryId);
                    });
                })
                ->paginate(20)
        ]);
    }

    public function filterProducts($categoryId, $subcategoryId)
    {
        $this->categoryId = $categoryId;
        $this->subcategoryId = $subcategoryId;
    }
}
