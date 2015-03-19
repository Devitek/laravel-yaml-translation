<?php namespace Devitek\Core\Translation;

use Illuminate\Translation\TranslationServiceProvider as IlluminateTranslationServiceProvider;


class TranslationServiceProvider extends IlluminateTranslationServiceProvider
{

	protected function registerLoader()
	{
		$this->app->singleton('translation.loader', function($app)
		{
			return new YamlFileLoader($app['files'], $app['path.lang']);
		});
	}


}
