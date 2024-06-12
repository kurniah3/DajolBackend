<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\RegularOrderController;
use App\Http\Livewire\Extensions\GloverWebsite\Services\CartService;
use App\Models\DeliveryAddress;
use App\Models\PaymentMethod;
use App\Models\Vendor;
use App\Traits\GoogleMapApiTrait;
use Carbon\Carbon;

class CheckoutLivewire extends BaseLivewireComponent
{
    use GoogleMapApiTrait;

    private $cartService;
    public $cartItems = [];
    public $coupon_code;
    public $vendor_id;
    public $vendor;
    public $payment_method_id;
    public $delivery_address_id;
    public $schedule_date;
    public $schedule_time;
    public $note;
    //
    public $deliveryAddresses = [];
    public $is_pickup = false;
    public $canDeliver = false;
    public $paymentMethods = [];
    //
    public $datesSlot = [];
    public $timesSlot = [];
    public $schedule_order = false;
    //summary
    public $total = 0;
    public $discount = 0;
    public $subtotal = 0;
    public $tax = 0;
    public $delivery_fee = 0;
    public $fees = [];
    //
    public $summaryToken;
    public $checkoutProducts;




    public function mount()
    {
        $this->fetchItems();

        try {
            //get token from url
            $token = request()->get('token');
            //decrypt token
            $checkoutData = decrypt($token);
            //
            $this->subtotal = $checkoutData['subtotal'];
            $this->total = $checkoutData['total'];
            $this->discount = $checkoutData['discount'];
            $this->coupon_code = $checkoutData['coupon_code'] ?? "";
            //
        } catch (\Exception $e) {
            $token = null;
            return redirect()->route('glover-website.404');
        }


        //
        $cartItems = $this->getCartService()->getItems();
        $indexed = array_values($cartItems) === $cartItems;
        if (!$indexed) {
            //convert the key object to array
            $cartItems = array_values($cartItems);
        }
        $this->vendor_id = $cartItems[0]['vendor_id'];
        $this->vendor = Vendor::find($this->vendor_id);
        //
        if ($this->vendor->pickup && !$this->vendor->delivery) {
            $this->is_pickup = true;
        }

        $this->fetchDeliveryAddresses();
        $this->fetchPaymentMethods();
        $this->fetchDateSlots();
        $this->calculateSummary();
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
        return view('livewire.extensions.glover-website.checkout')->layout('livewire.extensions.glover-website.layouts.app');
    }

    public function fetchItems()
    {
        $this->cartService = new CartService();
        $this->cartItems = $this->cartService->getItems();
    }
    public function fetchDeliveryAddresses()
    {
        $this->deliveryAddresses = DeliveryAddress::where('user_id', auth()->user()->id)->get();
    }

    public function fetchPaymentMethods()
    {
        $this->paymentMethods = PaymentMethod::active()->get();
    }

    public function fetchDateSlots()
    {

        $slots = [];
        $vendor = Vendor::find($this->vendor_id);

        //
        if ($vendor == null) {
            return collect($slots);
        }
        //
        $days = $vendor->days->pluck('name')->toArray();
        //
        if (!empty($days)) {
            //max order schedule days
            $daysCount = setting('maxScheduledDay', 5) + 1;
            //
            for ($i = 0; $i < $daysCount; $i++) {
                $date = \Carbon\Carbon::now()->addDays($i);
                $dateDayName = $date->format('l d M, Y');
                $slots[] = [
                    'id' => $date->format('Y-m-d'),
                    'name' => $dateDayName,
                ];
            }

            //create collection
            $slots = collect($slots);
        }

        $this->datesSlot = $slots;
    }

    public function fetchTimeSlots()
    {
        $slots = [];
        //
        $vendor = Vendor::find($this->vendor_id);
        $date = $this->schedule_date ?? null;

        //
        if ($date == null || $vendor == null) {
            return collect($slots);
        }



        $date = \Carbon\Carbon::parse($date);
        $dateDayName = $date->format('l');
        $days = $vendor->days->pluck('name')->toArray();
        $daysTiming = $vendor->days;
        //
        try {

            $maxScheduledTime = setting('maxScheduledTime', 2);
            $currentTime = now()->format('H:s:i');
            //
            //times
            $dayTimingIndex = array_search($dateDayName, $days);
            $dayTiming = $daysTiming[$dayTimingIndex];

            $hoursdIFF = $this->calculateDiffInHours($dayTiming->pivot->open, $dayTiming->pivot->close);
            $hoursdIFF -= $maxScheduledTime;
            if (!empty($hoursdIFF)) {

                for ($j = 1; $j < $hoursdIFF; $j++) {
                    //
                    $time = $this->carbonFromTime($dayTiming->pivot->open)->addHours($j);
                    //remove time on today
                    $minTime = $this->carbonFromTime($currentTime)->addHours($maxScheduledTime)->format('H:s:i');

                    //if date is today and time is less than min time
                    if ($date->isToday() && $time->format('H:s:i') < $minTime) {
                        continue;
                    }


                    $slots[] = [
                        'id' => $time->format('H:s:i'),
                        'name' => $time->format('h:i A'),
                    ];
                }
            }
        } catch (\Exception $ex) {
            logger("Error", [$ex]);
        }

        $this->timesSlot = collect($slots);
    }


    //
    public function calculateSummary()
    {
        //
        //fetch delivery fee
        $regularOrderController = new RegularOrderController();
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'delivery_address_id' => $this->delivery_address_id,
            'pickup' => $this->is_pickup,
            'delievryAddressOutOfRange' => !$this->canDeliver,
            'vendor_id' => $this->vendor_id,
            'products' => (new CartService())->getItemForCheckout(),
            'coupon_code' => $this->coupon_code,
            'tip' => 0,
        ]);
        $response = $regularOrderController->summary($request);
        //
        if ($response->getStatusCode() == 200) {
            $data = $response->getData(true);
            $this->delivery_fee = $data['delivery_fee'];
            $this->subtotal = $data['subtotal'];
            $this->discount = $data['discount'];
            $this->tax = $data['tax'];
            $this->fees = $data['fees'];
            $this->total = $data['total'];
            $this->checkoutProducts = $data['products'];
            $this->summaryToken = $data['token'];
            $this->canDeliver = true;
        } else {
            $this->showErrorAlert($response->getData(true)->message ?? __('Error'));
        }
    }

    // OTHER FUNCTIONS
    public function calculateDiffInHours($from, $to)
    {
        $from = Carbon::createFromFormat('H:s:i', $from);
        $to = Carbon::createFromFormat('H:s:i', $to);
        return $to->diffInHours($from) ?? 0;
    }

    public function carbonFromTime($time)
    {
        return Carbon::createFromFormat('H:s:i', $time);
    }


    public function onPaymentMethodSelected($payment_method_id)
    {
        $this->payment_method_id = $payment_method_id;
    }

    public function updatedIsPickup()
    {
        $this->delivery_address_id = null;
        $this->delivery_fee = 0;
        $this->calculateSummary();
    }

    public function updatedDeliveryAddressId()
    {
        try {
            //
            $this->canDeliver = true;
            //clear errors
            $this->resetErrorBag(['delivery_address_id']);
            //first check if vendor is available for delivery
            $this->checkIfVendorIsAvailableForDelivery();
            //        
            $this->calculateSummary();
        } catch (\Exception $ex) {
            $this->addError('delivery_address_id', $ex->getMessage() ?? __('Error'));
            $this->canDeliver = false;
        }
    }

    public function updatedScheduleDate()
    {
        $this->fetchTimeSlots();
    }

    public function checkIfVendorIsAvailableForDelivery()
    {
        $vendor = Vendor::find($this->vendor_id);
        $deliveryAddress = DeliveryAddress::find($this->delivery_address_id);
        //has zones
        if ($vendor->delivery_zones->count() > 0 && $deliveryAddress != null) {

            foreach ($vendor->delivery_zones as $delivery_zone) {
                $canService = $this->insideBound(
                    [
                        "lat" => $deliveryAddress->latitude,
                        "lng" => $deliveryAddress->longitude,
                    ],
                    $delivery_zone->points
                );
                //
                if ($canService) {
                    return true;
                }
            }
            //
            if (!$canService) {
                throw new \Exception(__('This vendor is not available for delivery at your address'));
            }
        }

        //uses distance
        $originLocation = $vendor->latitude . ',' . $vendor->longitude;
        $destinationLocations  = $deliveryAddress->latitude . ',' . $deliveryAddress->longitude;
        $distanceVendorToAddress = $this->getRelativeDistance($originLocation, $destinationLocations);

        //if distance is greater than vendor delivery range
        if ($distanceVendorToAddress > $vendor->delivery_range) {
            throw new \Exception(__('This vendor is not available for delivery at your address'));
        }
    }



    // CHECKOUT
    //submit booking
    public function placeOrder()
    {
        $data = $this->validate(
            [
                'schedule_date' => 'required_if:schedule_order,1',
                'schedule_time' => 'required_if:schedule_order,1',
                'payment_method_id' => 'required',
                'delivery_address_id' => 'required_if:is_pickup,0,false',
            ],
            [
                'schedule_date.required_if' => __('Please select a date'),
                'schedule_time.required_if' => __('Please select a time'),
                'payment_method_id.required' => __('Please select a payment method'),
                'delivery_address_id.required_if' => __('Please select a delivery address'),
            ]
        );

        //
        if (!$this->is_pickup && !$this->canDeliver) {
            $this->addError('delivery_address_id', __('This vendor is not available for delivery at your address'));
            $this->toastError(__('This vendor is not available for delivery at your address'));
            return;
        }


        //
        try {
            //create booking
            $orderController = new OrderController();
            $request = new \Illuminate\Http\Request();
            $request->merge([
                "coupon_code" => $this->coupon_code,
                'vendor_id' => $this->vendor_id,
                'schedule_date' => $this->schedule_date,
                'schedule_time' => $this->schedule_time,
                'payment_method_id' => $this->payment_method_id,
                'delivery_address_id' => $this->delivery_address_id,
                "sub_total" => $this->subtotal,
                "discount" => $this->discount,
                "delivery_fee" => $this->delivery_fee,
                "tax" => $this->tax,
                "tax_rate" => $this->vendor->tax,
                "fees" => $this->fees,
                "total" => $this->total,
                "note" => $this->note,
                "products" => $this->checkoutProducts,
                'token' => $this->summaryToken,
            ]);

            //store order
            $response = $orderController->store($request);
            //get response data
            $responseData = $response->getData(true);
            //if no error
            if ($response->getStatusCode() == 200) {
                //clear cart
                (new CartService())->clear();
                $this->emit('cartUpdated');
                //
                $this->toastSuccess($responseData['message'] ?? __('Order placed successfully'));
                $link = $responseData['link'];
                if (!empty($link)) {
                    return redirect($link);
                }
                return redirect()->route('glover-website.orders');
            } else {
                $errorMsg = $responseData['message'] ?? __('Something went wrong');
                $this->toastError($errorMsg);
            }
        } catch (\Exception $ex) {
            logger($ex);
            $this->toastError($ex->getMessage() ?? __('Something went wrong'));
        }
    }
}
