<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Option;

class CartService
{
    public function addItem($item, $extraData = [])
    {
        $cartItems = $this->getItems();
        $existingItem = Arr::first($cartItems, function ($cartItem) use ($item, $extraData) {
            return $cartItem['id'] === $item['id'] && $cartItem['extra_data'] === $extraData;
        });

        if ($existingItem) {
            $existingItem['quantity'] += $item['quantity'];
            $existingItem['extra_data'] = array_merge($existingItem['extra_data'], $extraData);
        } else {
            $item['extra_data'] = $extraData;
            $item = $this->stripProductData($item);
            $cartItems[] = $item;
        }


        //clear if the products are all from different vendors
        $this->clearIfDifferentVendor($cartItems);

        $this->saveItems($cartItems);
    }

    public function clearIfDifferentVendor($cartItems)
    {
        $vendor_ids = [];
        foreach ($cartItems as $cartItem) {
            $vendor_ids[] = $cartItem['vendor_id'];
        }
        $vendor_ids = array_unique($vendor_ids);
        if (count($vendor_ids) > 1) {
            throw new \Exception(__('You can not add products from different vendors to the cart'));
        }
    }

    public function removeItem($itemCode)
    {
        $cartItems = $this->getItems();

        $cartItems = array_filter($cartItems, function ($cartItem) use ($itemCode) {
            return $cartItem['code'] !== $itemCode;
        });

        $this->saveItems($cartItems);
    }

    public function getItems()
    {
        return json_decode(Session::get('cart.items', '[]'), true);
    }

    public function saveItems($cartItems)
    {
        Session::put('cart.items', json_encode($cartItems));
    }

    public function clear()
    {
        Session::forget('cart.items');
    }


    public function stripProductData($item)
    {
        return [
            'code' => \Str::random(10),
            'id' => $item['id'],
            'vendor_id' => $item['vendor_id'],
            'vendor_type_id' => $item['vendor']['vendor_type_id'],
            'name' => $item['name'],
            'photo' => $item['photo'],
            'sell_price' => $item['sell_price'],
            'options_total_price' => $item['options_total_price'],
            'price' => $item['price'],
            'discount_price' => $item['discount_price'],
            'quantity' => $item['quantity'],
            'extra_data' => $item['extra_data'],
        ];
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->getItems() as $item) {
            $total += ($item['sell_price'] + ($item['options_total_price'] ?? 0)) * $item['quantity'];
        }

        return $total;
    }

    public function getTotalItems()
    {
        $total = 0;

        foreach ($this->getItems() as $item) {
            $total += $item['quantity'];
        }

        return $total;
    }

    public function increaseCartItemQuantity($itemCode, $qty = 1)
    {
        $cartItems = $this->getItems();

        $cartItems = array_map(function ($cartItem) use ($itemCode, $qty) {
            if ($cartItem['code'] === $itemCode) {
                $product = Product::find($cartItem['id']);
                if ($product->available_quantity == null || $product->available_quantity >= ($cartItem['quantity'] + $qty)) {
                    $cartItem['quantity'] += $qty;
                } else {
                    $cartItem['quantity'] = $product->available_quantity;
                }
            }

            return $cartItem;
        }, $cartItems);

        $this->saveItems($cartItems);
    }

    public function decreaseCartItemQuantity($itemCode, $qty = 1)
    {
        $cartItems = $this->getItems();

        $cartItems = array_map(function ($cartItem) use ($itemCode, $qty) {
            if ($cartItem['code'] === $itemCode) {
                $newQty = $cartItem['quantity'] - $qty;
                $cartItem['quantity'] = $newQty > 0 ? $newQty : 1;
            }

            return $cartItem;
        }, $cartItems);

        $this->saveItems($cartItems);
    }


    //discount
    public function getDiscountPrice($couponArray)
    {
        //convert to Coupon model for easy access
        $coupon = Coupon::find($couponArray['id']);
        $vendors = $coupon->vendors;
        $vendorIds = $coupon->vendors->pluck('id');
        $products = $coupon->products;
        $productIds = $coupon->products->pluck('id');

        //if vendors and products are empty, then it is a global coupon
        if ($vendors->isEmpty() && $products->isEmpty()) {
            $total = $this->getTotal();
            if ($coupon->percentage) {
                return $total * ($coupon->discount / 100);
            } else {
                return $coupon->discount;
            }
        } else if ($vendors->isNotEmpty()) {
            //if vendors are not empty, then it is a vendor coupon
            $total = 0;
            foreach ($this->getItems() as $item) {
                if (in_array($item['vendor_id'], $vendorIds->toArray())) {
                    $total += $item['sell_price'] * $item['quantity'];
                }
            }

            if ($coupon->percentage) {
                return $total * ($coupon->discount / 100);
            } else {
                return $coupon->discount;
            }
        } else if ($products->isNotEmpty()) {
            //if products are not empty, then it is a product coupon
            $total = 0;
            foreach ($this->getItems() as $item) {
                if (in_array($item['id'], $productIds->toArray())) {
                    $total += $item['sell_price'] * $item['quantity'];
                }
            }

            if ($coupon->percentage) {
                return $total * ($coupon->discount / 100);
            } else {
                return $coupon->discount;
            }
        }

        return 0;
    }


    //cart items for checkout
    public function getItemForCheckout()
    {
        $cartItems = $this->getItems();
        $items = [];
        foreach ($cartItems as $cartItem) {
            $product = Product::find($cartItem['id']);
            $extraData = $cartItem['extra_data'];
            $flattenOptions = "";
            $optionIds = [];
            if ($extraData) {
                foreach ($extraData['selectedGroupOptions'] as $key => $value) {

                    //if value is an array, then it is a multiple select option
                    if (is_array($value)) {
                        foreach ($value as $optionValue) {
                            $optionIds[] = $optionValue;
                        }
                    } else {
                        $optionIds[] = $value;
                    }
                }
                $options = Option::whereIn('id', $optionIds)->get();
                foreach ($options as $option) {
                    $flattenOptions .= $option->name;
                    //if not last option, add comma
                    if ($option != $options->last()) {
                        $flattenOptions .= ", ";
                    }
                }
            }

            //
            $items[] = [
                "selected_qty" => $cartItem['quantity'],
                "price" => $product->sell_price + ($cartItem['options_total_price'] ?? 0),
                "product" => [
                    "id" => $product->id,
                    "name" => $product->name,
                ],
                "options_flatten" => $flattenOptions,
                "options_ids" => $optionIds,
            ];
        }
        return $items;
    }
}
