@extends('layouts.modern')

@section('page-title', 'Chakra Magnets')

@php
    $activeNav = 'home';
    $useAppShell = true;
@endphp

@push('head')
    <link href="{{ asset('css/app/products.css') }}" rel="stylesheet">
@endpush

@section('content')
    <main class="modern-main-content modern-main-content--fluid">
        <div class="row products-body" style="margin: 0;">
            <div class="col-md-12 col-lg-4 product-text" style="padding: 0;">
                <div style="padding: 24px 20px 12px;">
                    <p style="font-size: 0.9rem; line-height: 1.7; color: #374151;">
                        Anew Avenue Biomagnestim Magnets<br>
                        Made with pure italian leather<br>
                        Neodymium magnets<br>
                        Multi layered colored<br>
                        Chakra Color Spectrums Positive<br>
                        back side are<br>
                        Sand color, representing the Negative
                    </p>
                </div>
                <div style="padding: 0 20px 24px;">
                    <p class="modern-form-label" style="font-size: 1rem; margin-bottom: 1rem;">Magnets and Price</p>
                    <form name="checkout" action="{{ route('app.products.checkoutWithShipping') }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 1rem;">
                            @foreach(\App\Models\Product::orderBy('unit_price', 'desc')->where('category', '=', 'chakra')->get() as $index => $product)
                                <label style="display: flex; align-items: center; gap: 0.6rem; padding: 0.45rem 0; cursor: pointer; font-size: 0.9rem; color: #0f172a;">
                                    <input type="radio" name="product_id" value="{{ $product->id }}" {{ $index == 0 ? 'checked' : '' }}>
                                    {{ $product->size }} — {{ $product->name }} — <strong>${{ $product->unit_price }}</strong>
                                </label>
                            @endforeach
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <label class="modern-form-label mb-0">Quantity</label>
                            <input type="number" name="quantity" min="1" value="1" class="modern-data-cache-input" style="width: 70px;">
                        </div>
                        <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 1.25rem;">
                            This price excludes shipping. For shipping charges, we will send you an email.
                        </p>
                        @if (empty(Auth::user()))
                            <p style="font-size: 0.9rem;">You need to login first — <a href="/login" style="color: #14b8a6; font-weight: 600;">click here to login</a></p>
                        @else
                            <button type="submit" class="modern-btn modern-btn--primary">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                {{ __('Checkout') }}
                            </button>
                        @endif
                    </form>
                </div>
            </div>
            <div class="col-md-12 col-lg-8 products-bg">
                <img src="{{ asset('images/products/magnets.png') }}" alt="{{ env('APP_TITLE') }}" style="width: 100%; transform: translateY(25%);">
            </div>
        </div>
    </main>
@endsection
