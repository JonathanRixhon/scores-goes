<?php
return [
    [
        'method' => 'POST',
        'action' => 'store',
        'ressource' => 'match',
        'controller' => 'Matches',
        'callback' => 'store'
    ],

    [
        'method' => 'POST',
        'action' => 'store',
        'ressource' => 'team',
        'controller' => 'Team',
        'callback' => 'store'
    ],

    [
        'method' => 'GET',
        'action' => 'create',
        'ressource' => 'team',
        'controller' => 'Team',
        'callback' => 'create'
    ],
    [
        'method' => 'GET',
        'action' => 'create',
        'ressource' => 'match',
        'controller' => 'Matches',
        'callback' => 'create'
    ],

    [
        'method' => 'GET',
        'action' => '',
        'ressource' => '',
        'controller' => 'Dashboard',
        'callback' => 'index'

    ],

    [
        'method' => 'GET',
        'action' => 'view',
        'ressource' => 'login-form',
        'controller' => 'Login',
        'callback' => 'create'
    ],
    [
        'method' => 'POST',
        'action' => 'check',
        'ressource' => 'login',
        'controller' => 'Login',
        'callback' => 'check'
    ],
    [
        'method' => 'POST',
        'action' => 'logout',
        'ressource' => 'user',
        'controller' => 'Login',
        'callback' => 'delete'
    ],
    [
        'method' => 'GET',
        'action' => 'view',
        'ressource' => 'register-form',
        'controller' => 'Register',
        'callback' => 'create'
    ],
    [
        'method' => 'POST',
        'action' => 'store',
        'ressource' => 'user',
        'controller' => 'Register',
        'callback' => 'store'
    ],

];
