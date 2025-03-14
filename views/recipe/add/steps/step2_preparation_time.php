<?php
// Vérification de l'accès sécurisé pour empêcher l'accès direct au fichier.
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../../index.php?page=er");
    exit();
}

// Configuration de la page
$styles = ['recipe/add/card'];
$scripts = [];

// Inclusions des fichiers nécessaires.
include 'views/includes/header.php';
include 'class/formInput.php';
include 'includes/connection.php';
include 'models/recipeType.php';

// Vérification de l'étape actuelle
if (!isset($_SESSION['step2'])) {
    $_SESSION['step2'] = false;
}

// Initialisations des inputs
$inputPreparation = (new Input('preparation', 'Préparation'))
        ->settype('time')
        ->setRequired(false)
        ->setValue('00:00');
$inputRepos = (new Input('repos', 'Repos'))
        ->settype('time')
        ->setRequired(false)
        ->setValue('00:00');
$inputCuisson = (new Input('cuisson', 'Cuisson'))
        ->settype('time')
        ->setRequired(false)
        ->setValue('00:00');

// Récupération des données de session si elles existent
if(isset($_SESSION['recipe']['preparation']) && $_SESSION['recipe']['preparation'] !=''){
    $inputPreparation->setTime($_SESSION['recipe']['preparation']);
}

if(isset($_SESSION['recipe']['repos']) && $_SESSION['recipe']['repos'] !=''){
    $inputRepos->setTime($_SESSION['recipe']['repos']);
}

if(isset($_SESSION['recipe']['cuisson']) && $_SESSION['recipe']['cuisson'] !=''){
    $inputCuisson->setTime($_SESSION['recipe']['cuisson']);
}
?>

<main>
    <div class="container mt-3 mb-3">
        <div class="row">
            <!-- Progression -->
            <?php include 'views/recipe/add/step_status.php' ?>

            <!-- Aperçu Recette -->
            <?php include 'views/recipe/add/recipe_card.php' ?>

            <div class="col-12 col-md-6">
                <div class="card myCard">
                    <div class="card-body">
                        <form action="controllers/recipe/add/step2.php" method="post">
                            <!-- Titre de la card -->
                            <h5 class="label">Temps de préparation</h5>
                            <?php echo $inputPreparation->render() ?>
                            <?php echo $inputRepos->render() ?>
                            <?php echo $inputCuisson->render() ?>
                            <!-- Bouton de validation et de retour -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="index.php?page=recipestep1" class="btn btn-secondary myButton">Retour</a>
                                        <input type="submit" class="btn btn-primary myButton" value="Suivant">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>