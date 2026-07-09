@extends('layouts.modern')

@section('page-title', 'Home')

@php
    $activeNav = 'home';
    $l = $locale ?? 'en';
    $localePfx = $l === 'en' ? '' : "/{$l}";
@endphp

@section('content')
    <main class="modern-main-content">
        <section class="mb-5">
            <h3 class="eyebrow mb-4">
                {{ $l === 'es' ? 'Alineación Iónica Positiva y Negativa' : ($l === 'fr' ? 'Alignement Ionique Positif et Négatif' : 'Ion Positive & Negative Alignment') }}
            </h3>
            <h2 class="hero-heading mb-4">
                @if($l === 'es')
                    Un viaje hacia el <span class="italic-wellness">bienestar</span> a través de la energía magnética
                @elseif($l === 'fr')
                    Un voyage vers le <span class="italic-wellness">bien-être</span> à travers l'énergie magnétique
                @else
                    A journey to <span class="italic-wellness">wellness</span> through magnetic energy
                @endif
            </h2>
            <div class="mb-4" style="width:3rem;height:2px;background:#006a63;"></div>
            <p class="mb-0 text-secondary" style="max-width:32rem;">
                @if($l === 'es')
                    Abre los principales vórtices de los chakras para liberar el estrés emocional detrás del desequilibrio físico, u optimiza tu bio-terreno equilibrando los radicales de pH a través del biomagnetismo.
                @elseif($l === 'fr')
                    Ouvrez les grands vortex des chakras pour libérer le stress émotionnel à l'origine du déséquilibre physique, ou optimisez votre bio-terrain en équilibrant les radicaux de pH grâce au biomagnétisme.
                @else
                    Open Major Chakra Vortexes to Release the Emotional Stress
                    Behind Physical Imbalance, or Optimize Your Bio-Terrain by
                    Balancing pH Radicals Through Biomagnetism.
                @endif
            </p>
        </section>

        <section class="mb-5">
            <h4 class="section-eyebrow mb-4">
                {{ $l === 'es' ? 'Módulos Principales' : ($l === 'fr' ? 'Modules Principaux' : 'Core Modules') }}
            </h4>
            <div class="row modern-row-gap">
                <div class="col-12 col-md-6">
                    <a href="{{ route('app.bodyscan.info') }}"
                        class="module-card p-4 p-lg-5 h-100 d-flex flex-column modern-gap-4 text-decoration-none module-card-trigger"
                        data-drawer-title="{{ $l === 'es' ? 'Escáner Corporal' : ($l === 'fr' ? 'Scanner Corporel' : 'Body Scan') }}">
                        <div class="icon-tile icon-tile-body-scan">
                            <svg class="module-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="pill pill-body-scan mb-3"><span class="pill-dot"></span>{{ $l === 'es' ? 'Escáner Corporal' : ($l === 'fr' ? 'Scanner Corporel' : 'Body Scan') }}</span>
                            <h5 class="fw-bold text-dark mb-2">{{ $l === 'es' ? 'Análisis Corporal Completo' : ($l === 'fr' ? 'Analyse Corporelle Complète' : 'Full Body Analysis') }}</h5>
                            <p class="text-secondary mb-0">
                                @if($l === 'es')
                                    Detecta desequilibrios energéticos en todos los sistemas de órganos y campos de tejidos.
                                @elseif($l === 'fr')
                                    Détectez les déséquilibres énergétiques dans tous les systèmes d'organes et les champs tissulaires.
                                @else
                                    Detect energy imbalances across all organ systems and tissue fields.
                                @endif
                            </p>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-6">
                    <a href="{{ route('app.chakrascan.info') }}"
                        class="module-card p-4 p-lg-5 h-100 d-flex flex-column modern-gap-4 text-decoration-none module-card-trigger"
                        data-drawer-title="{{ $l === 'es' ? 'Escáner de Chakras' : ($l === 'fr' ? 'Scanner de Chakras' : 'Chakra Scan') }}">
                        <div class="icon-tile icon-tile-chakra-scan">
                            <svg class="module-card-icon" fill="none" stroke="currentColor" viewBox="0 0 100 100" aria-hidden="true" stroke-width="1.2">
                                <circle cx="50" cy="50" r="32"/><circle cx="50" cy="50" r="16"/><circle cx="66" cy="50" r="16"/><circle cx="58" cy="36.1" r="16"/><circle cx="42" cy="36.1" r="16"/><circle cx="34" cy="50" r="16"/><circle cx="42" cy="63.9" r="16"/><circle cx="58" cy="63.9" r="16"/>
                            </svg>
                        </div>
                        <div>
                            <span class="pill pill-chakra-scan mb-3"><span class="pill-dot"></span>{{ $l === 'es' ? 'Escáner de Chakras' : ($l === 'fr' ? 'Scanner de Chakras' : 'Chakra Scan') }}</span>
                            <h5 class="fw-bold text-dark mb-2">{{ $l === 'es' ? 'Mapa de Centros de Energía' : ($l === 'fr' ? 'Carte des Centres Énergétiques' : 'Energy Center Map') }}</h5>
                            <p class="text-secondary mb-0">
                                @if($l === 'es')
                                    Visualiza y evalúa la alineación vibratoria en todos los centros de energía.
                                @elseif($l === 'fr')
                                    Visualisez et évaluez l'alignement vibratoire dans tous les centres d'énergie.
                                @else
                                    Visualize and assess vibrational alignment across all energy centers.
                                @endif
                            </p>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-6">
                    <a href="{{ route('app.data_cache.info') }}"
                        class="module-card p-4 p-lg-5 h-100 d-flex flex-column modern-gap-4 text-decoration-none module-card-trigger"
                        data-drawer-title="{{ $l === 'es' ? 'Caché de Datos' : ($l === 'fr' ? 'Cache de Données' : 'Data Cache') }}">
                        <div class="icon-tile icon-tile-data-cache">
                            <svg class="module-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="pill pill-data-cache mb-3"><span class="pill-dot"></span>{{ $l === 'es' ? 'Caché de Datos' : ($l === 'fr' ? 'Cache de Données' : 'Data Cache') }}</span>
                            <h5 class="fw-bold text-dark mb-2">{{ $l === 'es' ? 'Registros de Sesiones' : ($l === 'fr' ? 'Registres de Sessions' : 'Session Records') }}</h5>
                            <p class="text-secondary mb-0">
                                @if($l === 'es')
                                    Accede al historial de tratamientos, registros de sesiones y progreso rastreado a lo largo del tiempo.
                                @elseif($l === 'fr')
                                    Accédez à l'historique des traitements, aux journaux de séances et aux progrès suivis au fil du temps.
                                @else
                                    Access treatment history, session logs, and tracked progress over time.
                                @endif
                            </p>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-6">
                    <a href="{{ route('app.bioconnect.info') }}"
                        class="module-card p-4 p-lg-5 h-100 d-flex flex-column modern-gap-4 text-decoration-none module-card-trigger"
                        data-drawer-title="{{ $l === 'es' ? 'El Navegador' : ($l === 'fr' ? 'Le Navigateur' : 'The Navigator') }}">
                        <div class="icon-tile icon-tile-bio-connect">
                            <svg class="module-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="pill pill-bio-connect mb-3"><span class="pill-dot"></span>{{ $l === 'es' ? 'Maestría Guiada' : ($l === 'fr' ? 'Maîtrise Guidée' : 'Guided Mastery') }}</span>
                            <h5 class="fw-bold text-dark mb-2">{{ $l === 'es' ? 'El Navegador' : ($l === 'fr' ? 'Le Navigateur' : 'The Navigator') }}</h5>
                            <p class="text-secondary mb-0">
                                @if($l === 'es')
                                    Navega tus sesiones con escaneo guiado paso a paso, instrucciones integradas de «Cómo hacerlo» y soporte de idiomas globales.
                                @elseif($l === 'fr')
                                    Naviguez dans vos sessions avec une analyse guidée étape par étape, des instructions intégrées «Comment faire» et une prise en charge multilingue.
                                @else
                                    Navigate your sessions with step-by-step guided scanning, integrated 'How-to' instructions, and global language support.
                                @endif
                            </p>
                        </div>
                    </a>
                </div>
                <div class="col-12">
                    <a href="{{ route('app.dr_goiz_pairs') }}" class="free-protocol-banner text-decoration-none">
                        <div class="free-protocol-banner__glow"></div>
                        <div class="free-protocol-banner__badge">{{ $l === 'fr' ? 'GRATUIT' : ($l === 'es' ? 'GRATIS' : 'FREE') }}</div>
                        <div class="free-protocol-banner__body">
                            <div class="free-protocol-banner__icon" aria-hidden="true">
                                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"/>
                                </svg>
                            </div>
                            <h4 class="free-protocol-banner__title">
                                {{ $l === 'es' ? 'Pares de Protocolo Gratuitos' : ($l === 'fr' ? 'Paires de Protocoles Gratuites' : 'Free Protocol Pairs') }}
                            </h4>
                            <p class="free-protocol-banner__desc text-white">
                                @if($l === 'es')
                                    Explora 267 Pares de Protocolo Originales de Biomagnetismo para Referencia Terapéutica — Sin Suscripción Necesaria.
                                @elseif($l === 'fr')
                                    Parcourez 267 Paires de Protocoles Originaux de Biomagnétisme pour Référence Thérapeutique — Sans Abonnement Requis.
                                @else
                                    Browse 267 Original Biomagnetism Protocol Pairs for Therapeutic Reference — No Subscription Needed.
                                @endif
                            </p>
                            <span class="free-protocol-banner__cta">
                                {{ $l === 'es' ? 'Explorar Pares de Protocolo Gratuitos' : ($l === 'fr' ? 'Explorer les Paires de Protocoles Gratuites' : 'Explore Free Protocol Pairs') }}
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <section id="course-banner" class="mb-5">
            <style>
                #course-banner .cb-box{background:linear-gradient(135deg,#0f766e,#0d9488);border-radius:18px;padding:26px 28px;color:#fff;cursor:pointer;display:flex;flex-wrap:wrap;gap:16px;align-items:center;justify-content:space-between}
                #course-banner .cb-box h3{font-family:Georgia,serif;margin:0 0 6px;font-size:1.4rem}
                #course-banner .cb-box p{margin:0;opacity:.9;font-size:.92rem}
                #course-banner .cb-btn{background:#fff;color:#0f766e;font-weight:700;border:none;border-radius:999px;padding:11px 22px;font-size:.9rem;cursor:pointer;white-space:nowrap}
                #cbModalBackdrop{display:none;position:fixed;inset:0;background:rgba(15,23,42,.55);z-index:2000;align-items:center;justify-content:center;padding:20px}
                #cbModal{background:#fff;border-radius:16px;max-width:440px;width:100%;padding:28px;text-align:center}
                #cbModal h3{font-family:Georgia,serif;color:#0f766e;margin-top:0}
                #cbModal ul{text-align:left;color:#475569;font-size:.9rem;line-height:1.8;max-width:300px;margin:16px auto}
                #cbModal .cb-price{font-size:2rem;font-family:Georgia,serif;color:#0f766e;margin:10px 0}
                #cbModal .cb-close{position:absolute;top:14px;right:18px;background:none;border:none;font-size:1.3rem;cursor:pointer;color:#94a3b8}
            </style>
            <div class="cb-box" id="cbOpen">
                <div>
                    <h3>New: Biomagnetism Certification Course</h3>
                    <p>9 modules, guided lessons and a certificate — available as a one-time purchase, separate from your app subscription.</p>
                </div>
                <a href="{{ route('course.checkout') }}" class="cb-btn" onclick="event.stopPropagation()">Purchase Now</a>
            </div>
        </section>

        <div id="cbModalBackdrop">
            <div id="cbModal" style="position:relative">
                <button type="button" class="cb-close" onclick="document.getElementById('cbModalBackdrop').style.display='none'">&times;</button>
                <h3>Biomagnetism Certification Course</h3>
                <div class="cb-price">$197 <span style="font-size:.9rem;color:#94a3b8;">one-time</span></div>
                <ul>
                    <li>&#10003; All 9 modules &amp; completion certificate</li>
                    <li>&#10003; Body Scan &amp; Chakra Scan tool access</li>
                    <li>&#10003; 1 year of full access</li>
                </ul>
                <a href="{{ route('course.checkout') }}" class="cb-btn" style="display:block;background:#0d9488;color:#fff;">Purchase Now</a>
            </div>
        </div>
        <script>
            document.getElementById('cbOpen').addEventListener('click', function(){ document.getElementById('cbModalBackdrop').style.display='flex'; });
        </script>

        <section id="pricing" class="mb-5">
            <h4 class="section-eyebrow mb-4">
                {{ $l === 'es' ? 'Planes y Precios' : ($l === 'fr' ? 'Plans et Tarifs' : 'Plans & Pricing') }}
            </h4>
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
                <span>{{ $l === 'es' ? 'Cargando…' : ($l === 'fr' ? 'Chargement…' : 'Loading…') }}</span>
            </div>
            <div class="info-drawer__content" id="infoDrawerContent"></div>
        </div>
    </aside>

    {{-- Contact Us Section --}}
    <section id="contact" class="dashboard-contact">
        <div class="dashboard-contact__inner">
            <h2 class="dashboard-contact__title">
                {{ $l === 'es' ? 'Contáctenos' : ($l === 'fr' ? 'Contactez-nous' : 'Contact Us') }}
            </h2>
            <p class="dashboard-contact__subtitle">
                @if($l === 'es')
                    ¿Tienes una pregunta o necesitas ayuda? Nos encantaría saber de ti.
                @elseif($l === 'fr')
                    Vous avez une question ou besoin d'aide ? Nous serions ravis de vous entendre.
                @else
                    Have a question or need help? We'd love to hear from you.
                @endif
            </p>
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
                        <span>{{ $l === 'es' ? 'Lun – Vie, 9 AM – 5 PM' : ($l === 'fr' ? 'Lun – Ven, 9h – 17h' : 'Mon – Fri, 9 AM – 5 PM') }}</span>
                    </div>
                    <p class="dashboard-contact__blurb">
                        @if($l === 'es')
                            Ya seas terapeuta, practicante o simplemente curioso sobre el biomagnetismo — escríbenos y te responderemos lo antes posible.
                        @elseif($l === 'fr')
                            Que vous soyez praticien, thérapeute ou simplement curieux à propos du biomagnétisme — contactez-nous et nous vous répondrons dès que possible.
                        @else
                            Whether you're a practitioner, therapist, or just curious about biomagnetism — reach out and we'll get back to you as soon as possible.
                        @endif
                    </p>
                </div>
                @if(session('contact.success'))
                    <div style="background:#f0fdfa;border:1.5px solid #14b8a6;border-radius:0.5rem;padding:1.25rem 1.5rem;display:flex;align-items:center;gap:0.75rem;">
                        <svg width="22" height="22" fill="none" stroke="#14b8a6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span style="color:#0f766e;font-weight:600;">{{ session('contact.success') }}</span>
                    </div>
                @else
                    @php
                        $contactRoute = $l === 'es' ? 'contact.store.es' : ($l === 'fr' ? 'contact.store.fr' : 'contact.store');
                    @endphp
                    <form class="dashboard-contact__form" action="{{ route($contactRoute) }}" method="POST">
                        @csrf
                        <input type="text" name="name" placeholder="{{ $l === 'es' ? 'Tu Nombre' : ($l === 'fr' ? 'Votre Nom' : 'Your Name') }}" class="dashboard-contact__input @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<span style="color:#dc2626;font-size:0.8rem;margin-top:-0.4rem;display:block;">{{ $message }}</span>@enderror
                        <input type="email" name="email" placeholder="{{ $l === 'es' ? 'Tu Correo Electrónico' : ($l === 'fr' ? 'Votre E-mail' : 'Your Email') }}" class="dashboard-contact__input @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<span style="color:#dc2626;font-size:0.8rem;margin-top:-0.4rem;display:block;">{{ $message }}</span>@enderror
                        <input type="text" name="subject" placeholder="{{ $l === 'es' ? 'Asunto' : ($l === 'fr' ? 'Objet' : 'Subject') }}" class="dashboard-contact__input" value="{{ old('subject') }}">
                        <textarea name="message" placeholder="{{ $l === 'es' ? 'Tu Mensaje' : ($l === 'fr' ? 'Votre Message' : 'Your Message') }}" class="dashboard-contact__textarea @error('message') is-invalid @enderror" rows="5" required>{{ old('message') }}</textarea>
                        @error('message')<span style="color:#dc2626;font-size:0.8rem;margin-top:-0.4rem;display:block;">{{ $message }}</span>@enderror
                        <button type="submit" class="dashboard-contact__submit">
                            {{ $l === 'es' ? 'Enviar Mensaje' : ($l === 'fr' ? 'Envoyer le Message' : 'Send Message') }}
                        </button>
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
