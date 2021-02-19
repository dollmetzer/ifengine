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
 * Class Engine
 *
 * @author Dirk Ollmetzer (dirk.ollmetzer@ollmetzer.com)
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL 3.0
 * @copyright 2020 Dirk Ollmetzer (dirk.ollmetzer@ollmetzer.com)
 *
 * @package dollmetzer\ifengine
 */
class Engine
{
    /**
     * @var GameState
     */
    private $gameState;

    public function __construct(GameState &$gameState)
    {
        $this->gameState = &$gameState;
    }

    public function execute(array $words): void
    {
        if (empty($words['verb'])) {
            $this->gameState->addOutput('Ich habe nicht verstanden, was ich tun soll.');
            return;
        }

        $actionClass = $words['verb']['action'];
        if(class_exists($actionClass)) {
            $action = new $actionClass($words, $this->gameState);
            $action->execute();
        }
    }
}