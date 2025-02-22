// controllers/user/logout.php
<?php
// La session est déjà démarrée dans le header, pas besoin de la redémarrer

// Sauvegarde du message temporaire
$message = "Déconnexion réussie";
$messageType = "text-bg-success";

// Nettoyage et destruction de la session
$_SESSION = array();

// Destruction du cookie de session si présent
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destruction de la session
session_destroy();

// Stockage temporaire du message dans un cookie
setcookie('temp_message', $message, 0, '/');
setcookie('temp_message_type', $messageType, 0, '/');

// Redirection vers la page de connexion en utilisant un chemin absolu depuis la racine
header('Location: /onmangequoi/index.php?page=login');  // Ajustez le chemin selon votre configuration
exit();