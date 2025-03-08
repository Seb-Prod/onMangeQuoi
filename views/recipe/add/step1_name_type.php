<?php
/**
 * Fichier : step1_name_type.php
 * Objectif : Formulaire d'ajout de recette - Étape 1 : Le nom de la recette et du type (pour les filtres).
 * Description : Ce fichier gère l'affichage et le traitement du formulaire pour ajouter les le nom et les filtres d'une recette.
 * Dépendances :
 * - class/formInput.php : Classe pour la création de champs de formulaire.
 * - includes/connection.php : Connexion à la base de données.
 * - models/recipeType.php : Modèle pour la gestion des types.
 * - views/includes/header.php : Inclusion de l'en-tête HTML.
 * - views/includes/footer.php : Inclusion du pied de page HTML.
 * - views/recipe/add/step_status.php : Vue pour l'affichage de la progression.
 * - views/recipe/add/recipe_card.php : Vue pour l'aperçu de la recette.
 * - controllers/recipe/add/step1.php : Contrôleur pour le traitement des données du formulaire.
 */

// Vérification de l'accès sécurisé pour empêcher l'accès direct au fichier.
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../../index.php?page=er");
    exit();
}

// Ajout des feuilles de styles
$styles = ['recipe/add/card'];

// Ajout de script
$scripts = ['recipe/add/addElements', 'recipe/add/addTypes'];

// Inclusions des fichiers nécessaires.
include 'views/includes/header.php';
include 'class/formInput.php';
include 'includes/connection.php';
include 'models/recipeType.php';

// Vérification de l'étape actuelle
if (!isset($_SESSION['step1'])) {
    $_SESSION['step1'] = false;
}

/**
 * Génère un boutton avec une pastille pour supprimer l'item
 *
 * @param [type] $type Type de plats
 * @return string Le HTML du groupe de boutton
 */
function addType($type): string
{
    return <<<HTML
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
}

// Récupération des types de plats depuis la base de données
$recipeTypeModel = new RecipeType($pdo);
$recipeTypeData = $recipeTypeModel->getTypesSimple();
$dataList = $recipeTypeData['success'] ? (new FormDataList("recipeType", $recipeTypeData['datas']))->render() : '';

// Initialisation des inputs
$inputName = new Input('name', 'Nom de la recette');
$inputType = (new Input('types[]', 'Sélectionner le type de plat'))
    ->addList('recipeType')
    ->addButton('type', '+')
    ->setRequired(false)
    ->render();

// Gestion des erreurs et des valeurs précédemment saisies
if (isset($_SESSION['message'])) {
    $inputName = $inputName->setErrorMessage($_SESSION['message'])->setValue($_SESSION['nom_plat']);
    unset($_SESSION['message'], $_SESSION['nom_plat']);
}

// Récupération du nom du plat si retour sur la page
if (isset($_SESSION['nom_plat'])) {
    // Nettoyage et validation des données
    $nomPlat = htmlspecialchars($_SESSION['nom_plat'], ENT_QUOTES, 'UTF-8');
    $inputName->setValue($nomPlat);
} else {
    $nomPlat = "";
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
                            <h5 class="myh5">Informations sur la recette</h5>
                            <!-- Input de saisie du nom -->
                            <?php echo $inputName->render() ?>
                            <hr>
                            <!-- Affichage des type de plat déjà ajouté -->
                            <div id="recipeTypes" class="me-1 mb-1">
                                <?php
                                if (isset($_SESSION['types_plat']) && is_array($_SESSION['types_plat'])) {
                                    foreach ($_SESSION['types_plat'] as $type) {
                                        echo addType(htmlspecialchars($type));
                                    }
                                }
                                ?>
                            </div>
                            <hr>
                            <!-- Input saisie du type de plat -->
                            <?php echo $inputType ?>
                            <!-- Ajout des option (liste des plats déjà en bdd) -->
                            <?php echo $dataList ?>
                            
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