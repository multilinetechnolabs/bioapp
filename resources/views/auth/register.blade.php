@extends('layouts.modern')

@section('page-title', 'Register / Registro')

@php
    $hideBottomNav = true;
@endphp

@section('content')
    <main class="modern-main-content">
        <div class="modern-auth-wrap">
            <section class="modern-auth-card">
                <div class="text-center mb-4">
                    <img src="/images/iconimages/load.png" alt="{{ env('APP_TITLE') }}" class="modern-auth-logo">
                    <h1 class="hero-heading modern-auth-title">Create <span class="italic-wellness">Account</span> / Crear <span class="italic-wellness">Cuenta</span></h1>
                    @php
                        $selectedPlan = request('plan_id') ? \App\Models\Plan::find(request('plan_id')) : null;
                    @endphp
                    @if($selectedPlan)
                        <div class="modern-info-highlight mb-0" style="background:#f0fdfa;border:1.5px solid #14b8a6;border-radius:0.5rem;padding:0.65rem 1rem;">
                            <strong style="color:#0f766e;">Selected Plan / Plan seleccionado: {{ ucfirst($selectedPlan->category) }} &mdash; ${{ number_format($selectedPlan->price, 2) }}</strong><br>
                            <small style="color:#374151;">Payment is required after email verification to activate your account. / Se requiere pago después de la verificación de correo para activar su cuenta.</small>
                        </div>
                    @else
                        <p class="text-muted mb-0" style="font-size:0.85rem;">After verifying your email, select a plan to activate your account. / Después de verificar su correo, seleccione un plan para activar su cuenta. <a href="{{ route('app.pricing') }}">View plans / Ver planes</a></p>
                    @endif
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

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ request('plan_id', old('plan_id')) }}">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="type" class="modern-auth-label">User Type / Tipo de usuario</label>
                            <select class="form-control modern-auth-input @error('type') is-invalid @enderror" id="type" name="type" required>
                                @foreach (\Spatie\Permission\Models\Role::orderBy('name', 'desc')->whereIn('name', ['student/estudiante', 'practitioner/practicante', 'guest/invitado'])->get() as $role)
                                    @if ($role->name != 'administrator')
                                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('type')
                                <span class="invalid-feedback d-block"><strong>{{ $errors->first('type') }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label for="name" class="modern-auth-label">Name / Nombre</label>
                            <input type="text" class="form-control modern-auth-input @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" name="name" id="name" required>
                            @error('name')
                                <span class="invalid-feedback d-block"><strong>{{ $errors->first('name') }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label for="username" class="modern-auth-label">Username / Nombre de usuario</label>
                            <input type="text" class="form-control modern-auth-input @error('username') is-invalid @enderror"
                                value="{{ old('username') }}" name="username" id="username" required>
                            @error('username')
                                <span class="invalid-feedback d-block"><strong>{{ $errors->first('username') }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="email" class="modern-auth-label">Email / Correo electrónico</label>
                            <input type="email" class="form-control modern-auth-input @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" name="email" id="email" required>
                            @error('email')
                                <span class="invalid-feedback d-block"><strong>{{ $errors->first('email') }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label for="password" class="modern-auth-label">Password / Contraseña</label>
                            <input type="password" class="form-control modern-auth-input @error('password') is-invalid @enderror"
                                name="password" id="password" required>
                            @error('password')
                                <span class="invalid-feedback d-block"><strong>{{ $errors->first('password') }}</strong></span>
                            @enderror
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label for="password_confirmation" class="modern-auth-label">Confirm Password / Confirmar contraseña</label>
                            <input type="password"
                                class="form-control modern-auth-input @error('password_confirmation') is-invalid @enderror"
                                name="password_confirmation" id="password_confirmation" required>
                            @error('password_confirmation')
                                <span class="invalid-feedback d-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn modern-auth-btn-main btn-block">{{ __('Sign Up') }} / Registrarse</button>
                </form>

                <p class="text-center mt-3 mb-0">
                    Already have an existing account? / ¿Ya tiene una cuenta?
                    <a href="{{ route('login') }}" class="btn btn-link p-0 align-baseline">Sign In here / Inicie sesión aquí</a>
                </p>
            </section>
        </div>
    </main>
@endsection
