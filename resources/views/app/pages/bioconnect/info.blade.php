@extends('layouts.modern')

@section('page-title', 'The Navigator')

@php
    $activeNav = 'connect';
@endphp

@section('content')
    <main class="modern-main-content">
        <section class="mb-4">
            <h3 class="eyebrow mb-2">Introduction</h3>
            <h1 class="hero-heading mb-0">
                The Navigator</span>
            </h1>
        </section>

        <div class="modern-info-card module-intro-card mb-4">
            <p class="cs-card-header mb-3">Bio Connect / Bio Conectar</p>
            <div class="icon-tile icon-tile-bio-connect mb-3">
                <svg class="module-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
            </div>
            <span class="pill pill-bio-connect mb-3"><span class="pill-dot"></span>Guided Mastery</span>
            <h5 class="fw-bold text-dark mb-2">The Navigator</h5>
            <p class="text-secondary mb-0">Navigate your sessions with step-by-step guided scanning, integrated 'How-to' instructions, and global language support.</p>
        </div>

        <div class="row modern-row-gap">

            {{-- Main content --}}
            <div class="col-12 col-lg-12">
                <article class="modern-info-card">

                    <ul class="bc-feature-list">

                        <li class="bc-feature-item">
                            <span class="bc-feature-dot"></span>
                            <div>
                                <p class="bc-feature-title">Guided Scanning &amp; Anatomy Search / <em>Escaneo Guiado y Búsqueda Anatómica</em></p>
                                <p class="bc-feature-en">Choose your path: follow a Guided Scan for a step-by-step directed session, or use the Itemized Anatomy Search to find specific points alphabetically.</p>
                                <p class="bc-feature-es">Elija su camino: siga un Escaneo Guiado para una sesión dirigida paso a paso, o utilice la Búsqueda Anatómica Detallada para encontrar puntos específicos alfabéticamente.</p>
                            </div>
                        </li>

                        <li class="bc-feature-item">
                            <span class="bc-feature-dot"></span>
                            <div>
                                <p class="bc-feature-title">Integrated Instructions / <em>Instrucciones Integradas</em></p>
                                <p class="bc-feature-en">Access dedicated "How-to" guides directly within the scan interface, explaining both Biomagnetism and the specialized Chakra techniques.</p>
                                <p class="bc-feature-es">Acceda a guías dedicadas de "Cómo hacerlo" directamente dentro de la interfaz de escaneo, que explican tanto el biomagnetismo como las técnicas especializadas de chakras.</p>
                            </div>
                        </li>

                        <li class="bc-feature-item">
                            <span class="bc-feature-dot"></span>
                            <div>
                                <p class="bc-feature-title">Global Support / <em>Soporte Global</em></p>
                                <p class="bc-feature-en">Native English and Spanish interface with a translation widget supporting 100+ additional languages.</p>
                                <p class="bc-feature-es">Interfaz nativa en inglés y español con un widget de traducción que admite más de 100 idiomas adicionales.</p>
                            </div>
                        </li>

                    </ul>

                    <div class="modern-info-highlight-block mt-4">
                        <p class="mb-1">
                            <strong>Healing is Better Together</strong> — Bio-Connect: Your space to find friends, share insights, and stay inspired on your path to wellness.
                        </p>
                        <p class="modern-info-highlight-es mb-0">
                            <em>Sanar es Mejor Juntos</em> — Bio-Connect: Tu espacio para encontrar amigos, compartir conocimientos y mantener la inspiración en tu camino hacia el bienestar.
                        </p>
                    </div>

                    <div class="modern-info-highlight-block mt-3 mb-0">
                        <p class="mb-1">
                            🎵 <strong>Harmonic Relaxation</strong> — Built-in healing music for your sessions. Use our library or upload your own to keep your clients in the flow.
                        </p>
                        <p class="modern-info-highlight-es mb-0">
                            🎵 <em>Relajación Armónica</em> — Música de sanación integrada para tus sesiones. Usa nuestra biblioteca o sube la tuya para mantener a tus clientes en sintonía.
                        </p>
                    </div>

                </article>
            </div>
        </div>
    </main>
@endsection

@push('head')
<style>
.bc-feature-list {
    list-style: none;
    padding: 0;
    margin: 0 0 0.5rem 0;
}

.bc-feature-item {
    display: flex;
    gap: 0.85rem;
    align-items: flex-start;
    padding: 1rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.bc-feature-item:last-child { border-bottom: none; }

.bc-feature-dot {
    flex-shrink: 0;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #f97316;
    margin-top: 0.35rem;
}

.bc-feature-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.3rem;
}

.bc-feature-title em { font-style: italic; color: #0f766e; }

.bc-feature-en {
    font-size: 0.855rem;
    color: #374151;
    line-height: 1.6;
    margin-bottom: 0.3rem;
}

.bc-feature-es {
    font-size: 0.84rem;
    color: #16a34a;
    line-height: 1.6;
    margin-bottom: 0;
}
</style>
@endpush
