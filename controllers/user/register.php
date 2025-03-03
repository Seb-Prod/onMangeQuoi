<?php
session_start();
// Vérification si la requête est POST et si les clés nécessaires existent
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['nom'], $_POST['prenom'], $_POST['pseudo'], $_POST['email'], $_POST['pass'], $_POST['confirmPass'])) {

    var_dump($_POST); // Affichage des données POST pour débogage

    define('SECURE_ACCESS', true);
    include '../../models/user.php';
    include '../../includes/connection.php';

    $user = new User($pdo);

    // Récupération et nettoyage des données POST
    $nom = htmlspecialchars(trim($_POST['nom']), ENT_QUOTES, 'UTF-8');
    $prenom = htmlspecialchars(trim($_POST['prenom']), ENT_QUOTES, 'UTF-8');
    $pseudo = htmlspecialchars(trim($_POST['pseudo']), ENT_QUOTES, 'UTF-8');
    $mail = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
    $pass = $_POST['pass'];
    $confirmPass = $_POST['confirmPass'];
    $admin = false;

    //suppresion du mot de passe du post
    unset($_POST['pass']);
    unset($_POST['confirmPass']);

    // Appel de la méthode register de la classe User
    $register = $user->register($nom, $prenom, $pseudo, $mail, $pass, $confirmPass, $admin);
    
    if($register["success"]){
        $_SESSION['datas'] = $register['values'];
        header("Location: ../../index.php?page=login");
        exit();
    }else{
        $_SESSION['datas'] = $register['values'];
        var_dump($register);
        header("Location: ../../index.php?page=register");
        exit();
    }

} else {
    $_SESSION['code_erreur'] = '403';
    header("Location: ../../index.php?page=error");
    exit();
}
?>