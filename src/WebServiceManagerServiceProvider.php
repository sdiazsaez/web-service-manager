<?php

namespace Larangular\WebServiceManager;

use \Illuminate\Support\ServiceProvider;
use Larangular\WebServiceManager\Register\ServiceRecords;
use Larangular\WebServiceManager\SoapClient\MTOMDecode;

class WebServiceManagerServiceProvider extends ServiceProvider {

    public function boot() {/*
        $this->publishes([
                             __DIR__ . '/../config/uf-scraper.php' => config_path('uf-scraper.php'),
                         ]);*/
    }

    public function register() {
        $this->app->register('Larangular\Support\SupportServiceProvider');
        //$this->mergeConfigFrom(__DIR__ . '/../config/uf-scraper.php', 'uf-scraper');

        $this->registerServiceRecords();

        $this->app->singleton('ws-manager.mtom-decode', function () {
            return new MTOMDecode();
        });

        $this->app->singleton(ServiceRequest::class, function ($app) {
            return new ServiceRequest($app['ws-manager.register']);
        });
    }

    public function provides() {
        return [
            ServiceRequest::class,
            ServiceRecords::class,
        ];
    }

    private function registerServiceRecords(): void {
        $this->app->singleton(ServiceRecords::class, function () {
            return new ServiceRecords();
        });
        $this->app->singleton('ws-manager.register', function () {
            return app(ServiceRecords::class);
        });
    }
}
