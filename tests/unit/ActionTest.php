<?php

namespace unit;

use dollmetzer\ifengine\Action;
use dollmetzer\ifengine\GameState;
use PHPUnit\Framework\TestCase;

class ActionTest extends TestCase
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
        if (!defined('PATH_SESSIONS')) {
            define('PATH_SESSIONS', __DIR__ . '/../data/sessions');
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

    public function testConstruct(): void
    {
        $gameState = $this->getMockBuilder(GameState::class)->disableOriginalConstructor()->getMock();
        $class = new Action([], $gameState);
        $this->assertInstanceOf(Action::class, $class);
    }
}
