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
 * Class GameState
 *
 * @author Dirk Ollmetzer (dirk.ollmetzer@ollmetzer.com)
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL 3.0
 * @copyright 2020 Dirk Ollmetzer (dirk.ollmetzer@ollmetzer.com)
 *
 * @package dollmetzer\ifengine
 */
class GameState
{
    const ERROR_FILE_NOT_FOUND = 202;
    const ERROR_FILE_PARSING = 203;

    /**
     * @var int
     */
    private $maxOutput = 10;

    /**
     * @var string
     */
    private $stateFile;

    /**
     * @var array
     */
    private $state = [];

    /**
     * @var string
     */
    private $gameDir;

    public function __construct(string $gameDir, string $sessionId)
    {
        $this->gameDir = $gameDir;

        $this->stateFile = PATH_SESSIONS .  '/' . $sessionId . '.gsf';
        if (file_exists($this->stateFile)) {
            $this->loadState();
        } else {
            $this->init();
        }
    }

    protected function init(): void
    {
        $this->state['output'] = [];
        $this->state['objects'] = $this->loadObjects();
        $this->state['roomname'] = $this->state['objects'][$this->state['objects']['_player']['room']]['name'];

        // set welcome message
        if ($this->state['objects']['_init']['welcome']) {
            $this->addOutput($this->state['objects']['_init']['welcome']);
        }
    }

    /**
     * Load objects from *.ini file
     */
    protected function loadObjects(): array
    {
        $filename = $this->gameDir . '/objects.ini';
        if (!file_exists($filename)) {
            throw new IfEngineException(
                'objectlist file not found: ' . $filename, self::ERROR_FILE_NOT_FOUND
            );
        }
        $objects = parse_ini_file($filename, true);
        if ($objects === false) {
            throw new IfEngineException('Objectlist file parsing error', self::ERROR_FILE_PARSING);
        }
        return $objects;
    }

    protected function loadState(): void
    {
        $this->state = json_decode(file_get_contents($this->stateFile), true);
    }

    public function save(): void
    {
        if (!is_writable(PATH_SESSIONS)) {
            throw new IfEngineException("Could not write PATH_SESSIONS Session directory not writeable.");
        }
        $fp = fopen($this->stateFile, 'w+');
        fwrite($fp, json_encode($this->state));
        fclose($fp);
    }

    public function getMoves(): int
    {
        return (int)$this->state['objects']['_player']['moves'];
    }

    public function setMoves(int $steps): void
    {
        $this->state['objects']['_player']['moves'] = $steps;
    }

    public function getScore(): int
    {
        return (int)$this->state['objects']['_player']['score'];
    }

    public function setScore(int $score): void
    {
        $this->state['objects']['_player']['score'] = $score;
    }

    public function getMaxScore(): int
    {
        return (int)$this->state['objects']['_player']['maxscore'];
    }

    public function getRoom(): string
    {
        return $this->state['objects']['_player']['room'];
    }

    public function setRoom(string $roomname): void
    {
        $this->state['objects']['_player']['room'] = $roomname;
    }

    public function getLanguage(): string
    {
        return $this->state['objects']['_init']['language'];
    }

    public function getOutput(): array
    {
        return $this->state['output'];
    }

    public function addOutput(string $output): void
    {
        $this->state['output'][] = $output;
        if (count($this->state['output']) > $this->maxOutput) {
            array_shift($this->state['output']);
        }
    }

    public function getObject(string $objectName): array
    {
        if (!empty($this->state['objects'][$objectName])) {
            return $this->state['objects'][$objectName];
        }
        return [];
    }

    public function setObject(string $objectName, array $values): void
    {
        $this->state['objects'][$objectName] = $values;
    }

    public function getObjectsInRoom(string $room): array
    {
        $result = [];
        foreach ($this->state['objects'] as $name => $object) {
            if (empty($this->state['objects'][$name]['room'])) {
                continue;
            }
            if ($this->state['objects'][$name]['room'] == $room) {
                $result[$name] = $object;
            }
        }
        return $result;
    }

    public function isObjectAvailable(string $object): bool
    {
        $result = false;
        $room = $this->state['objects']['_player']['room'];
        if ($this->state['objects'][$object]['room'] == $room) {
            $result = true;
        }
        if ($this->state['objects'][$object]['room'] == 'player') {
            $result = true;
        }
        return $result;
    }
}