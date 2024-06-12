<div class="">

    @if (empty($banners) || $banners->isEmpty())
    @else
        @php
            $sliderId = $eId ?? 'slider';
            $bannerHeight = env('WEBSITE_BANNER_HEIGHT', setting('bannerHeight', 250));
        @endphp


        <div class="{{ $sliderId }} rounded-lg overflow-hidden">
            <!-- Slides -->
            @foreach ($banners as $banner)
                <div class="carousel-cell w-full rounded-lg">
                    @php

                        if (!empty($banner->vendor)) {
                            $link = route('glover-website.vendor', [
                                'id' => $banner->vendor->id,
                                'slug' => \Str::slug($banner->vendor->name),
                            ]);
                        } elseif (!empty($banner->category)) {
                            $link = route('glover-website.category', [
                                'id' => $banner->category->id,
                                'slug' => \Str::slug($banner->category->name),
                            ]);
                        } elseif (!empty($banner->link)) {
                            $link = $banner->link ?? '#';
                        } else {
                            $link = '';
                        }
                    @endphp
                    @empty($link)
                        <img src="{{ $banner->photo }}"
                            class="h-[{{ $bannerHeight / 1.9 }}px] lg:h-[{{ $bannerHeight }}px]" />
                    @else
                        <a href="{{ $link }}">
                            <img src="{{ $banner->photo }}"
                                class="h-[{{ $bannerHeight / 1.9 }}px] lg:h-[{{ $bannerHeight }}px]" />
                        </a>
                    @endempty
                </div>
            @endforeach
        </div>


        @push('styles')
            <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
            <style>
                /* Small devices (up to 767px) */
                @media (max-width: 767px) {

                    /* CSS styles for small devices */
                    .carousel-cell {
                        width: 90%;
                        margin-right: 2.5%;
                        margin-left: 2.5%;
                    }
                }

                /* Medium devices (768px to 991px) */
                @media (min-width: 768px) and (max-width: 991px) {

                    /* CSS styles for medium devices */
                    .carousel-cell {
                        width: 50%;
                        margin-right: 2.5%;
                        margin-left: 2.5%;
                    }
                }

                /* Large devices (992px and above) */
                @media (min-width: 992px) {

                    /* CSS styles for large devices */
                    .carousel-cell {
                        width: 35%;
                        margin-right: 2%;
                        margin-left: 2%;
                    }
                }

                /* smaller, dark, rounded square */
                .flickity-button {
                    background: {{ setting('websiteColor', '#21a179') }};
                }

                .flickity-button:hover {
                    background: {{ setting('websiteColor', '#21a179') }};
                }

                .flickity-prev-next-button {
                    width: 30px;
                    height: 30px;
                    border-radius: 5px;
                }

                /* icon color */
                .flickity-button-icon {
                    fill: {{ setting('websiteColor', '#21a179') }};
                }

                .flickity-button-icon:hover {
                    fill: white;
                }

                /* position dots up a bit */
                .flickity-page-dots {
                    bottom: -22px;
                }

                /* dots are lines */
                .flickity-page-dots .dot {
                    height: 4px;
                    width: 40px;
                    margin: 0;
                    border-radius: 0;
                }
            </style>
        @endpush
        @push('scripts')
            <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {

                    var flkty = new Flickity('.{{ $sliderId }}', {
                        // options
                        autoPlay: true,
                        freeScroll: true,
                        wrapAround: true,
                        rightToLeft: {{ \Session::get('locale', env('WEBSITE_DEFAULT_LANGUAGE', config('app.locale'))) == 'ar' ? 'true' : 'false' }},
                    });

                });
            </script>
        @endpush

    @endif
</div>
