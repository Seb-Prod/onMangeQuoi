<?php

/**
 * Fichier : step2_preparation_time.php
 * Objectif : Formulaire d'ajout de recette - Étape 2 : Les temps de la préparations.
 * Description : Ce fichier gère l'affichage et le traitement du formulaire pour ajouter les temps de préparation d'une recette.
 * Dépendances :
 * - class/formInput.php : Classe pour la création de champs de formulaire.
 * - includes/connection.php : Connexion à la base de données.
 * - views/includes/header.php : Inclusion de l'en-tête HTML.
 * - views/includes/footer.php : Inclusion du pied de page HTML.
 * - views/recipe/add/step_status.php : Vue pour l'affichage de la progression.
 * - views/recipe/add/recipe_card.php : Vue pour l'aperçu de la recette.
 * - controllers/recipe/add/step2.php : Contrôleur pour le traitement des données du formulaire.
 */

// Vérification de l'accès sécurisé pour empêcher l'accès direct au fichier.
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../../index.php?page=er");
    exit();
}

// Ajout des feuilles de styles
$styles = ['recipe/add/card'];

// Ajout de script
$scripts = [];

// Inclusions des fichiers nécessaires.
include 'includes/header.php';
include 'class/formInput.php';
include 'includes/connection.php';
include 'functions/convertMinutesToHoursMinutes.php';

// Vérification de l'étape actuelle
if (!isset($_SESSION['step2'])) {
    $_SESSION['step2'] = false;
}

// Récupération des temps de prépatation
if (isset($_SESSION["preparation"], $_SESSION['repos'], $_SESSION['cuisson'])) {
    $preparation = filter_var($_SESSION['preparation'], FILTER_SANITIZE_NUMBER_INT);
    $repos = filter_var($_SESSION['repos'], FILTER_SANITIZE_NUMBER_INT);
    $cuisson = filter_var($_SESSION['cuisson'], FILTER_SANITIZE_NUMBER_INT);
} else {
    $preparation = 0;
    $repos = 0;
    $cuisson = 0;
}

/**
 * Génère un groupe de champs pour la saisie du temps (pré-rempie si retour sur la page)
 * @param string $type Type de temps (preparation, repos, cuisson)
 * @return string Le HTML du groupe de champs
 */
function inputTime($type): string
{
    global $preparation, $repos, $cuisson;

    // Récupération dynamique de la valeur
    $minutes = $$type;

    // Conversion en heures et minutes
    list($heure, $minute) = convertMinutesToHoursMinutes($minutes);

    // Génére un inpute heure
    $inputHeure = (new Input('heure' . $type, 'h'))
        ->settype('number')
        ->setMax(48)
        ->setRequired(false)
        ->setValue($heure);

    // Génére un input minute
    $inputMinute = (new Input('minute' . $type, 'min'))
        ->settype('number')
        ->setMax(59)
        ->setRequired(false)
        ->setValue($minute);

    return <<<HTML
    <div class="row justify-content-center">
        <div class="col-md-8"> <!-- Augmente la largeur de la colonne -->
            <div class="d-flex align-items-center">
                <div class="me-2 flex-grow-1"> <!-- Flex pour une meilleure adaptation -->
                    {$inputHeure->render()}
                </div>
                <div class="mx-2">:</div>
                <div class="ms-2 flex-grow-1">
                    {$inputMinute->render()}
                </div>
            </div>
        </div>
    </div>
    HTML;
}

// Définition des types de temps
$timeTypes = [
    'preparation' => 'Temps de préparation',
    'repos' => 'Temps de repos',
    'cuisson' => 'Temps de cuisson'
];

?>

<main>
    <div class="container mt-3 mb-3">
        <div class="row">
            <!-- Progression -->
            <?php include 'views/recipe/add/step_status.php' ?>

            <!-- Aperçu Recette -->
            <?php include 'views/recipe/add/recipe_card.php' ?>

            <div class="col-12 col-md-6">
                <div class="card myCard">
                    <div class="card-body">
                        <form action="controllers/recipe/add/step2.php" method="post">
                            <!-- Boucle qui génére les input de temps -->
                            <?php foreach ($timeTypes as $timeType => $timeLabel): ?>
                                <!-- Titre du champs -->
                                <h5 class="myh5"><?php echo $timeLabel; ?></h5>
                                <!-- Ajoute les inputes heure et minute -->
                                <?php echo inputTime($timeType); ?>
                                <!-- Condition qui ajoute une séparation -->
                                <?php if ($timeType !== 'cuisson'): ?>
                                    <hr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <!-- Bouton de validation et de retour -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="index.php?page=recipestep1" class="btn btn-secondary myButton">Retour</a>
                                        <input type="submit" class="btn btn-primary myButton" value="Suivant">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>