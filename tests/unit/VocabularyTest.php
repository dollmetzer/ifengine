<?php

namespace unit;

use dollmetzer\ifengine\IfEngineException;
use dollmetzer\ifengine\Vocabulary;
use PHPUnit\Framework\TestCase;

class VocabularyTest extends TestCase
{
    const PATH_GAME_SUCCESS = __DIR__ . '/../data/game/success';
    const PATH_GAME_FAILURE = __DIR__ . '/../data/game/failure';

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

    public function testConstruct(): void
    {
        $class = new Vocabulary(self::PATH_GAME_SUCCESS);
        $this->assertInstanceOf(Vocabulary::class, $class);
    }

    public function testLoadSuccess(): void
    {
        $class = new Vocabulary(self::PATH_GAME_SUCCESS);
        $this->assertArrayHasKey('get', $class->getAsArray());
    }

    public function testLoadErrorFileNotFound(): void
    {
        $this->expectException(IfEngineException::class);
        $this->expectExceptionCode(Vocabulary::ERROR_VOCABULARY_FILE_NOT_FOUND);
        $class = new Vocabulary('unknown');
    }

    public function testLoadErrorParsing(): void
    {
        $this->expectException(IfEngineException::class);
        $this->expectExceptionCode(Vocabulary::ERROR_VOCABULARY_FILE_PARSING);
        $class = new Vocabulary(self::PATH_GAME_FAILURE);
    }

    public function testIdentifyFailed(): void
    {
        $class = new Vocabulary(self::PATH_GAME_SUCCESS);
        $this->assertArrayHasKey('get', $class->getAsArray());
        $expected = [];
        $this->assertEquals($expected, $class->identify('unbekannt'));
    }

    /**
     * @dataProvider vocabularyProvider
     */
    public function testIdentifySuccess(string $word, array $expected): void
    {
        $class = new Vocabulary(self::PATH_GAME_SUCCESS);
        $this->assertArrayHasKey('get', $class->getAsArray());
        $this->assertEquals($expected, $class->identify($word));
    }

    public function vocabularyProvider(): array
    {
        return [
            [
                'inventur',
                [
                    [
                        'index' => 'inventory',
                        'word' => 'inventur',
                        'type' => 'verb',
                        'action' => 'dollemtzer\ifengine\action\Inventory'
                    ]
                ]
            ],
            [
                'nehme',
                [
                    [
                        'index' => 'get',
                        'word' => 'nimm',
                        'type' => 'verb',
                        'action' => 'dollemtzer\ifengine\action\Get'
                    ]
                ]
            ],
            [
                'weglegen',
                [
                    [
                        'index' => 'put',
                        'word' => 'leg',
                        'type' => 'verb',
                        'action' => 'dollemtzer\ifengine\action\Put'
                    ]
                ]
            ],
        ];
    }
}
