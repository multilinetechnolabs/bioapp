<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $this->mapApiRoutes();
            $this->mapWebRoutes();
            $this->mapAffiliateRoutes();
        });
    }

    protected function mapWebRoutes()
    {
        $route = Route::middleware('web')
            ->namespace('App\Http\Controllers');

        if ($this->shouldBindRoutesToDomains()) {
            $route->domain($this->normalizeRouteDomain(env('APP_WEB_URL')));
        }

        $route->group(base_path('routes/web.php'));
    }

    protected function mapApiRoutes()
    {
        $route = Route::middleware('api')
            ->namespace('App\Http\Controllers');

        if ($this->shouldBindRoutesToDomains()) {
            $route->domain($this->normalizeRouteDomain(env('APP_WEB_API_URL')));
        }

        $route->group(base_path('routes/api.php'));
    }

    protected function mapAffiliateRoutes()
    {
        $route = Route::middleware('web')
            ->namespace('App\Http\Controllers')
            ->name('affiliate.');

        if ($this->shouldBindRoutesToDomains()) {
            $route->domain($this->normalizeRouteDomain(env('APP_AFFILIATE_URL')));
        } else {
            $route->prefix('affiliate');
        }

        $route->group(base_path('routes/affiliate.php'));
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    protected function shouldBindRoutesToDomains()
    {
        return ! filter_var(env('APP_DISABLE_ROUTE_DOMAINS', false), FILTER_VALIDATE_BOOLEAN);
    }

    protected function normalizeRouteDomain($domain)
    {
        if (! $domain) {
            return null;
        }

        $host = parse_url($domain, PHP_URL_HOST);
        $port = parse_url($domain, PHP_URL_PORT);

        if ($host === null) {
            return $domain;
        }

        return $port ? $host.':'.$port : $host;
    }
}
