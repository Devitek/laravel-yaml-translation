<?php

namespace Devitek\Core\Translation;

use Illuminate\Translation\TranslationServiceProvider as IlluminateTranslationServiceProvider;

class TranslationServiceProvider extends IlluminateTranslationServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../../config/yaml-translation.php' => config_path('yaml-translation.php', 'yaml-translation'),
        ]);
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/yaml-translation.php', 'yaml-translation'
        );
    }

    /**
     * @return void
     */
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new YamlFileLoader($app[ 'files' ], $app[ 'path.lang' ]);
        });
    }
}
