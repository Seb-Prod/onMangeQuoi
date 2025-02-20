<?php
$titrePage = "Ajouter une recette";
require_once 'includes/header.php';

if(isset($_SESSION['recipe']) && $_SESSION !=''){
    $nom =  $_SESSION['recipe'];
    unset($_SESSION['recipe']);
}else{
    header('Location: index.php');
    exit();
}











?>
<div class="d-flex justify-content-center align-items-center">
    <div class="shadow-lg card text-bg-success mb-3">
        <h5 class="card-header">'<?php echo $nom ?>' ajoutée avec succès !</h5>
        <div class="card-body">
            <a href="recettes.php" class="nav-link">
                <i class="fa-solid fa-plus"></i>
                Ajouter une autre recette
            </a>
            <a href="index.php" class="nav-link">
                <i class="fa-solid fa-house"></i>
                Home
            </a>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php' ?>