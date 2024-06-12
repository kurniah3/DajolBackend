<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use App\Models\DeliveryAddress;
use Illuminate\Support\Facades\DB;

class DeliveryAddressLivewire extends BaseLivewireComponent
{

    public $model = DeliveryAddress::class;
    public $name;
    public $description;
    public $address;
    public $latitude;
    public $longitude;
    public $city;
    public $state;
    public $country;


    protected $rules = [
        "name" => "required|string",
        "address" => "required|string",
        "latitude" => "required",
        "longitude" => "required",
        "city" => "required|string",
        "state" => "required|string",
        "country" => "required|string",
    ];


    public function getListeners()
    {
        return $this->listeners + [
            'processDeletion' => 'processDeletion',
            'autocompleteAddressSelected' => 'autocompleteAddressSelected',
        ];
    }


    public function render()
    {
        return view('livewire.extensions.glover-website.delivery-addresses', [
            'delivery_addresses' => DeliveryAddress::where('user_id', auth()->user()->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ])->layout('livewire.extensions.glover-website.layouts.app');
    }


    public function deleteAddress($id)
    {
        $this->selectedModel = DeliveryAddress::find($id);
        //show confirm alert
        $this->confirm("", [
            'title' => __('Delete Address'),
            'text' => __('Are you sure you want to delete this address?'),
            'icon' => 'warning',
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __('Cancel'),
            'onConfirmed' => 'processDeletion',
            'onCancelled' => 'dismissModal'
        ]);
    }

    public function processDeletion()
    {
        try {
            $this->selectedModel->delete();
            $this->toastSuccess(__('Address deleted successfully!'));
        } catch (\Exception $e) {
            $this->toastError($e->getMessage());
        }
    }

    public function autocompleteAddressSelected($data)
    {
        $this->address = $data["address"];
        $this->latitude = $data["latitude"];
        $this->longitude = $data["longitude"];
        $this->city = $data["city"] ?? '';
        $this->state = $data["state"] ?? '';
        $this->country = $data["country"] ?? '';
    }

    public function showCreateModal()
    {
        $this->emit('initialAddressSelected', "");
        $this->reset(['name', 'description', 'address', 'latitude', 'longitude', 'city', 'state', 'country']);
        $this->showCreate = true;
    }

    public function save()
    {
        //validate
        $this->validate();

        try {

            DB::beginTransaction();
            $model = new DeliveryAddress();
            $model->name = $this->name;
            $model->description = $this->description;
            $model->address = $this->address;
            $model->latitude = $this->latitude;
            $model->longitude = $this->longitude;
            $model->city = $this->city;
            $model->state = $this->state;
            $model->country = $this->country;
            $model->user_id = auth()->user()->id;
            $model->save();

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Delivery address") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Delivery address") . " " . __('creation failed!'));
        }
    }


    // Updating model
    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->description = $this->selectedModel->description;
        $this->address = $this->selectedModel->address;
        $this->latitude = $this->selectedModel->latitude;
        $this->longitude = $this->selectedModel->longitude;
        $this->city = $this->selectedModel->city;
        $this->state = $this->selectedModel->state;
        $this->country = $this->selectedModel->country;
        $this->emit('initialAddressSelected', $this->address);
        $this->emit('showEditModal');
    }

    public function update()
    {
        $validateRules = $this->rules;
        unset($validateRules["user_id"]);
        //validate
        $this->validate($validateRules);

        try {

            DB::beginTransaction();
            $model = $this->selectedModel;
            $model->name = $this->name;
            $model->description = $this->description;
            $model->address = $this->address;
            $model->latitude = $this->latitude;
            $model->longitude = $this->longitude;
            $model->city = $this->city;
            $model->state = $this->state;
            $model->country = $this->country;
            $model->save();

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Delivery address") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Delivery address") . " " . __('updated failed!'));
        }
    }
}
