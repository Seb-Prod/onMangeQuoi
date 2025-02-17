<?php
define('SECURE_ACCESS', true);
require_once 'includes/connection.php';
$titrePage = "On Mange Quoi ? User";
require_once 'includes/header.php';
require_once 'includes/functions_html.php';
require_once 'includes/functions_sql.php';
require_once 'includes/functions_users.php';

$pdo = getDBConnection();

$newUser = isset($_GET['newUser']) and $_GET['newUser'] != "" ? true : false;
$titreCard = $newUser ? "Créer un compte" : "Se connecter";
$userIsEmpty = isTableEmptySafe($pdo, 'users');



if ($userIsEmpty['result']) {
    if ($userIsEmpty['data']) {
        $result = addUser($pdo, 'root', 'admin', 'super', 'super.admin@root.com', '1234', 1);
    }
} else {
    header("Location: error.php?code=500");
    exit();
}

?>
<div class="d-flex justify-content-center align-items-center">
    <div class="card text-bg-light mb-3">
        <div class="card-header">
            <h5 class="card-title">
                <i class="fa-solid fa-user"></i>
                <?php echo $titreCard ?>
            </h5>
        </div>
        <div class="card-body">

            <form action="result/newUser.php" method="post">
                <?php
                echo generateInput("Pseudo", "pseudo");
                if ($newUser) {
                    echo generateInput("Nom", "nom", "text", "nom", "is-valid");
                    echo generateInput("Prénom", "prenom");
                    echo generateInput("Email", "email", "email");
                }
                echo generateInput("Mot de passe", "password", "password");
                if ($newUser) {
                    echo generateInput("confimer mot de passe", "confirm", "password");
                }

                ?>
                <input class="btn btn-secondary" type="submit" value="Créer un compte">
                <?php if (!$newUser) : ?>
                    <p><a href="logIn.php?newUser=true" class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover mb-1">Créer un compte</a></p>
                <?php endif ?>
            </form>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php' ?>