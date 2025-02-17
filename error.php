<?php
require_once 'includes/header.php';
// Récupérer le code d'erreur depuis l'URL
$error_code = isset($_GET['code']) ? (int)$_GET['code'] : 500;

// Récupérer le lien de la page précédente
$referer = isset($_GET['referer']) ? urldecode($_GET['referer']) : 'index.php';

// Définir le code de réponse HTTP
http_response_code($error_code);

// Messages personnalisés
$messages = [
    403 => "Accès interdit ! Vous n'avez pas la permission d'accéder à cette page.",
    404 => "Page non trouvée ! L'élément demandé est introuvable.",
    500 => "Erreur interne du serveur ! Un problème est survenu."
];

$message = isset($messages[$error_code]) ? $messages[$error_code] : "Une erreur inconnue s'est produite.";
?>
<div class="d-flex justify-content-center align-items-center">
    <div class="card text-bg-danger mb-3" style="max-width: 18rem;">
        <div class="card-header">Erreur <?php echo $error_code ?></div>
        <div class="card-body">
            <h5 class="card-title"><?php echo $message ?></h5>
            <a href="<?php echo htmlspecialchars($referer); ?>" class="nav-link">
                <i class="fa-solid fa-circle-left"></i>
                Retour
            </a>
            <a href="index.php" class="nav-link">
                <i class="fa-solid fa-house"></i>
                Home
            </a>

        </div>
    </div>
</div>


<?php require_once 'includes/footer.php' ?>