<?php

namespace Vedmant\FeedReader;

use Config;
use Illuminate\Contracts\Container\Container;
use SimplePie;

class FeedReader
{
    /**
     * @var Container
     */
    private $app;

    /**
     * FeedReader constructor.
     *
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Used to parse an RSS feed.
     *
     * @param        $url
     * @param string $configuration
     * @param array  $options
     * @return SimplePie
     */
    public function read($url, $configuration = 'default', array $options = [])
    {
        // Setup the object
        $sp = $this->app->make(SimplePie::class);

        // Configure it
        if(($cache = $this->setupCacheDirectory($configuration)) !== false)
        {
            // Enable caching, and set the folder
            $sp->enable_cache(true);
            $sp->set_cache_location($cache);
            $sp->set_cache_duration($this->readConfig($configuration, 'cache.duration', 3600));
        }
        else
        {
            // Disable caching
            $sp->enable_cache(false);
        }

        // Whether or not to force the feed reading
        $sp->force_feed($this->readConfig($configuration, 'force-feed', false));

        // Should we be ordering the feed by date?
        $sp->enable_order_by_date($this->readConfig($configuration, 'order-by-date', false));

        if (! $this->readConfig($configuration, 'ssl-verify', true)) {
            $sp->set_curl_options([
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
            ]);
        }

        // If the user passes manual curl options, let's add them
        if (isset($options['curl_options'])) {
            $sp->set_curl_options($options['curl_options']);
        }

        // Set the feed URL
        $sp->set_feed_url($url);

        // Grab it
        $sp->init();

        // We are done, so return it
        return $sp;
    }

    /**
     * Used in order to setup the cache directory for future use.
     *
     * @param string $configuration The configuration to use
     * @return string The folder that is being cached to
     */
    private function setupCacheDirectory($configuration)
    {
        // Check if caching is enabled
        $cache_enabled = $this->readConfig($configuration, 'cache.enabled', false);

        // Is caching enabled?
        if(!$cache_enabled)
        {
            // It is disabled, so skip it
            return false;
        }

        // Grab the cache location
        $cache_location = storage_path($this->readConfig($configuration, 'cache.location', 'rss-feeds'));

        // Is the last character a slash?
        if(substr($cache_location, -1) != DIRECTORY_SEPARATOR)
        {
            // Add in the slash at the end
            $cache_location .= DIRECTORY_SEPARATOR;
        }

        // Check if the folder is available
        if(!file_exists($cache_location))
        {
            // It didn't, so make it
            mkdir($cache_location, 0777);

            // Also add in a .gitignore file
            file_put_contents($cache_location . '.gitignore', '!.gitignore' . PHP_EOL . '*');
        }

        return $cache_location;
    }

    /**
     * Used internally in order to retrieve a sepcific value from the configuration
     * file.
     *
     * @param string $configuration The name of the configuration to use
     * @param string $name The name of the value in the configuration file to retrieve
     * @param mixed $default The default value
     * @return mixed
     */
    private function readConfig($configuration, $name, $default)
    {
        return Config::get('feed-reader.profiles.' . $configuration . '.' . $name, $default);
    }
}
