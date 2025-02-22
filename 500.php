<?php
// Connection à la basse de donnée
define('SECURE_ACCESS', true);
require_once 'includes/header.php';
// Nettoyage et destruction de la session
$_SESSION = array();

// Destruction du cookie de session si présent
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destruction de la session
session_destroy();

// header
$nav = new Navigation('Erreur 505 - ' . SITE_TITLE);
$nav->render();
?>

<div class="d-flex flex-column align-items-center">
    <div class="shadow-lg card text-center p-4 shadow-lg" style="width: 22rem;">
        <div class="card-body">
            <h1 class="display-3 text-danger fw-bold">505</h1>
            <h5 class="card-title text-secondary">Problème interne du serveur</h5>
            <p class="card-text">'Erreur de connexion à la base de données. Veuillez réessayer plus tard.'</p>
            <a href="index.php" class="btn btn-primary mt-3">
                <i class="fa-solid fa-house"></i> Retour à l'accueil
            </a>
        </div>
    </div>
</div>

<?php
// footer
require_once 'includes/footer.php';
?>

