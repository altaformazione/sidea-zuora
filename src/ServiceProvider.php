<?php

namespace Sideagroup\Zuora;

use Illuminate\Foundation\Application;
use Sideagroup\Zuora\V2\ApiClient;
use Sideagroup\Zuora\V3\Client\Client;
use Sideagroup\Zuora\V3\Client\ClientConfiguration;
use Sideagroup\Zuora\V3\Services\Zuora;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('zuora')
            ->hasConfigFile();

        $this->registerZuoraApiClientV2();
        $this->registerZuoraApiClientV3();
        $this->registerZuoraService();
    }

    protected function registerZuoraApiClientV2(): void
    {
        // $this->app->singleton('zuora-api-client', function () {
        //     return new ApiClient(
        //         config('zuora.base_uri'),
        //         config('zuora.credentials.client_id'),
        //         config('zuora.credentials.client_secret'),
        //         config('zuora.credentials.entity_id', null),
        //         config('zuora.log_requests', false),
        //     );
        // });

        // $this->app->register(\Bilfeldt\LaravelHttpClientLogger\LaravelHttpClientLoggerServiceProvider::class);

        $this->app->singleton('zuora-api-client', function () {
            throw new \Exception("\"zuora-api-client\" (v2) can't be used. use V3 instead");
        });
    }

    protected function registerZuoraApiClientV3(): void
    {
        $this->app->singleton('zuora-api-client-v3', function (Application $app) {
            return new Client(
                ClientConfiguration::fromConfig($app->make('config')->get('zuora'))
            );
        });
    }

    protected function registerZuoraService(string $zuora_client_tag = 'zuora-api-client-v3'): void
    {
        $this->app->singleton(Zuora::class, fn (Application $app) => new Zuora($app->make($zuora_client_tag)));
    }
}
