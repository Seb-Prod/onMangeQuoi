<?php
define('SECURE_ACCESS', true);
require_once 'includes/connection.php';
$titrePage = "On Mange Quoi ?";
require_once 'includes/header.php';
require_once 'components/RecipeCard.php';

function getLatestRecipes(PDO $pdo): array
{
    try {
        // Requête principale pour les recettes
        $query = "SELECT r.*, u.pseudo as auteur 
                 FROM recettes r 
                 JOIN users u ON r.user_id = u.id 
                 ORDER BY r.date_ajout DESC, r.id DESC 
                 LIMIT 5";

        $stmt = $pdo->query($query);
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return enrichRecipesWithDetails($pdo, $recipes);
    } catch (PDOException $e) {
        // Log l'erreur et retourne un tableau vide
        error_log("Erreur dans getLatestRecipes: " . $e->getMessage());
        return [];
    }
}

function getBestRecipes(PDO $pdo): array
{
    try {
        // Requête principale pour les recettes
        $query = "SELECT r.*, u.pseudo as auteur 
                 FROM recettes r 
                 JOIN users u ON r.user_id = u.id 
                 WHERE r.note IS NOT NULL 
                 ORDER BY r.note DESC, r.date_ajout DESC 
                 LIMIT 5";

        $stmt = $pdo->query($query);
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return enrichRecipesWithDetails($pdo, $recipes);
    } catch (PDOException $e) {
        // Log l'erreur et retourne un tableau vide
        error_log("Erreur dans getBestRecipes: " . $e->getMessage());
        return [];
    }
}

function enrichRecipesWithDetails(PDO $pdo, array $recipes): array
{
    foreach ($recipes as &$recipe) {
        // Récupération des ingrédients
        $query = "SELECT i.nom as ingredient_nom, ri.quantite, um.nom as unite 
                 FROM recette_ingredient ri 
                 JOIN ingredients i ON ri.ingredient_id = i.id 
                 JOIN unites_mesure um ON ri.unite_mesure_id = um.id 
                 WHERE ri.recette_id = :recipe_id";

        $stmt = $pdo->prepare($query);
        $stmt->execute(['recipe_id' => $recipe['id']]);
        $recipe['ingredients'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupération des étapes
        $query = "SELECT etape 
                 FROM recette_etape 
                 WHERE recette_id = :recipe_id 
                 ORDER BY index_etape";

        $stmt = $pdo->prepare($query);
        $stmt->execute(['recipe_id' => $recipe['id']]);
        $recipe['etapes'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Calcul du temps total
        $recipe['temps_total'] = $recipe['temps_preparation'] +
            $recipe['temps_cuisson'] +
            $recipe['temps_repos'];
    }

    return $recipes;
}

$latestRecipes = getLatestRecipes($pdo);
$bestRecipes = getBestRecipes($pdo);



?>
<div class="container-fluid">
    <div class="row flex-nowrap overflow-auto gx-7">
        <?php foreach ($latestRecipes as $index => $recipe): ?>
            <?php
            $recipeCard = new RecipeCard($recipe, $index);
            echo $recipeCard->render();
            ?>
        <?php endforeach; ?>
    </div>
</div>
<!-- Début du contenu de la page -->

<!-- Fin du contenue de la page -->
<?php require_once 'includes/footer.php' ?>