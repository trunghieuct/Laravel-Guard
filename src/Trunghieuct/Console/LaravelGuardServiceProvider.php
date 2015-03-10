<?php namespace Trunghieuct\Console;

use Illuminate\Support\ServiceProvider;

class LaravelGuardServiceProvider extends ServiceProvider {

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
		$this->registerMake();
		$this->registerWatch();
		$this->registerRefresh();
		$this->registerCommands();
	}

	/**
	 * Register guard.make
	 *
	 * @return Trunghieuct\Console\GuardMakeCommand
	 */
	protected function registerMake()
	{
		$this->app['guard.make'] = $this->app->share(function($app)
		{
			$guardFile = new Guardfile($app['files'], $app['config'], base_path());
			$generator = new GuardGenerator($app['files'], $guardFile);
			$gem = new Gem;

			return new GuardMakeCommand($generator, $gem, $app['config']);
		});
	}

	/**
	 * Register guard.watch
	 *
	 * @return Trunghieuct\Console\GuardWatchCommand
	 */
	protected function registerWatch()
	{
		$this->app['guard.watch'] = $this->app->share(function($app)
		{
			return new GuardWatchCommand;
		});
	}

	/**
	 * Register guard.refresh
	 *
	 * @return Trunghieuct\Console\GuardRefreshCommand
	 */
	protected function registerRefresh()
	{
		$this->app['guard.refresh'] = $this->app->share(function($app)
		{
			$guardFile = new Guardfile($app['files'], $app['config'], base_path());

			return new GuardRefreshCommand($guardFile);
		});
	}
	
	/**
	 * Make commands visible to Artisan
	 *
	 * @return void
	 */
	protected function registerCommands()
	{
		$this->commands(
			'guard.make',
			'guard.watch',
			'guard.refresh'
		);
	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('trunghieuct/guard-laravel');
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

}
