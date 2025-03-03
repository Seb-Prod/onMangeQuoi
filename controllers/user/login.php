<?php
session_start();
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['pseudo'], $_POST['pass'])
) {
    var_dump($_POST); // Affichage des données POST pour débogage
    define('SECURE_ACCESS', true);
    include '../../models/user.php';
    include '../../includes/connection.php';

    $user = new User($pdo);

    // Récupération et nettoyage des données POST
    $pseudo = htmlspecialchars(trim($_POST['pseudo']), ENT_QUOTES, 'UTF-8');
    $pass = $_POST['pass'];

    //suppresion du mot de passe du post
    unset($_POST['pass']);

    $login = $user->login($pseudo, $pass);
    
    if ($login["success"]) {
        echo 'Ok';
        var_dump($_SESSION);
        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] !=''){
            $_SESSION['level'] = 1;
        }
        if(isset($_SESSION['admin']) && $_SESSION['admin'] === 1){
            $_SESSION['level'] = 2;
        }
        header("Location: ../../index.php?page=user");
        exit();
    } else {
        $_SESSION['datas'] = $login;
        header("Location: ../../index.php?page=login");
        exit();
    }

}
