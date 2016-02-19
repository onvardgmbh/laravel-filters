<?php

namespace Onvard\Filter;

use Illuminate\Support\ServiceProvider;

class FilterServiceProvider extends ServiceProvider
{
    /**
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
            [__DIR__.'/Filters' => base_path('app/Filters')],
            'filters'
        );
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
