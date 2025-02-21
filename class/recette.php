<?php
class Recipe {
    private PDO $pdo;
    private array $errors = [];
    private array $cleanData = [];
    
    // Constantes pour les messages d'erreur
    private const ERR_MISSING_FIELDS = "Champs obligatoires manquants: %s";
    private const ERR_INVALID_NUMBER = "Le champ %s doit être un nombre positif";
    private const ERR_EMPTY_STEP = "L'étape %d ne peut pas être vide";
    private const ERR_DUPLICATE_NAME = "Une recette avec le nom '%s' existe déjà";
    
    // Code d'erreur MySQL pour violation de contrainte d'unicité
    private const MYSQL_DUPLICATE_ERROR = '23000';

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    public function create(array $data, int $userId): bool {
        // Validation et nettoyage des données
        if (!$this->validateData($data)) {
            return false;
        }
        
        try {
            $this->pdo->beginTransaction();

            // Vérification du doublon avant l'insertion
            if ($this->recipeNameExists($this->cleanData['nom'])) {
                $this->errors[] = sprintf(self::ERR_DUPLICATE_NAME, $this->cleanData['nom']);
                return false;
            }
            
            // Ajout de la recette
            $recipeId = $this->addRecipe($userId);
            if (!$recipeId) throw new Exception("Erreur lors de l'ajout de la recette");
            
            // Ajout des ingrédients
            $this->addIngredients($recipeId);
            
            // Ajout des étapes
            $this->addSteps($recipeId);
            
            $this->pdo->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            
            // Gestion spécifique des erreurs de doublon SQL
            if ($e->getCode() === self::MYSQL_DUPLICATE_ERROR) {
                $this->errors[] = sprintf(self::ERR_DUPLICATE_NAME, $this->cleanData['nom']);
            } else {
                $this->errors[] = "Erreur de base de données : " . $e->getMessage();
            }
            return false;
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $this->errors[] = $e->getMessage();
            return false;
        }
    }

    public function isDuplicateError(): bool {
        foreach ($this->errors as $error) {
            if (str_starts_with($error, "Une recette avec le nom")) {
                return true;
            }
        }
        return false;
    }

    private function recipeNameExists(string $name): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM recettes WHERE nom = :nom");
        $stmt->execute([':nom' => $name]);
        return (bool)$stmt->fetchColumn();
    }
    
    private function addRecipe(int $userId): int {
        $sql = "INSERT INTO recettes (nom, temps_cuisson, temps_preparation, temps_repos, 
                `portion`, date_ajout, user_id)
                VALUES (:nom, :temps_cuisson, :temps_preparation, :temps_repos, 
                :portion, NOW(), :user_id)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $this->cleanData['nom'],
            ':temps_cuisson' => $this->cleanData['plat_cuisson'],
            ':temps_preparation' => $this->cleanData['plat_preparation'],
            ':temps_repos' => $this->cleanData['plat_repos'],
            ':portion' => $this->cleanData['plat_portions'],
            ':user_id' => $userId
        ]);
        
        return $this->pdo->lastInsertId();
    }
    
    private function addIngredients(int $recipeId): void {
        foreach ($this->cleanData['ingredient_nom'] as $index => $name) {
            $ingredientId = $this->getOrCreateIngredient($name);
            $unitId = $this->getOrCreateUnit($this->cleanData['ingredient_unite'][$index]);
            
            $stmt = $this->pdo->prepare("INSERT INTO recette_ingredient 
                    (recette_id, ingredient_id, quantite, unite_mesure_id) 
                    VALUES (:recette_id, :ingredient_id, :quantite, :unite_mesure_id)");
                    
            $stmt->execute([
                ':recette_id' => $recipeId,
                ':ingredient_id' => $ingredientId,
                ':quantite' => $this->cleanData['ingredient_qts'][$index],
                ':unite_mesure_id' => $unitId
            ]);
        }
    }
    
    private function addSteps(int $recipeId): void {
        $stmt = $this->pdo->prepare("INSERT INTO recette_etape 
                (recette_id, index_etape, etape) 
                VALUES (:recette_id, :index, :etape)");
                
        foreach ($this->cleanData['etapes'] as $index => $step) {
            $stmt->execute([
                ':recette_id' => $recipeId,
                ':index' => $index + 1,
                ':etape' => $step
            ]);
        }
    }
    
    private function getOrCreateIngredient(string $name): int {
        $stmt = $this->pdo->prepare("SELECT id FROM ingredients WHERE nom = :nom LIMIT 1");
        $stmt->execute([':nom' => $name]);
        $ingredient = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$ingredient) {
            $stmt = $this->pdo->prepare("INSERT INTO ingredients (nom) VALUES (:nom)");
            $stmt->execute([':nom' => $name]);
            return $this->pdo->lastInsertId();
        }
        return $ingredient['id'];
    }
    
    private function getOrCreateUnit(string $name): int {
        $stmt = $this->pdo->prepare("SELECT id FROM unites_mesure WHERE nom = :nom LIMIT 1");
        $stmt->execute([':nom' => $name]);
        $unit = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$unit) {
            $stmt = $this->pdo->prepare("INSERT INTO unites_mesure (nom) VALUES (:nom)");
            $stmt->execute([':nom' => $name]);
            return $this->pdo->lastInsertId();
        }
        return $unit['id'];
    }
    
    private function validateData(array $data): bool {
        $this->cleanData = [];
        $this->errors = [];
        
        // Nettoyage des données
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->cleanData[$key] = array_map('trim', $value);
            } else {
                $this->cleanData[$key] = trim($value);
            }
        }
        
        // Validation des champs requis
        $requiredFields = ['nom', 'plat_portions', 'ingredient_nom', 'etapes'];
        foreach ($requiredFields as $field) {
            if (empty($this->cleanData[$field])) {
                $this->errors[] = sprintf(self::ERR_MISSING_FIELDS, $field);
            }
        }

        // Validation des étapes vides
        if (isset($this->cleanData['etapes']) && is_array($this->cleanData['etapes'])) {
            foreach ($this->cleanData['etapes'] as $index => $step) {
                if (trim($step) === '') {
                    $this->errors[] = sprintf(self::ERR_EMPTY_STEP, $index + 1);
                }
            }
        }
        
        // Validation des nombres
        $numericFields = ['plat_preparation', 'plat_repos', 'plat_cuisson', 'plat_portions'];
        foreach ($numericFields as $field) {
            if (!is_numeric($this->cleanData[$field]) || $this->cleanData[$field] < 0) {
                $this->errors[] = sprintf(self::ERR_INVALID_NUMBER, $field);
            }
        }
        
        return empty($this->errors);
    }
    
    public function getErrors(): array {
        return $this->errors;
    }
}
?>