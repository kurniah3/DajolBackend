<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use App\Http\Controllers\API\OrderController;
use App\Models\DeliveryAddress;
use App\Models\PaymentMethod;
use App\Models\Service;
use App\Models\ServiceOption;
use Carbon\Carbon;

class ServiceBookingLivewire extends BaseLivewireComponent
{

    public $service;
    public $duration = 1;
    public $coupon;
    public $coupon_code;
    public $vendor_id;
    public $payment_method_id;
    public $delivery_address_id;
    public $schedule_date;
    public $schedule_time;
    public $note;
    //
    public $deliveryAddresses = [];
    public $paymentMethods = [];
    public $selectedGroupOptionsFlatten;
    public $selectedGroupOptions = [];
    //
    public $datesSlot = [];
    public $timesSlot = [];
    public $schedule_order = false;
    //summary
    public $total = 0;
    public $selectedOptionsTotalPrice = 0;
    public $discount = 0;
    public $subtotal = 0;
    public $tax = 0;
    public $fees = [];



    public function mount($id, $slug)
    {
        $this->service = Service::find($id);

        //if empty product, redirect to 404
        if (empty($this->service)) {
            return redirect()->route('glover-website.404');
        }

        //
        $this->selectedGroupOptions = session()->get('service_options', []);
        $this->getSelectedOptions();
        $this->fetchDeliveryAddresses();
        $this->fetchPaymentMethods();
        $this->fetchDateSlots();
        $this->calculateSummary();
    }


    public function render()
    {
        return view('livewire.extensions.glover-website.service-booking')->layout('livewire.extensions.glover-website.layouts.app');
    }

    public function fetchDeliveryAddresses()
    {
        $this->deliveryAddresses = DeliveryAddress::where('user_id', auth()->user()->id)->get();
    }

    public function fetchPaymentMethods()
    {
        //fetch the vendor payment methods
        $vendorPaymentMethods = $this->service->vendor->payment_methods()->pluck('payment_method_id')->toArray();
        if (count($vendorPaymentMethods) > 0) {
            $this->paymentMethods = PaymentMethod::whereIn('id', $vendorPaymentMethods)->active()->get();
            if (empty($this->paymentMethods)) {
                $this->paymentMethods = PaymentMethod::active()->get();
            }
        } else {
            $this->paymentMethods = PaymentMethod::active()->get();
        }
    }

    public function getSelectedOptions()
    {
        $this->selectedGroupOptionsFlatten = "";
        $selectedNames = [];
        foreach ($this->selectedGroupOptions ?? [] as $selectedOptions) {
            if (is_array($selectedOptions)) {

                foreach ($selectedOptions as $optionId) {
                    $option = ServiceOption::find($optionId);
                    if ($option) {
                        $selectedNames[] = $option->name;
                    }
                }
            } else {
                $option = ServiceOption::find($selectedOptions);
                if ($option) {
                    $selectedNames[] = $option->name;
                }
            }
        }
        //
        $this->selectedGroupOptionsFlatten = implode(", ", $selectedNames);
        $this->getOptionTotalPrice();
    }

    public function getOptionTotalPrice()
    {
        $this->selectedOptionsTotalPrice = 0;
        foreach ($this->selectedGroupOptions ?? [] as $selectedOptions) {
            if (is_array($selectedOptions)) {

                foreach ($selectedOptions as $optionId) {
                    $option = ServiceOption::find($optionId);
                    if ($option) {
                        $this->selectedOptionsTotalPrice += $option->price;
                    }
                }
            } else {
                $option = ServiceOption::find($selectedOptions);
                if ($option) {
                    $this->selectedOptionsTotalPrice += $option->price;
                }
            }
        }
    }

    //increase and decrease duration functions
    public function increaseDuration()
    {
        $this->duration++;
        $this->calculateSummary();
    }

    public function decreaseDuration()
    {
        if ($this->duration > 1) {
            $this->duration--;
        }

        $this->calculateSummary();
    }

    public function updatedScheduleDate()
    {
        $this->fetchTimeSlots();
    }

    public function fetchDateSlots()
    {

        $slots = [];
        $vendor = $this->service->vendor;

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
        $vendor = $this->service->vendor;
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



    public function onPaymentMethodSelected($payment_method_id)
    {
        $this->payment_method_id = $payment_method_id;
    }

    //
    public function calculateSummary()
    {
        $vendor = $this->service->vendor;
        $price = $this->service->price;
        if ($this->service->discount_price > 0) {
            $price = $this->service->discount_price;
        }
        $this->subtotal = $price * $this->duration;
        $this->tax = $this->subtotal * ($vendor->tax / 100);
        $totalFees = 0;
        $this->fees = [];
        foreach ($vendor->fees as $fee) {
            $currentFee = 0;
            $feeName = $fee->name;
            //if fee is percentage
            if ($fee->percentage) {
                $currentFee = $this->subtotal * ($fee->value / 100);
                $feeName = $fee->name . ' (' . $fee->value . '%)';
            } else {
                $currentFee = $fee->value;
            }
            $totalFees += $currentFee;
            $this->fees[] = [
                'name' => $feeName,
                'amount' => $currentFee,
            ];
        }
        //
        $this->getOptionTotalPrice();
        $this->total = $this->subtotal + $this->selectedOptionsTotalPrice + $this->tax + $totalFees;
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



    //submit booking
    public function placeBooking()
    {
        $this->validate(
            [
                'schedule_date' => 'required_if:schedule_order,1',
                'schedule_time' => 'required_if:schedule_order,1',
                'payment_method_id' => 'required',
                'delivery_address_id' => 'required',
            ],
            [
                'schedule_date.required_if' => __('Please select a date'),
                'schedule_time.required_if' => __('Please select a time'),
                'payment_method_id.required' => __('Please select a payment method'),
                'delivery_address_id.required' => __('Please select a booking address'),
            ]
        );

        //
        try {
            //create booking
            $orderController = new OrderController();
            $request = new \Illuminate\Http\Request();
            $request->merge([
                'type' => 'service',
                'vendor_id' => $this->service->vendor_id,
                'service_id' => $this->service->id,
                'options_flatten' => $this->selectedGroupOptionsFlatten,
                'duration' => $this->duration,
                'schedule_date' => $this->schedule_date,
                'schedule_time' => $this->schedule_time,
                'payment_method_id' => $this->payment_method_id,
                'delivery_address_id' => $this->delivery_address_id,
                "sub_total" => $this->subtotal,
                "discount" => $this->discount,
                "delivery_fee" => 0,
                "tax" => $this->tax,
                "tax_rate" => $this->service->vendor->tax,
                "fees" => json_encode($this->fees),
                "total" => $this->total,
                "service_price" => $this->service->discount_price > 0 ? $this->service->discount_price : $this->service->price,
                "hours" => $this->duration,
            ]);

            //store order
            $response = $orderController->store($request);
            //get response data
            $responseData = $response->getData(true);
            //if no error
            if ($response->getStatusCode() == 200) {
                $this->toastSuccess(__('Booking placed successfully'));

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
            logger("Error", [$ex]);
            $this->toastError($ex->getMessage() ?? __('Something went wrong'));
        }
    }
}
