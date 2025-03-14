<?php
session_start();

// Vérification que la requête est bien de type POST et que le champ 'name' est présent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {

    // Définition de l'accès sécurisé
    define('SECURE_ACCESS', true);

    // Inclusions des fichiers nécessaires
    include '../../../models/recipe.php';
    include '../../../includes/connection.php';

    // Nettoyage et récupération du nom de la recette
    $name = htmlspecialchars(trim($_POST['name']));

    // Traitement des types de plats s'ils sont présents
    $types = [];

    if (isset($_POST['item'])) {
        $types = $_POST['item'];
    }

    // Ajout de $_POST['type'] même s'il est vide
    if (isset($_POST['type'])) {
        $types[] = trim($_POST['type']); // On ajoute la valeur, même si elle est vide
    }

    // Nettoyage des données : suppression des espaces et des doublons (mais on garde les valeurs vides)
    $types = array_unique(array_map('trim', $types));

    // Stockage en session
    $_SESSION['recipe']['types_plat'] = $types;


    // Vérification si la recette existe déjà
    $recipe = new Recipe($pdo);
    $existResult = $recipe->exist($name);

    if (!$existResult['success']) {
        // Erreur lors de la vérification en base de données
        $_SESSION['code_erreur'] = '403';
        header("Location: ../../../index.php?page=error");
        exit();
    }

    // Si la recette existe déjà, on redirige vers l'étape 1 avec un message d'erreur
    if ($existResult['datas']) {
        $_SESSION['error']['step1']['nom_plat'] = $name;
        $_SESSION['error']['step1']['message'] = "Le nom existe déjà";
        header("Location: ../../../index.php?page=recipestep1");
        exit();
    }

    // Si tout est ok, on marque l'étape 1 comme complétée et on passe à l'étape 2
    unset($_SESSION['error']['step1']);
    $_SESSION['step1'] = true;
    $_SESSION['recipe']['nom_plat'] = $name;
    header("Location: ../../../index.php?page=recipestep2");
    exit();
}
