<header class="modern-brand-bar px-4 px-md-5" style="padding-top:0.75rem;padding-bottom:0.75rem;">
    <div class="d-flex align-items-center justify-content-between gap-3">

        <a href="{{ route('app.home') }}" class="brand-link text-decoration-none flex-shrink-0">
            <div class="d-flex align-items-center gap-2">
                <img src="/images/iconimages/load.png" alt="{{ config('app.name') }}" class="brand-logo-img">
                <span class="brand-title d-none d-sm-inline">{{ config('app.name') }}</span>
            </div>
        </a>

        <div class="d-flex align-items-center gap-2">
            {{-- Pills visible on md+ --}}
            <a href="#pricing" class="brand-nav__pill d-none d-md-inline-flex">Pricing</a>
            <a href="#contact" class="brand-nav__pill d-none d-md-inline-flex">Contact Us</a>

            {{-- Disclaimer button --}}
            <button type="button" class="brand-disclaimer-btn" id="disclaimerNavBtn" aria-label="View disclaimer">
                <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <span class="d-none d-md-inline">Disclaimer</span>
            </button>

            {{-- Nav hamburger: guests only on small screens --}}
            @guest
            <button class="brand-nav-toggle d-inline-flex d-md-none" id="brandNavToggle" type="button" aria-label="Toggle navigation" aria-expanded="false" aria-controls="brandNavMobile">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            @endguest

            @auth
                <button class="user-side-menu__trigger" id="userMenuTrigger" type="button" aria-label="Open user menu" aria-controls="userSideMenu">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            @else
                <a href="{{ route('login') }}" class="btn modern-auth-btn">Login</a>
            @endauth
        </div>

    </div>

    {{-- Mobile nav dropdown --}}
    <div class="brand-nav-mobile" id="brandNavMobile" aria-hidden="true">
        <a href="#pricing" class="brand-nav-mobile__link" id="brandNavPricing">Pricing</a>
        <a href="#contact" class="brand-nav-mobile__link" id="brandNavContact">Contact Us</a>
    </div>
</header>

{{-- Disclaimer Modal --}}
<div class="disclaimer-modal-backdrop" id="disclaimerBackdrop" aria-hidden="true"></div>
<div class="disclaimer-modal" id="disclaimerModal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="disclaimerModalTitle">
    <div class="disclaimer-modal__header">
        <div class="d-flex align-items-center gap-2">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" style="color:#f59e0b;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <h2 class="disclaimer-modal__title" id="disclaimerModalTitle">Disclaimer</h2>
        </div>
        <button class="disclaimer-modal__close" id="disclaimerModalClose" type="button" aria-label="Close">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <div class="disclaimer-modal__body">
        <p class="disclaimer-modal__text">
            <strong>Disclaimer:</strong> This app is for self-education and research purposes only. Biomagnetic pairs are intended as biofeedback to support normal body function and are not a treatment, diagnosis, or prescription for any medical or psychological condition. These statements have not been evaluated by the FDA, and this tool is not intended to support or sustain human life or prevent health impairment. Users with existing medical conditions use this platform at their own risk.
        </p>
        <hr class="disclaimer-modal__divider">
        <p class="disclaimer-modal__text">
            <strong>Descargo de responsabilidad:</strong> Esta aplicación es solo para fines de autoeducación e investigación. Los pares biomagnéticos están destinados a ser una bioretroalimentación para apoyar el funcionamiento normal del cuerpo y no son un tratamiento, diagnóstico o prescripción para ninguna condición médica o psicológica. Estas declaraciones no han sido evaluadas por la FDA, y esta herramienta no está destinada a apoyar o sostener la vida humana ni a prevenir el deterioro de la salud. Los usuarios con condiciones médicas existentes utilizan esta plataforma bajo su propio riesgo.
        </p>
    </div>
</div>

<script>
(function () {
    var toggle = document.getElementById('brandNavToggle');
    var menu   = document.getElementById('brandNavMobile');
    if (toggle && menu) {
        toggle.addEventListener('click', function () {
            var open = menu.classList.toggle('brand-nav-mobile--open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            menu.setAttribute('aria-hidden', open ? 'false' : 'true');
        });
        menu.querySelectorAll('.brand-nav-mobile__link').forEach(function (link) {
            link.addEventListener('click', function () {
                menu.classList.remove('brand-nav-mobile--open');
                toggle.setAttribute('aria-expanded', 'false');
                menu.setAttribute('aria-hidden', 'true');
            });
        });
    }

    var btn      = document.getElementById('disclaimerNavBtn');
    var modal    = document.getElementById('disclaimerModal');
    var backdrop = document.getElementById('disclaimerBackdrop');
    var closeBtn = document.getElementById('disclaimerModalClose');
    if (!btn || !modal) return;

    function open() {
        modal.setAttribute('aria-hidden', 'false');
        backdrop.setAttribute('aria-hidden', 'false');
        document.body.classList.add('disclaimer-modal-open');
        closeBtn.focus();
    }
    function close() {
        modal.setAttribute('aria-hidden', 'true');
        backdrop.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('disclaimer-modal-open');
        btn.focus();
    }

    btn.addEventListener('click', open);
    closeBtn.addEventListener('click', close);
    backdrop.addEventListener('click', close);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') close();
    });
}());
</script>
