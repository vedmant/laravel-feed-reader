<?php namespace Awjudd\FeedReader;
/**
 * @Author: Andrew Judd
 * @Date:   2015-03-22 22:16:19
 * @Last Modified by:   Andrew Judd
 * @Last Modified time: 2015-03-22 22:25:59
 */

use Config;
use SimplePie;

class FeedReader
{
    /**
     * Used to parse an RSS feed.
     * 
     * @return \SimplePie
     */
    public function read($url, $configuration = 'default')
    {
        // Setup the object
        $sp = new SimplePie();

        // Configure it
        if(($cache = $this->setup_cache_directory($configuration)) !== false)
        {
            // Enable caching, and set the folder
            $sp->enable_cache(true);
            $sp->set_cache_location($cache);
            $sp->set_cache_duration($this->read_config($configuration, 'cache.duration', 3600));
        }
        else
        {
            // Disable caching
            $sp->enable_cache(false);
        }
        
        // Whether or not to force the feed reading
        $sp->force_feed($this->read_config($configuration, 'force-feed', false));

        // Should we be ordering the feed by date?
        $sp->enable_order_by_date($this->read_config($configuration, 'order-by-date', false));

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
     * @param string The configuration to use
     * @return string The folder that is being cached to
     */
    private function setup_cache_directory($configuration)
    {
        // Check if caching is enabled
        $cache_enabled = $this->read_config($configuration, 'cache.enabled', false);

        // Is caching enabled?
        if(!$cache_enabled)
        {
            // It is disabled, so skip it
            return false;
        }

        // Grab the cache location
        $cache_location = storage_path($this->read_config($configuration, 'cache.location', 'rss-feeds'));

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
    private function read_config($configuration, $name, $default)
    {
        return Config::get('feed-reader.config.profiles.' . $configuration . '.' . $name, $default);
    }
}
