@extends('layouts.modern')

@section('page-title', 'Home')

@php
    $activeNav = 'home';
@endphp

@section('content')
    <main class="modern-main-content">
        <section class="mb-5">
            <h3 class="eyebrow mb-4">Ion Positive &amp; Negative Alignment</h3>
            <h2 class="hero-heading mb-4">
                A journey to <span class="italic-wellness">wellness</span> through magnetic energy
            </h2>
            <div class="mb-4" style="width:3rem;height:2px;background:#006a63;"></div>
            <p class="mb-0 text-secondary" style="max-width:32rem;">
                Open Major Chakra Vortexes to Release the Emotional Stress
                Behind Physical Imbalance, or Optimize Your Bio-Terrain by
                Balancing pH Radicals Through Biomagnetism.
            </p>
        </section>

        <section class="mb-5">
            <h4 class="section-eyebrow mb-4">Core Modules</h4>
            <div class="row modern-row-gap">
                <div class="col-12 col-md-6">
                    <a href="{{ route('app.bodyscan.info') }}"
                        class="module-card p-4 p-lg-5 h-100 d-flex flex-column modern-gap-4 text-decoration-none module-card-trigger"
                        data-drawer-title="Body Scan">
                        <div class="icon-tile icon-tile-body-scan">
                            <svg class="module-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="pill pill-body-scan mb-3"><span class="pill-dot"></span>Body Scan</span>
                            <h5 class="fw-bold text-dark mb-2">Full Body Analysis</h5>
                            <p class="text-secondary mb-0">Detect energy imbalances across all organ systems and tissue fields.</p>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-6">
                    <a href="{{ route('app.chakrascan.info') }}"
                        class="module-card p-4 p-lg-5 h-100 d-flex flex-column modern-gap-4 text-decoration-none module-card-trigger"
                        data-drawer-title="Chakra Scan">
                        <div class="icon-tile icon-tile-chakra-scan">
                            <svg class="module-card-icon" fill="none" stroke="currentColor" viewBox="0 0 100 100" aria-hidden="true" stroke-width="1.2">
                                <circle cx="50" cy="50" r="32"/><circle cx="50" cy="50" r="16"/><circle cx="66" cy="50" r="16"/><circle cx="58" cy="36.1" r="16"/><circle cx="42" cy="36.1" r="16"/><circle cx="34" cy="50" r="16"/><circle cx="42" cy="63.9" r="16"/><circle cx="58" cy="63.9" r="16"/>
                            </svg>
                        </div>
                        <div>
                            <span class="pill pill-chakra-scan mb-3"><span class="pill-dot"></span>Chakra Scan</span>
                            <h5 class="fw-bold text-dark mb-2">Energy Center Map</h5>
                            <p class="text-secondary mb-0">Visualize and assess vibrational alignment across all energy centers.</p>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-6">
                    <a href="{{ route('app.data_cache.info') }}"
                        class="module-card p-4 p-lg-5 h-100 d-flex flex-column modern-gap-4 text-decoration-none module-card-trigger"
                        data-drawer-title="Data Cache">
                        <div class="icon-tile icon-tile-data-cache">
                            <svg class="module-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="pill pill-data-cache mb-3"><span class="pill-dot"></span>Data Cache</span>
                            <h5 class="fw-bold text-dark mb-2">Session Records</h5>
                            <p class="text-secondary mb-0">Access treatment history, session logs, and tracked progress over time.</p>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-6">
                    <a href="{{ route('app.bioconnect.info') }}"
                        class="module-card p-4 p-lg-5 h-100 d-flex flex-column modern-gap-4 text-decoration-none module-card-trigger"
                        data-drawer-title="The Navigator">
                        <div class="icon-tile icon-tile-bio-connect">
                            <svg class="module-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="pill pill-bio-connect mb-3"><span class="pill-dot"></span>Guided Mastery</span>
                            <h5 class="fw-bold text-dark mb-2">The Navigator</h5>
                            <p class="text-secondary mb-0">Navigate your sessions with step-by-step guided scanning, integrated 'How-to' instructions, and global language support.</p>
                        </div>
                    </a>
                </div>
                <div class="col-12">
                    <a href="{{ route('app.dr_goiz_pairs') }}" class="free-protocol-banner text-decoration-none">
                        <div class="free-protocol-banner__glow"></div>
                        <div class="free-protocol-banner__badge">FREE</div>
                        <div class="free-protocol-banner__body">
                            <div class="free-protocol-banner__icon" aria-hidden="true">
                                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"/>
                                </svg>
                            </div>
                            <h4 class="free-protocol-banner__title">Free Protocol Pairs</h4>
                            <p class="free-protocol-banner__desc text-white">Browse 267 Original Biomagnetism Protocol Pairs for Therapeutic Reference — No Subscription Needed.</p>
                            <span class="free-protocol-banner__cta">
                                Explore Free Protocol Pairs
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <section id="pricing" class="mb-5">
            <h4 class="section-eyebrow mb-4">Plans &amp; Pricing</h4>
            @include('partials.modern.pricing')
        </section>

    </main>

    {{-- Info Drawer --}}
    <div class="info-drawer-backdrop" id="infoDrawerBackdrop" aria-hidden="true"></div>
    <aside class="info-drawer" id="infoDrawer" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="infoDrawerTitle">
        <div class="info-drawer__header">
            <h2 class="info-drawer__title" id="infoDrawerTitle"></h2>
            <button class="info-drawer__close" id="infoDrawerClose" type="button" aria-label="Close">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="info-drawer__body" id="infoDrawerBody">
            <div class="info-drawer__loading" id="infoDrawerLoading" aria-live="polite">
                <div class="info-drawer__spinner"></div>
                <span>Loading…</span>
            </div>
            <div class="info-drawer__content" id="infoDrawerContent"></div>
        </div>
    </aside>

    {{-- Contact Us Section --}}
    <section id="contact" class="dashboard-contact">
        <div class="dashboard-contact__inner">
            <h2 class="dashboard-contact__title">Contact Us</h2>
            <p class="dashboard-contact__subtitle">Have a question or need help? We'd love to hear from you.</p>
            <div class="dashboard-contact__grid">
                <div class="dashboard-contact__info">
                    <div class="dashboard-contact__info-item">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:{{ env('MAIL_FROM_ADDRESS', 'info@anewavenue.com') }}">{{ env('MAIL_FROM_ADDRESS', 'info@anewavenue.com') }}</a>
                    </div>
                    <div class="dashboard-contact__info-item">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        <span>{{ env('APP_URL') }}</span>
                    </div>
                    <div class="dashboard-contact__info-item">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Mon – Fri, 9 AM – 5 PM</span>
                    </div>
                    <p class="dashboard-contact__blurb">Whether you're a practitioner, therapist, or just curious about biomagnetism — reach out and we'll get back to you as soon as possible.</p>
                </div>
                @if(session('contact.success'))
                    <div style="background:#f0fdfa;border:1.5px solid #14b8a6;border-radius:0.5rem;padding:1.25rem 1.5rem;display:flex;align-items:center;gap:0.75rem;">
                        <svg width="22" height="22" fill="none" stroke="#14b8a6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span style="color:#0f766e;font-weight:600;">{{ session('contact.success') }}</span>
                    </div>
                @else
                    <form class="dashboard-contact__form" action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <input type="text" name="name" placeholder="Your Name" class="dashboard-contact__input @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<span style="color:#dc2626;font-size:0.8rem;margin-top:-0.4rem;display:block;">{{ $message }}</span>@enderror
                        <input type="email" name="email" placeholder="Your Email" class="dashboard-contact__input @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<span style="color:#dc2626;font-size:0.8rem;margin-top:-0.4rem;display:block;">{{ $message }}</span>@enderror
                        <input type="text" name="subject" placeholder="Subject" class="dashboard-contact__input" value="{{ old('subject') }}">
                        <textarea name="message" placeholder="Your Message" class="dashboard-contact__textarea @error('message') is-invalid @enderror" rows="5" required>{{ old('message') }}</textarea>
                        @error('message')<span style="color:#dc2626;font-size:0.8rem;margin-top:-0.4rem;display:block;">{{ $message }}</span>@enderror
                        <button type="submit" class="dashboard-contact__submit">Send Message</button>
                    </form>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
(function () {
    var backdrop = document.getElementById('infoDrawerBackdrop');
    var drawer   = document.getElementById('infoDrawer');
    var titleEl  = document.getElementById('infoDrawerTitle');
    var closeBtn = document.getElementById('infoDrawerClose');
    var loading  = document.getElementById('infoDrawerLoading');
    var content  = document.getElementById('infoDrawerContent');
    var cache    = {};

    function openDrawer(url, title) {
        titleEl.textContent = title || '';
        content.innerHTML = '';
        loading.style.display = 'flex';
        drawer.setAttribute('aria-hidden', 'false');
        backdrop.setAttribute('aria-hidden', 'false');
        document.body.classList.add('info-drawer-open');
        closeBtn.focus();
        if (cache[url]) { loading.style.display = 'none'; content.innerHTML = cache[url]; return; }
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.text(); })
            .then(function (html) {
                var doc  = (new DOMParser()).parseFromString(html, 'text/html');
                var main = doc.querySelector('.modern-main-content') || doc.querySelector('main');
                cache[url] = main ? main.innerHTML : doc.body.innerHTML;
                loading.style.display = 'none';
                content.innerHTML = cache[url];
            })
            .catch(function () {
                loading.style.display = 'none';
                content.innerHTML = '<p class="text-secondary p-3">Could not load. <a href="' + url + '">Open page directly</a>.</p>';
            });
    }
    function closeDrawer() {
        drawer.setAttribute('aria-hidden', 'true');
        backdrop.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('info-drawer-open');
    }
    document.querySelectorAll('.module-card-trigger').forEach(function (c) {
        c.addEventListener('click', function (e) { e.preventDefault(); openDrawer(c.href, c.dataset.drawerTitle); });
    });
    closeBtn.addEventListener('click', closeDrawer);
    backdrop.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeDrawer(); });
}());
</script>
@endpush
