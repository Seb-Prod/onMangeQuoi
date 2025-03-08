<?php
/**
 * Contrôleur pour la gestion des temps d'une recette - Étape 2
 * 
 * Ce script traite les données temporelles d'une recette (préparation, repos, cuisson)
 * et les valide avant de les stocker en session pour l'étape suivante.
 */

session_start();

// Vérification de la méthode d'accès
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 403 Forbidden');
    exit('Accès non autorisé.');
}

/**
 * Valide et récupère une valeur temporelle (heures ou minutes)
 * 
 * @param string $key Clé du paramètre dans $_POST
 * @param int $maxValue Valeur maximale autorisée
 * @param int $default Valeur par défaut si non spécifiée ou invalide
 * @return int|null Valeur validée ou null en cas d'erreur
 */
function getValidatedTime($key, $maxValue = 59, $default = 0) {
    $value = filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 0, 'max_range' => $maxValue]
    ]);
    
    // Si la valeur est invalide mais existe, on retourne null pour indiquer une erreur
    if ($value === false && isset($_POST[$key])) {
        return null;
    }
    
    // Si la valeur n'existe pas, on utilise la valeur par défaut
    return $value === false ? $default : $value;
}

/**
 * Convertit les heures et minutes en minutes totales
 * 
 * @param int $hours Nombre d'heures
 * @param int $minutes Nombre de minutes
 * @return int Total en minutes
 */
function convertToMinutes($hours, $minutes) {
    return ($hours * 60) + $minutes;
}

// Structure pour organiser les données temporelles
$timeFields = [
    'preparation' => [
        'hour' => ['key' => 'heurepreparation', 'max' => 48],
        'minute' => ['key' => 'minutepreparation', 'max' => 59]
    ],
    'repos' => [
        'hour' => ['key' => 'heurerepos', 'max' => 48],
        'minute' => ['key' => 'minuterepos', 'max' => 59]
    ],
    'cuisson' => [
        'hour' => ['key' => 'heurecuisson', 'max' => 48],
        'minute' => ['key' => 'minutecuisson', 'max' => 59]
    ]
];

// Récupération et validation de toutes les valeurs temporelles
$validationErrors = false;
$timeValues = [];

foreach ($timeFields as $fieldName => $fieldData) {
    $hours = getValidatedTime($fieldData['hour']['key'], $fieldData['hour']['max']);
    $minutes = getValidatedTime($fieldData['minute']['key'], $fieldData['minute']['max']);
    
    // Vérification des erreurs
    if ($hours === null || $minutes === null) {
        $validationErrors = true;
        break;
    }
    
    // Conversion et stockage
    $timeValues[$fieldName] = convertToMinutes($hours, $minutes);
}

// Gestion des erreurs
if ($validationErrors) {
    // Stocker les données soumises pour les réafficher dans le formulaire
    $_SESSION['form_data'] = $_POST;
    $_SESSION['form_error'] = "Veuillez saisir des valeurs valides pour les temps.";
    
    // Redirection vers la page du formulaire
    header("Location: ../../../index.php?page=recipestep2");
    exit();
}

// Stockage des valeurs validées en session
foreach ($timeValues as $key => $value) {
    $_SESSION[$key] = $value;
}

// Marquer l'étape comme complétée et rediriger vers l'étape suivante
$_SESSION['step2'] = true;
header("Location: ../../../index.php?page=recipestep3");
exit();