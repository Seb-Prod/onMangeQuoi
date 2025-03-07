<?php
// Vérification de l'accès sécurisé pour empêcher l'accès direct au fichier.
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../../index.php?page=er");
    exit();
}

$styles = ['card'];
$scripts = ['recipeType'];

// Inclusions des fichiers nécessaires.
include 'includes/header.php';
include  'class/formInput.php';
include 'includes/connection.php';
include 'models/recipeType.php';

// Création d'un champ de saisie pour le nom de la recette.
$inputName = (new Input('name', 'Nom de la recette'));

// Création d'un champ de saisie pour la sélection des types de plats, avec une datalist et un bouton d'ajout.
$inputType = (new Input('types[]', 'Sélectionner le type de plat'))->addList('recipeType')->addButton('type', '+')->setRequired(false)->render();

// Récupération des types de plats depuis la base de données.
$recipeType = (new RecipeType($pdo))->getTypesSimple();

// Vérification du succès de la récupération des types de plats.
if ($recipeType['success']) {
    // Création de la datalist HTML avec les types de plats récupérés.
    $dataList = (new FormDataList("recipeType", $recipeType['datas']))->render();
} else {
    // Si la récupération échoue, la datalist est vide.
    $dataList = '';
}

// Si le nom du plat existe déjà on recharge la page avec le nom du plat déjà saisie et le type de plat
var_dump($_SESSION);
if (isset($_SESSION['message'])) {
    $inputName = $inputName->setErrorMessage($_SESSION['message'])->setValue($_SESSION['nom_plat']);
    unset($_SESSION['message']);
    unset($_SESSION['nom_plat']);
}

function addType($type): string
{
    $html = <<<HTML
        <div class="type-row">
            <span class="btn btn-sm btn-primary position-relative green">
                {$type}
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <button type="button" class="btn btn-danger btn-sm remove-type p-0" style="font-size: 0.7rem; line-height: 1;">
                        X
                    </button>
                </span>
                <input type="hidden" name="types[]" value="{$type}">
            </span>
        </div>
        HTML;

    return $html;
}


?>
<main>
    <div class="card myCard">
        <div class="card-body">
            <form action="controllers/recipe/add/step1.php" method="post">
                <h5 class="myh5">Ajouter une recette</h5>
                <?php echo $inputName->render() ?>
                <hr>
                <h5 class="myh5">Type(s) de plas</h5>
                <div id="recipeTypes">
                    <?php
                    // Vérifier et afficher les types de plats déjà sélectionnés
                    if (isset($_SESSION['types_plat']) && is_array($_SESSION['types_plat'])) {
                        foreach ($_SESSION['types_plat'] as $type) {
                            echo addType(htmlspecialchars($type));
                        }
                        unset($_SESSION['types_plat']);
                    }

                    ?>
                </div>
                <?php echo $inputType ?>
                <?php echo  $dataList ?>
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
</main>
<?php
// Inclusion du fichier de pied de page (footer).
include 'includes/footer.php';
?>