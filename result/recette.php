<?php
session_start();
define('SECURE_ACCESS', true);
require_once '../includes/connection.php';

$requiredFields = ["nom", "plat_preparation", "plat_repos", "plat_cuisson", "plat_portions", "ingredient_nom", "ingredient_qts", "ingredient_unite", "etapes"];


$_POST = [
    "nom" => "riene2",
    "plat_preparation" => "10",
    "plat_repos" => "10",
    "plat_cuisson" => "10",
    "plat_portions" => "1",
    "ingredient_nom" => ["Pommes de terre", 'courgette'],
    "ingredient_qts" => ["3", "12"],
    "ingredient_unite" => ["unité", 'unité'],
    "etapes" => ["Eplucher les légunes"]
];

$resultRequiredFields = checkRequiredFields($requiredFields, $_POST);

// Fonction qui vérifie l'intégrité des données du post
function validateRecipeData($data)
{
    $errors = [];
    $cleanData = [];

    // Nettoyage des champs simples
    $simpleFields = ['nom', 'plat_preparation', 'plat_repos', 'plat_cuisson', 'plat_portions'];
    foreach ($simpleFields as $field) {
        if (isset($data[$field])) {
            $cleanData[$field] = cleanInput($data[$field]);
        }
    }

    // Nettoyage des tableaux
    $arrayFields = ['ingredient_nom', 'ingredient_qts', 'ingredient_unite', 'etapes'];
    foreach ($arrayFields as $field) {
        if (isset($data[$field]) && is_array($data[$field])) {
            $cleanData[$field] = array_map('cleanInput', $data[$field]);
        }
    }

    // Validation des champs simples obligatoires
    $requiredFields = [
        'nom' => 'Le nom de la recette est requis',
        'plat_portions' => 'Le nombre de portions est requis'
    ];

    foreach ($requiredFields as $field => $message) {
        if (!isset($cleanData[$field]) || $cleanData[$field] === '') {
            $errors[] = $message;
        }
    }

    // Validation des valeurs numériques (permettant 0)
    $numericFields = [
        'plat_preparation' => 'Le temps de préparation doit être un nombre',
        'plat_repos' => 'Le temps de repos doit être un nombre',
        'plat_cuisson' => 'Le temps de cuisson doit être un nombre',
        'plat_portions' => 'Le nombre de portions doit être un nombre positif'
    ];

    foreach ($numericFields as $field => $message) {
        if (isset($cleanData[$field])) {
            if (!is_numeric($cleanData[$field])) {
                $errors[] = $message;
            } else {
                // Convertir en nombre pour la comparaison
                $cleanData[$field] = (int)$cleanData[$field];

                // Vérifier que les portions sont supérieures à 0
                if ($field === 'plat_portions' && $cleanData[$field] <= 0) {
                    $errors[] = 'Le nombre de portions doit être supérieur à 0';
                }
                // Vérifier que les temps ne sont pas négatifs
                elseif (in_array($field, ['plat_preparation', 'plat_repos', 'plat_cuisson']) && $cleanData[$field] < 0) {
                    $errors[] = str_replace('doit être un nombre', 'ne peut pas être négatif', $message);
                }
            }
        }
    }

    // Validation des tableaux d'ingrédients
    $ingredientArrays = ['ingredient_nom', 'ingredient_qts', 'ingredient_unite'];

    // Vérifier si tous les tableaux d'ingrédients existent
    foreach ($ingredientArrays as $array) {
        if (!isset($cleanData[$array]) || !is_array($cleanData[$array])) {
            $errors[] = "Le tableau $array est manquant ou invalide";
            return ['errors' => $errors, 'cleanData' => $cleanData];
        }
    }

    // Vérifier que les tableaux d'ingrédients ont la même longueur
    $lengths = array_map(function ($array) use ($cleanData) {
        return count($cleanData[$array]);
    }, $ingredientArrays);

    if (count(array_unique($lengths)) > 1) {
        $errors[] = "Les tableaux d'ingrédients doivent avoir le même nombre d'éléments";
    }

    // Vérifier que chaque ingrédient a des données valides
    for ($i = 0; $i < count($cleanData['ingredient_nom']); $i++) {
        if (empty($cleanData['ingredient_nom'][$i])) {
            $errors[] = "Le nom de l'ingrédient " . ($i + 1) . " est requis";
        }
        if (!isset($cleanData['ingredient_qts'][$i]) || !is_numeric($cleanData['ingredient_qts'][$i]) || $cleanData['ingredient_qts'][$i] <= 0) {
            $errors[] = "La quantité de l'ingrédient " . ($i + 1) . " doit être un nombre positif";
        }
        if (empty($cleanData['ingredient_unite'][$i])) {
            $errors[] = "L'unité de l'ingrédient " . ($i + 1) . " est requise";
        }
    }

    // Validation des étapes
    if (!isset($cleanData['etapes']) || !is_array($cleanData['etapes'])) {
        $errors[] = "Les étapes de préparation sont requises";
    } else {
        foreach ($cleanData['etapes'] as $index => $etape) {
            if (empty($etape)) {
                $errors[] = "L'étape " . ($index + 1) . " ne peut pas être vide";
            }
        }
    }

    return [
        'errors' => $errors,
        'cleanData' => $cleanData
    ];
}

// Ajout dans la table recette
function addRecipe(PDO $pdo, array $data, int $userId): array
{
    $response = ['success' => false, 'message' => '', 'recipe_id' => null, 'errors' => []];

    try {
        $sql = "INSERT INTO recettes (
            nom, 
            temps_cuisson, 
            temps_preparation, 
            temps_repos, 
            `portion`, 
            date_ajout, 
            user_id
        ) VALUES (
            :nom, 
            :temps_cuisson, 
            :temps_preparation, 
            :temps_repos, 
            :portion, 
            NOW(), 
            :user_id
        )";

        $stmt = $pdo->prepare($sql);

        $result = $stmt->execute([
            ':nom'              => $data['nom'],
            ':temps_cuisson'    => $data['plat_cuisson'],
            ':temps_preparation' => $data['plat_preparation'],
            ':temps_repos'      => $data['plat_repos'],
            ':portion'         => $data['plat_portions'],
            ':user_id'         => $userId
        ]);

        if (!$result) {
            $response['message'] = "Échec de l'exécution de la requête";
            $response['errors'] = $stmt->errorInfo();
            return $response;
        }

        $recipeId = $pdo->lastInsertId();
        if (!$recipeId) {
            $response['message'] = "Impossible de récupérer l'ID de la recette";
            return $response;
        }

        $response['success'] = true;
        $response['message'] = "Recette ajoutée avec succès";
        $response['recipe_id'] = (int) $recipeId;
    } catch (PDOException $e) {
        $response['message'] = "Erreur lors de l'ajout de la recette";
        $response['errors'][] = $e->getMessage();
    }

    return $response;
}

function addIngredientsToRecipe(PDO $pdo, int $recipeId, array $ingredientsData): array
{
    $response = [
        'success' => false,
        'message' => '',
        'errors' => []
    ];

    try {
        $pdo->beginTransaction();

        // Préparer toutes les requêtes
        $checkIngredient = $pdo->prepare("SELECT id FROM ingredients WHERE nom = :nom LIMIT 1");
        $checkUnite = $pdo->prepare("SELECT id FROM unites_mesure WHERE nom = :nom LIMIT 1");
        $insertIngredient = $pdo->prepare("INSERT INTO ingredients (nom) VALUES (:nom)");
        $insertRecipeIngredient = $pdo->prepare("
            INSERT INTO recette_ingredient 
            (recette_id, ingredient_id, quantite, unite_mesure_id) 
            VALUES 
            (:recette_id, :ingredient_id, :quantite, :unite_mesure_id)
        ");

        foreach ($ingredientsData['ingredient_nom'] as $index => $nomIngredient) {
            if (empty($nomIngredient)) continue;

            // 1. Gérer l'ingrédient
            $checkIngredient->execute(['nom' => $nomIngredient]);
            $ingredient = $checkIngredient->fetch(PDO::FETCH_ASSOC);

            if (!$ingredient) {
                $insertIngredient->execute(['nom' => $nomIngredient]);
                $ingredientId = $pdo->lastInsertId();
            } else {
                $ingredientId = $ingredient['id'];
            }

            // 2. Vérifier l'unité de mesure
            $uniteNom = $ingredientsData['ingredient_unite'][$index] ?? '';
            $uniteId = null;

            if (!empty($uniteNom)) {
                $checkUnite->execute(['nom' => $uniteNom]);
                $unite = $checkUnite->fetch(PDO::FETCH_ASSOC);

                if (!$unite) {
                    throw new Exception("Unité de mesure '$uniteNom' non trouvée dans la base de données");
                }
                $uniteId = $unite['id'];
            }

            // 3. Associer l'ingrédient à la recette
            $insertRecipeIngredient->execute([
                ':recette_id' => $recipeId,
                ':ingredient_id' => $ingredientId,
                ':quantite' => $ingredientsData['ingredient_qts'][$index] ?? null,
                ':unite_mesure_id' => $uniteId
            ]);
        }

        $pdo->commit();

        $response['success'] = true;
        $response['message'] = "Ingrédients ajoutés avec succès";
    } catch (Exception $e) {
        $pdo->rollBack();
        $response['message'] = "Erreur lors de l'ajout des ingrédients";
        $response['errors'][] = $e->getMessage();
    }

    return $response;
}

function addStepsToRecipe(PDO $pdo, int $recipeId, array $steps): array
{
    $response = [
        'success' => false,
        'message' => '',
        'errors' => []
    ];

    try {
        $pdo->beginTransaction();

        $insertStep = $pdo->prepare("
            INSERT INTO recette_etape 
            (recette_id, index_etape, etape) 
            VALUES 
            (:recette_id, :index, :etape)
        ");

        foreach ($steps as $index => $etape) {
            if (empty($etape)) continue;

            $insertStep->execute([
                ':recette_id' => $recipeId,
                ':index' => $index + 1, // On commence à 1 pour l'index
                ':etape' => $etape
            ]);
        }

        $pdo->commit();

        $response['success'] = true;
        $response['message'] = "Étapes ajoutées avec succès";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $response['message'] = "Erreur lors de l'ajout des étapes";
        $response['errors'][] = $e->getMessage();
    }

    return $response;
}

function errorRedirect($errors) {
    $_SESSION['errors'] = $errors;
    header('Location: ../erreur.php');
    exit();
}

// Vérification de la présence de tous les champs
if (!checkRequiredFields($requiredFields, $_POST)) {
    errorRedirect("Certains champs obligatoires sont manquants.");
}

// Vérification des valeurs
$validationResult = validateRecipeData($_POST);

if (!empty($validationResult['errors'])) {
    errorRedirect($validationResult['errors']);
}
// Données nétoyé
$cleanData = $validationResult['cleanData'];

// Ajoute la recette
$recipe = addRecipe($pdo, $cleanData, $_SESSION['id']);

if (!$recipe['success']) {
    errorRedirect($recipe['errors']);
}

$recipeId = $recipe['recipe_id'];
$ingredientsData = [
    'ingredient_nom' => $_POST['ingredient_nom'],
    'ingredient_qts' => $_POST['ingredient_qts'],
    'ingredient_unite' => $_POST['ingredient_unite']
];

$ingredientResult = addIngredientsToRecipe($pdo, $recipeId, $ingredientsData);

if (!$ingredientResult['success']) {
    errorRedirect($ingredientResult['errors']);
}

$stepResult = addStepsToRecipe($pdo, $recipeId, $_POST['etapes']);

if (!$stepResult['success']) {
    errorRedirect($stepResult['errors']);
}

echo "Recette ajoutée avec succès !";

function errorRedirect($errors) {
    $_SESSION['errors'] = $errors;
    header('Location: ../erreur.php');
    exit();
}
