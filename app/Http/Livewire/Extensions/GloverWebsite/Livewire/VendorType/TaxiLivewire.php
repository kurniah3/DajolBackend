<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire\VendorType;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use App\Models\VendorType;
use App\Http\Controllers\API\TaxiOrderController;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\VehicleType;
use App\Traits\GoogleMapApiTrait;
use App\Traits\TaxiTrait;
use Illuminate\Support\Facades\DB;
use Exception;

class TaxiLivewire extends BaseLivewireComponent
{
    use GoogleMapApiTrait, TaxiTrait;

    public $vendorType;
    public $paymentMethod;
    public $pickup_address;
    public $dropoff_address;
    public $vehicleType;
    public $coupon_code;

    // amounts
    public $amount;
    public $discount;
    public $total_amount;


    protected $rules = [
        "pickup_address" => "required",
        "dropoff_address" => "required",
    ];


    public function getListeners()
    {
        return $this->listeners + [
            'selectedCoordinates' => 'selectedCoordinates',
            'payment_method_idUpdated' => 'paymentMethodSelected',
            'pickup_addressUpdated' => 'pickupAddressSelected',
            'dropoff_addressUpdated' => 'dropoffAddressSelected',
            'vehcile_type_idUpdated' => 'vehicleTypeSelected',
        ];
    }


    public function mount()
    {
        $this->vendorType = VendorType::whereSlug('taxi')->first();
    }


    public function render()
    {
        $this->generateSummary();
        return view('livewire.extensions.glover-website.vendor-types.taxi')->layout('livewire.extensions.glover-website.layouts.app');
    }


    // createOrder function to save order
    public function createOrder()
    {
        //show error alert if payment method is null
        if (!$this->paymentMethod) {
            $this->showErrorAlert(__('Please select payment method'));
            return;
        }
        //show error alert if pickup address is null
        if (!$this->pickup_address) {
            $this->showErrorAlert(__('Please select pickup address'));
            return;
        }
        //show error alert if dropoff address is null
        if (!$this->dropoff_address) {
            $this->showErrorAlert(__('Please select dropoff address'));
            return;
        }
        //show error alert if vehicle type is null
        if (!$this->vehicleType) {
            $this->showErrorAlert(__('Please select vehicle type'));
            return;
        }
        //show error alert if amount is null
        if (!$this->amount || !$this->total_amount) {
            $this->showErrorAlert(__('Please select pickup and dropoff address'));
            return;
        }
        //create DB transaction with try catch
        try {
            DB::beginTransaction();
            $taxiOrderController = new TaxiOrderController();
            $request = new \Illuminate\Http\Request();
            $request->merge([
                'user_id' => \Auth::id(),
                'vehicle_type_id' => $this->vehicleType->id,
                'payment_method_id' => $this->paymentMethod->id,
                'pickup' => [
                    "lat" => $this->pickup_address['geometry']['location']['lat'],
                    "lng" => $this->pickup_address['geometry']['location']['lng'],
                    "address" => $this->pickup_address['formatted_address'],
                ],
                'dropoff' => [
                    "lat" => $this->dropoff_address['geometry']['location']['lat'],
                    "lng" => $this->dropoff_address['geometry']['location']['lng'],
                    "address" => $this->dropoff_address['formatted_address'],
                ],
                'sub_total' => $this->amount,
                'discount' => $this->discount,
                'coupon_code' => $this->coupon_code,
                'total' => $this->total_amount,
            ]);
            $response = $taxiOrderController->book($request);
            if ($response->getStatusCode() != 200) {
                logger("error", [$response->getData(true)['message']]);
                throw new \Exception($response->getData(true)['message'] ?? __('Something went wrong'));
            }

            DB::commit();
            $this->showSuccessAlert(__('Order created successfully'));
            $this->reset();
            return redirect()->route('glover-website.orders');
        } catch (Exception $e) {
            DB::rollBack();
            $this->showErrorAlert($e->getMessage());
        }
    }


    public function paymentMethodSelected($data)
    {
        $this->paymentMethod = PaymentMethod::find($data['value']);
    }

    public function pickupAddressSelected($data)
    {
        try {
            $this->pickup_address = json_decode($data['value'], true);
            // check if address is within taxi service area
            $this->pickup_address = $this->checkAddressWithinServiceArea($this->pickup_address);
        } catch (\Exception $e) {
            logger($e->getMessage());
            $this->pickup_address = null;
        }
    }

    public function dropoffAddressSelected($data)
    {
        try {
            $this->dropoff_address = json_decode($data['value'], true);
            // check if address is within taxi service area
            $this->dropoff_address = $this->checkAddressWithinServiceArea($this->dropoff_address);
        } catch (\Exception $e) {
            logger($e->getMessage());
            $this->dropoff_address = null;
        }
    }

    public function vehicleTypeSelected($data)
    {
        $this->vehicleType = VehicleType::find($data['value']);
    }


    public function generateSummary()
    {
        $this->amount = 0;
        $this->total_amount = 0;
        if ($this->pickup_address && $this->dropoff_address && $this->vehicleType) {
            $pickupLocation = $this->pickup_address['geometry']['location']['lat'] . ',' . $this->pickup_address['geometry']['location']['lng'];
            $dropoffLocation = $this->dropoff_address['geometry']['location']['lat'] . ',' . $this->dropoff_address['geometry']['location']['lng'];
            $this->amount = $this->getTaxiOrderTotalPrice($this->vehicleType, $pickupLocation, $dropoffLocation);
            $this->total_amount = $this->amount - $this->discount;
        } else {
            $this->discount = 0;
        }
    }



    //Utils functions
    public function checkAddressWithinServiceArea($address)
    {
        $lat = $address['geometry']['location']['lat'];
        $lng = $address['geometry']['location']['lng'];
        $taxiOrderController = new TaxiOrderController();
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'latitude' => $lat,
            'longitude' => $lng,
        ]);
        $response = $taxiOrderController->location_available($request);
        if ($response->getStatusCode() != 200) {
            $this->showErrorAlert(__('Address is not within service area'));
            return null;
        }
        return $address;
    }
}
