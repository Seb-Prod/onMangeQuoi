<?php
session_start();
echo '<pre>';
echo var_dump($_POST);
echo '</pre>';

// Vérification que la requête est bien de type POST et que le champ 'portion' est présent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['portion'])) {
    // Initialisation des erreurs et des variables
    $errors = [];
    $tableauIngredient = false;
    $values = []; // Initialisation correcte du tableau vide
    unset($_SESSION['recipe']['ingredients']);

    // Validation de la portion (vérification que c'est un nombre positif)
    if (!is_numeric($_POST['portion']) || $_POST['portion'] <= 0) {
        $errors['portion'] = "Le nombre de portions doit être un nombre positif.";
    } else {
        $_SESSION['recipe']['portion'] = $_POST['portion'];
    }

    // Vérification si les tableaux d'ingrédients existent et sont bien formatés
    if (
        isset($_POST['itemQts'], $_POST['itemUnit'], $_POST['item']) &&
        is_array($_POST['itemQts']) && is_array($_POST['itemUnit']) && is_array($_POST['item'])
    ) {
        if (count($_POST['itemQts']) !== count($_POST['itemUnit']) || count($_POST['itemUnit']) !== count($_POST['item'])) {
            $errors['items'] = "Erreur : Les tableaux d'ingrédients ne sont pas de la même taille.";
        } else {
            $tableauIngredient = true;

            // Validation des valeurs dans les tableaux
            foreach ($_POST['itemQts'] as $index => $qts) {
                if (empty(trim($qts))) {
                    $errors['itemQts'][$index] = "La quantité est requise pour l'ingrédient #" . ($index + 1);
                } elseif (!is_numeric(trim($qts))) {
                    $errors['itemQts'][$index] = "La quantité doit être un nombre pour l'ingrédient #" . ($index + 1);
                }

                if (empty(trim($_POST['itemUnit'][$index]))) {
                    $errors['itemUnit'][$index] = "L'unité de mesure est requise pour l'ingrédient #" . ($index + 1);
                }

                if (empty(trim($_POST['item'][$index]))) {
                    $errors['item'][$index] = "Le nom de l'ingrédient est requis pour l'ingrédient #" . ($index + 1);
                }
            }
        }
    }

    // Vérification d'un seul ingrédient (seulement si pas de tableau ou si le tableau est vide)
    $hasInput = !empty($_POST['qts']) || !empty($_POST['unit']) || !empty($_POST['ingredient']);

    if ($hasInput) {
        $values = ['qts' => $_POST['qts'], 'unit' => $_POST['unit'], 'ingredient' => $_POST['ingredient']];

        if (empty($_POST['qts'])) {
            $errors['qts'] = "La quantité est requise.";
        } elseif (!is_numeric($_POST['qts'])) {
            $errors['qts'] = "La quantité doit être un nombre.";
        }

        if (empty($_POST['unit'])) {
            $errors['unit'] = "L'unité de mesure est requise.";
        }

        if (empty($_POST['ingredient'])) {
            $errors['ingredient'] = "L'ingrédient est requis.";
        }
    }

    // Si erreurs, on renvoie les messages
    if (!empty($errors)) {
        $_SESSION['error']['step3'] = $errors;
        if (!empty($values)) {
            $_SESSION['input_values'] = $values; // Stocker les valeurs pour les réafficher
        }
        header("Location: ../../../index.php?page=recipestep3");
        exit();
    }

    // Initialisation de la session pour stocker les ingrédients
    if (!isset($_SESSION['recipe']['ingredients'])) {
        $_SESSION['recipe']['ingredients'] = [];
    }

    // Ajout des ingrédients sous forme de tableau si valide
    if ($tableauIngredient) {
        foreach ($_POST['itemQts'] as $index => $qts) {
            echo $index;
            $_SESSION['recipe']['ingredients'][] = [
                'qts' => floatval(trim($_POST['itemQts'][$index])), // Conversion en nombre
                'unit' => trim($_POST['itemUnit'][$index]),
                'ingredient' => trim($_POST['item'][$index])
            ];
        }
    }

    // Ajout d'un seul ingrédient si tout est rempli, indépendamment de la présence du tableau
    if ($hasInput && empty($errors)) {
        $_SESSION['recipe']['ingredients'][] = [
            'qts' => floatval(trim($_POST['qts'])), // Conversion en nombre
            'unit' => trim($_POST['unit']),
            'ingredient' => trim($_POST['ingredient'])
        ];
    }

    // Vérifier qu'au moins un ingrédient a été ajouté
    if (empty($_SESSION['recipe']['ingredients'])) {
        $_SESSION['error']['step3']['general'] = "Veuillez ajouter au moins un ingrédient à la recette.";
        header("Location: ../../../index.php?page=recipestep3");
        exit();
    }

    // Supprimer les doublons dans les ingrédients
    if (!empty($_SESSION['recipe']['ingredients'])) {
        // Tableau temporaire pour stocker les clés de hachage des ingrédients
        $uniqueIngredients = [];
        $result = [];

        foreach ($_SESSION['recipe']['ingredients'] as $item) {
            // Créer une clé unique basée sur l'ingrédient et l'unité (insensible à la casse)
            $key = strtolower($item['ingredient'] . '|' . $item['unit']);

            if (!isset($uniqueIngredients[$key])) {
                // Premier occurence de cet ingrédient
                $uniqueIngredients[$key] = $item;
                $result[] = $item;
            } else {
                // Si l'ingrédient existe déjà, additionner les quantités
                foreach ($result as $index => $existingItem) {
                    if (strtolower($existingItem['ingredient'] . '|' . $existingItem['unit']) == $key) {
                        $result[$index]['qts'] += $item['qts'];
                        break;
                    }
                }
            }
        }

        // Remplacer le tableau original par le tableau sans doublons
        $_SESSION['recipe']['ingredients'] = $result;
    }

    // Marquer l'étape 3 comme complétée et passer à l'étape 4
    unset($_SESSION['error']['step3']);
    $_SESSION['step3'] = true;

    // Redirection vers l'étape 4
    header("Location: ../../../index.php?page=recipestep4");
    exit();
}
// Si la requête n'est pas de type POST, rediriger vers l'étape 3
else {
    header("Location: ../../../index.php?page=recipestep3");
    exit();
}
