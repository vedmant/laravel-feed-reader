Laravel 5 - Feed Reader
===============

A simple RSS feed reader for **Laravel 5**

## Features

 * One command to read any RSS feed
 * Different RSS feed profiles enabled

## Quick Start

To install this package run the Composer command

```
$ composer require vedmant/laravel-feed-reader
```

In your `config/app.php` add `Awjudd\FeedReader\FeedReaderServiceProvider::class` to the end of the `$providers` array

```php
'providers' => [

    Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
    Illuminate\Auth\AuthServiceProvider::class,
    ...
    Awjudd\FeedReader\FeedReaderServiceProvider::class,

[,

'aliases' => [

    'App'        => Illuminate\Support\Facades\App::class,
    'Artisan'    => Illuminate\Support\Facades\Artisan::class,
    ...
    'FeedReader' => Awjudd\FeedReader\Facades\FeedReader::class,
],
```

## Setup

### Publishing the Configuration

After installing through composer, you should publish the config file.  To do this, run the following command:

```
$ php artisan vendor:publish --provider="Awjudd\FeedReader\FeedReaderServiceProvider"
```

### Configuration Values

Once published, the configuration file contains an array of profiles.  These will define how the RSS feed reader will react.  By default the "default" profile will used.  For more information on: [here]http://simplepie.org/wiki/reference/simplepie/start.

### How to use

Once you have all of the configuration settings set up, in order to read a RSS feed all you need to do is call the `read` function:

```php
FeedReader::read('http://www.example.com/rss');
```

This function accepts 2 parameters however, the second parameter is optional.  The second parameter is the configuration profile that should be used when reading the RSS feed.

This will return to you the SimplePie object with the RSS feed in it.

## License

Feed Reader is free software distributed under the terms of the MIT license

## Additional Information

Any issues, please [report here](https://github.com/vedmant/laravel-feed-reader/issues)