<?php
if (isset($_SESSION['nom_plat'])) {
    // Nettoyage et validation des données
    $nomPlat = htmlspecialchars($_SESSION['nom_plat'], ENT_QUOTES, 'UTF-8');
} else {
    $nomPlat = "Nom du plat non défini";
}

if(isset($_SESSION["types_plat"])){
    
}


?>

<div class="col-12 col-md-6 mb-4 mb-md-0">
    <div class="card myCard">
        <div class="card-body">
            <h5 class="myh5"><?php echo $nomPlat ?></h5>
            <p>Contenu de la deuxième carte.</p>
        </div>
    </div>
</div>