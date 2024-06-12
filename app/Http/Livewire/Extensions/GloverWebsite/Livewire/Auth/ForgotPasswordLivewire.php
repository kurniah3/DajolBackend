<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire\Auth;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use App\Models\User;
use App\Mail\PlainMail;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordLivewire extends BaseLivewireComponent
{

    public $email;

    public function render()
    {
        return view('livewire.extensions.glover-website.auth.forgot-password')->layout('livewire.extensions.glover-website.layouts.app');
    }

    public function initiateForgetPassword()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        try {
            //check if email exists
            $user = User::where('email', $this->email)->first();
            //user exists
            if ($user) {

                $token = encrypt([
                    'email' => $this->email,
                    'time' => now()->timestamp,
                    'expire' => now()->addMinutes(15)->timestamp
                ]);
                $link = route('glover-website.password.reset', ['token' => $token]);
                //send mail
                $color = setting('appColorTheme.accentColor', '#64bda1');
                $title = __('Password Reset');
                $body = "<h1><b>" . __('Hello') . " {$user->name}</b><br></h1>";
                $body .= "<p>" . __('We were asked to reset your account password. Follow the instructions below if this request comes from you.') . "</p><br>";
                $body .= "<p>" . __("Ignore the email if the request to reset your password does not come from you. Don't worry, your account is safe.") . "</p><br>";
                $body .= "<p>" . __('Click the button below to set a new password.') . "</p><br>";
                $body .= "<p class='my-2'><a href='{$link}' class='rounded-full px-4 py-2 bg-primary-500 text-theme text-white' style='padding: 10px; background-color: {$color};'>" . __('Reset Password') . "</a></p><br><br>";
                $body .= "<p>" . __('If clicking the link does not work you can copy the link into your browser window or type it there directly.') . "</p><br>";
                $body .= "<p>{$link}</p>";
                Mail::to($this->email)->send(new PlainMail($title, $body));
            }


            //show success message
            $this->showSuccessAlert(__('If email exists, we have sent you a password reset link'));
        } catch (\Exception $e) {
            $this->addError('email', $e->getMessage() ?? __('Something went wrong'));
        }
    }
}
