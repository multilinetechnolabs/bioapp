<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAffiliateRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        $route = Route::middleware('web')
             ->namespace($this->namespace);

        if ($this->shouldBindRoutesToDomains()) {
            $route->domain($this->normalizeRouteDomain(env('APP_WEB_URL')));
        }

        $route->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        $route = Route::middleware('auth:api')
             ->namespace($this->namespace);

        if ($this->shouldBindRoutesToDomains()) {
            $route->domain($this->normalizeRouteDomain(env('APP_WEB_API_URL')));
        }

        $route->group(base_path('routes/api.php'));
    }

    protected function mapAffiliateRoutes()
    {
        $route = Route::middleware('web')
             ->namespace($this->namespace)
             ->name('affiliate.');

        if ($this->shouldBindRoutesToDomains()) {
            $route->domain($this->normalizeRouteDomain(env('APP_AFFILIATE_URL')));
        } else {
            $route->prefix('affiliate');
        }

        $route->group(base_path('routes/affiliate.php'));
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
