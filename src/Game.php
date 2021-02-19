<?php
/**
 * i f e n g i n e
 * ===============
 *
 * This library is a mini framework for writing interactive fiction
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 3 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see <http://www.gnu.org/licenses/>.
 */

namespace dollmetzer\ifengine;

/**
 * Class Game
 *
 * @author Dirk Ollmetzer (dirk.ollmetzer@ollmetzer.com)
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL 3.0
 * @copyright 2020 Dirk Ollmetzer (dirk.ollmetzer@ollmetzer.com)
 *
 * @package dollmetzer\ifengine
 */
class Game
{
    const ERROR_GAME_DIR_NOT_FOUND = 402;

    /**
     * @var string $gameName
     */
    private $gameName;

    /**
     * @var string $gameDir
     */
    private $gameDir;

    /**
     * @var Engine $engine
     */
    private $engine;

    /**
     * @var GameState
     */
    private $gameState;

    /**
     * Game constructor.
     *
     * @param string $gameName
     * @throws IfEngineException
     */
    public function __construct(string $gameName, string $sessionId)
    {
        $this->gameName = $gameName;
        $this->gameDir = $this->getGameDir($gameName);

        $this->gameState = new GameState($this->gameDir, $sessionId);
        $this->engine = new Engine($this->gameState);
    }

    public function process(?string $rawInput): void
    {
        $parser = new Parser($this->gameDir);

        if($this->gameState->getMoves() == 0) {
            $this->gameState->setMoves($this->gameState->getMoves() + 1);
            $this->gameState->save();
            return;
        }

        $input = $parser->sanitizeInput($rawInput);
        $this->gameState->addOutput('> ' . $input);
        if(empty($input)) {
            $input = 'warte';
        }

        $words = $parser->parseInput($input);
        error_log($input);
        error_log(var_export($words, true));

        $this->engine->execute($words);

        $this->gameState->setMoves($this->gameState->getMoves() + 1);

        $this->gameState->save();
    }

    public function getRoom(): string
    {
        return $this->gameState->getRoom();
    }

    public function getMoves(): int
    {
        return $this->gameState->getMoves();
    }

    public function getScore(): int
    {
        return $this->gameState->getScore();
    }

    public function getMaxScore(): int
    {
        return $this->gameState->getMaxScore();
    }

    public function getOutput(): array
    {
        return $this->gameState->getOutput();
    }

    public function getLanguage(): string
    {
        return $this->gameState->getLanguage();
    }

    /**
     * @param string $gameName
     * @return string
     * @throws IfEngineException
     */
    private function getGameDir(string $gameName): string
    {
        $gameDir = PATH_GAMES . '/' . $gameName;
        if (!is_dir($gameDir)) {
            throw new IfEngineException('Gamedirectory not found error: ' . $gameDir, self::ERROR_GAME_DIR_NOT_FOUND);
        }
        return $gameDir;
    }
}