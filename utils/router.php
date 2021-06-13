<?php
$routes = require('./configs/routes.php');

$method = $_SERVER['REQUEST_METHOD']; //GET
$methodName = "_" . $method; //_GET
$action = $$methodName['action'] ?? '';
$ressource = $$methodName['ressource'] ?? '';

$route = array_filter($routes, function ($r) use ($method, $action, $ressource) {
    return $r['method'] === $method
        && $r['action'] === $action
        && $r['ressource'] === $ressource;
});

if (!$route) {
    header('Location: index.php');
    exit();
}

return $route = reset($route);
