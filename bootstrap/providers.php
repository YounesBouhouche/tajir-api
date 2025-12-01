<?php

use App\Providers\ImageServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\ImageServiceProvider::class,
    App\Providers\SwaggerUiServiceProvider::class,
    ImageServiceProvider::class
];
