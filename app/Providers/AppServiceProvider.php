<?php

namespace App\Providers;

use App\Filesystem\Plugins\ZipExtractTo;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();

        Storage::extend('s3', function ($app, $config) {
            return Storage::createS3Driver($config)->addPlugin(
                new ZipExtractTo()
            );
        });

        Storage::extend('local', function ($app, $config) {
            return Storage::createLocalDriver($config)->addPlugin(
                new ZipExtractTo()
            );
        });
    }
}
