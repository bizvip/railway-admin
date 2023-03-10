<?php

/******************************************************************************
 * Copyright (c) ArChang 2023.                                                *
 ******************************************************************************/

declare(strict_types=1);

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group([
    'domain'     => config('admin.route.domain'),
    'prefix'     => config('admin.route.prefix'),
    'middleware' => config('admin.route.middleware'),
], static function (Router $router) {
    $router->resource('dashboard', \App\Admin\Controllers\HomeController::class);
    $router->resource('system/settings', \App\Admin\Controllers\SettingController::class);
    $router->resource('categories', \App\Admin\Controllers\CategoryController::class);
    $router->post('categories/quick', [\App\Admin\Controllers\CategoryController::class, 'quickEdit']);
    $router->resource('slider', \App\Admin\Controllers\SliderController::class);
    $router->post('slider/quick', [\App\Admin\Controllers\SliderController::class, 'quickEdit']);
    $router->resource('apps', \App\Admin\Controllers\AppController::class);
    $router->post('apps/quick', [\App\Admin\Controllers\AppController::class, 'quickEdit']);
});
