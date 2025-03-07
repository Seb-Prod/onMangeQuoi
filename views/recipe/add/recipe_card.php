<?php
if (isset($_SESSION['nom_plat'])) {
    // Nettoyage et validation des données
    $nomPlat = htmlspecialchars($_SESSION['nom_plat'], ENT_QUOTES, 'UTF-8');
} else {
    $nomPlat = "Nom du plat non défini";
}

if(isset($_SESSION["types_plat"])){
    $types = $_SESSION['types_plat'];

    // Nettoyage des données
    $types = array_map('trim', $types);
    $types = array_filter($types);
}else{
    $types = null;
}



function addType($type): string
{
    $html = <<<HTML
        <span class="badge text-bg-secondary me-1 mb-1">{$type}</span>
        HTML;

    return $html;
}


?>

<div class="col-12 col-md-6 mb-4 mb-md-0">
    <div class="card myCard">
        <div class="card-body">
            <h5 class="myh5"><?php echo $nomPlat ?></h5>
            <?php foreach($types as $type){
                echo addType($type);
            }
            ?>
        </div>
    </div>
</div>