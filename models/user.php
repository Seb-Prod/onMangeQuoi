<?php
class User
{
    private PDO $pdo;
    private int $maxAttemps = 3;
    private int $lockoutTime = 600;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function sanitize(string $data): string
    {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    private function validateName(string $name, int $minLength = 2, int $maxLength = 50): bool
    {
        if (empty($name)) {
            return false; // Le nom ne peut pas être vide
        }

        $name = trim($name); // Supprimer les espaces inutiles

        if (strlen($name) < $minLength || strlen($name) > $maxLength) {
            return false; // Longueur invalide
        }

        if (!preg_match("/^[\p{L} '-]+$/u", $name)) {
            return false; // Caractères invalides
        }

        return true; // Validation réussie
    }

    private function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function validatePassword(string $password): bool
    {
        return strlen($password) >= 8 && preg_match('/[A-Z]/', $password) && preg_match('/[0-9]/', $password);
    }

    private function userExists(string $value, string $champ): bool
    {
        $champ = strtolower(trim($champ));

        if ($champ !== 'email' && $champ !== 'pseudo') {
            return false;
        }

        try {
            $sql = "SELECT id FROM users WHERE $champ = :value";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['value' => $value]);

            return $stmt->fetch() ? true : false;
        } catch (PDOException $e) {
            // Gestion de l'erreur (par exemple, journalisation, affichage d'un message)
            error_log("Erreur PDO dans userExists: " . $e->getMessage());
            return false; // Ou lancez une exception, selon votre gestion des erreurs
        }
    }

    // Inscription
    public function register(string $lastName, string $firstName, string $username, string $email, string $password, string $confirmation, bool $admin = false): array
{
    $success = true;
    $values = [];

    // Sécurisation des entrées
    $username = $this->sanitize($username);
    $lastName = $this->sanitize($lastName);
    $firstName = $this->sanitize($firstName);
    $email = $this->sanitize($email);
    $password = trim($password);
    $confirmation = trim($confirmation);

    // Vérification de l'email
    if (!$this->validateEmail($email)) {
        $success = false;
        $values['email'] = ['value' => false, 'message' => "Email invalide."];
    } else {
        $values['email'] = ['value' => true, 'message' => $email];
    }

    // Vérification du nom
    if (!$this->validateName($lastName)) {
        $success = false;
        $values['nom'] = ['value' => false, 'message' => "Nom de famille invalide."];
    } else {
        $values['nom'] = ['value' => true, 'message' => $lastName];
    }

    // Vérification du prénom
    if (!$this->validateName($firstName)) {
        $success = false;
        $values['prenom'] = ['value' => false, 'message' => "Prénom invalide."];
    } else {
        $values['prenom'] = ['value' => true, 'message' => $firstName];
    }

    // Vérification du mot de passe
    if (!$this->validatePassword($password)) {
        $success = false;
        $values['pass'] = ['value' => false, 'message' => "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre."];
        $values['confirmPass'] = ['value' => false, 'message' => ""];
    } else {
        $values['pass'] = ['value' => true, 'message' => ""];
        $values['confirmPass'] = ['value' => true, 'message' => ""];
    }

    // Vérification si l'email est déjà utilisé
    if ($this->userExists($email, 'email')) {
        $success = false;
        $values['email'] = ['value' => false, 'message' => "Cet email est déjà utilisé."];
    } else {
        $values['email'] = ['value' => true, 'message' => $email];
    }

    // Vérification si le pseudo est déjà utilisé
    if ($this->userExists($username, 'pseudo')) {
        $success = false;
        $values['pseudo'] = ['value' => false, 'message' => "Ce pseudo est déjà utilisé."];
    } else {
        $values['pseudo'] = ['value' => true, 'message' => $username];
    }

    // Vérification si les mots de passe correspondent
    if ($password !== $confirmation) {
        $success = false;
        $values['pass'] = ['value' => false, 'message' => "Les mots de passe ne correspondent pas."];
        $values['confirmPass'] = ['value' => false, 'message' => ""];
    } else {
        $values['pass'] = ['value' => true, 'message' => ""];
        $values['confirmPass'] = ['value' => true, 'message' => ""];
    }

    // Hachage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Si aucune erreur Insertion dans la base de données
    if ($success) {
        try {
            $sql = "INSERT INTO users (nom, prenom, pseudo, email, pass, admin, created_at) VALUES (:nom, :prenom, :username, :email, :pass, :admin, NOW())";
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute(
                [
                    'nom' => $lastName,
                    'prenom' => $firstName,
                    'username' => $username,
                    'email' => $email,
                    'pass' => $hashedPassword,
                    'admin' => intval($admin)
                ]
            )) {
                $success = true;
                $values['register'] = ['value' => true, 'message' => "L'inscription est validée"];
            } else {
                $success = false;
                $values['register'] = ['value' => false, 'message' => "Erreur lors de l'inscription."];
            }
        } catch (PDOException $e) {
            $success = false;
            $values['register'] = ['value' => false, 'message' => "Erreur de base de données : " . $e->getMessage()];
        }
    }

    return ['success' => $success, 'values' => $values];
}
}
