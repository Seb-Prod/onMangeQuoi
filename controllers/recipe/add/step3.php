<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 403 Forbidden');
    exit('Accès non autorisé.');
}

$erreurs = [];

// Vérifier la portion (devrait être un nombre positif)
if (isset($_POST['portion']) && is_numeric($_POST['portion']) && (float)$_POST['portion'] > 0) {
    $_SESSION['portion'] = (float)$_POST['portion'];
} else {
    $erreurs['portion'] = "Le nombre de portions doit être un nombre positif";
}

// Vérifier que les tableaux qts, unit et ingredient existent, ont le même nombre d'éléments
// et que chaque élément est valide
if (isset($_POST['qts']) && is_array($_POST['qts']) && 
    isset($_POST['unit']) && is_array($_POST['unit']) && 
    isset($_POST['ingredient']) && is_array($_POST['ingredient'])) {
    
    // Vérifier que les tableaux ont la même taille
    if (count($_POST['qts']) === count($_POST['unit']) && 
        count($_POST['unit']) === count($_POST['ingredient'])) {
        
        $ingredients_valides = true;
        $ingredients = [];
        
        // Parcourir tous les ingrédients
        for ($i = 0; $i < count($_POST['qts']); $i++) {
            // Vérifier que la quantité est un nombre positif
            if (!is_numeric($_POST['qts'][$i]) || (float)$_POST['qts'][$i] <= 0) {
                $erreurs[] = "La quantité de l'ingrédient " . ($i + 1) . " doit être un nombre positif";
                $ingredients_valides = false;
            }
            
            // Vérifier que l'unité n'est pas vide
            if (empty(trim($_POST['unit'][$i]))) {
                $erreurs[] = "L'unité de l'ingrédient " . ($i + 1) . " ne peut pas être vide";
                $ingredients_valides = false;
            }
            
            // Vérifier que l'ingrédient n'est pas vide
            if (empty(trim($_POST['ingredient'][$i]))) {
                $erreurs[] = "Le nom de l'ingrédient " . ($i + 1) . " ne peut pas être vide";
                $ingredients_valides = false;
            }
            
            // Si tout est valide, ajouter l'ingrédient au tableau final
            if ($ingredients_valides) {
                $ingredients[] = [
                    'qts' => (float)$_POST['qts'][$i],
                    'unit' => trim($_POST['unit'][$i]),
                    'ingredient' => trim($_POST['ingredient'][$i])
                ];
            }else{
                $erreurs['datas'] = [
                    'qts' => (float)$_POST['qts'][$i],
                    'unit' => trim($_POST['unit'][$i]),
                    'ingredient' => trim($_POST['ingredient'][$i])
                ];
            }
        }
        
        // Si tous les ingrédients sont valides, les enregistrer en session
        if ($ingredients_valides) {
            $_SESSION['ingredients'] = $ingredients;
        }
    } else {
        $erreurs = $erreurs['datas'];
    }
} else {
    $erreurs[] = "Les données d'ingrédients sont incorrectes ou incomplètes";
}

// Si des erreurs ont été détectées
if (!empty($erreurs)) {
    $_SESSION['erreurs'] = $erreurs;
    var_dump($_SESSION['erreurs']);
    echo '<pre>';
    var_dump($_POST);
    echo '</pre>';

    //header('Location: ../../../index.php?page=recipestep3');
    //exit();
}

// Marquer l'étape comme complétée et rediriger vers l'étape suivante
$_SESSION['step3'] = true;
echo '<pre>';
    var_dump($_POST);
    echo '</pre>';
//header("Location: ../../../index.php?page=recipestep4");
//exit();