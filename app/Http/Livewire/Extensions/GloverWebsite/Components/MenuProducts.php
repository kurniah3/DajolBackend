<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Components;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use App\Models\Menu;
use App\Models\Product;

class MenuProducts extends BaseLivewireComponent
{

    public $menu;
    public $menuId;
    public $vendorId;
    public $pageName;


    public function mount()
    {
        $this->menu = Menu::find($this->menuId);
        $this->pageName = \Str::slug($this->menu->name ?? \Str::random(10));
    }

    public function render()
    {
        return view('livewire.extensions.glover-website.components.menu-products', [
            'products' => Product::active()
                ->when($this->vendorId, function ($query) {
                    return $query->where('vendor_id', $this->vendorId);
                })
                ->when($this->menu, function ($query) {
                    return $query->whereHas('menus', function ($query) {
                        return $query->where('menu_id', $this->menu->id);
                    });
                })
                ->paginate(50, ['*'], $this->pageName)
        ]);
    }
}
