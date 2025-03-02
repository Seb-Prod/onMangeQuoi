<?php
session_start();
define('SECURE_ACCESS', true);
$_SESSION['level'] = 2;
$route = isset($_GET['page']) ? $_GET['page'] : 'home';

// Définir les routes disponibles
$routes = [
    'home' =>
    [
        'adresse' => 'views/home.php',
        'label' => 'Acceuil',
        'levels' => [0, 1, 2]
    ],
    'login' =>
    [
        'adresse' => 'views/user/login.php',
        'label' => 'Login',
        'levels' => [0]
    ],
    'logout' =>
    [
        'adresse' => 'views/logout.php',
        'label' => 'logout',
        'levels' => [1,2]
    ],
    'register' =>
    [
        'adresse' => 'views/user/register.php'
    ],
    'user' =>
    [
        'adresse' => 'views/user.php',
        'label' => 'Mon compte',
        'levels' => [1,2]
    ],
    'about' =>
    [
        'adresse' =>
        'views/about.php',
        'label' => 'A propos',
        'levels' => [0,1,2]
    ],
    'error' =>
    [
        'adresse' => 'error.php'
    ]
];

// Affichage de la page
if (array_key_exists($route, $routes)) {
    if (file_exists($routes[$route]['adresse'])) {
        include $routes[$route]['adresse'];
    } else {
        include 'error.php';
    }
} else {
    include 'error.php';
}
