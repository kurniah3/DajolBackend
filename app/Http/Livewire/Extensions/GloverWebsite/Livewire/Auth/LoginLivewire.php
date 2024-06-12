<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire\Auth;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;

class LoginLivewire extends BaseLivewireComponent
{

    public $email;
    public $password;

    public function render()
    {
        return view('livewire.extensions.glover-website.auth.login')->layout('livewire.extensions.glover-website.layouts.app');
    }


    public function emaillogin()
    {

        $this->validate(
            [
                'email' => 'required|email',
                'password' => 'required',
            ]
        );

        if (auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            return redirect()->intended(route('glover-website.index'));
        } else {
            $this->addError('email', __('Email or password is incorrect'));
        }
    }
}
