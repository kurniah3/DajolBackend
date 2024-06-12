<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Components;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;


class NavBreadcrumb extends BaseLivewireComponent
{

    public $links;


    public function render()
    {
        return view('livewire.extensions.glover-website.components.nav-breadcrumb');
    }
}
