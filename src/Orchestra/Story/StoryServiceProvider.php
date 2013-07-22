<?php namespace Orchestra\Story;

use Illuminate\Support\ServiceProvider;

class StoryServiceProvider extends ServiceProvider {

	/**
	 * Register service provider.
	 *
	 * @return void
	 */
	public function register() 
	{
		$this->registerStoryTeller();
		$this->registerFormatManager();
	}

	/**
	 * Register service provider.
	 *
	 * @return void
	 */
	protected function registerStoryTeller()
	{
		$this->app['orchestra.story'] = $this->app->share(function ($app)
		{
			return new Storyteller($app);
		});
	}

	/**
	 * Register service provider.
	 *
	 * @return void
	 */
	protected function registerFormatManager()
	{
		$this->app['orchestra.story.format'] = $this->app->share(function ($app)
		{
			return new FormatManager($app);
		});
	}

	/**
	 * Boot the service provider
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('orchestra/story', 'orchestra/story');

		include __DIR__.'/../../start/global.php';
		include __DIR__.'/../../start/events.php';
		include __DIR__.'/../../filters.php';
		include __DIR__.'/../../routes.php';
	}
}
