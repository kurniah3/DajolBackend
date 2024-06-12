<div class='bg-white p-2 px-4 rounded-lg shadow'>
    <p class="text-lg font-medium mb-2">{{ __('Categories') }}</p>
    <div class="gap-2 md:gap-0 md:space-y-2 grid grid-cols-2 md:block">
        @foreach ($categories as $category)
            @php
                $selected = $category->id == $selectedCategoryId ? 'font-bold text-primary-500 bg-gray-200 p-2' : 'p-2 md:p-0';
            @endphp
            <div>
                <a href="{{ route('glover-website.search', ['category_id' => $category->id]) }}">
                    <div
                        class="{{ $selected }} cursor-pointer hover:bg-gray-200 hover:px-2 hover:text-primary-500 flex gap-2 items-center">
                        <img src="{{ $category->photo }}" alt="{{ $category->name }}" class="w-6 h-6" />
                        <p class="capitalize text-xs font-medium line-clamp-1 w-full text-ellipsis">
                            {{ $category->name }}
                        </p>
                    </div>
                </a>
            </div>
        @endforeach
        {{-- if categories have more paginate --}}
        @if ($categories->hasPages())
            <a href="{{ route('glover-website.categories', ['vendor_type_id' => $vendorType->id]) }}">
                <div class="cursor-pointer hover:bg-gray-200 p-2 hover:text-primary-500 flex gap-2 items-center">
                    {{-- <x-lineawesome-arrow-up class="w-4 h-4" /> --}}
                    <x-lineawesome-arrow-up-solid class="w-4 h-4 text-primary-500" />
                    <p class="capitalize text-xs font-medium line-clamp-1 w-full text-ellipsis">
                        {{ __('View all') }}
                    </p>
                </div>
            </a>
        @endif
    </div>
</div>
