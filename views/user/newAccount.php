<?php
$styles = ['card'];
include 'includes/header.php';
include  'class/form.php';
$_POST['data'] =
    [
        'nom' => ['value' => 'Drillaud', 'message' => true],
        'prenom' => ['value' => 'Sébastien', 'message' => true],
        'mail' => ['value' => 'sebastien.drillaud@gmail.com', 'message' => true],
        'pseudo' => ['value' => 'sebT56', 'message' => false],
        'pass' => ['value' => '', 'message' => false],
    ];

//Initialisation des inputs
$formInputs = [
    'nom' => new FormInput("nom", "Nom"),
    'prenom' => new FormInput("prenom", "Prenom"),
    'pseudo' => new FormInput("pseudo", "Pseudo"),
    'mail' => (new FormInput("mail", "Email"))->setType("mail"),
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
            <form action="controllers/user/newAccount.php" method="post">
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
                        <input type="submit" class="btn btn-primary myButton" value="Créer un compte">
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>