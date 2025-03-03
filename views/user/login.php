<?php
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../index.php?page=er");
    exit();
}
$styles = ['card'];
include 'includes/header.php';
$message = null;
if (isset($_SESSION['datas'])) {
    $message = $_SESSION['datas']['register']['message'];
}
unset($_SESSION['datas']);
?>
<main>
    <div class="card myCard">
        <div class="row">

        </div>
        <div class="card-body">
            <h5 class="myh5">Se connecter</h5>
            <form action="" method="post">
                <?php
                include  'class/formInput.php';
                echo (new FormInput("pseudo", "Pseudo"))->render();
                echo ((new FormInput("pass", "Mot de passe"))->setType('password'))->render();
                ?>
                <p class="messageValid"><?php echo $message ?></p>
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