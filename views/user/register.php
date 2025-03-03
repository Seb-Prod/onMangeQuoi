<?php
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../index.php?page=er");
    exit();
}
$styles = ['card'];
include 'includes/header.php';
include  'class/formInput.php';

//Initialisation des inputs
$formInputs = [
    'nom' => new FormInput("nom", "Nom"),
    'prenom' => new FormInput("prenom", "Prenom"),
    'pseudo' => new FormInput("pseudo", "Pseudo"),
    'email' => (new FormInput("email", "Email"))->setType("email"),
    'pass' => (new FormInput("pass", "Mot de passe"))->setType("password"),
    'confirmPass' => (new FormInput("confirmPass", "Confirmer mot de passe"))->setType("password")
];

$messageErreur = [];

if (!empty($_SESSION) && isset($_SESSION['datas'])) {
    $datas = $_SESSION['datas'];
    foreach ($datas as $key => $item) {
        if (isset($formInputs[$key])) {
            if($item['value']){
                $value = $item['message'];
            }else{
                $value = "";
                if($item['message']){
                    $messageErreur[] = $item['message'];
                }
            }
            if(($key === 'pass' && $item['value']) ||  ($key === 'confirmPass' && $item['value'])){

            }else{
                $formInputs[$key]->setValue($value, $item['value']);
            }
            
        }
        
        if($key==='register'){
            $messageErreur[] = $item['message'];
        }
    }
    unset($_SESSION['datas']);
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
                <?php foreach($messageErreur as $message): ?>
                    <p class="messageErreur">*<?php echo $message ?></p>
                <?php endforeach ?>
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