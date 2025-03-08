<?php

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
        'adresse' => 'views/user/logout.php',
        'label' => 'logout',
        'levels' => [1,2]
    ],
    'register' =>
    [
        'adresse' => 'views/user/register.php'
    ],
    'profile' =>
    [
        'adresse' => 'views/profile.php',
        'label' => 'Mon compte',
        'levels' => [1,2]
    ],
    'about' =>
    [
        'adresse' => 'views/about.php',
        'label' => 'A propos',
        'levels' => [0,1,2]
    ],
    'error' =>
    [
        'adresse' => 'error.php'
    ]
    
];

// routes de l'ajout de recettes
$routes['recipestep1'] = ['adresse' => 'views/recipe/add/step1_name_type.php'];
$routes['recipestep2'] = ['adresse' => 'views/recipe/add/step2_preparation_time.php'];
$routes['recipestep3'] = ['adresse' => 'views/recipe/add/step3_ingredients.php'];
$routes['recipestep4'] = ['adresse' => 'views/recipe/add/step4_steps'];


?>