<?php

namespace App\Providers;

use App\Service\ImageService;
use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('image.product', function () {
            $disk = config('filesystems.default', 'public');
            return new ImageService($disk, 'products');
        });

        $this->app->bind('image.user', function () {
            $disk = config('filesystems.default', 'public');
            return new ImageService($disk, 'users');
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
