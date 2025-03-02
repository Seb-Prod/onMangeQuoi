<?php
// Empêcher l'accès direct au fichier
if (!defined('SECURE_ACCESS')) {
    header("Location: ../index.php?page=er");
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
        header("Location: 500.php");
        exit();
    }
}

$pdo = getDBConnection();
