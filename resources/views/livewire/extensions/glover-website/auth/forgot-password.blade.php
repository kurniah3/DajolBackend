@section('title', __('Forgot Password'))
<div>
    <div class="bg-white rounded border border-gray-200 p-4 px-8">
        <div class="w-full md:w-6/12 lg:w-4/12 mx-auto mt-4 mb-12">
            <h1 class="font-bold text-2xl">{{ __('Forgot Password') }}</h1>
            <form wire:submit.prevent="initiateForgetPassword">
                <x-input type="email" name="email" title="{{ __('Email') }}" />
                <div>
                    <x-buttons.primary type="submit" title="{{ __('Send Password Reset Link') }}" />
                </div>
            </form>
        </div>
    </div>
    {{-- loading --}}
    @include('livewire.extensions.glover-website.components.loading')
</div>
