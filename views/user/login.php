<?php
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../index.php?page=er");
    exit();
}

$styles = ['card'];
include 'includes/header.php';

$messageValid = isset($_SESSION['datas']['register']) ? $_SESSION['datas']['register']['message'] : null;
$messageErreur = isset($_SESSION['datas']['message']) ? $_SESSION['datas']['message'] : null;

// Suppression des messages de la session après utilisation
unset($_SESSION['datas']['register']);
unset($_SESSION['datas']['message']);
?>

<main>
    <div class="card myCard">
        <div class="row">
        </div>
        <div class="card-body">
            <h5 class="myh5">Se connecter</h5>
            <form action="controllers/user/login.php" method="post">
                <?php
                include 'class/formInput.php';
                echo (new FormInput("pseudo", "Pseudo"))->render();
                echo ((new FormInput("pass", "Mot de passe"))->setType('password'))->render();
                ?>
                <p class="messageValid"><?php echo $messageValid ? $messageValid : ''; ?></p>
                <p class="messageErreur"><?php echo $messageErreur ? $messageErreur : ''; ?></p>
                <div class="row">
                    <div class="col myCol">
                        <a href="index.php?page=register" class="myLink">Créer un compte</a>
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