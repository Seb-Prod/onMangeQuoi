<?php
if (!defined('SECURE_ACCESS')) {
    header('Location: ../index.php');
    exit();
}

if (isset($_COOKIE['temp_message']) && isset($_COOKIE['temp_message_type'])) {
    $_SESSION['message'] = $_COOKIE['temp_message'];
    $_SESSION['message_type'] = $_COOKIE['temp_message_type'];
    
    // Suppression des cookies
    setcookie('temp_message', '', time() - 3600, '/');
    setcookie('temp_message_type', '', time() - 3600, '/');
}
include 'helpers/form.php';
?>
<div class="d-flex flex-column align-items-center">
    <?php include 'views/components/message-card.php'; ?>
    <div class="shawos-lg card text-bg-light mb-3 w-100">
        <div class="card-header">
            <h5 class="card-title">
                <i class="fa-solid fa-user"></i>
                Se connecter
            </h5>
        </div>
        <div class="card-body">
            <form action="controllers/user/login.php" method="post">
                <?php
                echo generateInput("Pseudo", "pseudo", "text");
                echo generateInput("Mot de passe", "password", "password", "");
                ?>
                <input class="btn btn-secondary" type="submit" value="Connexion">
                <p>
                    <a href="?page=register" class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover mb-1">
                        Créer un compte
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>