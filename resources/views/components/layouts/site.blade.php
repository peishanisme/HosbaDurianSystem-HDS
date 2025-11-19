<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="robots" content="{{ $robots ?? 'index, follow' }}">
    <meta name="title" content="{{ $pageTitle ?? 'Hosba Durian System' }}">
    {{-- <meta name="description"
        content="{{ $pageDescription ?? '' }}">
    <meta name="keywords"
        content="carnival, fair, event, exhibition, trade show, featuring home and electrical, property, car, baby, kids education, food and beverage, wedding, innovative, deals">
    <meta property="og:title" content="{{ config('app.name') . ' - ' . ($pageTitle ?? 'Hosba Durian System') }}">
    <meta property="og:description"
        content="{{ $pageDescription ?? 'Where industries unite and opportunities thrive. More than a fair—it\'s a MEGA EXPERIENCE under one roof!' }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ !empty($ogImage) ? $ogImage : secure_asset('assets/logo/logo.png') }}">
    <meta property="og:image:secure_url"
        content="{{ !empty($ogImage) ? $ogImage : secure_asset('assets/logo/logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ secure_asset('assets/logo/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ secure_asset('assets/logo/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ secure_asset('assets/logo/favicon/favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ secure_asset('assets/logo/favicon/favicon.ico') }}">
    <link rel="manifest" href="{{ secure_asset('assets/logo/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ secure_asset('assets/logo/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff"> --}}

    <title>@yield('title', $title ?? 'Hosba Durian System')</title>
    @include('components.site.layouts.styles')
</head>

<body class="bg-neutral min-h-screen font-sans overflow-x-hidden">
    <header class="bg-dark shadow-md sticky top-0 z-50 transition-all duration-300">
        @include('components.site.layouts.header')
    </header>

    <main class="flex-grow container mx-auto px-4 py-8 relative">
        {{ $slot }}
    </main>

    <footer  class="bg-primary/10 py-12"
      style="border-top-left-radius: 50% 20%; border-top-right-radius: 50% 20%;">
        @include('components.site.layouts.footer')
    </footer>

    @include('components.site.layouts.scripts')
</body>

</html>
