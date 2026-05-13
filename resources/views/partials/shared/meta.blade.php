<link rel="canonical" href="{{ env('APP_URL')}}" />

<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<meta name="csrf-token" content="{{ csrf_token() }}">

<meta name="description" content="@yield('meta-description', env('META_DESCRIPTION'))">
<meta name="keywords" content="@yield('meta-keywords', env('META_KEYWORDS'))">
<meta name="robots" content="{{ empty(Auth::user()) ? 'index, follow' : 'noindex, nofollow' }}">

<meta property="og:type" content="@yield('meta-type', 'website')" />
<meta property="og:title" content="@yield('meta-title', env('APP_TITLE'))" />
<meta property="og:description" content="@yield('meta-keywords', env('META_KEYWORDS'))" />
<meta property="og:image" content="@yield('meta-image', env('APP_URL').'/images/homepage/flamingo.png')" />
<meta property="og:url" content="@yield('meta-url', env('APP_URL'))" />
<meta property="og:site_name" content="@yield('meta-title', env('APP_TITLE'))" />

<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="" />
<meta name="twitter:creator" content="" />


@if(Auth::user())
    <meta name="api-host" content="{{ env('APP_WEB_API_URL') }}">
    <meta name="api-version" content="{{ env('APP_WEB_API_VERSION') ?? 'v1' }}">
    <meta name="api-token" content="{{ Auth::user()->apiToken() }}">

    <meta name="firebase-version" content="{{ env('FIREBASE_VERSION') }}">
    <meta name="firebase-api-key" content="{{ env('FIREBASE_API_KEY') }}">
    <meta name="firebase-auth-domain" content="{{ env('FIREBASE_AUTH_DOMAIN') }}">
    <meta name="firebase-database-url" content="{{ env('FIREBASE_DATABASE_URL') }}">
    <meta name="firebase-project-id" content="{{ env('FIREBASE_PROJECT_ID') }}">
    <meta name="firebase-storage-bucket" content="{{ env('FIREBASE_STORAGE_BUCKET') }}">
    <meta name="firebase-messaging-sender-id" content="{{ env('FIREBASE_MESSAGING_SENDER_ID') }}">
@endif

<link rel="icon" href="{{ \App\Support\VersionedAsset::url('images/favicons/32x32.png') }}" sizes="32x32" type="image/png">

<link rel="dns-prefetch" href="{{ env('APP_WEB_ASSETS_URL') }}">
