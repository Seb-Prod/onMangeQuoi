<?php
function razVarSessionUser()
{
    unset($_SESSION["newUser_pseudo"]);
    unset($_SESSION["newUser_nom"]);
    unset($_SESSION["newUser_prenom"]);
    unset($_SESSION["newUser_email"]);
    unset($_SESSION["pass"]);
    unset($_SESSION['newUser']);
}



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

function userConect($pdo, $pseudo, $pass)
{
    try {
        $sql = "SELECT pseudo,nom,prenom, pass, admin FROM users WHERE pseudo = :pseudo LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':pseudo' => $pseudo]);


        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($pass, $user["pass"])) {
                return [
                    'result' => true,
                    'data' => [
                        'nom' => $user['nom'],
                        'prenom' => $user['prenom'],
                        'pseudo' => $user['pseudo'],
                        'admin' => $user['admin']
                    ]
                ];
            } else {
                return [
                    'result' => false,
                    'message' => 'Mauvais pass',
                    'error' => [
                        'message' => 'Mauvais pass',
                        'code' => "pass"
                    ]
                ];
            }
        } else {
            return [
                'result' => false,
                'message' => 'Mauvais user',
                'error' => [
                    'message' => 'Mauvais user',
                    'code' => 'user'
                ]
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
}
