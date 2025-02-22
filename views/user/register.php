<?php
if (!defined('SECURE_ACCESS')) {
    header('Location: ../index.php');
    exit();
}
include 'helpers/form.php';
?>
<div class="d-flex flex-column align-items-center">
    <?php include 'views/components/message-card.php'; ?>
    
    <div class="shadow-lg card text-bg-light mb-3 w-100">
        <div class="card-header">
            <h5 class="card-title">
                <i class="fa-solid fa-user-plus"></i>
                Créer un compte
            </h5>
        </div>
        <div class="card-body">
            <form action="controllers/user/register.php" method="post">
                <?php
                echo generateInput("Pseudo", "pseudo", "text");
                echo generateInput("Nom", "nom", "text");
                echo generateInput("Prénom", "prenom", "text");
                echo generateInput("Email", "email", "email");
                echo generateInput("Mot de passe", "password", "password", "");
                echo generateInput("Confirmer mot de passe", "confirm", "password", "");
                ?>
                <input class="btn btn-secondary" type="submit" value="Créer le compte">
                <p>
                    <a href="?page=login" class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover mb-1">
                        Se connecter
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>