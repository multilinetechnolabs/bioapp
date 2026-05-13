@extends('layouts.modern')

@section('page-title', 'Forgot Password')

@php
    $hideBottomNav = true;
@endphp

@section('content')
    <main class="modern-main-content">
        <div class="modern-auth-wrap">
            <section class="modern-auth-card">
                <div class="text-center mb-4">
                    <img src="/images/iconimages/load.png" alt="{{ env('APP_TITLE') }}" class="modern-auth-logo">
                    <h1 class="hero-heading modern-auth-title">Forgot <span class="italic-wellness">Password</span></h1>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="username" class="modern-auth-label">Username</label>
                        <input id="username" type="text" class="form-control modern-auth-input @error('username') is-invalid @enderror"
                            name="username" value="{{ old('username') }}" required>
                        @error('username')
                            <span class="invalid-feedback d-block"><strong>{{ $errors->first('username') }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="modern-auth-label">Email</label>
                        <input id="email" type="email" class="form-control modern-auth-input @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback d-block"><strong>{{ $errors->first('email') }}</strong></span>
                        @enderror
                    </div>

                    <button type="submit" class="btn modern-auth-btn-main btn-block">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </form>
            </section>
        </div>
    </main>
@endsection
