<?php
$titrePage = "Ajouter une recette";
require_once 'includes/header.php';
?>
<div class="d-flex justify-content-center align-items-center">
    <div class="shadow-lg card text-bg-success mb-3" style="max-width: 18rem;">
        <h5 class="card-header">Erreur</h5>
        <div class="card-body">
            <?php foreach ($errors as $error) : ?>
                <p><?php echo $error ?></p>
            <?php endforeach ?>
            <a href="index.php" class="nav-link">
                <i class="fa-solid fa-house"></i>
                Home
            </a>

        </div>
    </div>
</div>
<?php require_once 'includes/footer.php' ?>