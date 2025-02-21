<?php
session_start();
define('SECURE_ACCESS', true);
require_once '../includes/connection.php';
require_once '../class/user.php';

$user = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données communes
    $pseudo = $_POST['pseudo'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Sauvegarde des données POST pour réaffichage en cas d'erreur
    $postData = $_POST;
    unset($postData['password'], $postData['confirm']);
    
    if (isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['confirm'])) {
        // Traitement de la création de compte
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $confirm = $_POST['confirm'];
        
        // Vérification de la correspondance des mots de passe
        if ($password !== $confirm) {
            $_SESSION['errors'] = "Les mots de passe ne correspondent pas.";
            $_SESSION['post_data'] = $postData;
            header('Location: ../user.php?newUser=true');
            exit();
        }
        
        // Création de l'utilisateur
        $result = $user->createUser($pseudo, $nom, $prenom, $email, $password);
        
        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
            unset($_SESSION['post_data']); // Nettoyage des données temporaires
            header('Location: ../user.php');
            exit();
        } else {
            $_SESSION['errors'] = $result['message'];
            if (!empty($result['errors'])) {
                $_SESSION['validation_errors'] = $result['errors'];
            }
            $_SESSION['post_data'] = $postData;
            header('Location: ../user.php?newUser=true');
            exit();
        }
        
    } else {
        // Traitement de la connexion
        $result = $user->login($pseudo, $password);
        
        if ($result['success']) {
            // La session est déjà initialisée dans la méthode login
            unset($_SESSION['errors'], $_SESSION['post_data']); // Nettoyage
            header('Location: ../index.php');
            exit();
        } else {
            $_SESSION['errors'] = $result['message'];
            $_SESSION['post_data'] = ['pseudo' => $pseudo]; // Garde le pseudo pour réaffichage
            header('Location: ../user.php');
            exit();
        }
    }
}

// Si on arrive ici, c'est qu'il n'y a pas eu de POST
header('Location: ../user.php');
exit();
