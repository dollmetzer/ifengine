<?php

namespace unit;

use dollmetzer\ifengine\IfEngineException;
use dollmetzer\ifengine\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    /**
     * @var string
     */
    public $gameDir;

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

    public function setUp(): void
    {
        $this->gameDir = __DIR__ . '/../data/game/success';
    }

    public function testConstructFailed(): void
    {
        $this->expectException(IfEngineException::class);
        $class = new Parser('malfunctional/game/directory/');
    }

    public function testConstruct(): void
    {
        $class = new Parser($this->gameDir);
        $this->assertInstanceOf(Parser::class, $class);
    }

    public function testSanitize(): void
    {
        $class = new Parser($this->gameDir);
        $sanitized = 'This is a bold word. Two sentences in two lines...';
        $this->assertEquals($sanitized, $class->sanitizeInput($sanitized));
    }

    public function testSanitizeHTML(): void
    {
        $class = new Parser($this->gameDir);
        $raw = "This is a <b>bold</b> word. Two sentences in two lines...";
        $sanitized = 'This is a bold word. Two sentences in two lines...';
        $this->assertEquals($sanitized, $class->sanitizeInput($raw));
    }

    public function testSanitizeTrim(): void
    {
        $class = new Parser($this->gameDir);
        $raw = "  This is a bold word. Two sentences in two lines...  ";
        $sanitized = 'This is a bold word. Two sentences in two lines...';
        $this->assertEquals($sanitized, $class->sanitizeInput($raw));
    }

    public function testSanitizeMultipleSpaces(): void
    {
        $class = new Parser($this->gameDir);
        $raw = "This  is a bold word.   Two sentences in      two lines...  ";
        $sanitized = 'This is a bold word. Two sentences in two lines...';
        $this->assertEquals($sanitized, $class->sanitizeInput($raw));
    }

    public function testSanitizeSpecialChars(): void
    {
        $class = new Parser($this->gameDir);
        $raw = "This is a \tbold word. \nTwo sentences in two lines...";
        $sanitized = 'This is a bold word. Two sentences in two lines...';
        $this->assertEquals($sanitized, $class->sanitizeInput($raw));
    }

    /**
     * @dataProvider sentenceProvider
     */
    public function testParseInput(string $input, array $expected): void
    {
        $class = new Parser($this->gameDir);
        $identified = $class->parseInput($input);
        $this->assertEquals($expected, $identified);
    }

    public function sentenceProvider(): array
    {
        return [
            [
                '',
                []
            ],
            [
                'This is a simple sentence without any known words.',
                []
            ],
            [
                'Untersuche die Dose',
                [
                    'verb' => [
                        'index' => 'examine',
                        'word' => 'untersuch',
                        'action' => 'dollemtzer\ifengine\action\Examine'
                    ],
                    'object_1' => [
                        'index' => 'can',
                        'word' => 'dose'
                    ]
                ]
            ],
            [
                'Stell die blaue Dose auf den kleinen Tisch',
                [
                    'verb' => [
                        'index' => 'put',
                        'word' => 'leg',
                        'action' => 'dollemtzer\ifengine\action\Put'
                    ],
                    'object_1' => [
                        'index' => 'can',
                        'word' => 'dose',
                        'adjective' => 'blue'
                    ],
                    'preposition' => [
                        'index' => 'on',
                        'word' => 'auf'
                    ],
                    'object_2' => [
                        'index' => 'table',
                        'word' => 'tisch'
                    ]
                ]
            ]
        ];
    }
}