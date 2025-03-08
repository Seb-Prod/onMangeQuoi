<?php
// Vérification de l'accès sécurisé pour empêcher l'accès direct au fichier.
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../../index.php?page=er");
    exit();
}

$styles = ['recipe/add/card'];
$scripts = [''];

// Inclusions des fichiers nécessaires.
include 'includes/header.php';
include 'class/formInput.php';
include 'includes/connection.php';

// Etape actuel
if(!isset($_SESSION['step3'])){
    $_SESSION['step3'] = false;
}

$inputPortion = (new Input('portion', 'Nombre de Portion(s)'))->settype('number')->setMin(1);


?>
<main>
    <div class="container">
        <div class="row">
            <!-- Progression -->
            <?php include 'views/recipe/add/step_status.php' ?>
            <!-- Aperçs Recette -->
            <?php include 'views/recipe/add/recipe_card.php' ?>
            <div class="col-12 col-md-6">
                <div class="card myCard">
                    <div class="card-body">
                        <form action="controllers/recipe/add/step3.php" method="post">
                            <h5 class="myh5">Ingrédients pour :</h5>
                            <?php echo $inputPortion->render() ?>
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