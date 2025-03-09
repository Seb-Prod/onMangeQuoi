<?php
// Vérification de l'accès sécurisé pour empêcher l'accès direct au fichier.
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../../index.php?page=er");
    exit();
}

// Ajout des feuilles de styles
$styles = ['recipe/add/card'];

// Ajout de script
$scripts = ['recipe/add/addElements', 'recipe/add/addTypes'];

// Inclusions des fichiers nécessaires.
include 'views/includes/header.php';
include 'class/formInput.php';
include 'includes/connection.php';
include 'models/recipeType.php';

// Vérification de l'étape actuelle
if (!isset($_SESSION['step4'])) {
    $_SESSION['step4'] = false;
}

?>

<main>
    <div class="container mt-3 mb-3">
        <div class="row">
            <!-- Progression -->
            <?php include 'views/recipe/add/step_status.php' ?>
            
            <!-- Aperçu Recette -->
            <?php include 'views/recipe/add/recipe_card.php' ?>
            
            <!-- Formulaire -->
            <div class="col-12 col-md-6">
                <div class="card myCard">
                    <div class="card-body">
                        <form action="controllers/recipe/add/step4.php" method="post">
                            <!-- Titre de la card -->
                            <h5 class="myh5">Etapes de préparation</h5>
                            <!-- Affichage des étapes déjà ajouté -->
                            <div id="etapes" class="me-1 mb-1">
                                
                            </div>
                            <hr>
                            
                            <div class="row justify-content-end mt-3">
                                <div class="col-auto">
                                    <!-- Soumission du formulaire -->
                                    <input type="submit" class="btn btn-primary myButton" value="Suivant">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'views/includes/footer.php'; ?>