@extends('layouts.modern')

@section('page-title', 'Shipping Info')

@php
    $activeNav = 'home';
    $useAppShell = true;
@endphp

@push('head')
    <link href="{{ asset('css/app/products.css') }}" rel="stylesheet">
@endpush

@section('content')
    <main class="modern-main-content">
        <div class="modern-data-cache-wrap">
            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title">{{ __('Shipping Info') }}</h1>
                    <p class="modern-page-subtitle">Información de envío</p>
                </div>
            </header>

            <section class="data-cache-client-page">
                <div class="modern-info-card data-cache-client-panel" style="max-width: 600px; margin: 0 auto; padding: 32px;">
                    <form id="shippingaddressform" action="/products/checkout" method="POST">
                        @csrf
                        <input type="hidden" id="media_id" name="id" value="" />
                        <div class="form-group">
                            <label class="modern-form-label" for="shipping_address">Shipping address</label>
                            <input required type="text" autocomplete="nope" class="form-control modern-data-cache-input w-100" id="shipping_address" name="shipping_address">
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="modern-form-label" for="shipping_zip">Shipping zip</label>
                                    <input type="number" required autocomplete="nope" class="form-control modern-data-cache-input w-100" id="shipping_zip" name="shipping_zip">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="modern-form-label" for="shipping_day_set">Delivery day</label>
                                    <select required name="shipping_day_set" id="shipping_day_set" class="form-control modern-data-cache-select w-100">
                                        <option value="1">1-3 Days</option>
                                        <option value="2">1 Day</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="modern-form-label" for="shipping_rate">Shipping rate</label>
                            <input type="text" required autocomplete="nope" readonly class="form-control modern-data-cache-input w-100" id="shipping_rate" name="shipping_rate" placeholder="Enter zip to calculate...">
                        </div>
                        <div class="text-right">
                            <button type="submit" class="modern-btn modern-btn--primary">
                                Next
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $("input[type='number']#shipping_zip").bind("input", function() {
            if ($(this).val().length >= 5) {
                var formData = $('#shippingaddressform').serializeArray();
                $.ajax({
                    url: "{{ URL::route('app.products.usps') }}",
                    type: "POST",
                    data: formData,
                    success: function(msg) {
                        if (msg != 'usps limit over') {
                            if (msg.Package.Error) {
                                $('#shipping_rate').val('');
                                swal({ title: msg.Package.Error.Description, icon: "error", closeOnClickOutside: false });
                            } else {
                                $('#shipping_rate').val(msg.Package.Postage.Rate);
                            }
                        } else {
                            swal({ title: "USPS limit over. Your product should be less than 70lbs", icon: "error", closeOnClickOutside: false });
                        }
                    }
                });
            } else if ($(this).val().length <= 4) {
                $('#shipping_rate').val('');
            }
        });

        $("#shipping_day_set").change(function() {
            var formData = $('#shippingaddressform').serializeArray();
            $.ajax({
                url: "{{ URL::route('app.products.usps') }}",
                type: "POST",
                data: formData,
                success: function(msg) {
                    if (msg != 'usps limit over') {
                        if (msg.Package.Error) {
                            $('#shipping_rate').val('');
                            swal({ title: msg.Package.Error.Description, icon: "error", closeOnClickOutside: false });
                        } else {
                            $('#shipping_rate').val(msg.Package.Postage.Rate);
                        }
                    } else {
                        swal({ title: "USPS limit over. Your product should be less than 70lbs", icon: "error", closeOnClickOutside: false });
                    }
                }
            });
        });
    </script>
@endpush
