@extends('layouts.modern')

@section('page-title', 'Verify Email')

@php
    $hideBottomNav = true;
@endphp

@section('content')
    <main class="modern-main-content">
        <div class="modern-auth-wrap">
            <section class="modern-auth-card">
                <h1 class="hero-heading modern-auth-title text-center mb-3">Verify <span class="italic-wellness">Email</span></h1>

                @if (session('message.success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message.success') }}
                    </div>
                @elseif (session('resent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif
                @if (session('message.fail'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('message.fail') }}
                    </div>
                @endif

                <p class="mb-2">{{ __('Before proceeding, please check your email for a verification link.') }}</p>
                <p class="mb-0">
                    {{ __('If you did not receive the email') }},
                    <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
                </p>
            </section>
        </div>
    </main>
@endsection
