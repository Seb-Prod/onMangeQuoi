<?php
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../index.php?page=er");
    exit();
}

// Vérifier si une session est active avant de la détruire
if (session_status() === PHP_SESSION_ACTIVE) {
    session_unset();
    session_destroy();
}

$styles = ['card'];
include 'includes/header.php';



$messageValid = "Vous avez été déconnecté avec succès.";
?>
<main>
    <div class="card myCard">
        <div class="card-body">
            <h5 class="myh5">Déconnexion</h5>
            <p class="messageValid mb-4"><?php echo $messageValid; ?></p>
            <div class="d-flex flex-column align-items-center gap-3 mt-3">
                <a href="?page=login" class="myLink">Se reconnecter</a>
                <a href="index.php" class="btn btn-primary myButton">Retour à l'accueil</a>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>