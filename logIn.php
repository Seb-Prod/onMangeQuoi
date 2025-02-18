<?php
define('SECURE_ACCESS', true);
require_once 'includes/connection.php';
$titrePage = "On Mange Quoi ? User";
require_once 'includes/header.php';
require_once 'includes/functions_html.php';
require_once 'includes/functions_sql.php';
require_once 'includes/functions_users.php';
require_once 'includes/functions.php';

$pdo = getDBConnection();
$userIsEmpty = isTableEmptySafe($pdo, 'users');
$newUser = false;
$nomValid = "";
$prenomValid = "";
$emailValid = "";
$speudoValid = "";
$nom = "";
$prenom = "";
$email = "";
$pseudo = "";
$passValid = "";

monVarDump($_SESSION);

// Récupère si c'est la création d'un compte
if (isset($_GET['newUser']) && $_GET['newUser'] === "true") {
    $newUser = (bool)$_GET['newUser'];
}

// Récupère le nom, prénom et mail si retour au formulaire
if (checkRequiredFields(['newUser_nom', 'newUser_prenom', 'newUser_email'], $_SESSION)) {
    $nom = cleanInput($_SESSION['newUser_nom'] ?? '');
    $prenom = cleanInput($_SESSION['newUser_prenom'] ?? '');
    $email = filter_var($_SESSION['newUser_email'] ?? '', FILTER_SANITIZE_EMAIL);
    $nomValid = $nom != '' ? "is-valid" : "is-invalid";
    $prenomValid = $prenom != '' ? "is-valid" : "is-invalid";
    $emailValid = $email != '' ? "is-valid" : "is-invalid";
}

// Récupère le pseudo si retour au formulaire et que selui-ci soit conforme
if (isset($_SESSION['newUser_pseudo'])) {
    $pseudo = cleanInput($_SESSION['newUser_pseudo'] ?? '');
    $speudoValid = $pseudo != '' ? "is-valid" : "is-invalid";
}

// Récupère si le mot de passe est conforme
if (isset($_SESSION['pass'])) {
    $pass = "";
    if ($_SESSION['pass'] === "true") {
        $passValid = "";
    } else {
        $passValid = "is-invalid";
    }
}

// Récupère si le compte est bien créé
if (isset($_SESSION['newUser']) && $_SESSION['newUser'] === 'true') {
    $newUserOk = true;
} else {
    $newUserOk = false;
}

// raz les variables de session utilisé
razVarSessionUser();

// Création d'un compte admin si aucun compte n'existe
if ($userIsEmpty['result']) {
    if ($userIsEmpty['data']) {
        $result = addUser($pdo, 'root', 'admin', 'super', 'super.admin@root.com', '1234', 1);
    }
} else {
    header("Location: error.php?code=500");
    exit();
}

$titreCard = $newUser ? "Créer un compte" : "Se connecter";
$linkUser = $newUser ? "result/newUser.php" : "result/connectionUser.php";

?>
<div class="d-flex justify-content-center align-items-center">
    <div class="shadow-lg card text-bg-light mb-3">
        <div class="card-header">
            <h5 class="card-title">
                <i class="fa-solid fa-user"></i>
                <?php echo $titreCard ?>
            </h5>
        </div>
        <?php if ($newUserOk) : ?>
            <h5 class="text-center my-3">Votre compte a été créé</h5>
        <?php endif ?>
        <div class="card-body">
            <form action="<?php echo $linkUser ?>" method="post">
                <?php
                echo generateInput("Pseudo", "pseudo", "text", $pseudo, $speudoValid);
                if ($newUser) {
                    echo generateInput("Nom", "nom", "text", $nom, $nomValid);
                    echo generateInput("Prénom", "prenom", "text", $prenom, $prenomValid);
                    echo generateInput("Email", "email", "email", $email, $emailValid);
                }
                echo generateInput("Mot de passe", "password", "password", "", $passValid);
                if ($newUser) {
                    echo generateInput("confimer mot de passe", "confirm", "password", "", $passValid);
                }

                ?>
                <input class="btn btn-secondary" type="submit" value="Valider">
                <?php if (!$newUser) : ?>
                    <p><a href="logIn.php?newUser=true" class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover mb-1">Créer un compte</a></p>
                <?php endif ?>
            </form>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php' ?>