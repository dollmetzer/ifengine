<?php
/**
 * i f e n g i n eo
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
 * Class GarbageCollector
 *
 * @author Dirk Ollmetzer (dirk.ollmetzer@ollmetzer.com)
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL 3.0
 * @copyright 2020 Dirk Ollmetzer (dirk.ollmetzer@ollmetzer.com)
 *
 * @package dollmetzer\ifengine
 */
class GarbageCollector
{
    const ERROR_SESSIONS_DIR_NOT_FOUND = 403;
    const ERROR_SESSIONS_DIR_NOT_WRITEABLE = 404;
    const SESSION_TIMEOUT = 3600;

    /**
     * @var string
     */
    protected $sessionsDir;

    /**
     * GarbageCollector constructor.
     *
     * @param string $gameName
     * @throws IfEngineException
     */
    public function __construct(string $gameName)
    {
        $sessionsDir = PATH_GAMES . '/' . $gameName . '/sessions';
        if (!is_dir($sessionsDir)) {
            throw new IfEngineException(
                'Session directory not found error: ' . $sessionsDir,
                self::ERROR_SESSIONS_DIR_NOT_FOUND
            );
        }
        if (!is_writeable($sessionsDir)) {
            throw new IfEngineException(
                'Session directory not writeable error: ' . $sessionsDir,
                self::ERROR_SESSIONS_DIR_NOT_WRITEABLE
            );
        }
        $this->sessionsDir = $sessionsDir;
    }

    /**
     * @return int
     */
    protected function cleanSessions(): int
    {
        $deleted = 0;
        $now = time();
        $dir = opendir($this->sessionsDir);
        while (false !== ($file = readdir($dir))) {
            if(($file == '.') || ($file == '..')) continue;
            $mtime = filemtime($this->sessionsDir . '/' . $file);
            $diff = $now - $mtime;
            if ($diff > self::SESSION_TIMEOUT) {
                if (true === unlink($this->sessionsDir . '/' . $file)) {
                    $deleted++;
                }
            }
        }
        closedir($dir);
        return $deleted;
    }
}