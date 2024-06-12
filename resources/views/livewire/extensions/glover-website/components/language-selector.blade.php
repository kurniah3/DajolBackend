<div>
    {{-- small screen --}}
    <div class="relative md:hidden">
        <select wire:model="lan"
            class="block appearance-none w-full bg-white border border-gray-100 text-gray-700 py-1 pl-2 pr-10 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
            @foreach ($languages as $language)
                @php
                    $optionId = $language->id ?? ($language['id'] ?? $language);
                @endphp
                <option value="{{ $optionId }}">{{ $language['name'] }}</option>
            @endforeach
        </select>

    </div>
    {{-- Large screen --}}
    <div class="hidden md:block">
        <div class="relative" x-data="{ isMenuOpen: false }">
            <button type="button" x-on:click="isMenuOpen = !isMenuOpen"
                class="rounded-full px-4 border border-gray-200 font-medium">
                <p>{{ $currentLanguage ?? '' }}</p>
            </button>
            <div x-show="isMenuOpen" x-on:click.away="isMenuOpen = false"
                class="absolute right-0 w-48 bg-white rounded-lg shadow-lg overflow-clip text-black">
                @foreach ($languages as $language)
                    <p class="hover:bg-gray-100 px-4 py-2 cursor-pointer"
                        wire:click="setNewLang('{{ $language['id'] }}')">
                        {{ $language['name'] }}
                    </p>
                @endforeach
            </div>
        </div>
    </div>

    {{-- loading --}}
    @include('livewire.extensions.glover-website.components.loading')
</div>
