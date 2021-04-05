<?php
define('BD_PATH', $_SERVER['DOCUMENT_ROOT'] . '/data/scores.sqlite');
define('FILE_PATH', 'matches.csv');
define('TODAY', (new DateTime('now', new DateTimeZone('Europe/Brussels')))->format('M jS, Y'));
