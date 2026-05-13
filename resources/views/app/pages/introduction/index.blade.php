@extends('layouts.modern')

@section('page-title', 'Introduction')

@php
    $activeNav = 'home';
    $useAppShell = false;
@endphp

@section('content')
    <main class="modern-main-content">
        <div class="container py-5">
            <h1 class="text-center" style="margin-bottom:35px">{{ __('Ion Positive and Negative Changes') }}</h1>
            <h3 style="margin-bottom:35px">{{ __('Essssssngage to discover transforming energy to alignment, channeling cell activity with magnet fields to wellness.') }}</h3>
            <h5 style="margin-bottom:35px">{{ __('A broad approach to positive and negative electron fields, working to eradicate diseases. (toxins, endotoxins, radicals, dysfunctions, emotional crisis, embedded negative codes, negative memory and PH imbalance.)') }}</h5>
            <h3 class="text-center">{{ __('A journey to health - Biomagnetism Therapy') }}</h3>
            <br/><br/>
            <div class="row">
                <div class="col-md-3 col-6 text-center mb-4">
                    <a href="{{ url('/bodyscan/info') }}" target="_blank">
                        <img src="{{ asset('/images/iconimages/bodyscan312.png') }}" title="{{ __('Body Scan') }}" alt="{{ env('APP_TITLE') }}" style="width:216px;height:212px;margin-bottom:15px;">
                    </a>
                    <a href="{{ url('/bodyscan/info') }}" target="_blank">
                        <button type="button" class="modern-btn modern-btn--primary">{{ __('Learn More') }}</button>
                    </a>
                </div>
                <div class="col-md-3 col-6 text-center mb-4">
                    <a href="{{ url('/chakrascan/info') }}" target="_blank">
                        <img src="{{ asset('/images/iconimages/chakra312.png') }}" title="{{ __('Chakra Scan') }}" alt="{{ env('APP_TITLE') }}" style="width:216px;height:212px;margin-bottom:15px;">
                    </a>
                    <a href="{{ url('/chakrascan/info') }}" target="_blank">
                        <button type="button" class="modern-btn modern-btn--primary">{{ __('Learn More') }}</button>
                    </a>
                </div>
                <div class="col-md-3 col-6 text-center mb-4">
                    <a href="{{ url('/data_cache/info') }}" target="_blank">
                        <img src="{{ asset('/images/iconimages/datalog312.png') }}" title="{{ __('Data Cache') }}" alt="{{ env('APP_TITLE') }}" style="width:216px;height:212px;margin-bottom:15px;">
                    </a>
                    <a href="{{ url('/data_cache/info') }}" target="_blank">
                        <button type="button" class="modern-btn modern-btn--primary">{{ __('Learn More') }}</button>
                    </a>
                </div>
                <div class="col-md-3 col-6 text-center mb-4">
                    <a href="{{ url('/bioconnect/info') }}" target="_blank">
                        <img src="{{ asset('/images/iconimages/bio312.png') }}" title="{{ __('Bio Connect') }}" alt="{{ env('APP_TITLE') }}" style="width:216px;height:212px;margin-bottom:15px;">
                    </a>
                    <a href="{{ url('/bioconnect/info') }}" target="_blank">
                        <button type="button" class="modern-btn modern-btn--primary">{{ __('Learn More') }}</button>
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection
