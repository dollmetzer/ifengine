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
 * Class Parser
 *
 * @author Dirk Ollmetzer (dirk.ollmetzer@ollmetzer.com)
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL 3.0
 * @copyright 2020 Dirk Ollmetzer (dirk.ollmetzer@ollmetzer.com)
 *
 * @package dollmetzer\ifengine
 */
class Parser
{

    /**
     * @var Vocabulary $vocabulary
     */
    private $vocabulary;

    /**
     * @var array $tokens Slices of the input
     */
    private $tokens = [];

    /**
     * @var array $identified Found and identified in dictionary
     */
    private $identified = [];

    /**
     * @var array $classified Identified and classified Words
     */
    private $classified = [];

    public function __construct(string $gameDir)
    {
        $this->vocabulary = new Vocabulary($gameDir);
    }

    /**
     * Trim and strip tags from input
     *
     * @param string $rawInput
     * @return string
     */
    public function sanitizeInput(string $rawInput)
    {
        $input = strip_tags(trim($rawInput));
        $input = str_replace("\t", ' ', $input);
        $input = str_replace("\n", ' ', $input);
        $input = preg_replace("/ +/", ' ', $input);
        return $input;
    }

    /**
     * @param string $input
     * @return array
     */
    public function parseInput(string $input): array
    {
        $this->tokenize($input);
        $this->identifyWords();

        $this->classified = [];
        $adjective = '';
        for ($i = 0; $i < count($this->identified); $i++) {
            foreach ($this->identified[$i] as $meaning) {
                $this->processVerb($meaning);
                $this->processPreposition($meaning);
                if ($meaning['type'] == 'adjective') {
                    $adjective = $meaning['index'];
                }
                if (true === $this->processNoun($meaning, $adjective)) {
                    $adjective = '';
                }
            }
        }
        return $this->classified;
    }

    /**
     * Split input into tokens
     *
     * @param string $input
     */
    protected function tokenize(string $input): void
    {
        if (empty($input)) {
            $this->tokens = [];
        } else {
            $this->tokens = explode(' ', strtolower($input));
        }
    }

    /**
     * Identify words from tokens via vocabulary
     */
    protected function identifyWords(): void
    {
        $this->identified = [];
        foreach ($this->tokens as $token) {
            $meanings = $this->vocabulary->identify($token);
            if (!empty($meanings)) {
                $this->identified[] = $meanings;
            }
        }
    }

    protected function processVerb(array $meaning): void
    {
        if ($meaning['type'] == 'verb') {
            if (empty($this->classified['verb'])) {
                $this->classified['verb'] = [
                    'index' => $meaning['index'],
                    'word' => $meaning['word'],
                    'action' => $meaning['action']
                ];
            } else {
                // todo: display message "only one verb please"
            }
        }
    }

    protected function processPreposition(array $meaning): void
    {
        if ($meaning['type'] == 'preposition') {
            // todo: define constraints
            $this->classified['preposition']['index'] = $meaning['index'];
            $this->classified['preposition']['word'] = $meaning['word'];
        }
    }

    protected function processNoun(array $meaning, string $adjective): bool
    {
        $found = false;
        if ($meaning['type'] == 'noun') {
            $found = true;
            if (empty($this->classified['object_1'])) {
                $attribute = 'object_1';
            } elseif (empty($this->classified['object_2'])) {
                $attribute = 'object_2';
            } else {
                // todo: no more than two objects
                $attribute = null;
            }
            if ($attribute) {
                $this->classified[$attribute] = [
                    'index' => $meaning['index'],
                    'word' => $meaning['word']
                ];
                if (!empty($adjective)) {
                    // todo: check, if adjective matches object
                    $this->classified[$attribute]['adjective'] = $adjective;
                }
            }
        }
        return $found;
    }
}