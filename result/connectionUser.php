<?php
session_start();
define('SECURE_ACCESS', true);
require_once '../includes/connection.php';
require_once '../includes/functions.php';
require_once '../includes/functions_users.php';

monVarDump($_POST);

if (checkRequiredFields(['pseudo', 'password'], $_POST)) {
    $pseudo = cleanInput($_POST['pseudo'] ?? '');
    $pass = cleanInput($_POST['password'] ?? '');

    $result = userConect($pdo, $pseudo, $pass);
    monVarDump($result);

    if ($result['result']) {
        $_SESSION['nom'] = $result['data']['nom'];
        $_SESSION['prenom'] = $result['data']['prenom'];
        $_SESSION['pseudo'] = $result['data']['pseudo'];
        $_SESSION['admin'] = $result['data']['admin'];
        header("Location: ../index.php");
        exit();
    } else {
        if ($result['error']['code'] == "user" || $result['error']['code'] == "pass") {
            $_SESSION['newUser_pseudo'] = '';
            $_SESSION['pass'] = '';
            header("Location: ../logIn.php");
            exit();
        }
    }
}
