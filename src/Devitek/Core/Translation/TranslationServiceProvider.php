<?php

namespace Devitek\Core\Translation;

class TranslationServiceProvider extends \Illuminate\Translation\TranslationServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__ . '/../../../config/yaml-translation.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([$source => config_path('yaml-translation.php')]);
        }

        $this->mergeConfigFrom($source, 'yaml-translation');
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
