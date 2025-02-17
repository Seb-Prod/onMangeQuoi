<?php
require_once 'includes/header.php';

// Récupérer les codes d'erreur depuis l'URL
$error_code = isset($_GET['code']) ? (int)$_GET['code'] : 500;

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
        <h5 class="card-header">Erreur <?php echo $error_code ?></h5>
        <div class="card-body">
            <p class="card-title"><?php echo $message ?></p>
            <a href="index.php" class="nav-link">
                <i class="fa-solid fa-house"></i>
                Home
            </a>

        </div>
    </div>
</div>


<?php require_once 'includes/footer.php' ?>