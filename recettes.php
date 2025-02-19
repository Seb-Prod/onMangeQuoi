<?php
define('SECURE_ACCESS', true);
require_once 'includes/connection.php';
$titrePage = "Ajouter une recette";
$script = 'ajoutRecette.js';
require_once 'includes/header.php';

function input($text, $name, $list = "")
{
    return '
    <div class="input-group input-group-sm mb-3">
        <span class="input-group-text fixed-width">' . htmlspecialchars($text) . '</span>
        <input 
            type="text" 
            class="form-control" 
            name="' . htmlspecialchars($name) . '"
            autocomplete="off"
            required
            list="' . htmlspecialchars($list) . '">
    </div>
    ';
}

function inputNumber($text, $name, $unite = "min.")
{
    return '
    <div class="input-group input-group-sm mb-3">
        <span class="input-group-text fixed-width">' . htmlspecialchars($text) . '</span>
        <input 
            type="number" 
            class="form-control" 
            name="' . htmlspecialchars($name) . '" 
            value="0" 
            required
            min="0">
        <span class="input-group-text">' . htmlspecialchars($unite) . '</span>
    </div>
    ';
}

function inputQts()
{
    return '
    <div class="input-group input-group-sm mb-3">
        <span class="input-group-text fixed-width">Quantité</span>
            <input 
                type="number" 
                class="form-control"
                name="ingredient_qts[]" 
                min="0"
                value="0"
                required
                step="0.5">
            <input 
                type="text" 
                class="form-control" 
                autocomplete="off"
                name="ingredient_unite[]" 
                required
                placeholder="unité" 
                list="listUnite">
    </div>
    ';
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
?>

<!-- Début du contenu de la page -->
<form action="result/recette.php" method="post" class="needs-validation" novalidate>
    <div class="d-flex flex-column align-items-center">
        <h1>Ajouter une recette</h1>
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
                echo inputNumber('Pour', 'plat_portions', 'personne(s)');
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
                    echo input('Nom', 'ingredient_nom[]', 'listIngredient');
                    echo inputQts();
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
                    <div class="form-floating mb-3">
                        <textarea 
                            class="form-control" 
                            name="etapes[]" 
                            required
                            placeholder="Décrivez l'étape ici"></textarea>
                        <label>Étape n°1</label>
                    </div>
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
<!-- Fin du contenu de la page -->
<?php require_once 'includes/footer.php' ?>