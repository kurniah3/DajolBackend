<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use App\Models\Vendor;

class VendorLivewire extends BaseLivewireComponent
{

    public $vendor;
    public $selectedCategoryId;
    public $selectedSubcategoryId;

    public function mount($id, $slug)
    {
        $this->vendor = Vendor::find($id);

        //if empty product, redirect to 404
        if (empty($this->vendor)) {
            return redirect()->route('glover-website.404');
        }

        //
        if (in_array($this->vendor->vendor_type->slug, ['parcel', 'package', 'taxi'])) {
            return redirect()->route('glover-website.vendor.type', [
                'id' => $this->vendor->vendor_type_id,
                'slug' => $this->vendor->vendor_type->slug,
            ]);
        } else if (in_array($this->vendor->vendor_type->slug, ['booking', 'service'])) {
            return redirect()->route('glover-website.service.vendor', [
                'id' => $this->vendor->id,
                'slug' => \Str::slug($this->vendor->name),
            ]);
        }
    }


    public function render()
    {
        return view('livewire.extensions.glover-website.vendor-details')->layout('livewire.extensions.glover-website.layouts.app');
    }


    public function onCategorySelected($categoryId)
    {
        $this->selectedCategoryId = $categoryId;
        $this->selectedSubcategoryId = null;
        $this->emit('filterProducts', $this->selectedCategoryId, $this->selectedSubcategoryId);
    }

    public function onSubcategorySelected($categoryId, $subcategoryId)
    {
        $this->selectedCategoryId = $categoryId;
        $this->selectedSubcategoryId = $subcategoryId;
        $this->emit('filterProducts', $this->selectedCategoryId, $this->selectedSubcategoryId);
    }
}
