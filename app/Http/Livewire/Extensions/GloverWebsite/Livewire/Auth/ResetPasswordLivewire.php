<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire\Auth;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordLivewire extends BaseLivewireComponent
{

    public $email;
    public $time;
    public $expire;
    public $token;
    public $password;
    public $password_confirmation;

    public function mount($token)
    {
        $this->token = $token;
        try {
            $token = decrypt($token);
            $this->email = $token['email'];
            $this->time = $token['time'];
            $this->expire = $token['expire'];

            //if now is greater than expire
            if (now()->timestamp > $this->expire) {
                $this->addError('token', __('Token has expired'));
            }
        } catch (\Exception $e) {
            $this->addError('token', $e->getMessage() ?? __('Something went wrong'));
        }
    }

    public function render()
    {
        return view('livewire.extensions.glover-website.auth.reset-password')->layout('livewire.extensions.glover-website.layouts.app');
    }


    public function updateAccountPassword()
    {
        $this->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $this->email)->first();
        if ($user) {
            $user->password = Hash::make($this->password);
            $user->save();
            $this->showSuccessAlert(__('Password updated successfully'));
            $this->addError('updated', __('Password updated successfully'));
        } else {
            $this->addError('token', __('User not found'));
        }
    }
}
