<?php
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../index.php?page=er");
    exit();
}
$styles = ['card'];
include 'includes/header.php';
$messageValid = null;
$messageErreur = null;

if (isset($_SESSION['datas']['register'])) {
    $messageValid = $_SESSION['datas']['register']['message'];
}
if (isset($_SESSION['datas']['message'])) {
    $messageErreur = $_SESSION['datas']['message'];
}


unset($_SESSION['datas']);



?>
<main>
    <div class="card myCard">
        <div class="row">

        </div>
        <div class="card-body">
            <h5 class="myh5">Se connecter</h5>
            <form action="controllers/user/login.php" method="post">
                <?php
                include  'class/formInput.php';
                echo (new FormInput("pseudo", "Pseudo"))->render();
                echo ((new FormInput("pass", "Mot de passe"))->setType('password'))->render();
                ?>
                <p class="messageValid"><?php echo $messageValid ?></p>
                <p class="messageErreur"><?php echo $messageErreur ?></p>
                <div class="row">
                    <div class="col myCol">
                        <a href="?page=register" class="myLink">Créer un compte</a>
                    </div>
                    <div class="col">
                        <input type="submit" class="btn btn-primary myButton" value="Se connecter">
                    </div>
                </div>
            </form>
        </div>

    </div>
</main>
<?php include 'includes/footer.php'; ?>