<?php


namespace unit;

use dollmetzer\ifengine\GameState;
use PHPUnit\Framework\TestCase;

class GameStateTest extends TestCase
{
    const PATH_GAME_SUCCESS = __DIR__ . '/../data/game/success';
    const PATH_GAME_FAILURE = __DIR__ . '/../data/game/failure';

    /**
     * Execute once on class test start
     */
    public static function setUpBeforeClass(): void
    {
        echo "Start " . __CLASS__ . "\n";
        if(!defined('PATH_SESSIONS')) {
            define('PATH_SESSIONS', __DIR__ . '/../../sessions');
        }
    }

    /**
     * Execute once after class test finish
     */
    public static function tearDownAfterClass(): void
    {
        echo "\n";
    }

    public function testConstruct(): void
    {
        $class = new GameState(self::PATH_GAME_SUCCESS, 'someSessionId');
        $this->assertInstanceOf(GameState::class, $class);
    }

    public function testInitState(): void
    {
        $class = new GameState(self::PATH_GAME_SUCCESS, 'someSessionId');
        $this->assertEquals(0, $class->getMoves());
        $this->assertEquals(0, $class->getScore());
        $this->assertEquals(199, $class->getMaxScore());
        $this->assertEquals('besenkammer', $class->getRoom());
        $this->assertEquals(['Das ist die Startnachricht'], $class->getOutput());
    }

    public function testGetObject(): void
    {
        $class = new GameState(self::PATH_GAME_SUCCESS, 'someSessionId');
        $expected = [
            'type' => 'player',
            'room' => 'besenkammer',
            'moves' => '0',
            'score' => '0',
            'maxscore' => '199'
        ];
        $this->assertEquals($expected, $class->getObject('_player'));
    }
}