<?php
// Vérification de l'accès sécurisé pour empêcher l'accès direct au fichier.
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../../index.php?page=er");
    exit();
}

$styles = ['card'];
$scripts = [''];

// Inclusions des fichiers nécessaires.
include 'includes/header.php';
include 'class/formInput.php';
include 'includes/connection.php';


var_dump($_SESSION);

?>
<main>
    <div class="container">
        <div class="row">
            <?php include 'views/recipe/add/recipe_card.php' ?>
            <div class="col-12 col-md-6">
                <div class="card myCard">
                    <div class="card-body">
                        <form action="controllers/recipe/addName.php" method="post">
                            <h5 class="myh5">Temps de préparations</h5>
                            <hr>
                            <h5 class="myh5">Type(s) de plas</h5>
                            <div class="row justify-content-between">
                                <div class="col-auto">
                                </div>
                                <div class="col-auto">
                                    <input type="submit" class="btn btn-primary myButton" value="Suivant">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
// Inclusion du fichier de pied de page (footer).
include 'includes/footer.php';
?>