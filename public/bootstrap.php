<?php

namespace adventure;

use dollmetzer\ifengine\Game;

require __DIR__ . '/../vendor/autoload.php';

define('PATH_GAMES', realpath(__DIR__ . '/../games'));
define('PATH_SESSIONS', realpath(__DIR__ . '/../sessions'));
session_start();

$game = new Game('die_stimme', session_id());
