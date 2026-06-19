@if (!empty(env('GOOGLE_ANALYTICS_ID')))
@php
    $gaId = env('GOOGLE_ANALYTICS_ID');
    $userType = 'guest';
    if (Auth::check()) {
        $authUser = Auth::user();
        if (method_exists($authUser, 'hasValidSubscription') && $authUser->hasValidSubscription()) {
            $userType = 'subscriber';
        }
    }
    $currentLocale = $locale ?? app()->getLocale();
    $currentPage   = $seoPage ?? request()->path();
@endphp
<!-- Google Analytics 4 -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());
    gtag('config', "{{ $gaId }}", {
        'custom_map': { 'dimension1': 'user_type' },
        'user_type': "{{ $userType }}",
        'language': "{{ $currentLocale }}"
    });
    gtag('event', 'page_view', {
        'user_type': "{{ $userType }}",
        'page_locale': "{{ $currentLocale }}"
    });

    // subscription_click custom event — fires when any subscription CTA is clicked
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.pricing-card-single__btn').forEach(function (el) {
            el.addEventListener('click', function () {
                var planType = el.getAttribute('data-plan') || 'unknown';
                var planPrice = el.getAttribute('data-plan-price') || '';
                gtag('event', 'subscription_click', {
                    'event_category': 'subscription',
                    'event_label': planType,
                    'plan_type': planType,
                    'plan_price': planPrice,
                    'page_location': window.location.href,
                    'page_locale': "{{ $currentLocale }}",
                    'user_type': "{{ $userType }}"
                });
            });
        });
    });
</script>
@endif
