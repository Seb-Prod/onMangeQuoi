<?php
// Vérification de l'accès sécurisé pour empêcher l'accès direct au fichier.
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../../index.php?page=er");
    exit();
}

// Configuration de la page
$styles = ['recipe/add/card'];
$scripts = ['recipe/add/addTypes'];

// Inclusions des fichiers nécessaires.
include 'views/includes/header.php';
include 'class/formInput.php';
include 'includes/connection.php';
include 'models/recipeType.php';
include_once __DIR__ . '/../functions/getList.php';
include_once __DIR__ . '/../components/renderItem.php';

// Initialisation de l'étape
if (!isset($_SESSION['step1'])) {
    $_SESSION['step1'] = false;
}

// Récupération des types de plats
$listType = getList($pdo, 'recipeType');

// Initialisation des inputs
$inputName = new Input('name', 'Nom de la recette', 'Saisir le nom de la recette');
$inputType = (new Input('type', 'Type de plat', 'Sélectionner le type de plat'))
    ->addList('recipeType')
    ->addButton('type')
    ->setRequired(false)
    ->render();

// Initialisation des variables
$errorName = '';
$types = [];
$emptyItem = '<span id="emptyItem">Aucun type de choisie</span>';

// Gestion des erreurs
if (isset($_SESSION['error']['step1'])) {
    $inputName->setValue($_SESSION['error']['step1']['nom_plat']);
    $errorName = '<span class="myError">' . $_SESSION['error']['step1']['message'] . '</span>';
    unset($_SESSION['error']['step1']);
}

// Récupération des données de session si elles existent
if (isset($_SESSION['recipe']['nom_plat']) && $_SESSION['recipe']['nom_plat'] != '') {
    $inputName->setValue($_SESSION['recipe']['nom_plat']);
}

if (isset($_SESSION['recipe']['types_plat']) && is_array($_SESSION['recipe']['types_plat']) && !empty($_SESSION['recipe']['types_plat'])) {
    $types = $_SESSION['recipe']['types_plat'];
    $emptyItem = '';
}


?>

<main>
    <div class="container mt-3 mb-3">
        <div class="row">
            <!-- Progression -->
            <?php include 'views/recipe/add/step_status.php' ?>
            <!-- Aperçu Recette -->
            <?php include 'views/recipe/add/recipe_card.php' ?>
            <!-- Formulaire -->
            <div class="col-12 col-md-6">
                <div class="card myCard">
                    <div class="card-body">
                        <form action="controllers/recipe/add/step1.php" method="post">
                            <!-- Titre de la card -->
                            <h5 class="label">Informations sur la recette</h5>
                            <!-- Input de saisie du nom -->
                            <?php echo $inputName->render() ?>
                            <?php echo $errorName ?>
                            <hr>
                            <!-- Affichage des type de plat déjà ajouté -->
                            <div id="recipeTypes" class="me-1 mb-1">
                                <?php
                                echo $emptyItem;
                                foreach ($types as $type) {
                                    echo addType($type);
                                }
                                ?>
                            </div>
                            <hr>
                            <!-- Input saisie du type de plat -->
                            <?php echo $inputType ?>
                            <!-- Ajout des option (liste des plats déjà en bdd) -->
                            <?php echo $listType ?>

                            <div class="row justify-content-end mt-3">
                                <div class="col-auto">
                                    <!-- Soumission du formulaire -->
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

<?php include 'views/includes/footer.php'; ?>