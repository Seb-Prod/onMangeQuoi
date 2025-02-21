<?php
session_start();
define('SECURE_ACCESS', true);
require_once '../includes/connection.php';
require_once '../class/recette.php';

if(isset($_POST) && !empty($_POST)){
    try {
        $recipe = new Recipe($pdo);
    
        if ($recipe->create($_POST, $_SESSION['id'])) {
            $_SESSION['success'] = "Recette ajoutée avec succès !";
            header('Location: ../recettes.php');
            exit();
        } else {
            if ($recipe->isDuplicateError()) {
                $_SESSION['errors'] = $recipe->getErrors();
                $_SESSION['post_data'] = $_POST;
                header('Location: ../recettes.php');
                exit();
            } else {
                // Autres erreurs de validation
                $_SESSION['errors'] = $recipe->getErrors();
                header('Location: ../error.php');
                exit();
            }
        }
    } catch (Exception $e) {
        $_SESSION['errors'] = ["Une erreur inattendue s'est produite"];
        header('Location: ../error.php');
        exit();
    }
}else{
    $_SESSION['errors'] = ["Une erreur inattendue s'est produite"];
        header('Location: ../error.php');
        exit();
}



