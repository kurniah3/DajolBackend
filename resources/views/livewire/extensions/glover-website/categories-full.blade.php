@section('title', __('Categories'))
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
                'url' => '',
            ],
        ],
    ])

    <div class='bg-white p-2 px-4 rounded-lg shadow'>
        <p class="text-lg font-medium mb-2">{{ __('Categories') }}</p>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mt-4">
            @foreach ($categories as $category)
                @php
                    $link = route('glover-website.search', ['category_id' => $category->id]);
                    
                    //if category has subcategories
                    if ($category->sub_categories->isNotEmpty()) {
                        $link = route('glover-website.category', [
                            'id' => $category->id,
                            'slug' => \Str::slug($category->name),
                        ]);
                    }
                @endphp

                <a href="{{ $link }}">
                    <div class="border border-gray-200 rounded shadow py-4 justify-center items-center text-center">
                        <img src="{{ $category->photo }}" alt="{{ $category->name }}" class="w-16 h-16 mx-auto" />
                        <p class="capitalize text-xs font-medium line-clamp-1 w-full text-ellipsis line-clamp-1">
                            {{ $category->name }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
        {{-- if categories have more paginate --}}
        <div class="my-4">
            {{ $categories->links() }}
        </div>

    </div>
</div>
