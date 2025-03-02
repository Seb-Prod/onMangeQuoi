<?php
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../index.php?page=er");
    exit();
}
$styles = ['card'];
include 'includes/header.php';
include  'class/formInput.php';

//Variable pour les testes
$debug =
    [
        'nom' => ['value' => 'Drillaud', 'message' => true],
        'prenom' => ['value' => 'Sébastien', 'message' => true],
        'mail' => ['value' => 'sebastien.drillaud@gmail.com', 'message' => true],
        'pseudo' => ['value' => 'sebT56', 'message' => true],
        'pass' => ['value' => '1234', 'message' => false],
        'confirmPass' => ['value' => '1234', 'message' => false]
    ];
$_POST['data'] = $debug;

//Initialisation des inputs
$formInputs = [
    'nom' => new FormInput("nom", "Nom"),
    'prenom' => new FormInput("prenom", "Prenom"),
    'pseudo' => new FormInput("pseudo", "Pseudo"),
    'mail' => (new FormInput("mail", "Email"))->setType("email"),
    'pass' => (new FormInput("pass", "Mot de passe"))->setType("password"),
    'confirmPass' => (new FormInput("confirmPass", "Confirmer mot de passe"))->setType("password")
];



if (!empty($_POST) && isset($_POST['data'])) {
    foreach ($_POST['data'] as $key => $item) {
        if (isset($formInputs[$key])) {
            $formInputs[$key]->setValue($item['value'], $item['message']);
        }
    }
}

?>
<main>
    <div class="card myCard">
        <div class="card-body">
            <h5 class="myh5">Créer un compte</h5>
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