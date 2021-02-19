<?php

namespace unit;

use dollmetzer\ifengine\Game;
use dollmetzer\ifengine\IfEngineException;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
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

    public function testConstructSuccess(): void
    {
        $class = new Game('success', 'fakeSessionId');
        $this->assertInstanceOf(Game::class, $class);
    }

    public function testConstructFailed(): void
    {
        $this->expectException(IfEngineException::class);
        $this->expectExceptionCode(Game::ERROR_GAME_DIR_NOT_FOUND);
        $class = new Game('nogame', 'fakeSessionId');
    }

    public function testGetOutput(): void
    {
        $class = new Game('success', 'fakeSessionId');
        $expected = ['Das ist die Startnachricht'];
        $this->assertEquals($expected, $class->getOutput());
    }

    public function testProcessEmpty(): void
    {
        $this->markTestSkipped('Process is not stable yet');
        /**
        $class = new Game('success', 'fakeSessionId');
        $class->process('');
        $expected = [
            'Das ist die Startnachricht',
            '',
            'Du wartest.'
        ];
        $this->assertEquals($expected, $class->getOutput());
         * **/
    }
}
