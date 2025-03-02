<?php
class User{
    private PDO $pdo;
    private int $maxAttemps = 3;
    private int $lockoutTime = 600;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function sanitize($data){
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    private function validateEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function validatePassword($password){
        return strlen($password) >= 8 && preg_match('/[A-Z]/', $password) && preg_match('/[0-9]/', $password);
    }

    private function userExists($email) {
        $sql = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ? true : false;
    }

    // Inscription
    public function register($username, $email, $password) {
        // Sécurisation des entrées
        $username = $this->sanitize($username);
        $email = $this->sanitize($email);
        $password = trim($password);

        // Vérification de l'email
        if (!$this->validateEmail($email)) {
            return "Email invalide.";
        }

        // Vérification du mot de passe
        if (!$this->validatePassword($password)) {
            return "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.";
        }

        // Vérification si l'email est déjà utilisé
        if ($this->userExists($email)) {
            return "Cet email est déjà utilisé.";
        }

        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insertion dans la base de données
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashedPassword])) {
            return true;
        } else {
            return "Erreur lors de l'inscription.";
        }
    }


}