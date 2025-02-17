<?php
// Empêcher l'accès direct au fichier
if (!defined('SECURE_ACCESS')) {
    $referer = isset($_SERVER['HTTP_REFERER']) ? urlencode($_SERVER['HTTP_REFERER']) : 'index.php';
    header("Location: error.php?code=403&referer=$referer");
    exit();
}

require_once dirname(__DIR__). '/config.php';
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
        $referer = isset($_SERVER['HTTP_REFERER']) ? urlencode($_SERVER['HTTP_REFERER']) : 'index.php';
        header("Location: error.php?code=500&referer=$referer");
        exit();
    }
}

$pdo = getDBConnection();
