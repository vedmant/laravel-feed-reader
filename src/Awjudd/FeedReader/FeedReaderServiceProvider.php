<?php namespace Awjudd\FeedReader;
/**
 * @Author: Andrew Judd
 * @Date:   2015-03-22 22:16:19
 * @Last Modified by:   Andrew Judd
 * @Last Modified time: 2015-03-22 22:22:37
 */

use Illuminate\Support\ServiceProvider;

class FeedReaderServiceProvider extends ServiceProvider
{

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/feed-reader.php' => config_path('feed-reader.php')
        ], 'config');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        // Bind to the "Asset" section
        $this->app->bind('feedreader', function() {
            return new FeedReader();
        });

        $this->mergeConfigFrom(
            __DIR__.'/../../config/feed-reader.php', 'feed-reader'
        );
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('feed-reader');
	}

}