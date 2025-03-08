<?php
/**
 * Convertit un temps en minutes en un tableau contenant les heures et les minutes.
 *
 * @param float $totalMinutes Le temps total en minutes.
 * @return array Un tableau contenant les heures et les minutes [heures, minutes].
 */
function convertMinutesToHoursMinutes(float $totalMinutes): array {
    // Calcule le nombre d'heures en arrondissant à l'entier inférieur.
    $hours = floor($totalMinutes / 60);

    // Calcule le nombre de minutes restantes en utilisant l'opérateur modulo.
    $minutes = $totalMinutes % 60;

    // Détermine le format de retour en fonction des valeurs calculées.
    if ($hours > 0 && $minutes > 0) {
        // Retourne les heures et les minutes si les deux sont supérieurs à zéro.
        return [$hours, $minutes];
    } elseif ($hours > 0) {
        // Retourne les heures et zéro minute si seulement les heures sont supérieures à zéro.
        return [$hours, 0];
    } else {
        // Retourne zéro heure et les minutes si seulement les minutes sont supérieures à zéro.
        return [0, $minutes];
    }
}
?>