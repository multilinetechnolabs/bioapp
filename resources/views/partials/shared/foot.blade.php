<!-- Scripts -->
<script src="{{ \App\Support\VersionedAsset::url('js/manifest.js') }}"></script>
<script src="{{ \App\Support\VersionedAsset::url('js/vendor.js') }}"></script>
<script src="{{ \App\Support\VersionedAsset::url('js/app.js') }}"></script>
<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GOOGLE_ANALYTICS_ID') }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());
    gtag('config', "{{ env('GOOGLE_ANALYTICS_ID') }}");
</script>
<!-- Sentry -->
@if (!empty(env('SENTRY_LARAVEL_DSN')))
<script src="https://cdn.ravenjs.com/3.26.4/raven.min.js" crossorigin="anonymous"></script>
<script>
    Raven.config("{{ env('SENTRY_LARAVEL_DSN') }}").install()
</script>
@endif
