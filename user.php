<?php
define('SECURE_ACCESS', true);
require_once 'includes/connection.php';
$titrePage = "Utilisateur";
$script = 'ajoutRecette.js';
require_once 'includes/header.php';


$messageCard = false;

// Si compte est créer avec succes
if (isset($_SESSION['success'])) {
    $messageCard = true;
    $typeMessage = "text-bg-success";
    $message = $_SESSION['success'];
    unset($_SESSION['success']);
}

// Si erreur
if (isset($_SESSION['errors']) && isset($_SESSION['post_data'])) {
    $_POST = $_SESSION['post_data'];
    $messageCard = true;
    $typeMessage = "text-bg-danger";
    $message = $_SESSION['errors'];
    unset($_SESSION['errors']);
    unset($_SESSION['post_data']);
}

// Récupère si c'est la création d'un compte
if (isset($_GET['newUser']) && $_GET['newUser'] === "true") {
    $newUser = (bool)$_GET['newUser'];
} else {
    $newUser = false;
}

function generateInput($label, $name, $type = "text", $defaultValue = "")
{
    $value = $_POST[$name] ?? $defaultValue;
    return '
    <div class="input-group input-group-sm mb-3">
        <span class="input-group-text" id="inputGroup-' . htmlspecialchars($name) . '">' . htmlspecialchars($label) . '</span>
        <input type="' . htmlspecialchars($type) . '" 
               class="form-control" 
               name="' . htmlspecialchars($name) . '" 
               id="' . htmlspecialchars($name) . '"
               value="' . htmlspecialchars($value) . '"
               aria-label="' . htmlspecialchars($label) . '" 
               aria-describedby="inputGroup-' . htmlspecialchars($name) . '"
               required>
    </div>';
}

$titreCard = $newUser ? "Créer un compte" : "Se connecter";

?>
<div class="d-flex flex-column align-items-center">
    <?php if ($messageCard) : ?>
        <div class="shadow-lg card <?php echo $typeMessage ?> mb-3 w-100">
            <h5 class="card-header"><?php echo $message ?></h5>
        </div>
    <?php endif ?>
    <div class="shadow-lg card text-bg-light mb-3 w-100">
        <div class="card-header">
            <h5 class="card-title">
                <i class="fa-solid fa-user"></i>
                <?php echo $titreCard ?>
            </h5>
        </div>
        <div class="card-body">
            <form action="result/user.php" method="post">
                <?php
                echo generateInput("Pseudo", "pseudo", "text");
                if ($newUser) {
                    echo generateInput("Nom", "nom", "text");
                    echo generateInput("Prénom", "prenom", "text");
                    echo generateInput("Email", "email", "email");
                }
                echo generateInput("Mot de passe", "password", "password", "");
                if ($newUser) {
                    echo generateInput("confimer mot de passe", "confirm", "password", "");
                }

                ?>
                <input class="btn btn-secondary" type="submit" value="Valider">
                <?php if (!$newUser) : ?>
                    <p><a href="user.php?newUser=true" class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover mb-1">Créer un compte</a></p>
                <?php else : ?>
                    <p><a href="user.php" class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover mb-1">Se connecter</a></p>
                <?php endif ?>
            </form>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php' ?>