<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use App\Models\User;


class ProfileLivewire extends BaseLivewireComponent
{

    public $model = User::class;
    public $user;
    public $name;
    public $email;
    public $phone;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $this->user = auth()->user();
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
        $this->phone = auth()->user()->phone;
    }

    public function render()
    {
        return view('livewire.extensions.glover-website.profile')->layout('livewire.extensions.glover-website.layouts.app');
    }


    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->user()->id . '',
            'phone' => 'required|string|max:255|unique:users,phone,' . auth()->user()->id . '',
        ]);

        try {
            $user = auth()->user();
            $user->name = $this->name;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->save();

            $this->toastSuccess(__('Profile updated successfully'));
            $this->mount();
        } catch (\Exception $e) {
            $this->showErrorAlert($e->getMessage());
        }
    }

    public function changePassword()
    {
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        try {
            //validate current password
            if (!\Hash::check($this->current_password, auth()->user()->password)) {
                $this->showErrorAlert(__('Current password is incorrect'));
                $this->addError('current_password', __('Current password is incorrect'));
                return;
            }

            //change password
            $user = auth()->user();
            $user->password = bcrypt($this->new_password);
            $user->save();

            $this->toastSuccess(__('Password changed successfully'));
            $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        } catch (\Exception $e) {
            $this->showErrorAlert($e->getMessage());
        }
    }
}
