<?php
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../index.php?page=er");
    exit();
}

$styles = ['card'];
include 'includes/header.php';
include 'class/formInput.php';

// Initialisation des inputs
$formInputs = [
    'nom' => new FormInput("nom", "Nom"),
    'prenom' => new FormInput("prenom", "Prenom"),
    'pseudo' => new FormInput("pseudo", "Pseudo"),
    'email' => (new FormInput("email", "Email"))->setType("email"),
    'pass' => (new FormInput("pass", "Mot de passe"))->setType("password"),
    'confirmPass' => (new FormInput("confirmPass", "Confirmer mot de passe"))->setType("password")
];

$messageErreur = [];

if (!empty($_SESSION['datas']) && is_array($_SESSION['datas'])) {
    $datas = $_SESSION['datas'];

    foreach ($formInputs as $key => $input) {
        if (isset($datas[$key])) {
            $item = $datas[$key];

            // Vérifier si une valeur existe
            $value = isset($item['value']) ? $item['value'] : "";

            // Ajouter un message d'erreur si présent
            if (!empty($item['message'])) {
                $messageErreur[] = $item['message'];
            }

            // Ne pas pré-remplir les champs de mot de passe pour des raisons de sécurité
            if ($key !== 'pass' && $key !== 'confirmPass') {
                $input->setValue($value);
            }
        }
    }

    // Ajouter un message général d'erreur si présent
    if (isset($datas['register']['message'])) {
        $messageErreur[] = $datas['register']['message'];
    }

    // Nettoyage des données en session après affichage
    unset($_SESSION['datas']);
}
?>

<main>
    <div class="card myCard">
        <div class="card-body">
            <h5 class="myh5">Créer un compte</h5>

            <?php if (!empty($messageErreur)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($messageErreur as $message): ?>
                            <li><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="controllers/user/register.php" method="post">
                <?php
                foreach ($formInputs as $input) {
                    echo $input->render();
                }
                ?>
                <div class="row">
                    <div class="col myCol">
                        <a href="?page=login" class="myLink">Se Connecter</a>
                    </div>
                    <div class="col">
                        <input type="submit" class="btn btn-primary myButton" value="Créer le compte">
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>