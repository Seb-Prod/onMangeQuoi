<?php
function monVarDump($var){
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}




function checkRequiredFields($requiredFields, $var)
{
    $erreurs = []; // Stocke toutes les erreurs

    foreach ($requiredFields as $field) {
        // Vérifie si le champ est absent
        if (!isset($var[$field])) {
            $erreurs[] = "Le champ '$field' est manquant.";
            continue; // Passe au champ suivant
        }

        // Vérifie si le champ est vide, sauf si c'est "0" ou 0
        if (is_string($var[$field]) && trim($var[$field]) === '' && $var[$field] !== "0") {
            $erreurs[] = "Le champ '$field' ne peut pas être vide.";
        }

        // Vérifie si le champ est un tableau non vide
        if (is_array($var[$field]) && empty($var[$field])) {
            $erreurs[] = "Le champ '$field' ne peut pas être un tableau vide.";
        }
    }

    return empty($erreurs) ? true : $erreurs;
}

function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
 }