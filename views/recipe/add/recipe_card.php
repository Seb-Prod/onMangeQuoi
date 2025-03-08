<?php
// Nom du plat
if (isset($_SESSION['nom_plat'])) {
    // Nettoyage et validation des données
    $nomPlat = htmlspecialchars($_SESSION['nom_plat'], ENT_QUOTES, 'UTF-8');
} else {
    $nomPlat = "Nom du plat non défini";
}

// Type(s) de plat
if (isset($_SESSION["types_plat"])) {
    $types = $_SESSION['types_plat'];
    // Nettoyage des données
    $types = array_map('trim', $types);
    $types = array_filter($types);
} else {
    $types = [];
}

// Temps de préparation
if (isset($_SESSION["preparation"], $_SESSION['repos'], $_SESSION['cuisson'])) {
    $preparation = filter_var($_SESSION['preparation'], FILTER_SANITIZE_NUMBER_INT);
    $repos = filter_var($_SESSION['repos'], FILTER_SANITIZE_NUMBER_INT);
    $cuisson = filter_var($_SESSION['cuisson'], FILTER_SANITIZE_NUMBER_INT);
} else {
    $preparation = 0;
    $repos = 0;
    $cuisson = 0;
}

function addTypePreview(string $type): string
{
    $html = <<<HTML
        <span class="badge text-bg-secondary me-1 mb-1">{$type}</span>
    HTML;

    return $html;
}

function getTime(float $time): string {
    $heure = floor($time / 60);
    $minute = $time % 60;
    
    if ($heure > 0 && $minute > 0) {
        return $heure . "h " . $minute . "min";
    } elseif ($heure > 0) {
        return $heure . "h";
    } else {
        return $minute . "min";
    }
}

?>
<div class="col-12 col-md-6 mb-4 mb-md-0">
    <div class="card myCard">
        <div class="card-body">
            <h5 class="myh5 text-center"><?php echo $nomPlat ?></h5>
            <!-- Div pour centrer les types -->
            <div class="text-center">
                <?php foreach ($types as $type) {
                    echo addTypePreview($type);
                }
                ?>
            </div>
            <hr>
            <h5 class="myh5 text-center">Préparation</h5>
            <p class="fw-bold text-center mb-1">Temps total : <?php echo getTime($preparation + $repos + $cuisson) ?></p>
            <p class="small text-muted text-center mt-0">
                Préparation : <?php echo getTime($preparation) ?> • 
                Repos : <?php echo getTime($repos) ?> • 
                Cuisson : <?php echo getTime($cuisson) ?>
            </p>
            <hr>
        </div>
    </div>
</div>