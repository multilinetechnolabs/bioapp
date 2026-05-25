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
                <form class="freemiusCheckoutForm">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $monthlyPlan->id }}">
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
                <form class="freemiusCheckoutForm">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $yearlyPlan->id }}">
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
<script type="text/javascript" src="https://checkout.freemius.com/js/v1/"></script>
<script>
    function initFreemiusCheckout() {
        document.querySelectorAll('.freemiusCheckoutForm').forEach(form => {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                
                try {
                    const formData = new FormData(this);
                    const response = await fetch("{{ route('app.plans.subscribe') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (!data.success) {
                        alert(data.message || 'Something went wrong while preparing the payment.');
                        console.error('Freemius init error:', data);
                        return;
                    }

                    if (!data.product_id || !data.plan_id || !data.public_key) {
                        alert('Payment configuration is incomplete.');
                        console.error('Freemius missing config:', data);
                        return;
                    }

                    const checkoutData = {
                        product_id: data.product_id,
                        plan_id: data.plan_id,
                        public_key: data.public_key,
                        image: data.image,
                        ...(data.sandbox && { sandbox: {
                            token: data.sandbox_token,
                            ctx: data.sandbox_ctx
                        }}),
                    };

                    const handler = new FS.Checkout(checkoutData);

                    handler.open({
                        name: data.purchase_name,
                        licenses: data.licenses,
                        purchaseCompleted: async (response) => {
                            console.log('Freemius purchase completed:', response);
                            try {
                                const responseData = await fetch('/plans/success', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({
                                        transaction_id: data.transaction_id,
                                        status: 'success',
                                        response: response
                                    })

                                });
                                const result = await responseData.json();
                                if (result.success) {
                                    alert(result.message || 'Subscription updated successfully.');
                                } else {
                                    alert(result.message || 'Unable to update payment.');
                                }
                            } catch (error) {
                                console.error('Success update failed:', error);
                            }
                        },
                        cancel: async () => {
                            try {
                                const responseData = await fetch('/plans/failed', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({
                                        transaction_id: data.transaction_id,
                                        status: 'failed'
                                    })
                                });
                                const result = await responseData.json();
                                if (result.success) {
                                    alert(result.message || 'Subscription Failed.');
                                } else {
                                    alert(result.message || 'Unable to update payment.');
                                }
                            } catch (error) {
                                console.error('Failure update failed:', error);
                            }
                        },
                        success: () => {
                            alert('Purchase completed successfully.');
                        }

                    });
                } catch (error) {
                    console.error('Freemius checkout failed:', error);
                    alert('Unable to open the payment window right now. Please try again.');
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFreemiusCheckout);
    } else {
        initFreemiusCheckout();
    }
</script>