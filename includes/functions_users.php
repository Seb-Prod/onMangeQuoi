<?php
function addUser($pdo, $pseudo, $nom, $prenom, $email, $pass, $admin)
{
    try {
        // Hashage du mot de passe
        $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

        // Préparation de la requête
        $sql = "INSERT INTO users (pseudo, nom, prenom, email, pass, admin) VALUES (:pseudo, :nom, :prenom, :email, :pass, :admin)";
        $stmt = $pdo->prepare($sql);

        // Exécution de la requête avec les paramètres
        $result = $stmt->execute([
            ':pseudo' => $pseudo,
            ':pass' => $hashedPassword,
            ':admin' => $admin,
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email
        ]);

        if ($result) {
            return [
                'result' => true
            ];
        } else {
            return [
                'result' => false,
                'message' => $result
            ];
        }
    } catch (PDOException $e) {
        return [
            'result' => false,
            'message' => $e->getMessage(),
            'error' => [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]
        ];
    }
    return $message;
}
