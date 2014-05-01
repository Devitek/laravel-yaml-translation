<?php namespace Devitek\Core\Translation;

use \Illuminate\Translation\TranslationServiceProvider as IlluminateTranslationServiceProvider;

class TranslationServiceProvider extends IlluminateTranslationServiceProvider
{

	/**
	 * Register the translation line loader.
     * Add support for YAML files.
	 *
	 * @return void
	 */
	protected function registerLoader()
	{
		$this->app->bindShared('translation.loader', function($app)
		{
            return new YamlFileLoader($app['files'], $app['path'].'/lang');
		});
	}

}
