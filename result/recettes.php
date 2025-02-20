<?php
session_start();
define('SECURE_ACCESS', true);
require_once '../includes/connection.php';

$_POST = [
    "nom" => "Salade de fruit",
    "plat_preparation" => "15",
    "plat_repos" => "0",
    "plat_cuisson" => "2",
    "plat_portions" => "4",
    "ingredient_nom" => ["Pomme", "Banane", "Orange"],
    "ingredient_qts" => ["2", "1", "3"],
    "ingredient_unite" => ["unité", "unité", "unité"],
    "etapes" => [
        "Éplucher et couper les fruits en morceaux.",
        "Mélanger dans un saladier.",
        "Servir frais."
    ]
];

function addRecipe(PDO $pdo, array $data, int $userId): array
{
    try {
        $sql = "INSERT INTO recettes (nom, temps_cuisson, temps_preparation, temps_repos, `portion`, date_ajout, user_id)
                VALUES (:nom, :temps_cuisson, :temps_preparation, :temps_repos, :portion, NOW(), :user_id)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom'              => $data['nom'],
            ':temps_cuisson'    => $data['plat_cuisson'],
            ':temps_preparation'=> $data['plat_preparation'],
            ':temps_repos'      => $data['plat_repos'],
            ':portion'          => $data['plat_portions'],
            ':user_id'          => $userId
        ]);
        
        return ['success' => true, 'recipe_id' => $pdo->lastInsertId()];
    } catch (PDOException $e) {
        return ['success' => false, 'error' => $e->getMessage(), 'code' => $e->getCode()];
    }
}

function addIngredient(PDO $pdo, string $name): int
{
    $stmt = $pdo->prepare("SELECT id FROM ingredients WHERE nom = :nom LIMIT 1");
    $stmt->execute([':nom' => $name]);
    $ingredient = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$ingredient) {
        $stmt = $pdo->prepare("INSERT INTO ingredients (nom) VALUES (:nom)");
        $stmt->execute([':nom' => $name]);
        return $pdo->lastInsertId();
    }
    return $ingredient['id'];
}

function addUnit(PDO $pdo, string $name): int
{
    $stmt = $pdo->prepare("SELECT id FROM unites_mesure WHERE nom = :nom LIMIT 1");
    $stmt->execute([':nom' => $name]);
    $unit = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$unit) {
        $stmt = $pdo->prepare("INSERT INTO unites_mesure (nom) VALUES (:nom)");
        $stmt->execute([':nom' => $name]);
        return $pdo->lastInsertId();
    }
    return $unit['id'];
}

function addIngredientsToRecipe(PDO $pdo, int $recipeId, array $ingredients): array
{
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO recette_ingredient (recette_id, ingredient_id, quantite, unite_mesure_id) VALUES (:recette_id, :ingredient_id, :quantite, :unite_mesure_id)");
        
        foreach ($ingredients['ingredient_nom'] as $index => $name) {
            if (empty($name)) continue;
            
            $ingredientId = addIngredient($pdo, $name);
            $unitId = addUnit($pdo, $ingredients['ingredient_unite'][$index] ?? '');
            
            $stmt->execute([
                ':recette_id' => $recipeId,
                ':ingredient_id' => $ingredientId,
                ':quantite' => $ingredients['ingredient_qts'][$index] ?? null,
                ':unite_mesure_id' => $unitId
            ]);
        }
        
        $pdo->commit();
        return ['success' => true];
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'error' => $e->getMessage(),'code' => $e->getCode()];
    }
}

function addStepsToRecipe(PDO $pdo, int $recipeId, array $steps): array
{
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO recette_etape (recette_id, index_etape, etape) VALUES (:recette_id, :index, :etape)");
        
        foreach ($steps as $index => $step) {
            if (!empty($step)) {
                $stmt->execute([
                    ':recette_id' => $recipeId,
                    ':index' => $index + 1,
                    ':etape' => $step
                ]);
            }
        }
        
        $pdo->commit();
        return ['success' => true];
    } catch (PDOException $e) {
        $pdo->rollBack();
        return ['success' => false, 'error' => $e->getMessage(),'code' => $e->getCode()];
    }
}

function validateRequiredFields(array $data, array $requiredFields): array
{
    $missingFields = [];
    
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || $data[$field] === '') {
            $missingFields[] = $field;
        }
    }
    
    if (!empty($missingFields)) {
        return ['success' => false, 'missing_fields' => $missingFields];
    }
    
    return ['success' => true];
}

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

    // Validation des champs obligatoires
    $requiredFields = ['nom', 'plat_portions', 'etapes'];
    foreach ($requiredFields as $field) {
        if (empty($cleanData[$field])) {
            $errors[] = "Le champ $field est requis";
        }
    }

    // Validation des valeurs numériques
    $numericFields = ['plat_preparation', 'plat_repos', 'plat_cuisson', 'plat_portions'];
    foreach ($numericFields as $field) {
        if (isset($cleanData[$field]) && (!is_numeric($cleanData[$field]) || $cleanData[$field] < 0)) {
            $errors[] = "Le champ $field doit être un nombre positif";
        }
    }

    // Validation des tableaux d'ingrédients
    $ingredientArrays = ['ingredient_nom', 'ingredient_qts', 'ingredient_unite'];
    foreach ($ingredientArrays as $array) {
        if (!isset($cleanData[$array]) || !is_array($cleanData[$array])) {
            $errors[] = "Le tableau $array est manquant ou invalide";
            return ['errors' => $errors, 'cleanData' => $cleanData];
        }
    }

    if (count(array_unique(array_map('count', array_intersect_key($cleanData, array_flip($ingredientArrays))))) > 1) {
        $errors[] = "Les tableaux d'ingrédients doivent avoir la même longueur";
    }

    foreach ($cleanData['ingredient_nom'] as $i => $nom) {
        if (empty($nom)) {
            $errors[] = "Le nom de l'ingrédient " . ($i + 1) . " est requis";
        }
        if (empty($cleanData['ingredient_qts'][$i]) || !is_numeric($cleanData['ingredient_qts'][$i]) || $cleanData['ingredient_qts'][$i] <= 0) {
            $errors[] = "La quantité de l'ingrédient " . ($i + 1) . " doit être un nombre positif";
        }
        if (empty($cleanData['ingredient_unite'][$i])) {
            $errors[] = "L'unité de l'ingrédient " . ($i + 1) . " est requise";
        }
    }

    foreach ($cleanData['etapes'] as $index => $etape) {
        if (empty($etape)) {
            $errors[] = "L'étape " . ($index + 1) . " ne peut pas être vide";
        }
    }

    return ['errors' => $errors, 'cleanData' => $cleanData];
}

function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
 }

function errorRedirect($errors) {
    $_SESSION['errors'] = $errors;
    header('Location: ../erreur.php');
    exit();
}

// Vérification des champs requis
$requiredFields = ["nom", "plat_preparation", "plat_repos", "plat_cuisson", "plat_portions", "ingredient_nom", "ingredient_qts", "ingredient_unite", "etapes"];
$validationResult = validateRequiredFields($_POST, $requiredFields);
if (!$validationResult['success']) {
    errorRedirect(["Champs obligatoires manquants: " . implode(", ", $validationResult['missing_fields'])]);
}

// Nettoyage et validation des données
$validatedData = validateRecipeData($_POST);
if (!empty($validatedData['errors'])) {
    errorRedirect($validatedData['errors']);
}

$cleanData = $validatedData['cleanData'];
$userId = $_SESSION['id'] ?? null;
if (!$userId) {
    errorRedirect(["Utilisateur non authentifié"]);
}

// Ajout de la recette
global $pdo;
$recipeResult = addRecipe($pdo, $cleanData, $userId);
if (!$recipeResult['success']) {
    errorRedirect(["Erreur lors de l'ajout de la recette: " . $recipeResult['error']]);
}
$recipeId = $recipeResult['recipe_id'];

// Ajout des ingrédients
$ingredientResult = addIngredientsToRecipe($pdo, $recipeId, $cleanData);
if (!$ingredientResult['success']) {
    errorRedirect(["Erreur lors de l'ajout des ingrédients: " . $ingredientResult['error']]);
}

// Ajout des étapes
$stepResult = addStepsToRecipe($pdo, $recipeId, $cleanData['etapes']);
if (!$stepResult['success']) {
    errorRedirect(["Erreur lors de l'ajout des étapes: " . $stepResult['error']]);
}

// Succès
$_SESSION['success'] = "Recette ajoutée avec succès !";
header('Location: ../recetteValide.php');
exit();