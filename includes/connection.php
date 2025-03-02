<?php
// Empêcher l'accès direct au fichier
if (!defined('SECURE_ACCESS')) {
    session_start();
    include dirname(__DIR__) . '/includes/error.php';
    $_SESSION['code_erreur'] = '403';
    var_dump($_SESSION);
    header("Location: " . $base_url . "index.php?page=error");
    exit();
}

require_once dirname(__DIR__) . '/config/config.php';
// Fonction pour se connecter à la base de données
function getDBConnection()
{
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        include dirname(__DIR__) . '/includes/error.php';
        $_SESSION['code_erreur'] = '500';
        header("Location: " . $base_url . "index.php?page=error");
        exit();
    }
}

$pdo = getDBConnection();
