<?php

namespace Wilbere\BeautyTheme\Providers;

use Illuminate\Support\ServiceProvider;

class BeautyThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/themes.php',
            'themes.shop'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'beauty');

        if (is_dir(__DIR__ . '/../Resources/lang')) {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'beauty');
        }

        // Publishable Assets for Production Deployments
        $this->publishes([
            __DIR__ . '/../../publishable/build' => public_path('themes/shop/beauty/build'),
        ], 'beauty-theme-assets');

        // Publishable Views for Local Customizations
        $this->publishes([
            __DIR__ . '/../Resources/views' => resource_path('themes/beauty/views'),
        ], 'beauty-theme-views');
    }
}
