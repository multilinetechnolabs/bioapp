<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Laravel\Passport\Passport;

use App\Models\Activity\Category as ActivityCategory;
use App\Models\Activity;
use App\Models\Logo;
use App\Models\Media;
use App\Models\ModelLabel;
use App\Models\Order;
use App\Models\Pair;
use App\Models\DrGoizPair;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Playlist;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;

use App\Policies\ActivityCategoryPolicy;
use App\Policies\ActivityPolicy;
use App\Policies\LogoPolicy;
use App\Policies\MediaPolicy;
use App\Policies\ModelLabelPolicy;
use App\Policies\OrderPolicy;
use App\Policies\PairPolicy;
use App\Policies\DrGoizPairPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\PlanPolicy;
use App\Policies\PlaylistPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SubscriptionPolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        ActivityCategory::class => ActivityCategoryPolicy::class,
        Activity::class => ActivityPolicy::class,
        Logo::class => LogoPolicy::class,
        Media::class => MediaPolicy::class,
        ModelLabel::class => ModelLabelPolicy::class,
        Order::class => OrderPolicy::class,
        Pair::class => PairPolicy::class,
        DrGoizPair::class => DrGoizPairPolicy::class,
        Payment::class => PaymentPolicy::class,
        Playlist::class => PlaylistPolicy::class,
        Plan::class => PlanPolicy::class,
        Product::class => ProductPolicy::class,
        Subscription::class => SubscriptionPolicy::class,
        User::class => UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
