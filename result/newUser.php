<?php
session_start();
define('SECURE_ACCESS', true);
require_once '../includes/connection.php';
require_once '../includes/functions.php';
require_once '../includes/functions_users.php';

function razSession()
{
    unset($_SESSION["newUser_pseudo"]);
    unset($_SESSION["newUser_nom"]);
    unset($_SESSION["newUser_prenom"]);
    unset($_SESSION["newUser_email"]);
    unset($_SESSION["pass"]);
}

if (checkRequiredFields(['pseudo', 'nom', 'prenom', 'email', 'password', 'confirm'], $_POST)) {
    $_SESSION["newUser_pseudo"] = cleanInput($_POST['pseudo'] ?? '');
    $_SESSION["newUser_nom"] = cleanInput($_POST['nom'] ?? '');
    $_SESSION["newUser_prenom"] = cleanInput($_POST['prenom'] ?? '');
    $_SESSION["newUser_email"] = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $pass = filter_var($_POST['password'] ?? '');
    $passConfirm = filter_var($_POST['confirm'] ?? '');

    if (($_POST['password'] === $_POST['confirm']) && $pass != "") {
        $result = addUser($pdo, $_SESSION["newUser_pseudo"], $_SESSION["newUser_nom"], $_SESSION["newUser_prenom"], $_SESSION["newUser_email"], $pass, 0);
        if ($result['result']) {
            razSession();
            $_SESSION["newUser"] = "true";
            header("Location: ../logIn.php");
            exit();
        } else {
            if ($result["error"]["code"] == "23000") {
                $_SESSION["newUser_pseudo"] = "";
                $_SESSION["pass"] = "true";
                header("Location: ../logIn.php?newUser=true");
                exit();
            } else {
                header("Location: ../logIn.php?newUser=true");
                exit();
            }
        }
    } else {
        $_SESSION["pass"] = "false";
        header("Location: ../logIn.php?newUser=true");
        exit();
    }
}
