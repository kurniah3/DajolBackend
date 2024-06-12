@section('title', __('Subcategories'))
<div>
    {{-- breadcrumb --}}
    @include('livewire.extensions.glover-website.components.nav-breadcrumb', [
        'links' => [
            [
                'title' => __('Home'),
                'url' => route('glover-website.index'),
            ],
            [
                'title' => $vendor_type->name,
                'url' => route('glover-website.vendor.type', [
                    'id' => $vendor_type->id,
                    'slug' => $vendor_type->slug ?? \Str::slug($vendor_type->name),
                ]),
            ],
            [
                'title' => __('Categories'),
                'url' => route('glover-website.categories', [
                    'vendor_type_id' => $vendor_type->id,
                    'slug' => $vendor_type->slug ?? \Str::slug($vendor_type->name),
                ]),
            ],
            [
                'title' => __('Subcategories'),
                'url' => '',
            ],
        ],
    ])

    <div class='bg-white p-2 px-4 rounded-lg shadow'>
        <p class="text-lg font-medium mb-2">
            {{ $category->name }} - <span class="font-medium text-sm">{{ __('Subcategories') }}</span>
        </p>

        {{-- subcategories --}}
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">

            @foreach ($subcategories as $subcategory)
                @php
                    $link = route('glover-website.search', [
                        'id' => $subcategory->id,
                        'type' => 'subcategory',
                    ]);
                @endphp
                <a href="{{ $link }}" class="text-sm text-gray-600 hover:text-gray-800">
                    <div class="border border-gray-200 rounded shadow py-4 justify-center items-center text-center">
                        <img src="{{ $subcategory->photo }}" alt="{{ $subcategory->name }}" class="w-16 h-16 mx-auto" />
                        <p class="capitalize text-xs font-medium line-clamp-1 w-full text-ellipsis line-clamp-1">
                            {{ $subcategory->name }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
        {{-- links --}}
        <div class="my-4">
            {{ $subcategories->links() }}
        </div>

    </div>
</div>
