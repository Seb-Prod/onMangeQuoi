<?php
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../index.php?page=er");
    exit();
}
$styles = ['card'];
include 'includes/header.php';

?>
<main>
    <div class="card myCard">
        <div class="card-body">
            <h5 class="myh5">Se connecter</h5>
            <form action="" method="post">
                <?php
                include  'class/formInput.php';
                echo (new FormInput("pseudo", "Pseudo"))->render();
                echo ((new FormInput("pass", "Mot de passe"))->setType('password'))->render();
                ?>
                <div class="row">
                    <div class="col myCol">
                        <a href="?page=register" class="myLink">Créer un compte</a>
                    </div>
                    <div class="col">
                        <a href="#" class="btn btn-primary myButton">Valider</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>