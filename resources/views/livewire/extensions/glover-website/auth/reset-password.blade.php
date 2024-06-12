@section('title', __('Set New Password'))
<div>
    <div class="bg-white rounded border border-gray-200 p-4 px-8">
        <div class="w-full md:w-6/12 lg:w-4/12 mx-auto mt-4 mb-12">
            <h1 class="font-bold text-2xl">{{ __('Set New Password') }}</h1>
            {{-- if has token error --}}
            @if ($errors->has('token'))
                <div class="text-red-500 text-sm mt-2">
                    {{ $errors->first('token') }}
                </div>
            @elseif ($errors->has('updated'))
                <div class="text-center justify-center items-center my-4">
                    <x-heroicon-o-check-circle class="w-12 h-12 text-green-500 mx-auto" />
                    <p class="text-xl text-center">{{ __('Your password has been updated!') }}</p>
                    <div class="text-green-500 text-sm mt-2">
                        {{ $errors->first('updated') }}
                    </div>
                </div>
            @else
                <form wire:submit.prevent="updateAccountPassword">
                    <x-input type="password" name="password" title="{{ __('New Password') }}" />
                    <x-input type="password" name="password_confirmation" title="{{ __('Confirm New Password') }}" />
                    <div>
                        <x-buttons.primary type="submit" title="{{ __('Set New Password') }}" />
                    </div>
                </form>
            @endif
        </div>
    </div>
    {{-- loading --}}
    @include('livewire.extensions.glover-website.components.loading')
</div>
