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
 * Class Vocabulary
 *
 * @author Dirk Ollmetzer (dirk.ollmetzer@ollmetzer.com)
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL 3.0
 * @copyright 2020 Dirk Ollmetzer (dirk.ollmetzer@ollmetzer.com)
 *
 * @package dollmetzer\ifengine
 */
class Vocabulary
{
    const ERROR_VOCABULARY_FILE_TYPE = 101;
    const ERROR_VOCABULARY_FILE_NOT_FOUND = 102;
    const ERROR_VOCABULARY_FILE_PARSING = 103;

    /**
     * @var array $vocabulary
     */
    protected $vocabulary = [];


    /**
     * Vocabulary constructor.
     *
     * @param string $gameDir
     * @throws IfEngineException
     */
    public function __construct(string $gameDir)
    {
        $vocabularyFile = $gameDir . '/vocabulary.ini';
        $this->load($vocabularyFile);
    }

    /**
     * Load vocabulary from *.ini file
     *
     * @param string $filename
     * @throws IfEngineException
     */
    protected function load(string $filename): void
    {
        if (!file_exists($filename)) {
            throw new IfEngineException(
                'Vocabulary file not found: ' . $filename, self::ERROR_VOCABULARY_FILE_NOT_FOUND
            );
        }

        $this->vocabulary = parse_ini_file($filename, true);

        if ($this->vocabulary === false) {
            $this->vocabulary = [];
            throw new IfEngineException('Vocabulary file parsing error', self::ERROR_VOCABULARY_FILE_PARSING);
        }

        if (empty($this->vocabulary)) {
            throw new IfEngineException('Vocabulary file parsing error', self::ERROR_VOCABULARY_FILE_PARSING);
        }
    }

    /**
     * Return whole vocabulary as an array
     *
     * @return array
     */
    public function getAsArray(): array
    {
        return $this->vocabulary;
    }

    /**
     * Find matching words for a token in the vocabulary
     *
     * @param string $token
     * @return array
     */
    public function identify(string $token)
    {
        $result = [];
        foreach ($this->vocabulary as $index => $entry) {
            $allWords = explode(',', $entry['words']);
            if (in_array($token, $allWords)) {
                $found = [
                    'index' => $index,
                    'word' => $allWords[0]
                ];
                if (!empty($entry['type'])) {
                    $found['type'] = $entry['type'];
                }
                if (!empty($entry['action'])) {
                    $found['action'] = $entry['action'];
                }

                $result[] = $found;
            }
        }
        return $result;
    }

}