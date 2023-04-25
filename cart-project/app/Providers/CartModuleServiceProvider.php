<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use App\Modules\Cart\Application\Usecases\SaveProductToCartUseCase;
use App\Modules\Cart\Connectors\Adapters\Secondary\IlluminateHttpAdapter;
use App\Modules\Cart\Connectors\Adapters\Secondary\CartInMemoryRepository;
use App\Modules\Cart\Connectors\Ports\Inbound\SaveProductToCartInboundPort;
use App\Modules\Cart\Connectors\Adapters\Secondary\Abstractions\HttpAdapterAbstraction;
use App\Modules\Cart\Connectors\Adapters\Secondary\GetProductByIdProductsCatalogAdapter;

class CartModuleServiceProvider extends ServiceProvider
{
    public $singletons = [
        SaveProductToCartOutboundPort::class => CartInMemoryRepository::class,
        GetProductByIdOutputPort::class => GetProductByIdProductsCatalogAdapter::class,
        HttpAdapterAbstraction::class => IlluminateHttpAdapter::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SaveProductToCartInboundPort::class, function (Application $app) {
            return new SaveProductToCartUseCase(
                $app->make(SaveProductToCartOutboundPort::class),
                $app->make(GetProductByIdOutputPort::class),
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        
    }
}
