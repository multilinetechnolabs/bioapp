@extends('layouts.modern')

@section('page-title', 'Reset Password')

@php
    $hideBottomNav = true;
@endphp

@section('content')
    <main class="modern-main-content">
        <div class="modern-auth-wrap">
            <section class="modern-auth-card">
                <div class="text-center mb-4">
                    <img src="/images/iconimages/load.png" alt="{{ env('APP_TITLE') }}" class="modern-auth-logo">
                    <h1 class="hero-heading modern-auth-title">Reset <span class="italic-wellness">Password</span></h1>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('password.request') }}">
                    @csrf
                    <input type="hidden" id="token" name="token" value="{{ $token }}">

                    <div class="form-group mb-3">
                        <label for="username" class="modern-auth-label">Username</label>
                        <input id="username" type="text"
                            class="form-control modern-auth-input @error('username') is-invalid @enderror"
                            name="username" value="{{ $username ?? old('username') }}" required autofocus>
                        @error('username')
                            <span class="invalid-feedback d-block"><strong>{{ $errors->first('username') }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="modern-auth-label">Email</label>
                        <input id="email" type="email" class="form-control modern-auth-input @error('email') is-invalid @enderror"
                            name="email" value="{{ $email ?? old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback d-block"><strong>{{ $errors->first('email') }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="modern-auth-label">Password</label>
                        <input id="password" type="password"
                            class="form-control modern-auth-input @error('password') is-invalid @enderror"
                            name="password" required>
                        @error('password')
                            <span class="invalid-feedback d-block"><strong>{{ $errors->first('password') }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="password-confirm" class="modern-auth-label">Confirm Password</label>
                        <input id="password-confirm" type="password" class="form-control modern-auth-input"
                            name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn modern-auth-btn-main btn-block">{{ __('Reset Password') }}</button>
                </form>

                <p class="text-center mt-3 mb-0">
                    <a href="{{ route('login') }}" class="btn btn-link p-0">Return to Sign In</a>
                </p>
            </section>
        </div>
    </main>
@endsection
