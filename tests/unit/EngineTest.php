<?php

namespace unit;

use dollmetzer\ifengine\Engine;
use dollmetzer\ifengine\GameState;
use PHPUnit\Framework\TestCase;

class EngineTest extends TestCase
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
        $sessionFile = __DIR__ . '/../data/game/success/sessions/fakeSessionId_output.json';
        if (file_exists($sessionFile)) {
            unlink($sessionFile);
        }
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
        $class = new Engine($gameState);
        $this->assertInstanceOf(Engine::class, $class);
    }
}
