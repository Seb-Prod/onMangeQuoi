<?php
session_start();
define('SECURE_ACCESS', true);
$appName = "Menu Zen";
$route = isset($_GET['page']) ? $_GET['page'] : 'home';

include 'routes.php';

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
