{{--
    Shared Contact Us form — used by the dedicated /contact page (app/pages/contact/index.blade.php)
    and the contact section embedded in the main landing page (home.blade.php), so the captcha and
    field markup only exist in one place. Pass in $locale ('en'|'es'|'fr'); defaults to 'en'.

    IDs are fixed (not per-instance) because only one copy of this form is ever rendered on a page.
--}}
@php
    $locale = $locale ?? 'en';
    $contactRoute = $locale === 'es' ? 'contact.store.es' : ($locale === 'fr' ? 'contact.store.fr' : 'contact.store');
@endphp

<form class="dashboard-contact__form" action="{{ route($contactRoute) }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="{{ $locale === 'es' ? 'Tu Nombre' : ($locale === 'fr' ? 'Votre Nom' : 'Your Name') }}" class="dashboard-contact__input @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
    @error('name')<span style="color:#dc2626;font-size:0.8rem;margin-top:-0.4rem;display:block;">{{ $message }}</span>@enderror
    <input type="email" name="email" placeholder="{{ $locale === 'es' ? 'Tu Correo Electrónico' : ($locale === 'fr' ? 'Votre E-mail' : 'Your Email') }}" class="dashboard-contact__input @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
    @error('email')<span style="color:#dc2626;font-size:0.8rem;margin-top:-0.4rem;display:block;">{{ $message }}</span>@enderror
    <input type="text" name="subject" placeholder="{{ $locale === 'es' ? 'Asunto' : ($locale === 'fr' ? 'Objet' : 'Subject') }}" class="dashboard-contact__input" value="{{ old('subject') }}">
    <textarea name="message" placeholder="{{ $locale === 'es' ? 'Tu Mensaje' : ($locale === 'fr' ? 'Votre Message' : 'Your Message') }}" class="dashboard-contact__textarea @error('message') is-invalid @enderror" rows="5" required>{{ old('message') }}</textarea>
    @error('message')<span style="color:#dc2626;font-size:0.8rem;margin-top:-0.4rem;display:block;">{{ $message }}</span>@enderror

    <div style="margin-bottom:0.85rem;">
        <label for="contactCaptcha" style="display:block;color:#cbd5e1;font-size:0.8rem;margin-bottom:0.4rem;">
            {{ $locale === 'es' ? 'Código de seguridad' : ($locale === 'fr' ? 'Code de sécurité' : 'Security Code') }}
        </label>
        <div style="display:flex;align-items:center;flex-wrap:wrap;gap:0.6rem;margin-bottom:0.5rem;">
            <img id="contactCaptchaImage" src="{{ route('captcha.image', [], false) }}?{{ uniqid() }}"
                alt="{{ $locale === 'es' ? 'Código de seguridad' : ($locale === 'fr' ? 'Code de sécurité' : 'Security code') }}"
                width="170" height="56" style="border:1.5px solid #334155;border-radius:0.5rem;max-width:100%;height:auto;">
            <button type="button" id="contactCaptchaRefresh" style="background:none;border:none;color:#14b8a6;font-size:0.8rem;cursor:pointer;padding:0;">
                &#8635; {{ $locale === 'es' ? 'Nuevo código' : ($locale === 'fr' ? 'Nouveau code' : 'New code') }}
            </button>
        </div>
        <input type="text" name="captcha" id="contactCaptcha" autocomplete="off" autocapitalize="characters" spellcheck="false" maxlength="10" required
            placeholder="{{ $locale === 'es' ? 'Escriba el código de arriba' : ($locale === 'fr' ? 'Tapez le code ci-dessus' : 'Type the code above') }}"
            class="dashboard-contact__input @error('captcha') is-invalid @enderror">
        @error('captcha')<span style="color:#dc2626;font-size:0.8rem;margin-top:-0.4rem;display:block;">{{ $message }}</span>@enderror
    </div>

    <button type="submit" class="dashboard-contact__submit">
        {{ $locale === 'es' ? 'Enviar Mensaje' : ($locale === 'fr' ? 'Envoyer le Message' : 'Send Message') }}
    </button>
</form>

@push('scripts')
    <script>
        (function () {
            var image = document.getElementById('contactCaptchaImage');
            var input = document.getElementById('contactCaptcha');
            var refresh = document.getElementById('contactCaptchaRefresh');
            if (!image || !input || !refresh) return;

            refresh.addEventListener('click', function () {
                image.src = '{{ route('captcha.image', [], false) }}?' + Date.now();
                input.value = '';
                input.focus();
            });
        })();
    </script>
@endpush
