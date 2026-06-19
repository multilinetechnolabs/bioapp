@php
    // ── Locale & SEO data ─────────────────────────────────────────────────────
    $currentLocale = $locale ?? app()->getLocale();
    $currentPage   = $seoPage ?? null;

    // SEO page title takes precedence; otherwise fall back to @section('page-title') + site name
    $__yieldedTitle  = trim((string) $__env->yieldContent('page-title'));
    $__siteTitle     = config('app.title');
    $pageTitle       = $seoData['title'] ?? ($__yieldedTitle !== '' ? $__yieldedTitle . ' - ' . $__siteTitle : $__siteTitle);
    $pageDescription = $seoData['description'] ?? env('META_DESCRIPTION', '');

    // ── Canonical & hreflang ──────────────────────────────────────────────────
    $baseUrl       = rtrim(env('APP_URL', 'https://biomagnetism.app'), '/');
    $canonicalUrl  = request()->url();

    // Build hreflang URLs when we know which SEO page we're on
    $hreflangUrls = [];
    if ($currentPage) {
        $hreflangUrls = [
            'en'        => $baseUrl . '/' . $currentPage,
            'es'        => $baseUrl . '/es/' . $currentPage,
            'fr'        => $baseUrl . '/fr/' . $currentPage,
            'x-default' => $baseUrl . '/' . $currentPage,
        ];
    }

    // ── OG image ──────────────────────────────────────────────────────────────
    $ogImage = env('APP_URL', 'https://biomagnetism.app') . '/images/homepage/flamingo.png';
@endphp

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- SEO meta --}}
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">
<meta name="keywords" content="@yield('meta-keywords', env('META_KEYWORDS', ''))">
<meta name="robots" content="{{ empty(Auth::user()) ? 'index, follow' : 'noindex, nofollow' }}">

{{-- Canonical --}}
<link rel="canonical" href="{{ $canonicalUrl }}" />

{{-- Hreflang (only on localised SEO pages) --}}
@if (!empty($hreflangUrls))
    @foreach ($hreflangUrls as $lang => $url)
        <link rel="alternate" hreflang="{{ $lang }}" href="{{ $url }}" />
    @endforeach
@endif

{{-- Open Graph --}}
<meta property="og:type"        content="website" />
<meta property="og:title"       content="{{ $pageTitle }}" />
<meta property="og:description" content="{{ $pageDescription }}" />
<meta property="og:url"         content="{{ $canonicalUrl }}" />
<meta property="og:image"       content="{{ $ogImage }}" />
<meta property="og:site_name"   content="{{ config('app.name') }}" />
<meta property="og:locale"      content="{{ $currentLocale === 'es' ? 'es_ES' : ($currentLocale === 'fr' ? 'fr_FR' : 'en_US') }}" />

{{-- Twitter Card --}}
<meta name="twitter:card"    content="summary_large_image" />
<meta name="twitter:title"   content="{{ $pageTitle }}" />
<meta name="twitter:description" content="{{ $pageDescription }}" />
<meta name="twitter:image"   content="{{ $ogImage }}" />

@if(Auth::user())
    <meta name="api-host"                    content="{{ env('APP_WEB_API_URL') }}">
    <meta name="api-version"                 content="{{ env('APP_WEB_API_VERSION') ?? 'v1' }}">
    <meta name="api-token"                   content="{{ Auth::user()->apiToken() }}">
    <meta name="firebase-version"            content="{{ env('FIREBASE_VERSION') }}">
    <meta name="firebase-api-key"            content="{{ env('FIREBASE_API_KEY') }}">
    <meta name="firebase-auth-domain"        content="{{ env('FIREBASE_AUTH_DOMAIN') }}">
    <meta name="firebase-database-url"       content="{{ env('FIREBASE_DATABASE_URL') }}">
    <meta name="firebase-project-id"         content="{{ env('FIREBASE_PROJECT_ID') }}">
    <meta name="firebase-storage-bucket"     content="{{ env('FIREBASE_STORAGE_BUCKET') }}">
    <meta name="firebase-messaging-sender-id" content="{{ env('FIREBASE_MESSAGING_SENDER_ID') }}">
@endif

<link rel="icon" href="{{ \App\Support\VersionedAsset::url('images/favicons/32x32.png') }}" sizes="32x32" type="image/png">
<link rel="dns-prefetch" href="{{ env('APP_WEB_ASSETS_URL') }}">
