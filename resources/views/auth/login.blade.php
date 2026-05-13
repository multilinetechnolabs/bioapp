@extends('layouts.modern')

@section('page-title', 'Login')

@php
    $hideBottomNav = true;
@endphp

@section('content')
    <main class="modern-main-content">
        <div class="modern-auth-wrap">
            <section class="modern-auth-card">
                <div class="text-center mb-4">
                    <img src="/images/iconimages/load.png" alt="{{ env('APP_TITLE') }}" class="modern-auth-logo">
                    <h1 class="hero-heading modern-auth-title">Sign In / <span class="italic-wellness">Iniciar sesión</span></h1>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                @if (session('message.success'))
                    <div class="alert alert-success">{{ session('message.success') }}</div>
                @endif
                @if (session('message.fail'))
                    <div class="alert alert-danger">{{ session('message.fail') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="username" class="modern-auth-label">Username</label>
                        <input type="text" class="form-control modern-auth-input @error('username') is-invalid @enderror"
                            value="{{ old('username') }}" name="username" id="username" required autofocus>
                        @error('username')
                            <span class="invalid-feedback d-block"><strong>{{ $errors->first('username') }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="modern-auth-label">Password</label>
                        <input type="password" class="form-control modern-auth-input @error('password') is-invalid @enderror"
                            name="password" id="password" required>
                        @error('password')
                            <span class="invalid-feedback d-block"><strong>{{ $errors->first('password') }}</strong></span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="mb-0"><input type="checkbox" name="remember"> Remember Me</label>
                        <a href="{{ route('password.request') }}" class="btn btn-link p-0">Forgot your Password?</a>
                    </div>

                    <button type="submit" class="btn modern-auth-btn-main btn-block">{{ __('Sign In') }}</button>
                </form>

                <p class="text-center mt-3 mb-0">
                    Don't have an account yet?
                    <a href="{{ route('register') }}" class="btn btn-link p-0 align-baseline">Sign Up now</a>
                </p>
            </section>
        </div>
    </main>
@endsection
