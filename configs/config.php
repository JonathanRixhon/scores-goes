<?php
define('BD_PATH', $_SERVER['DOCUMENT_ROOT'] . '/data/scores.sqlite');
define('TODAY', \Carbon\Carbon::now('Europe/Brussels')->locale('fr_BE')->isoFormat('dddd DD MMMM YYYY'));
define('THUMBS', './assets/images/thumbs/');
define('FULL', './assets/images/full/');
$data = [];
$view = './views/dashboard.php';
