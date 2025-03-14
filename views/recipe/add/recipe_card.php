<?php
include_once __DIR__ . '/includes/components/ingredients.php';


// Nom du plat
if (isset($_SESSION['recipe']['nom_plat']) && !empty($_SESSION['recipe']['nom_plat'])) {
    // Nettoyage et validation des données
    $nomPlat = htmlspecialchars($_SESSION['recipe']['nom_plat'], ENT_QUOTES, 'UTF-8');
} else {
    $nomPlat = "Nom du plat non défini";
}

// Type(s) de plat
if (isset($_SESSION['recipe']["types_plat"]) && is_array($_SESSION['recipe']["types_plat"])) {
    $types = $_SESSION['recipe']['types_plat'];
    // Nettoyage des données
    $types = array_map(function ($type) {
        return htmlspecialchars(trim($type), ENT_QUOTES, 'UTF-8');
    }, $types);
    $types = array_filter($types);
} else {
    $types = [];
}

// Temps de préparation
if (isset($_SESSION['recipe']["preparation"], $_SESSION['recipe']['repos'], $_SESSION['recipe']['cuisson'])) {
    $preparation = max(0, filter_var($_SESSION['recipe']['preparation'], FILTER_SANITIZE_NUMBER_INT));
    $repos = max(0, filter_var($_SESSION['recipe']['repos'], FILTER_SANITIZE_NUMBER_INT));
    $cuisson = max(0, filter_var($_SESSION['recipe']['cuisson'], FILTER_SANITIZE_NUMBER_INT));
} else {
    $preparation = 0;
    $repos = 0;
    $cuisson = 0;
}

// Nombre de portions - Correction de la faute d'orthographe et du pluriel
if (isset($_SESSION['recipe']['portion']) && !empty($_SESSION['recipe']['portion'])) {
    $nbPortions = intval($_SESSION['recipe']['portion']);
    if ($nbPortions > 1) {
        $portions = "Liste d'ingrédients pour {$nbPortions} portions";
    } else {
        $portions = "Liste d'ingrédient pour {$nbPortions} portion";
    }
} else {
    $portions = "Liste d'ingrédients vide";
}

/**
 * Génère le HTML pour afficher un type de plat
 * 
 * @param string $type Le type de plat à afficher
 * @return string Le HTML généré
 */
function addTypePreview(string $type): string
{
    $typeSecurise = htmlspecialchars($type, ENT_QUOTES, 'UTF-8');
    $html = <<<HTML
        <span class="badge text-bg-secondary me-1 mb-1">{$typeSecurise}</span>
    HTML;

    return $html;
}

/**
 * Génère le HTML pour afficher un ingrédient
 * 
 * @param string $type Le type de plat à afficher
 * @return string Le HTML généré
 */
function addIngredientPreview(array $ingredient): string
{
    $ingredientSecurise = htmlspecialchars($ingredient['ingredient'], ENT_QUOTES, 'UTF-8');
    $qtsSecurise = htmlspecialchars($ingredient['qts'], ENT_QUOTES, 'UTF-8');
    $unitSecurise = htmlspecialchars($ingredient['unit'], ENT_QUOTES, 'UTF-8');
    $html = <<<HTML
        <span class="badge text-bg-secondary me-1 mb-1">{$qtsSecurise} {$unitSecurise} {$ingredientSecurise}</span>
    HTML;

    return $html;
}


/**
 * Convertit un temps en minutes en format heures/minutes lisible
 * 
 * @param float $time Le temps en minutes
 * @return string Le temps formaté
 */
function getTime(float $time): string
{
    $time = max(0, $time); // Assure que le temps n'est pas négatif
    $heure = floor($time / 60);
    $minute = $time % 60;

    if ($heure > 0 && $minute > 0) {
        return "{$heure}h {$minute}min";
    } elseif ($heure > 0) {
        return "{$heure}h";
    } else {
        return "{$minute}min";
    }
}

// Détermine si on doit afficher les informations de debug
$showDebug = true; // Mettre à true pour activer le debugging
?>

<div class="col-12 col-md-6 mb-4 mb-md-0">
    <div class="card myCard">
        <div class="card-body">
            <h5 class="label"><?php echo $nomPlat; ?></h5>
            <!-- Types de plat -->
            <?php if (!empty($types)): ?>
                <div class="text-center">
                    <?php foreach ($types as $type): ?>
                        <?php echo addTypePreview($type); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <hr>

            <!-- Temps de préparation -->
            <h5 class="myh5 text-center">Préparation</h5>
            <p class="fw-bold text-center mb-1">Temps total : <?php echo getTime($preparation + $repos + $cuisson); ?></p>
            <p class="small text-muted text-center mt-0">
                Préparation : <?php echo getTime($preparation); ?> •
                Repos : <?php echo getTime($repos); ?> •
                Cuisson : <?php echo getTime($cuisson); ?>
            </p>

            <hr>

            <!-- Liste d'ingrédients -->
            <h5 class="myhr text-center"><?php echo $portions; ?></h5>
            <div class="text-center">
                <?php if (isset($_SESSION['recipe']['ingredients']) && !empty($_SESSION['recipe']['ingredients'])): ?>
                    <?php foreach ($_SESSION['recipe']['ingredients'] as $ingredient) : ?>
                        <?php echo addIngredientPreview($ingredient) ?>
                    <?php endforeach ?>
                <?php else: ?>
                    <p class="text-center text-muted">Aucun ingrédient ajouté</p>
                <?php endif; ?>
            </div>
            <?php if ($showDebug): ?>
                <hr>
                <h6>Débogage</h6>
                <div class="small">
                    <pre><?php var_dump($_SESSION['error'] ?? 'Aucune erreur'); ?></pre>
                    <pre><?php var_dump($_SESSION['recipe'] ?? 'Aucune recette'); ?></pre>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>