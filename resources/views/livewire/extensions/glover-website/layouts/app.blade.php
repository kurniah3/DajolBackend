<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()"
    lang="{{ Session::get('locale', env('WEBSITE_DEFAULT_LANGUAGE', config('app.locale'))) }}"
    dir="{{ isRTL() ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ setting('favicon') }}" />
    <title>@yield('title', '') - {{ setting('websiteName', env('APP_NAME')) }}</title>
    <script src="{{ asset('js/extensions/glover-website/tailwindcss.js') }}"></script>
    <script src="{{ asset('js/extensions/glover-website/main.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    @include('livewire.extensions.glover-website.includes.styles')
    @yield('styles')
    @stack('styles')
</head>

<body class="bg-gray-100">
    @include('livewire.extensions.glover-website.includes.header')
    @yield('extra-top-content')

    <main class="h-full min-h-[80vh] overflow-y-auto bg-gray-100">
        <div class="w-full md:w-[80vw] lg:w-[70vw] xl:w-[60vw] container grid px-6 py-5 mx-auto">
            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </main>

    {{-- include --}}
    @include('livewire.extensions.glover-website.includes.footer')
    @include('layouts.partials.scripts')
    @stack('scripts')
</body>

</html>
