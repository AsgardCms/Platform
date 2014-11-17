<?php namespace Modules\Setting\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Modules\Setting\Entities\Setting;
use Modules\Setting\Repositories\Eloquent\EloquentSettingRepository;
use Modules\Setting\Support\Settings;

class SettingServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->booted(function () {
			$this->registerBindings();
		});

		$this->app['setting.settings'] = $this->app->share(function($app)
		{
			return new Settings($app['Modules\Setting\Repositories\SettingRepository'], $app['cache']);
		});

		$this->app->booting(function()
		{
			$loader = AliasLoader::getInstance();
			$loader->alias('Modules\Setting\Facades\Settings', 'Settings');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

	private function registerBindings()
	{
		$this->app->bind(
			'Modules\Setting\Repositories\SettingRepository',
			function() {
				return new EloquentSettingRepository(new Setting);
			}
		);
		$this->app->bind(
			'Modules\Core\Contracts\Setting',
			'Modules\Setting\Support\Settings'
		);
	}
}
