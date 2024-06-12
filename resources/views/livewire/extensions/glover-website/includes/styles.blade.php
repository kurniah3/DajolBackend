<!-- Tailwind -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<livewire:styles />
<link href="{{ asset('css/main.css') }}" rel="stylesheet">
<style>
    [x-cloak] {
        display: none !important;
    }

    @php $savedColor=setting('websiteColor', '#21a179');
    $appColor=new \OzdemirBurak\Iris\Color\Hex($savedColor);
    $appColorHsla=new \OzdemirBurak\Iris\Color\Hsla(''.$appColor->toHsla()->hue().',40%, 75%, 0.45');
    $colorShades=[50,
    100,
    200,
    300,
    400,
    500,
    600,
    700,
    800,
    900];
    $colorShadePercentages=[95,
    90,
    75,
    50,
    25,
    0,
    5,
    15,
    25,
    35];
    @endphp

    .focus\:shadow-outline-primary:focus {
        box-shadow: 0 0 0 3px {{ $appColorHsla }};
    }



    @foreach ($colorShades as $key => $colorShade)
        @php if($key < 5) {
            $appColorShade=$appColor->brighten($colorShadePercentages[$key]);
        }

        else {
            $appColorShade=$appColor->darken($colorShadePercentages[$key]);
        }
    @endphp

    .bg-primary-{{ $colorShade }} {
        background-color: {{ $appColorShade }} !important;
    }

    .focus\:border-primary-{{ $colorShade }}:focus {
        border-color: {{ $appColorShade }} !important;
    }

    .hover\:bg-primary-{{ $colorShade }}:hover {
        background-color: {{ $appColorShade }} !important;
    }

    .border-primary-{{ $colorShade }}:focus {
        border-color: {{ $appColorShade }} !important;
    }



    .text-primary-{{ $colorShade }} {
        color: {{ $appColorShade }} !important;
    }

    .ring-primary-{{ $colorShade }} {
        color: {{ $appColorShade }} !important;
    }

    .border-primary-{{ $colorShade }} {
        border-color: {{ $appColorShade }} !important;
    }

    @endforeach

    html,
    body {
        font-family: 'Montserrat', sans-serif !important;
    }


    @php $hex=setting('websiteColor', '#21a179');
    $average=381; // range 1 - 765

    if(strlen(trim($hex))==4) {
        $hex="#". substr($hex, 1, 1) . substr($hex, 1, 1) . substr($hex, 2, 1) . substr($hex, 2, 1) . substr($hex, 3, 1) . substr($hex, 3, 1);
    }

    $isDark=((hexdec(substr($hex, 1, 2))+hexdec(substr($hex, 3, 2))+hexdec(substr($hex, 5, 2)) < $average) ? true : false);


    @endphp

    .text-theme {
        color: {{ $isDark ? '#fff' : '#000' }} !important;
    }
</style>
