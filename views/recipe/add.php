<?php
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../index.php?page=er");
    exit();
}
$styles = ['card'];
$scripts = ['recipe'];
include 'includes/header.php';
include  'class/formInput.php';

$formInputsInfoRecipe = [
    'nom' => new FormInput("nom", "Nom"),
    'preparation' => (new FormInput("preparation", "Préparation"))
        ->settype("number")
        ->addLabelUnit("min")
        ->setMin(0)
        ->setStep(1),
    'repos' => (new FormInput("repos", "Repos"))
        ->setType("number")
        ->addLabelUnit("min")
        ->setMin(0)
        ->setStep(1),
    'cuisson' => (new FormInput("cuisson", "Cuisson"))
        ->setType("number")
        ->addLabelUnit("min")
        ->setMin(0)
        ->setStep(1),
];

function renderTripleInput() {
    return <<<HTML
    <div class="input-group mb-3">
        <input type="number" class="form-control w-sm-33" placeholder="Qts">
        <input type="text" class="form-control" placeholder="unité">
        <input type="text" class="form-control w-50" placeholder="ingredient">
    </div>
    HTML;
}

?>
<main>
    <div class="card myCard">
        <div class="row">

        </div>
        <div class="card-body">
            <form action="controllers/user/login.php" method="post">
                <h5 class="myh5">Ajouter la recette</h5>
                <?php
                foreach ($formInputsInfoRecipe as $input) {
                    echo $input->render();
                }
                ?>
                <hr>
                <h5 class="myh5">Liste des ingrédients</h5>
                <div id="ingredients-container">
                    <?php echo renderTripleInput(); ?>
                </div>
                <div class="mb-3">
                    <button type="button" id="add-ingredient" class="btn btn-success">Ajouter un ingrédient</button>
                </div>
                <input type="submit" class="btn btn-primary myButton" value="Ajouter">
            </form>
        </div>

    </div>
</main>
<?php include 'includes/footer.php'; ?>