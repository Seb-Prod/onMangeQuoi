<?php
function isTableEmptySafe($pdo, $tableName)
{
    try {
        $query = "SELECT EXISTS (SELECT 1 FROM {$tableName} LIMIT 1)";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $isEmpty = !$stmt->fetchColumn();

        return [
            'result' => true,
            'message' => $isEmpty ? "La table $tableName est vide" : "La table $tableName contient des données",
            'data' => $isEmpty
        ];
    } catch (PDOException $e) {
        return [
            'result' => false,
            'message' => "Erreur lors de la vérification de la table: " . $e->getMessage(),
            'data' => [
                'tableName' => $tableName,
                'errorCode' => $e->getCode()
            ]
        ];
    }
}
