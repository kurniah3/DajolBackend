<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire\Auth;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use Propaganistas\LaravelPhone\PhoneNumber;
use App\Http\Controllers\API\AuthController;

class RegisterLivewire extends BaseLivewireComponent
{

    public $name;
    public $email;
    public $phone;
    public $password;
    public $referral_code;
    public $terms;



    public function mount()
    {
        //get email from query if any
        $this->email = request()->query('email');
    }



    public function render()
    {
        return view('livewire.extensions.glover-website.auth.register')->layout('livewire.extensions.glover-website.layouts.app');
    }


    public function signup()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'terms' => 'required',
        ]);

        //create an account
        try {

            $phoneNumber = new PhoneNumber($this->phone);
            //
            $authController = new AuthController();
            $request = new \Illuminate\Http\Request();
            $request->merge([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => $this->password,
                'referral_code' => $this->referral_code,
                'country_code' => $phoneNumber->getCountry() ?? "us",
            ]);

            //create user account via api
            $response = $authController->register($request);
            if ($response->getStatusCode() == 200 && auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
                return redirect()->route('glover-website.index');
            } else {
                try {
                    $errormsg = json_decode($response->getContent(), true)['message'];
                    $this->showErrorAlert($errormsg ?? __('An error occurred while creating your account. Please try again later.'));
                } catch (\Exception $e) {
                    $this->showErrorAlert(__('An error occurred while creating your account. Please try again later.'));
                }
            }
        } catch (\Exception $e) {
            $this->addError('terms', $e->getMessage() ?? __('An error occurred while creating your account. Please try again later.'));
        }
    }
}
