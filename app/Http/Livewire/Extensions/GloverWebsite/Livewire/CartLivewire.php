<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use App\Http\Controllers\API\CouponController;
use App\Http\Livewire\Extensions\GloverWebsite\Services\CartService;
use App\Models\DeliveryAddress;
use App\Models\PaymentMethod;

class CartLivewire extends BaseLivewireComponent
{

    private $cartService;
    public $cartItems = [];
    public $coupon;
    public $coupon_code;
    public $vendor_id;
    //summary
    public $total = 0;
    public $discount = 0;
    public $subtotal = 0;


    public function mount()
    {
        $this->fetchItems();
        $this->getSubtotal();
    }

    public function getCartService()
    {
        if ($this->cartService == null) {
            $this->cartService = new CartService();
        }
        return $this->cartService;
    }

    public function render()
    {
        return view('livewire.extensions.glover-website.cart')->layout('livewire.extensions.glover-website.layouts.app');
    }

    public function fetchItems()
    {
        $this->cartService = new CartService();
        $this->cartItems = $this->cartService->getItems();
    }


    public function removeItem($itemCode)
    {
        if ($this->cartService == null) {
            $this->cartService = new CartService();
        }
        $this->cartService->removeItem($itemCode);
        $this->cartItems = $this->cartService->getItems();
        $this->emit('cartUpdated');
        $this->getSubtotal();
    }



    public function incrementItem($itemCode)
    {
        $this->getCartService()->increaseCartItemQuantity($itemCode);
        $this->emit('cartUpdated');
        $this->fetchItems();
        $this->getSubtotal();
    }
    public function decrementItem($itemCode)
    {
        $this->getCartService()->decreaseCartItemQuantity($itemCode);
        $this->emit('cartUpdated');
        $this->fetchItems();
        $this->getSubtotal();
    }

    public function clearCart()
    {
        $this->getCartService()->clear();
        $this->emit('cartUpdated');
        $this->fetchItems();
        $this->getSubtotal();
    }


    public function applyCoupon()
    {
        $this->validate([
            'coupon_code' => 'required',
        ], [
            'coupon_code.required' => __('Coupon code is required'),
        ]);

        try {
            $couponController = new CouponController();
            $request = new \Illuminate\Http\Request();
            $request->merge([
                'code' => $this->coupon_code,
                'vendor_type_id' => $this->cartItems[0]['vendor_type_id'] ?? "",
            ]);
            $response = $couponController->show($request, $this->coupon_code);
            //
            if ($response->status() == 200) {
                $this->coupon = $response->getData(true);
                $this->calculateDiscount();
            } else {
                $this->coupon = null;
                $this->discount = 0;
                $this->addError('coupon_code', $response ? $response->getData()->message : __('Coupon code is invalid'));
            }
        } catch (\Exception $e) {
            $this->coupon = [];
            $this->discount = 0;
            $this->addError('coupon_code', $e->getMessage());
        }
    }

    public function calculateDiscount()
    {
        $this->discount = 0;
        if ($this->coupon) {
            $this->discount = $this->getCartService()->getDiscountPrice($this->coupon);
        }
        $this->calculateTotal();
    }

    public function getSubtotal()
    {
        $this->subtotal = $this->getCartService()->getTotal();
        $this->calculateDiscount();
    }

    public function calculateTotal()
    {
        $this->total = $this->subtotal - $this->discount;
    }

    public function proceedToCheckout()
    {
        //encrypt cart info
        $data = [
            'discount' => $this->discount,
            'coupon_code' => $this->coupon_code,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
        ];
        $encryptedData = encrypt($data);
        $link = route('glover-website.checkout', [
            'token' => $encryptedData,
        ]);
        return redirect($link);
    }
}
