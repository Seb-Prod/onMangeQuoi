<?php
// Connection à la basse de donnée
define('SECURE_ACCESS', true);
require_once 'config/connection.php';
require_once 'includes/header.php';


// Validation du nom de la page
function sanitizeAndValidateRoute($input)
{
    $input = trim($input);
    $input = strtolower($input);
    $input = preg_replace('/[^a-z0-9-]/', '-', $input);
    $input = preg_replace('/-+/', '-', $input);
    $input = trim($input, '-');
    $input = substr($input, 0, 50);
    return empty($input) ? 'accueil' : $input;
}

$page = sanitizeAndValidateRoute($_GET['page'] ?? 'accueil');

// Mapping des URLs vers les fichiers
$routes = [
    'accueil' =>
    [
        'url' => 'views/accueil.php',
        'name' => "Page d'accueil",
        'css' => "",
    ],
    'contact' =>
    [
        'url' => 'views/contact.php',
        'name' => "Page contact",
        'css' => "contact"
    ],
    'login' =>
    [
        'url' => 'views/user/login.php',
        'name' => 'Connexion'
    ],
    'register' =>
    [
        'url' => 'views/user/register.php',
        'name' => 'Inscription'
    ],
    'logout' => [
        'controller' => true,  // Indique que c'est un contrôleur
        'url' => 'controllers/user/logout.php',
        'name' => 'Déconnexion'
    ]
];
// Vérification et inclusion
if (isset($routes[$page])) {
    $nav = new Navigation(
        $routes[$page]['name'] . ' - ' . SITE_TITLE,
        isset($routes[$page]['css']) ? $routes[$page]['css'] : ""
    );
    
    if (isset($routes[$page]['controller']) && $routes[$page]['controller']) {
        // Si c'est un contrôleur, on l'inclut directement
        require_once $routes[$page]['url'];
        exit(); // Important pour les contrôleurs
    } else {
        // Sinon on affiche la page normalement
        $nav->render();
        require_once $routes[$page]['url'];
    }
} else {
    $nav = new Navigation('Erreur 404 - ' . SITE_TITLE);
    $nav->render();
    require_once 'views/404.php';
}

// Le footer
require_once 'includes/footer.php';
