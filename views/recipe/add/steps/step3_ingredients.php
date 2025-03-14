<?php
// Vérification de l'accès sécurisé pour empêcher l'accès direct au fichier.
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../../index.php?page=er");
    exit();
}

// Configuration de la page
$styles = ['recipe/add/card'];
$scripts = ['recipe/add/addIngredients'];

// Inclusions des fichiers nécessaires.
include 'views/includes/header.php';
include 'class/formInput.php';
include 'includes/connection.php';
include 'models/unit.php';
include 'models/ingredient.php';
include_once __DIR__ . '/../functions/getList.php';
include_once __DIR__ . '/../components/renderItem.php';

// Vérification de l'étape actuelle
if (!isset($_SESSION['step3'])) {
    $_SESSION['step3'] = false;
}

// Récupération des unité de messure
$listUnit = getList($pdo, 'unit');
// Récupération des unité de messure
$listIngredient = getList($pdo, 'ingredient');

// Initialisation des inputs
$inputPortion = (new Input('portion', 'Nombre de Portion'))
    ->settype('number')
    ->setMin(1)
    ->setValue(4);
$inputQts = (new Input('qts', ''))
    ->settype('number')
    ->setMin(0.25)
    ->setStep(0.25)
    ->setValue(1)
    ->setWhithLabel(false)
    ->setId('input-qts')
    ->setRequired(false);
$inputUnit = (new Input('unit', 'Unité de mesure', ''))
    ->setWhithLabel(false)
    ->addList('unit')
    ->setId('input-unit')
    ->setValue('unité')
    ->setRequired(false);
$inputIngredient = (new Input('ingredient', 'Ingrédient', ''))
    ->addButton('ingredient')
    ->setWhithLabel(false)
    ->addList('ingredient')
    ->setRequired(false);

// Initialisation des variables
$errorPortion = '';
$errorQts = '';
$errorUnit = '';
$errorIngredient = '';
$ingredients = [];
$emptyItem = '<span id="emptyItem">Liste vide</span>';

// Gestion des erreurs
if (isset($_SESSION['error']['step3'])) {
    if (isset($_SESSION['error']['step3']['ingredient'])) {
        $errorIngredient = '<span class="myError">' . $_SESSION['error']['step3']['ingredient'] . '</span>';
    }
    if (isset($_SESSION['error']['step3']['qts'])) {
        $errorQts = '<span class="myError">' . $_SESSION['error']['step3']['qts'] . '</span>';
    }
    if (isset($_SESSION['error']['step3']['unit'])) {
        $errorUnit = '<span class="myError">' . $_SESSION['error']['step3']['unit'] . '</span>';
    }
    if (isset($_SESSION['error']['step3']['portion'])) {
        $errorPortion = '<span class="myError">' . $_SESSION['error']['step3']['portion'] . '</span>';
    }
    if (isset($_SESSION['error']['step3']['general'])) {
        unset($_SESSION['recipe']['ingredients']);
        $emptyItem = '<span class="myError">' . $_SESSION['error']['step3']['general'] . '</span>';
    }
}

// Récupération des valeurs
if (isset($_SESSION['recipe']['portion'])) {
    $inputPortion->setValue($_SESSION['recipe']['portion']);
}

if (isset($_SESSION['input_values'])) {
    $inputUnit->setValue($_SESSION['input_values']['unit']);
    $inputQts->setValue($_SESSION['input_values']['qts']);
    $inputIngredient->setValue($_SESSION['input_values']['ingredient']);
    unset($_SESSION['input_values']);
}

if (isset($_SESSION['recipe']['ingredients'])) {
    $emptyItem = '';
    $inputUnit->setValue('');
    $inputQts->setValue('');
    $inputIngredient->setValue('');
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
                        <form action="controllers/recipe/add/step3.php" method="post">
                            <!-- Titre de la card -->
                            <h5 class="label">Ingrédients</h5>
                            <?php
                            echo $errorPortion;
                            echo $inputPortion->render()
                            ?>
                            <hr>
                            <!-- Affichage des ingrédient déjà ajouté -->
                            <div id="recipeIngredients" class="me-1 mb-1">
                                <?php
                                echo $emptyItem;
                                if (isset($_SESSION['recipe']['ingredients'])) {
                                    foreach ($_SESSION['recipe']['ingredients'] as $ingredient) {
                                        echo addIngredient($ingredient);
                                    }
                                }
                                ?>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col">
                                    <?php
                                    echo $errorQts;
                                    echo $inputQts->render();
                                    ?>
                                </div>
                                <div class="col">
                                    <?php
                                    echo $errorUnit;
                                    echo $inputUnit->render()
                                    ?>
                                </div>
                            </div>

                            <?php
                            echo $errorIngredient;
                            echo $inputIngredient->render();
                            ?>

                            <?php echo $listUnit ?>
                            <?php echo $listIngredient ?>
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