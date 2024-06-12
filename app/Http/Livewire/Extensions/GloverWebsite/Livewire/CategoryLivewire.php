<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use App\Models\Category;
use App\Models\Subcategory;


class CategoryLivewire extends BaseLivewireComponent
{

    public $category_id;
    public $category;
    public $vendor_type;

    public function mount($id, $slug)
    {
        $this->category_id = $id;
        $this->category = Category::find($this->category_id);
        $this->vendor_type = $this->category->vendor_type;
    }

    public function render()
    {
        return view('livewire.extensions.glover-website.category', [
            'subcategories' => Subcategory::active()
                ->when($this->category_id, function ($query) {
                    return $query->where('category_id', $this->category_id);
                })
                ->paginate(30)
        ])->layout('livewire.extensions.glover-website.layouts.app');
    }
}
