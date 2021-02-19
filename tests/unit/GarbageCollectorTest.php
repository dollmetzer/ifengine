<?php

namespace unit;

use dollmetzer\ifengine\GarbageCollector;
use PHPUnit\Framework\TestCase;

class GarbageCollectorTest extends TestCase
{
    /**
     * Execute once on class test start
     */
    public static function setUpBeforeClass(): void
    {
        echo "Start " . __CLASS__ . "\n";
        if (!defined('PATH_GAMES')) {
            define('PATH_GAMES', __DIR__ . '/../data/game');
        }
    }

    /**
     * Execute once after class test finish
     */
    public static function tearDownAfterClass(): void
    {
        echo "\n";
    }

    /**
     * Execute before test method start
     */
    public function setUp(): void
    {
    }

    /**
     * Execute after test method finish
     */
    public function tearDown(): void
    {
    }

    public function testConstructSuccess(): void
    {
        $class = new GarbageCollector('success');
        $this->assertInstanceOf(Garbagecollector::class, $class);
        $this->assertTrue(method_exists($class, 'cleanSessions'));
    }

}
