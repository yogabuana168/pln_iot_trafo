<!DOCTYPE html>
<html lang="en">

    <meta charset="utf-8" />
    @php
        $siteName = class_exists('\App\Helpers\SiteSettings') ? \App\Helpers\SiteSettings::getSiteName() : (DB::table('site_settings')->where('setting_key', 'site_name')->value('setting_value') ?: 'PLN GPS Center');
    @endphp
    <title>@yield('title', ' | ' . $siteName)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    @php
        $siteDescription = class_exists('\App\Helpers\SiteSettings') ? \App\Helpers\SiteSettings::getSiteDescription() : (DB::table('site_settings')->where('setting_key', 'site_description')->value('setting_value') ?: 'PLN GPS Center Management System');
    @endphp
    <meta content="{{ $siteDescription }}" name="description" />
    <meta content="Pixeleyez" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

<!-- layout setup -->
<script type="module" src="{{ asset('assets/js/layout-setup.js') }}"></script>

    <!-- App favicon -->
    @php
        $faviconUrl = class_exists('\App\Helpers\SiteSettings') ? \App\Helpers\SiteSettings::getFaviconUrl() : (($faviconPath = DB::table('site_settings')->where('setting_key', 'favicon_path')->value('setting_value')) ? asset($faviconPath) : asset('assets/images/k_favicon_32x.png'));
    @endphp
    <link rel="shortcut icon" href="{{ $faviconUrl }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $faviconUrl }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $faviconUrl }}">

@yield('css')
@include('partials.head-css')

<body>

    @include('partials.header')
    @include('partials.sidebar')
    @include('partials.horizontal')

    <main class="app-wrapper">
        <div class="container-fluid">

            @include('partials.page-title')

            @yield('content')
            @include('partials.switcher')
            @include('partials.scroll-to-top')
        </div>
    </main>
    @include('partials.footer')

            @include('partials.vendor-scripts')

            @yield('js')

    </body>

</html>
