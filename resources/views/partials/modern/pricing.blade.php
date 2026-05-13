@php
    $monthlyPlan = \App\Models\Plan::where('category', 'monthly')->orderBy('price')->first();
    $yearlyPlan  = \App\Models\Plan::where('category', 'yearly')->orderBy('price')->first();

    // Fallback prices if plans not in DB yet
    $monthlyPrice = $monthlyPlan ? number_format($monthlyPlan->price, 2) : '4.99';
    $yearlyPrice  = $yearlyPlan  ? number_format($yearlyPlan->price, 2)  : '44.99';
@endphp

<div class="pricing-toggle-wrap">

    {{-- Toggle --}}
    <div class="pricing-toggle" role="group" aria-label="Billing period">
        <span class="pricing-toggle__label" id="ptLabelMonthly">Monthly</span>
        <button class="pricing-toggle__switch" id="pricingToggle"
                role="switch" aria-checked="false" aria-label="Switch to yearly billing">
            <span class="pricing-toggle__thumb"></span>
        </button>
        <span class="pricing-toggle__label" id="ptLabelYearly">Yearly</span>
    </div>

    {{-- Monthly card --}}
    <div class="pricing-card-single" id="ptCardMonthly">
        <div class="pricing-card-single__name">Monthly</div>
        <div class="pricing-card-single__price">
            <span class="pricing-card-single__currency">$</span>
            <span class="pricing-card-single__amount">{{ $monthlyPrice }}</span>
        </div>
        <p class="pricing-card-single__period">Per Month</p>
        @if($monthlyPlan && !empty($monthlyPlan->description))
            <p class="pricing-card-single__desc">{{ $monthlyPlan->description }}</p>
        @endif
        <div class="pricing-card-single__footer">
            @auth
                <form method="POST" action="{{ $monthlyPlan ? route('app.plans.subscribe', ['id' => $monthlyPlan->id]) : '#' }}">
                    @csrf
                    <button type="submit" class="pricing-card-single__btn">Get Started</button>
                </form>
            @else
                <a href="{{ route('register', ['plan_id' => $monthlyPlan ? $monthlyPlan->id : '']) }}" class="pricing-card-single__btn">Get Started</a>
            @endauth
        </div>
    </div>

    {{-- Yearly card --}}
    <div class="pricing-card-single pricing-card-single--hidden" id="ptCardYearly">
        <div class="pricing-card-single__badge">Best Value</div>
        <div class="pricing-card-single__name">Yearly</div>
        <div class="pricing-card-single__price">
            <span class="pricing-card-single__currency">$</span>
            <span class="pricing-card-single__amount">{{ $yearlyPrice }}</span>
        </div>
        <p class="pricing-card-single__period">Per Year</p>
        @if($yearlyPlan && !empty($yearlyPlan->description))
            <p class="pricing-card-single__desc">{{ $yearlyPlan->description }}</p>
        @endif
        <div class="pricing-card-single__footer">
            @auth
                <form method="POST" action="{{ $yearlyPlan ? route('app.plans.subscribe', ['id' => $yearlyPlan->id]) : '#' }}">
                    @csrf
                    <button type="submit" class="pricing-card-single__btn">Get Started</button>
                </form>
            @else
                <a href="{{ route('register', ['plan_id' => $yearlyPlan ? $yearlyPlan->id : '']) }}" class="pricing-card-single__btn">Get Started</a>
            @endauth
        </div>
    </div>

</div>

<script>
(function () {
    var toggle   = document.getElementById('pricingToggle');
    var cardM    = document.getElementById('ptCardMonthly');
    var cardY    = document.getElementById('ptCardYearly');
    var labelM   = document.getElementById('ptLabelMonthly');
    var labelY   = document.getElementById('ptLabelYearly');
    if (!toggle) return;

    function setYearly(yearly) {
        toggle.setAttribute('aria-checked', yearly ? 'true' : 'false');
        cardM.classList.toggle('pricing-card-single--hidden', yearly);
        cardY.classList.toggle('pricing-card-single--hidden', !yearly);
        labelM.classList.toggle('pricing-toggle__label--active', !yearly);
        labelY.classList.toggle('pricing-toggle__label--active', yearly);
    }

    setYearly(false); // start on monthly

    toggle.addEventListener('click', function () {
        setYearly(toggle.getAttribute('aria-checked') !== 'true');
    });
}());
</script>
