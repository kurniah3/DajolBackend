<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use App\Models\Order;

class MyOrdersLivewire extends BaseLivewireComponent
{

    public $status = '';

    public function render()
    {
        return view('livewire.extensions.glover-website.my-order', [
            'orders' => Order::where('user_id', auth()->id())
                ->when($this->status, function ($query) {
                    return $query->currentStatus($this->status);
                })
                ->latest()
                ->paginate(10)
        ])->layout('livewire.extensions.glover-website.layouts.app');
    }

    public function updatedStatus($value)
    {
        $this->status = $value;
        //clear the page
        $this->resetPage();
    }
}
