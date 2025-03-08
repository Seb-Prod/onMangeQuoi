<?php
/**
 * Contrôleur pour la gestion des type et du nom de la recette - Étape 1
 * 
 * Ce script traite les types d'une recette et son nom 
 * et les valide avant de les stocker en session pour l'étape suivante.
 */ 

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
    if (isset($_POST['types'])) {
        $types = $_POST['types'];
        // Nettoyage des données : suppression des espaces, valeurs vides et doublons
        $types = array_unique(array_filter(array_map('trim', $types)));
        $_SESSION['types_plat'] = $types;
    }
    
    // Sauvegarde du nom pour réutilisation en cas d'erreur
    $_SESSION['nom_plat'] = $name;
    
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
        $_SESSION['message'] = "Le nom existe déjà";
        header("Location: ../../../index.php?page=recipestep1");
        exit();
    }
    
    // Si tout est ok, on marque l'étape 1 comme complétée et on passe à l'étape 2
    $_SESSION['step1'] = true;
    header("Location: ../../../index.php?page=recipestep2");
    exit();
}