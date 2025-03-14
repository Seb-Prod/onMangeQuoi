<?php
session_start();
var_dump($_POST);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['preparation'])) {
    $minutes = array();

    // Fonction de conversion
    function convertToMinutes($time)
    {
        if (preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $time)) {
            $timeParts = explode(':', $time);
            $heures = (int)$timeParts[0];
            $minutesPart = (int)$timeParts[1];
            return ($heures * 60) + $minutesPart;
        } else {
            return 0;
        }
    }

    // Accès direct aux valeurs de temps
    $preparation = $_POST['preparation'];
    $repos = $_POST['repos'];
    $cuisson = $_POST['cuisson'];

    // Conversion de chaque valeur en minutes
    $_SESSION['recipe']['preparation'] = convertToMinutes($preparation);
    $_SESSION['recipe']['repos'] = convertToMinutes($repos);
    $_SESSION['recipe']['cuisson'] = convertToMinutes($cuisson);

    // Marquer l'étape comme complétée et rediriger vers l'étape suivante
    $_SESSION['step2'] = true;
    header("Location: ../../../index.php?page=recipestep3");
    exit();
}
