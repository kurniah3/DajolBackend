<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use App\Models\Product;
use App\Models\Option;
use App\Http\Livewire\Extensions\GloverWebsite\Services\CartService;

class ProductLivewire extends BaseLivewireComponent
{

    public $product;
    public $selectedGroupOptions = [];
    public $selectedQty = 1;
    protected $cartService;

    public function mount($id, $slug)
    {
        $this->product = Product::find($id);

        //if empty product, redirect to 404
        if (empty($this->product)) {
            return redirect()->route('glover-website.404');
        }

        //prepare the selected group option
        $this->selectedGroupOptions = [];
        foreach ($this->product->option_groups as $optionGroup) {
            if (!$optionGroup->multiple) {
                $this->selectedGroupOptions[$optionGroup->id] = null;
            } else {
                $this->selectedGroupOptions[$optionGroup->id] = [];
            }
        }
    }


    public function render()
    {
        return view('livewire.extensions.glover-website.product-details')->layout('livewire.extensions.glover-website.layouts.app');
    }


    public function increaseQty()
    {
        if ($this->product->available_qty == null) {
            $this->selectedQty++;
        } else if ($this->product->available_qty != null && $this->selectedQty < $this->product->available_qty) {
            $this->selectedQty++;
        }

        //
        $this->product['quantity'] = $this->selectedQty;
    }

    public function decreaseQty()
    {
        if ($this->selectedQty > 1) {
            $this->selectedQty--;
            //
            $this->product['quantity'] = $this->selectedQty;
        }
    }

    function mask_string($value, $maskingStart = 0, $maskingEnd = 0, $maskingChar = '*')
    {
        if (strlen($value) <= $maskingStart + $maskingEnd) {
            return $value;
        }

        $maskedValue = substr($value, 0, $maskingStart);
        $maskedValue .= str_repeat($maskingChar, strlen($value) - $maskingStart - $maskingEnd);
        $maskedValue .= substr($value, -$maskingEnd);

        return $maskedValue;
    }

    ///
    /// Add to cart
    public function addToCart()
    {
        $this->validate([
            'selectedQty' => 'required|numeric|min:1',
        ]);

        try {
            //check if the product has option group that is required and not selected
            $requiredOptionSelection = false;
            foreach ($this->product->option_groups as $optionGroup) {
                if ($optionGroup->required && empty($this->selectedGroupOptions[$optionGroup->id])) {
                    $this->addError('option_group.' . $optionGroup->id, __('Please select :option_group', ['option_group' => $optionGroup->name]));
                    $requiredOptionSelection = true;
                }
            }

            if ($requiredOptionSelection) {
                return;
            }

            //if cart service is not set, set it
            if (empty($this->cartService)) {
                $this->cartService = new CartService();
            }

            //
            $selectedOptionsTotalPrice = 0;
            foreach ($this->selectedGroupOptions as $selectedOptions) {
                if (is_array($selectedOptions)) {

                    foreach ($selectedOptions as $optionId) {
                        $option = Option::find($optionId);
                        if ($option) {
                            $selectedOptionsTotalPrice += $option->price;
                        }
                    }
                } else {
                    $option = Option::find($selectedOptions);
                    if ($option) {
                        $selectedOptionsTotalPrice += $option->price;
                    }
                }
            }
            //clear cart
            //add to cart through cart service
            $this->product['quantity'] = $this->selectedQty;
            $this->product['options_total_price'] =  $selectedOptionsTotalPrice;

            //flatten selected options
            $selectedOptionsFlatten = "";
            foreach ($this->selectedGroupOptions as $selectedOptions) {
                if (is_array($selectedOptions)) {
                    foreach ($selectedOptions as $optionId) {
                        $option = Option::find($optionId);
                        if ($option) {
                            $selectedOptionsFlatten .= $option->name . ', ';
                        }
                    }
                } else {
                    $option = Option::find($selectedOptions);
                    if ($option) {
                        $selectedOptionsFlatten .= $option->name . ', ';
                    }
                }
            }

            $extraData = [
                'selectedGroupOptions' => $this->selectedGroupOptions,
                'selectedOptionFlatten' => $selectedOptionsFlatten,
            ];

            //
            $this->cartService->addItem($this->product, $extraData);
            //emit event to update cart
            $this->emit('cartUpdated');
            //toast
            $this->toastSuccess(__('Added to cart'));
        } catch (\Exception $e) {
            $this->toastError($e->getMessage() ?? __('Failed to add to cart'));
        }
    }

    ///buy now
    public function buyNow()
    {
        $this->addToCart();
        return redirect()->route('glover-website.cart');
    }
}