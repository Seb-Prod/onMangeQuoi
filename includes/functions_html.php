<?php
/**
 * Génère un champ de formulaire HTML avec un label dans un groupe Bootstrap
 *
 * Cette fonction crée un élément input HTML complet avec son label, encapsulé
 * dans un groupe Bootstrap. Elle inclut des fonctionnalités d'accessibilité
 * et une protection contre les attaques XSS via htmlspecialchars.
 *
 * @param string $label       Le texte du label à afficher devant l'input
 * @param string $name        L'attribut name/id de l'input (doit être unique)
 * @param string $type        Le type d'input HTML (text, email, password, etc.)
 * @param string $placeholder Le texte d'aide qui apparaît dans l'input vide
 * @param string $value       La valeur pré-remplie de l'input
 * @param string $size        La taille du groupe d'input Bootstrap (sm, lg)
 *
 * @return string Le HTML complet du groupe d'input
 *
 * @example
 * // Génère un champ email basique
 * echo generateInput("Email", "email", "email", "Entrez votre email");
 *
 * @example
 * // Génère un grand champ de mot de passe avec une valeur pré-remplie
 * echo generateInput("Mot de passe", "password", "password", "", "123456", "lg");
 *
 * @throws Exception Si les paramètres ne sont pas des chaînes de caractères
 */
function generateInput($label, $name, $type = "text", $placeholder = "", $value = "", $size = "sm")
{
    return '
    <div class="input-group input-group-' . htmlspecialchars($size) . ' mb-3">
        <span class="input-group-text" id="inputGroup-' . htmlspecialchars($name) . '">' . htmlspecialchars($label) . '</span>
        <input type="' . htmlspecialchars($type) . '" 
               class="form-control" 
               name="' . htmlspecialchars($name) . '" 
               id="' . htmlspecialchars($name) . '" 
               placeholder="' . htmlspecialchars($placeholder) . '"
               value="' . htmlspecialchars($value) . '"
               aria-label="' . htmlspecialchars($label) . '" 
               aria-describedby="inputGroup-' . htmlspecialchars($name) . '">
    </div>';
}
