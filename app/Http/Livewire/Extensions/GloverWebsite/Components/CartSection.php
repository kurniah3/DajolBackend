<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Components;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use App\Http\Livewire\Extensions\GloverWebsite\Services\CartService;

class CartSection extends BaseLivewireComponent
{

    protected $cartService;
    public $totalItem = 0;
    public $styles;

    public function getListeners()
    {
        return [
            'cartUpdated' => 'updateCart',
        ];
    }

    public function mount()
    {
        $this->updateCart();
    }

    public function render()
    {
        return view('livewire.extensions.glover-website.components.cart-section');
    }


    public function updateCart()
    {
        if ($this->cartService == null) {
            $this->cartService = new CartService();
        }
        //
        $this->totalItem = $this->cartService->getTotalItems();
    }
}
