<?php

//Crontab
// 0  * * * * wget -O - http://btc-bot-new/index.php?func=mailing

require_once 'classes/Telegram.php';

$func = $_GET['func'];

Telegram::$func();