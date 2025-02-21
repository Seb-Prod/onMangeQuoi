<?php
define('SECURE_ACCESS', true);
require_once 'includes/connection.php';
$titrePage = "Ajouter une recette";
$script = 'ajoutRecette.js';
require_once 'includes/header.php';

$messageCard = false;

// Si recette ajouté avec succes
if (isset($_SESSION['success'])) {
    $messageCard = true;
    $typeMessage = "text-bg-success";
    $message = $_SESSION['success'];
    unset($_SESSION['success']);
}

// Si doublon de recette
if (isset($_SESSION['errors']) && isset($_SESSION['post_data'])) {
    $_POST = $_SESSION['post_data'];
    $messageCard = true;
    $typeMessage = "text-bg-danger";
    $message = $_SESSION['errors'][0];
    unset($_SESSION['errors']);
    unset($_SESSION['post_data']);
}

function getSqlTableValue($pdo, $table)
{
    try {
        $sql = "SELECT nom FROM " . $table . " ORDER BY nom ASC";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return null;
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des données de la table " . $table . ": " . $e->getMessage());
        return null;
    }
}

$ingredients = getSqlTableValue($pdo, 'ingredients');
$unitesMesure = getSqlTableValue($pdo, 'unites_mesure');

$ingredients = $ingredients ? array_column($ingredients, 'nom') : [];
$unitesMesure = $unitesMesure ? array_column($unitesMesure, 'nom') : [];


function input($text, $name)
{
    $value = isset($_POST[$name]) ? $_POST[$name] : "";
    if (is_array($value)) {
        return '';
    }

    return '
    <div class="input-group input-group-sm mb-3">
        <span class="input-group-text fixed-width">' . htmlspecialchars($text) . '</span>
        <input 
            type="text" 
            class="form-control" 
            name="' . htmlspecialchars($name) . '"
            value="' . htmlspecialchars($value) . '"
            autocomplete="off"
            required>
    </div>
    ';
}

function inputNumber($text, $name, $unite = "min.", $min = 0)
{
    $value = isset($_POST[$name]) ? $_POST[$name] : "";

    return '
    <div class="input-group input-group-sm mb-3">
        <span class="input-group-text fixed-width">' . htmlspecialchars($text) . '</span>
        <input 
            type="number" 
            class="form-control" 
            name="' . htmlspecialchars($name) . '" 
            value="' . htmlspecialchars($value) . '" 
            required
            min="'.$min.'">
        <span class="input-group-text">' . htmlspecialchars($unite) . '</span>
    </div>
    ';
}

function inputQts($index = 0)
{
    $qts = isset($_POST['ingredient_qts'][$index]) ? $_POST['ingredient_qts'][$index] : "";
    $unite = isset($_POST['ingredient_unite'][$index]) ? $_POST['ingredient_unite'][$index] : "";

    return '
    <div class="input-group input-group-sm mb-3">
        <span class="input-group-text fixed-width">Quantité</span>
            <input 
                type="number" 
                class="form-control"
                name="ingredient_qts[]" 
                min="0.1"
                value="' . htmlspecialchars($qts) . '"
                required
                step="0.5">
            <input 
                type="text" 
                class="form-control" 
                autocomplete="off"
                name="ingredient_unite[]" 
                value="' . htmlspecialchars($unite) . '"
                required
                placeholder="unité" 
                list="listUnite">
    </div>
    ';
}

function inputIngredient($index = 0)
{
    $nom = isset($_POST['ingredient_nom'][$index]) ? $_POST['ingredient_nom'][$index] : "";

    $html = '<div class="ingredient-container">';

    $html .= '
    <div class="input-group input-group-sm mb-3">
        <span class="input-group-text fixed-width">Nom</span>
        <input 
            type="text" 
            class="form-control" 
            name="ingredient_nom[]"
            value="' . htmlspecialchars($nom) . '"
            autocomplete="off"
            required
            list="listIngredient">';

    // Ajouter le bouton de suppression sauf pour le premier ingrédient
    if ($index > 0) {
        $html .= '
        <button type="button" class="btn btn-danger d-flex align-items-center" onclick="removeIngredient(this)">
            <i class="fa-solid fa-trash-can"></i>
        </button>';
    }

    $html .= '</div>';

    // Ajouter les quantités
    $html .= inputQts($index);

    // Ajouter le séparateur
    $html .= '<hr>';

    $html .= '</div>';

    return $html;
}

function inputEtape($index = 0)
{
    $etape = isset($_POST['etapes'][$index]) ? $_POST['etapes'][$index] : "";
    $numero = $index + 1;

    $html = '
    <div class="step-container">
        <div class="form-floating mb-3">
            <div class="d-flex gap-2 align-items-start">
                <div class="flex-grow-1 form-floating">
                    <textarea 
                        class="form-control" 
                        name="etapes[]" 
                        required
                        id="etape' . $numero . '"
                        style="height: 100px"
                        placeholder="Décrivez l\'étape ici">' . htmlspecialchars($etape) . '</textarea>
                    <label for="etape' . $numero . '">Étape n°' . $numero . '</label>
                </div>';

    // Ajouter le bouton de suppression sauf pour la première étape
    if ($index > 0) {
        $html .= '
                <button type="button" class="btn btn-danger align-self-stretch" onclick="removeStep(this)">
                    <i class="fa-solid fa-trash-can"></i>
                </button>';
    }

    $html .= '
            </div>
        </div>
    </div>';

    return $html;
}

// Le reste de votre code reste le même jusqu'à la section des ingrédients
?>

<!-- Début du contenu de la page -->
<form action="result/recettes.php" method="post" class="needs-validation" novalidate>
    <div class="d-flex flex-column align-items-center">
        <h1>Ajouter une recette</h1>
        <?php if ($messageCard) : ?>
            <div class="shadow-lg card <?php echo $typeMessage ?> mb-3 w-100">
                <h5 class="card-header"><?php echo $message ?></h5>
            </div>
        <?php endif ?>
        <!-- Informations sur la recette -->
        <div class="shadow-lg card text-bg-light mb-3 w-100">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fa-solid fa-book"></i>
                    Informations sur la recette
                </h5>
            </div>
            <div class="card-body">
                <?php
                echo input('Nom', 'nom');
                echo inputNumber('Préparation', 'plat_preparation');
                echo inputNumber('Repos', 'plat_repos');
                echo inputNumber('Cuisson', 'plat_cuisson');
                echo inputNumber('Pour', 'plat_portions', 'personne(s)', 1);
                ?>
            </div>
        </div>
        <!-- Liste des ingrédients -->
        <div class="shadow-lg card text-bg-light mb-3 w-100">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fa-solid fa-drumstick-bite"></i>
                    Ingrédients
                </h5>
            </div>
            <div class="card-body">
                <div id="ingredients">
                    <?php
                    if (isset($_POST['ingredient_nom'])) {
                        foreach ($_POST['ingredient_nom'] as $index => $nom) {
                            echo inputIngredient($index);
                        }
                    } else {
                        echo inputIngredient();
                    }
                    ?>
                </div>
                <!-- Liste des ingrédients existants -->
                <datalist id="listIngredient">
                    <?php foreach ($ingredients as $ingredient) : ?>
                        <option value="<?php echo htmlspecialchars($ingredient) ?>"></option>
                    <?php endforeach ?>
                </datalist>
                <!-- Liste des unités existantes -->
                <datalist id="listUnite">
                    <?php foreach ($unitesMesure as $uniteMesure) : ?>
                        <option value="<?php echo htmlspecialchars($uniteMesure) ?>"></option>
                    <?php endforeach ?>
                </datalist>
                <!-- Bouton pour ajouter un autre ingrédient -->
                <button type="button" class="btn btn-primary" onclick="addIngredient()">
                    <i class="fa-solid fa-plus"></i> Ajouter un ingrédient
                </button>
            </div>
        </div>
        <!-- Étapes -->
        <div class="shadow-lg card text-bg-light mb-3 w-100">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fa-solid fa-pen"></i>
                    Préparation
                </h5>
            </div>
            <div class="card-body">
                <div id="instructions">
                    <?php
                    if (isset($_POST['etapes'])) {
                        foreach ($_POST['etapes'] as $index => $etape) {
                            echo inputEtape($index);
                        }
                    } else {
                        echo inputEtape();
                    }
                    ?>
                </div>
                <button type="button" class="btn btn-primary" onclick="addStep()">
                    <i class="fa-solid fa-plus"></i> Ajouter une étape
                </button>
            </div>
        </div>
        <button type="submit" class="btn btn-success mb-3">
            <i class="fa-solid fa-check"></i> Valider la recette
        </button>
    </div>
</form>

<?php require_once 'includes/footer.php' ?>