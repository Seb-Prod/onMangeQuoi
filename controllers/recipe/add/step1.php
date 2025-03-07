<?php
session_start();

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['name'])
) {

    define('SECURE_ACCESS', true);
    include '../../../models/recipe.php';
    include '../../../includes/connection.php';

    $name = htmlspecialchars(trim($_POST['name']));

    $recipe = new Recipe($pdo);
    $exist = $recipe->exist($name);

    if ($exist['success']) {
        if (isset($_POST['types'])) {
            $types = $_POST['types'];

            // Nettoyage des données
            $types = array_map('trim', $types);
            $types = array_filter($types);
            $types = array_unique($types);

            $_SESSION['types_plat'] = $types;
            $_SESSION['nom_plat'] = $name;
        }

        if ($exist['datas']) {
            $_SESSION['message'] = "Le nom existe déjà";
            header("Location: ../../../index.php?page=recipeadd");
            exit();
        } else {
            header("Location: ../../../index.php?page=recipestep2");
            exit();
        }
    } else {
        $_SESSION['code_erreur'] = '403';
        header("Location: ../../../index.php?page=error");
        exit();
    }
}
