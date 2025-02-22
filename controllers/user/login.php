<?php
session_start();
define('SECURE_ACCESS', true);
require_once '../../config/connection.php';
require_once '../../models/User.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php?page=login');
    exit();
}

$user = new User($pdo);
$pseudo = $_POST['pseudo'] ?? '';
$password = $_POST['password'] ?? '';

$result = $user->login($pseudo, $password);

if ($result['success']) {
    $_SESSION['message'] = "Connexion réussie";
    $_SESSION['message_type'] = "text-bg-success";
    header('Location: ../../index.php');
} else {
    $_SESSION['message'] = $result['message'];
    $_SESSION['message_type'] = "text-bg-danger";
    $_SESSION['form_data'] = ['pseudo' => $pseudo];
    header('Location: ../../index.php?page=login');
}
exit();