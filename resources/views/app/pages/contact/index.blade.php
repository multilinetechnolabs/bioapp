@extends('layouts.modern')

@section('page-title', $seoData['title'] ?? 'Contact')

@php
    $activeNav  = 'home';
    $useAppShell = false;
@endphp

@section('content')
    <main class="modern-main-content">
        <section id="contact" class="dashboard-contact">
            <div class="dashboard-contact__inner">
                <h2 class="dashboard-contact__title">
                    @if(($locale ?? 'en') === 'es')
                        Contáctenos
                    @elseif(($locale ?? 'en') === 'fr')
                        Contactez-nous
                    @else
                        Contact Us
                    @endif
                </h2>
                <p class="dashboard-contact__subtitle">
                    @if(($locale ?? 'en') === 'es')
                        ¿Tienes una pregunta o necesitas ayuda? Nos encantaría saber de ti.
                    @elseif(($locale ?? 'en') === 'fr')
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
                            <span>{{ ($locale ?? 'en') === 'es' ? 'Lun – Vie, 9 AM – 5 PM' : (($locale ?? 'en') === 'fr' ? 'Lun – Ven, 9h – 17h' : 'Mon – Fri, 9 AM – 5 PM') }}</span>
                        </div>
                        <p class="dashboard-contact__blurb">
                            @if(($locale ?? 'en') === 'es')
                                Ya seas terapeuta, practicante o simplemente curioso sobre el biomagnetismo — escríbenos y te responderemos lo antes posible.
                            @elseif(($locale ?? 'en') === 'fr')
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
                            <button type="submit" class="dashboard-contact__submit">
                                {{ $locale === 'es' ? 'Enviar Mensaje' : ($locale === 'fr' ? 'Envoyer le Message' : 'Send Message') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection
