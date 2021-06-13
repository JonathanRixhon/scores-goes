<?php
session_start();
//importation composer
use Carbon\Carbon;

require('./vendor/autoload.php');

require("./configs/config.php");

$route = require('./utils/router.php');

$controllerName = "Controllers\\" . $route['controller'];

$controller = new $controllerName;

$data = call_user_func([$controller, $route['callback']]);

extract($data, EXTR_OVERWRITE);
/* --------------TEMPLATE-------------- */
require($view);
/* --------------FIN TEMPLATE-------------- */

$_SESSION['errors'] = [];
$_SESSION['old'] = [];
