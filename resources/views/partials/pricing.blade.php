<h1 class="text-center pricing-header">Plans and Pricing</h1>
<div class="row text-center price-body">
    @foreach(\App\Models\Plan::all() as $plan)
        <div class="columns">
            <ul class="price">
                <li class="header" style="background-color: {{ $plan->category == 'monthly' ? '#d66c87' : '#f28857' }}">{{ $plan->name }}</li>
                <li class="grey" style="color: {{ $plan->category == 'monthly' ? '#47b9e9' : '#e4a33d' }}">${{ number_format($plan->price, 2) }} </li>
                <li class="grey"></li>
                <li class="grey">
                    @if (Route::is('affilicate.pricing') || empty(Auth::user()))
                        <a href="{{ route('register') }}" class="button try-it">Try it</a>
                    @endif
                    @if (Route::is('app.pricing') && !empty(Auth::user()))
                        <form name="form_{{$plan->id}}" method="POST" action="{{ route('app.plans.subscribe', ['id' => $plan->id]) }}">
                            @csrf
                            <button type="submit" class="button buy-now">Buy Now</button>
                        </form>
                    @endif
                </li>
            </ul>
        </div>
    @endforeach
</div>