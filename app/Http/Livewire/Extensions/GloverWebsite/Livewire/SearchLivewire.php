<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use App\Models\Product;
use App\Models\Service;
use App\Models\Vendor;
use App\Models\VendorType;

class SearchLivewire extends BaseLivewireComponent
{

    public $category_id;
    public $vendor_type_id;
    public $vendor_id;
    public $type;
    public $keyword;
    public $showProducts = true;
    public $showServices = true;
    public $perPage = 30;
    public $orderBy = 'desc';
    public $sort = '';

    protected $queryString = ['category_id', 'vendor_type_id', 'type', 'keyword', 'vendor_id'];

    public function mount()
    {
        if ($this->type == 'product') {
            $this->showServices = false;
        } else if ($this->type == 'service') {
            $this->showProducts = false;
        } else if ($this->type = "vendor") {
            $vendor = Vendor::find($this->vendor_id);
            if ($vendor != null) {
                if (!in_array($vendor->vendor_type->slug, ['booking', 'service', 'book'])) {
                    $this->showServices = false;
                } else {
                    $this->showProducts = false;
                }
            }
        } else {
            $this->showProducts = VendorType::whereIn('slug', ['product', 'food', 'commerce'])->count() > 0;
            $this->showServices = VendorType::whereIn('slug', ['booking', 'book', 'service'])->count() > 0;
        }
    }

    public function render()
    {
        return view('livewire.extensions.glover-website.search', [
            'products' => $this->getProducts(),
            'services' => $this->getServices(),
            'vendors' => $this->getVendors(),
        ])->layout('livewire.extensions.glover-website.layouts.app');
    }

    public function getProducts()
    {
        $products = Product::where('name', 'like', '%' . $this->keyword . '%')
            ->when($this->orderBy, function ($query) {
                return $query->orderBy('name', $this->orderBy);
            })
            ->when($this->category_id, function ($query) {
                return $query->whereHas('categories', function ($q) {
                    return $q->where('categories.id', $this->category_id);
                });
            })
            ->when($this->vendor_type_id, function ($query) {
                return $query->whereHas('vendor', function ($q) {
                    return $q->whereHas('vendor_type', function ($q) {
                        return $q->where('id', $this->vendor_type_id);
                    });
                });
            })
            ->when($this->sort == "campaign", function ($q) {
                return $q->where('discount_price', '!=', 0);
            })
            ->when($this->vendor_id, function ($q) {
                return $q->where('vendor_id', $this->vendor_id);
            })
            ->paginate($this->perPage ?? 20, ['*'], 'productsPage');
        return $products;
    }

    public function getServices()
    {
        $services = Service::where('name', 'like', '%' . $this->keyword . '%')
            ->when($this->orderBy, function ($query) {
                return $query->orderBy('name', $this->orderBy);
            })
            ->when($this->category_id, function ($query) {
                return $query->whereHas('category', function ($q) {
                    return $q->where('id', $this->category_id);
                });
            })
            ->when($this->vendor_type_id, function ($query) {
                return $query->whereHas('vendor', function ($q) {
                    return $q->whereHas('vendor_type', function ($q) {
                        return $q->where('id', $this->vendor_type_id);
                    });
                });
            })
            ->when($this->sort == "campaign", function ($q) {
                return $q->where('discount_price', '!=', 0);
            })
            ->when($this->vendor_id, function ($q) {
                return $q->where('vendor_id', $this->vendor_id);
            })
            ->paginate($this->perPage ?? 20, ['*'], 'servicesPage');
        return $services;
    }

    public function getVendors()
    {
        $vendors = Vendor::where('name', 'like', '%' . $this->keyword . '%')
            ->when($this->orderBy, function ($query) {
                return $query->orderBy('name', $this->orderBy);
            })
            ->when($this->category_id, function ($query) {
                return $query->whereHas('categories', function ($q) {
                    return $q->where('categories.id', $this->category_id);
                });
            })
            ->when($this->vendor_type_id, function ($query) {
                return $query->whereHas('vendor_type', function ($q) {
                    return $q->where('id', $this->vendor_type_id);
                });
            })
            ->paginate($this->perPage ?? 20, ['*'], 'vendorsPage');
        return $vendors;
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }
}
