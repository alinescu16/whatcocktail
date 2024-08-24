<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use GuzzleHttp\Client;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(Client::class, function () {
            return new Client( array(
                'base_uri' => 'https://cocktail-recipe-api-apiverve.p.rapidapi.com/v1/',
                'timeout'  => 20.0,
                'headers'  => array(
                    'Accept'          => 'application/json',
                    'x-rapidapi-host' => 'cocktail-recipe-api-apiverve.p.rapidapi.com',
                    'x-rapidapi-key'  => 'f9ccf06362mshe427f03d747111fp18682ajsn1eb1efafa2e7',
                )
            ) );
        });

        $this->app->alias(Client::class, 'http.client');
    }
}
