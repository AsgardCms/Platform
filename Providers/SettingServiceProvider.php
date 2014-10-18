<?php namespace Modules\Setting\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Setting\Entities\Setting;
use Modules\Setting\Repositories\Eloquent\EloquentSettingRepository;

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
	}
}
