<?php
session_start();
define('SECURE_ACCESS', true);
require_once '../../config/connection.php';
require_once '../../models/User.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php?page=register');
    exit();
}

$user = new User($pdo);
$formData = [
    'pseudo' => $_POST['pseudo'] ?? '',
    'nom' => $_POST['nom'] ?? '',
    'prenom' => $_POST['prenom'] ?? '',
    'email' => $_POST['email'] ?? '',
    'password' => $_POST['password'] ?? '',
    'confirm' => $_POST['confirm'] ?? ''
];

// Validation du mot de passe
if ($formData['password'] !== $formData['confirm']) {
    $_SESSION['message'] = "Les mots de passe ne correspondent pas";
    $_SESSION['message_type'] = "text-bg-danger";
    $_SESSION['form_data'] = array_diff_key($formData, ['password' => '', 'confirm' => '']);
    header('Location: ../../index.php?page=register');
    exit();
}

$result = $user->createUser(
    $formData['pseudo'],
    $formData['nom'],
    $formData['prenom'],
    $formData['email'],
    $formData['password']
);

if ($result['success']) {
    $_SESSION['message'] = $result['message'];
    $_SESSION['message_type'] = "text-bg-success";
    header('Location: ../../index.php?page=login');
} else {
    $_SESSION['message'] = $result['message'];
    $_SESSION['message_type'] = "text-bg-danger";
    if (!empty($result['errors'])) {
        $_SESSION['validation_errors'] = $result['errors'];
    }
    $_SESSION['form_data'] = array_diff_key($formData, ['password' => '', 'confirm' => '']);
    header('Location: ../../index.php?page=register');
}
exit();