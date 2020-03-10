<?php

namespace Vedmant\FeedReader\Tests\Unit;

use SimplePie;
use Vedmant\FeedReader\FeedReader;
use Vedmant\FeedReader\Facades\FeedReader as FeedReaderFacade;
use Vedmant\FeedReader\Tests\TestCase;
use Symfony\Component\Process\Process;

class FeedReaderTest extends TestCase
{
    /** @var Process */
    private static $process;

    public function testInstance()
    {
        $this->assertInstanceOf(FeedReader::class, $this->app->make('feed-reader'));
    }

    public function testReadRss()
    {
        self::$process = new Process(['php', '-S', 'localhost:8123', '-t', './tests/resources']);
        self::$process->start();
        usleep(500000);

        /** @var SimplePie $rss */
        $rss = FeedReaderFacade::read('http://localhost:8123/rss.xml');

        $this->assertEquals('FeedForAll Sample Feed', $rss->get_title());
        $this->assertObjectHasAttribute('term', $rss->get_category());
        $this->assertEquals(9, $rss->get_item_quantity());

        self::$process->stop();
    }
}
