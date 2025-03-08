<?php
// Vérification de l'accès sécurisé pour empêcher l'accès direct au fichier.
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../../index.php?page=er");
    exit();
}

$styles = ['recipe/add/card'];
$scripts = ['recipe/add/addElements', 'recipe/add/addIngredients'];

// Inclusions des fichiers nécessaires.
include 'includes/header.php';
include 'class/formInput.php';
include 'includes/connection.php';
include 'models/unit.php';
include 'models/ingredient.php';

// Etape actuelle
if (!isset($_SESSION['step3'])) {
    $_SESSION['step3'] = false;
}

// Récupération des listes d'ingrédients et d'unités
$ingredientModel = new Ingredient($pdo);
$unitModel = new Unit($pdo);
$ListIngredient = $ingredientModel->get();
$ListUnit = $unitModel->get();

// Création des datalists
$dataListIngredient = $ListIngredient['success'] ? (new FormDataList("dataIngredients", $ListIngredient['datas']))->render() : '';
$dataListUnit = $ListUnit['success'] ? (new FormDataList("dataUnits", $ListUnit['datas']))->render() : '';

// Initialisation des inputs
$inputPortion = (new Input('portion', 'Nombre de Portion(s)'))
    ->settype('number')
    ->setMin(1);

$inputQts = (new Input('qts[]', 'Quantité'))
    ->settype('number')
    ->setMin(1)
    ->setId('qts')
    ->setRequired(false);

$inputUnit = (new Input('unit[]', 'Séléctionner une unité'))
    ->setId('unit')
    ->addList('dataUnits')
    ->setRequired(false);

$inputIngredient = (new Input('ingredient[]', 'Séléctionner un ingrédient'))
    ->addList('dataIngredients')
    ->addButton('ingredient', '+')
    ->setRequired(false)
    ->render();

function ingredient(int $qts, string $unit, string $name){
    return <<<HTML
    <div class="type-row">
        <span class="btn btn-sm btn-primary position-relative green">
            {$name} ({$qts} {$unit})
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <button type="button" class="btn btn-danger btn-sm remove-type p-0" style="font-size: 0.7rem; line-height: 1;">
                    X
                </button>
            </span>
            <input type="hidden" name="ingrdient[]" value="{$name}">
        </span>
    </div>
HTML;
}
?>

<main>
    <div class="container container mt-3 mb-3">
        <div class="row">
            <!-- Progression -->
            <?php include 'views/recipe/add/step_status.php' ?>
            
            <!-- Aperçu Recette -->
            <?php include 'views/recipe/add/recipe_card.php' ?>
            
            <div class="col-12 col-md-6">
                <div class="card myCard">
                    <div class="card-body">
                        <form action="controllers/recipe/add/step3.php" method="post">
                            <h5 class="myh5">Ingrédients pour :</h5>
                            <!-- Input pour le nombre de portion -->
                            <?php echo $inputPortion->render() ?>
                            <hr>
                            <!-- Affichage des type de plat déjà ajouté -->
                            <div id="recipeIngredients" class="me-1 mb-1">
                            </div>
                            <hr>
                            <!-- Ajout d'un ingédient -->
                            <div class="row">
                                <div class="col-4">
                                    <?php echo $inputQts->render(); ?>
                                </div>
                                <div class="col-8">
                                    <?php echo $inputUnit->render(); ?>
                                </div>
                            </div>
                            
                            <?php 
                            echo $inputIngredient;
                            echo $dataListIngredient;
                            echo $dataListUnit;
                            ?>
                            <!-- Bouton de validation et de retour -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="index.php?page=recipestep2" class="btn btn-secondary myButton">Retour</a>
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