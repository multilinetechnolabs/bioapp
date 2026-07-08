@extends('layouts.modern')

@section('page-title', 'Course Pricing')

@php
    $useAppShell = true;
@endphp

@push('head')
    <link href="{{ \App\Support\VersionedAsset::url('css/app/course.css') }}" rel="stylesheet">
@endpush

@section('content')
<main class="modern-main-content modern-main-content--fluid">
    <div class="course-shell">
        <div class="course-container" style="max-width:520px;">
            <div class="course-panel" style="text-align:center;">
                <span class="course-demo-flag">Dummy payment &mdash; UI/UX demo only</span>

                @if ($status === 'success' && $alreadyPaid)
                    <div class="course-locked-state__icon" style="background:#d1fae5;border-color:#6ee7b7;color:#065f46;"><i class="fa fa-check" aria-hidden="true"></i></div>
                    <h1 class="course-hero__title" style="font-size:1.4rem;">Payment Successful / Pago exitoso</h1>
                    <p class="course-hero__subtitle" style="margin:0 auto 20px;">You now have 1 year of course access.<br>Ya tienes 1 año de acceso al curso.</p>
                    <a href="{{ route('course.index') }}" class="course-btn course-btn--primary course-btn--block">Continue to Course / Continuar al curso</a>

                @elseif ($status === 'failed')
                    <div class="course-locked-state__icon" style="background:#fee2e2;border-color:#fca5a5;color:#b91c1c;"><i class="fa fa-times" aria-hidden="true"></i></div>
                    <h1 class="course-hero__title" style="font-size:1.4rem;">Payment Failed / Cancelled</h1>
                    <p class="course-hero__subtitle" style="margin:0 auto 20px;">No charge was made. You can try again.<br>No se realizó ningún cargo. Puedes intentarlo de nuevo.</p>
                    <a href="{{ route('course.checkout') }}" class="course-btn course-btn--primary course-btn--block">Try Again / Intentar de nuevo</a>

                @else
                    <h1 class="course-hero__title" style="font-size:1.5rem;">Biomagnetism Certification Course</h1>
                    <p class="course-hero__subtitle" style="margin:0 auto 20px;">Full access to the certification course, plus Body Scan &amp; Chakra Scan tools, for 1 year.</p>
                    <div style="font-size:2.4rem;font-family:var(--course-font-serif);color:var(--course-primary-dark);margin:10px 0;">
                        $197 <span style="font-size:.9rem;color:var(--course-ink-soft);">one-time / pago único</span>
                    </div>
                    <ul style="text-align:left;max-width:320px;margin:18px auto;padding:0;list-style:none;color:var(--course-ink-soft);font-size:.88rem;line-height:1.9;">
                        <li>&#10003; All 9 modules &amp; certificate / Todos los módulos y certificado</li>
                        <li>&#10003; Body Scan &amp; Chakra Scan access / Acceso a Body Scan y Chakra Scan</li>
                        <li>&#10003; 1 year of full access / 1 año de acceso completo</li>
                    </ul>

                    @if ($alreadyPaid)
                        <a href="{{ route('course.index') }}" class="course-btn course-btn--primary course-btn--block">Go to Course / Ir al curso</a>
                    @else
                        <form action="{{ route('course.checkout.pay') }}" method="POST" style="margin-bottom:8px;">
                            @csrf
                            <button type="submit" class="course-btn course-btn--primary course-btn--block">Pay $197 (Simulate Success) / Pagar</button>
                        </form>
                        <form action="{{ route('course.checkout.fail') }}" method="POST">
                            @csrf
                            <button type="submit" class="course-btn course-btn--outline course-btn--block">Cancel Payment (Simulate Failure) / Cancelar</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
