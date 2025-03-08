<?php
session_start();

// Empêcher l'accès direct
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Accès non autorisé.');
}

// Fonction pour valider et récupérer une valeur entière
function getValidatedTime($key, $maxHour = 48) {
    return filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 0, 'max_range' => $maxHour]
    ]);
}

// Récupération et validation des données
$heurepreparation = getValidatedTime('heurepreparation', 49);
$minutepreparation = getValidatedTime('minutepreparation', 59);
$heurerepos = getValidatedTime('heurerepos', 48);
$minuterepos = getValidatedTime('minuterepos', 59);
$heurecuisson = getValidatedTime('heurecuisson', 48);
$minutecuisson = getValidatedTime('minutecuisson', 59);

// Vérification des erreurs
if (in_array(false, [$heurepreparation, $minutepreparation, $heurerepos, $minuterepos, $heurecuisson, $minutecuisson], true)) {
    echo 'erreur';
    var_dump($_POST);
    header("Location: ../../../index.php?page=recipestep2");
    exit();
}

// Conversion en minutes
$preparation = ($heurepreparation * 60) + $minutepreparation;
$repos = ($heurerepos * 60) + $minuterepos;
$cuisson = ($heurecuisson * 60) + $minutecuisson;

// Stocker les valeurs validées en session pour affichage ou traitement ultérieur
$_SESSION['preparation'] = $preparation;
$_SESSION['repos'] = $repos;
$_SESSION['cuisson'] = $cuisson;

// Redirection vers la page suivante après validation
$_SESSION['step2'] = true;
header("Location: ../../../index.php?page=recipestep3");
exit();
?>