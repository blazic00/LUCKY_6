<?php

Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_ALL);
require_once 'Database.php';
require_once 'GameManager.php';
require_once 'GameServer.php';
require_once 'GameRoundDAO.php';

//date_default_timezone_set('Europe/Belgrade');
$gs = new GameServer();
$gs->start();
