<?php
class User {
    private PDO $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    private function validateUserData(string $email, string $pseudo, array $data): array {
        $errors = [];
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide";
        }
        
        if (strlen($pseudo) < 3 || strlen($pseudo) > 50) {
            $errors[] = "Le pseudo doit contenir entre 3 et 50 caractères";
        }
        
        foreach ($data as $key => $value) {
            if (empty(trim($value)) && $key !== 'password') {
                $errors[] = "Le champ $key est requis";
            }
        }
        
        return $errors;
    }
    
    private function checkUserExists(string $email, string $pseudo): bool {
        $sql = "SELECT COUNT(*) FROM users WHERE email = ? OR pseudo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email, $pseudo]);
        return $stmt->fetchColumn() > 0;
    }
    
    public function createUser(
        string $pseudo,
        string $nom,
        string $prenom,
        string $email,
        string $password,
        bool $admin = false
    ): array {
        $response = [
            'success' => false,
            'message' => '',
            'errors' => []
        ];
        
        try {
            $this->pdo->beginTransaction();
            
            // Validation des données
            $errors = $this->validateUserData($email, $pseudo, [
                'nom' => $nom,
                'prenom' => $prenom,
                'password' => $password
            ]);
            
            if (!empty($errors)) {
                $response['errors'] = $errors;
                $response['message'] = "Erreurs de validation";
                return $response;
            }
            
            // Vérification si l'utilisateur existe déjà
            if ($this->checkUserExists($email, $pseudo)) {
                $response['message'] = "Email ou pseudo déjà utilisé";
                return $response;
            }
            
            // Hash du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (pseudo, nom, prenom, email, pass, admin, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                htmlspecialchars($pseudo),
                htmlspecialchars($nom),
                htmlspecialchars($prenom),
                $email,
                $hashedPassword,
                (int)$admin
            ]);
            
            $this->pdo->commit();
            
            if ($result) {
                $response['success'] = true;
                $response['message'] = "Utilisateur créé avec succès";
            } else {
                $response['message'] = "Erreur lors de la création de l'utilisateur";
            }
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $response['message'] = "Erreur système : " . $e->getMessage();
        }
        
        return $response;
    }
    
    public function login(string $pseudo, string $password): array {
        $response = [
            'success' => false,
            'message' => '',
            'user_data' => null
        ];
        
        try {
            $sql = "SELECT pseudo, id, pass, admin, blocked, failed_attempts, last_failed_attempt 
                    FROM users WHERE pseudo = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([htmlspecialchars($pseudo)]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                $response['message'] = "Identifiants invalides";
                return $response;
            }
            
            // Vérification si le compte est bloqué
            if ($user['blocked']) {
                if (time() - strtotime($user['last_failed_attempt']) < 1800) {
                    $remainingTime = ceil((1800 - (time() - strtotime($user['last_failed_attempt']))) / 60);
                    $response['message'] = "Compte bloqué. Réessayez dans {$remainingTime} minutes";
                    return $response;
                }
                $this->resetFailedAttempts($user['id']);
            }
            
            if (!password_verify($password, $user['pass'])) {
                $this->incrementFailedAttempts($user['id']);
                $response['message'] = "Identifiants invalides";
                return $response;
            }
            
            // Réinitialisation des tentatives échouées
            $this->resetFailedAttempts($user['id']);
            
            // Régénération de l'ID de session
            session_regenerate_id(true);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['admin'] = (bool)$user['admin'];
            $_SESSION['last_activity'] = time();
            $_SESSION['pseudo'] = $user['pseudo'];
            
            $response['success'] = true;
            $response['message'] = "Connexion réussie";
            $response['user_data'] = [
                'id' => $user['id'],
                'admin' => (bool)$user['admin']
            ];
            
        } catch (Exception $e) {
            $response['message'] = "Erreur système : " . $e->getMessage();
        }
        
        return $response;
    }
    
    public function updateUser(
        int $id,
        string $pseudo,
        string $nom,
        string $prenom,
        string $email,
        ?string $password = null,
        bool $admin = false
    ): array {
        $response = [
            'success' => false,
            'message' => '',
            'errors' => []
        ];
        
        try {
            $this->pdo->beginTransaction();
            
            // Validation des données
            $errors = $this->validateUserData($email, $pseudo, [
                'nom' => $nom,
                'prenom' => $prenom
            ]);
            
            if (!empty($errors)) {
                $response['errors'] = $errors;
                $response['message'] = "Erreurs de validation";
                return $response;
            }
            
            // Vérification si l'email/pseudo existe déjà pour un autre utilisateur
            $sql = "SELECT COUNT(*) FROM users WHERE (email = ? OR pseudo = ?) AND id != ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$email, $pseudo, $id]);
            if ($stmt->fetchColumn() > 0) {
                $response['message'] = "Email ou pseudo déjà utilisé";
                return $response;
            }
            
            if ($password) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET 
                        pseudo = ?, 
                        nom = ?, 
                        prenom = ?, 
                        email = ?, 
                        pass = ?, 
                        admin = ?,
                        updated_at = NOW()
                        WHERE id = ?";
                $params = [
                    htmlspecialchars($pseudo),
                    htmlspecialchars($nom),
                    htmlspecialchars($prenom),
                    $email,
                    $hashedPassword,
                    (int)$admin,
                    $id
                ];
            } else {
                $sql = "UPDATE users SET 
                        pseudo = ?, 
                        nom = ?, 
                        prenom = ?, 
                        email = ?, 
                        admin = ?,
                        updated_at = NOW()
                        WHERE id = ?";
                $params = [
                    htmlspecialchars($pseudo),
                    htmlspecialchars($nom),
                    htmlspecialchars($prenom),
                    $email,
                    (int)$admin,
                    $id
                ];
            }
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            
            $this->pdo->commit();
            
            if ($result) {
                $response['success'] = true;
                $response['message'] = "Utilisateur mis à jour avec succès";
            } else {
                $response['message'] = "Erreur lors de la mise à jour de l'utilisateur";
            }
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $response['message'] = "Erreur système : " . $e->getMessage();
        }
        
        return $response;
    }
    
    public function deleteUser(int $id): array {
        $response = [
            'success' => false,
            'message' => ''
        ];
        
        try {
            $this->pdo->beginTransaction();
            
            // Vérification si l'utilisateur existe
            $sql = "SELECT COUNT(*) FROM users WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() == 0) {
                $response['message'] = "Utilisateur non trouvé";
                return $response;
            }
            
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$id]);
            
            $this->pdo->commit();
            
            if ($result) {
                $response['success'] = true;
                $response['message'] = "Utilisateur supprimé avec succès";
            } else {
                $response['message'] = "Erreur lors de la suppression de l'utilisateur";
            }
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $response['message'] = "Erreur système : " . $e->getMessage();
        }
        
        return $response;
    }
    
    private function incrementFailedAttempts(int $userId): void {
        $sql = "UPDATE users SET 
                failed_attempts = failed_attempts + 1,
                last_failed_attempt = NOW(),
                blocked = CASE WHEN failed_attempts >= 4 THEN 1 ELSE blocked END
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
    }
    
    private function resetFailedAttempts(int $userId): void {
        $sql = "UPDATE users SET failed_attempts = 0, blocked = 0, last_failed_attempt = NULL WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
    }
}