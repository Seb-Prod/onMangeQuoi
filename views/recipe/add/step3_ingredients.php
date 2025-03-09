<?php
// Vérification de l'accès sécurisé
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../../index.php?page=er");
    exit();
}

// Configuration de la page
$styles = ['recipe/add/card'];
$scripts = ['recipe/add/addElements', 'recipe/add/addIngredients'];

// Inclusions des fichiers nécessaires
include 'includes/header.php';
include 'class/formInput.php';
include 'includes/connection.php';
include 'models/unit.php';
include 'models/ingredient.php';

// Initialisation de l'étape
if (!isset($_SESSION['step3'])) {
    $_SESSION['step3'] = false;
}

/**
 * Génère le HTML pour un ingrédient déjà ajouté
 * 
 * @param int $qts Quantité de l'ingrédient
 * @param string $unit Unité de mesure
 * @param string $name Nom de l'ingrédient
 * @return string HTML généré
 */
function renderIngredientItem(int $qts, string $unit, string $name) {
    return <<<HTML
    <div class="type-row">
        <span class="btn btn-sm btn-primary position-relative green">
            {$name} ({$qts} {$unit})
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <button type="button" class="btn btn-danger btn-sm remove-type p-0" style="font-size: 0.7rem; line-height: 1;">
                    X
                </button>
            </span>
            <input type="hidden" name="ingredient[]" value="{$name}">
        </span>
    </div>
HTML;
}

/**
 * Récupère les données des modèles
 * 
 * @param PDO $pdo Connexion à la base de données
 * @return array Tableaux des ingrédients et unités
 */
function fetchModelData($pdo) {
    $ingredientModel = new Ingredient($pdo);
    $unitModel = new Unit($pdo);
    
    return [
        'ingredients' => $ingredientModel->get(),
        'units' => $unitModel->get()
    ];
}

/**
 * Crée les listes déroulantes (datalists)
 * 
 * @param array $modelData Données des modèles
 * @return array HTML des datalists
 */
function createDataLists($modelData) {
    $dataListIngredient = $modelData['ingredients']['success'] 
        ? (new FormDataList("dataIngredients", $modelData['ingredients']['datas']))->render() 
        : '';
        
    $dataListUnit = $modelData['units']['success'] 
        ? (new FormDataList("dataUnits", $modelData['units']['datas']))->render() 
        : '';
        
    return [
        'ingredients' => $dataListIngredient,
        'units' => $dataListUnit
    ];
}

/**
 * Initialise les champs de formulaire
 * 
 * @return array Champs de formulaire initialisés
 */
function initializeFormFields() {
    $fields = [];
    
    // Champ pour le nombre de portions
    $fields['portion'] = (new Input('portion', 'Nombre de Portion(s)'))
        ->settype('number')
        ->setMin(1);
    
    // Champ pour la quantité
    $fields['qts'] = (new Input('qts[]', 'Quantité'))
        ->settype('number')
        ->setMin(1)
        ->setId('qts')
        ->setRequired(false);
    
    // Champ pour l'unité
    $fields['unit'] = (new Input('unit[]', 'Séléctionner une unité'))
        ->setId('unit')
        ->addList('dataUnits')
        ->setRequired(false);
    
    // Champ pour l'ingrédient
    $fields['ingredient'] = (new Input('ingredient[]', 'Séléctionner un ingrédient'))
        ->addList('dataIngredients')
        ->addButton('ingredient', '+')
        ->setRequired(false);
    
    return $fields;
}

/**
 * Remplit les champs avec les valeurs de session existantes
 * 
 * @param array $fields Champs de formulaire
 * @return array Champs mis à jour
 */
function populateFieldsFromSession($fields) {
    // Définir la valeur de portion si elle existe en session
    if (isset($_SESSION['portion'])) {
        $fields['portion']->setValue($_SESSION['portion']);
    }
    
    return $fields;
}

/**
 * Prérempli les champs avec les valeurs d'erreur si présentes
 * 
 * @param array $fields Champs de formulaire
 * @return array Champs mis à jour avec les messages d'erreur
 */
function populateErrorFields($fields) {
    // Vérifier si l'utilisateur est connecté et s'il y a des erreurs
    if (isset($_SESSION['erreurs']['datas'])) {
        $errorData = $_SESSION['erreurs']['datas'];
        unset($_SESSION['erreurs']);
        
        // Gestion du champ quantité
        if (isset($errorData['qts'])) {
            if (!empty($errorData['qts'])) {
                $fields['qts']->setValue($errorData['qts']);
            } else {
                $fields['qts']->setErrorMessage("Manque la quantité");
            }
        }
        
        // Gestion du champ unité
        if (isset($errorData['unit'])) {
            if (!empty($errorData['unit'])) {
                $fields['unit']->setValue($errorData['unit']);
            } else {
                $fields['unit']->setErrorMessage("Manque l'unité de mesure");
            }
        }
        
        // Gestion du champ ingrédient
        if (isset($errorData['ingredient'])) {
            if (!empty($errorData['ingredient'])) {
                $fields['ingredient']->setValue($errorData['ingredient']);
            } else {
                $fields['ingredient']->setErrorMessage("Manque l'ingrédient");
            }
        }
    }
    
    return $fields;
}

/**
 * Affiche les ingrédients déjà ajoutés
 * 
 * @return string HTML généré
 */
function renderExistingIngredients() {
    $html = '';
    
    if (isset($_SESSION['ingredients'])) {
        foreach ($_SESSION['ingredients'] as $ingredient) {
            $html .= renderIngredientItem(
                $ingredient['qts'], 
                $ingredient['unit'], 
                $ingredient['ingredient']
            );
        }
    }
    
    return $html;
}

// Préparation des données
$modelData = fetchModelData($pdo);
$dataLists = createDataLists($modelData);
$formFields = initializeFormFields();
$formFields = populateFieldsFromSession($formFields);
$formFields = populateErrorFields($formFields);
$existingIngredients = renderExistingIngredients();
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
                            <h5 class="myh5">Ingrédients pour :</h5>
                            
                            <!-- Input pour le nombre de portion -->
                            <?php echo $formFields['portion']->render() ?>
                            <hr>
                            
                            <!-- Affichage des ingrédients déjà ajoutés -->
                            <div id="recipeIngredients" class="me-1 mb-1">
                                <?php echo $existingIngredients; ?>
                            </div>
                            <hr>
                            
                            <!-- Ajout d'un ingrédient -->
                            <div class="row">
                                <div class="col-4">
                                    <?php echo $formFields['qts']->render(); ?>
                                </div>
                                <div class="col-8">
                                    <?php echo $formFields['unit']->render(); ?>
                                </div>
                            </div>
                            
                            <?php 
                            echo $formFields['ingredient']->render();
                            echo $dataLists['ingredients'];
                            echo $dataLists['units'];
                            ?>
                            
                            <!-- Boutons de navigation -->
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