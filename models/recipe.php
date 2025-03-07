<?php
class Recipe {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function exist($name) {
        try {
            // Requête SQL préparée pour vérifier l'existence d'une recette par son nom.
            $sql = "SELECT COUNT(*) FROM recettes WHERE nom = :nom";
            $stmt = $this->pdo->prepare($sql);

            // Liaison du paramètre.
            $stmt->bindParam(':nom', $name, PDO::PARAM_STR);

            // Exécution de la requête.
            $stmt->execute();

            // Récupération du résultat.
            $count = $stmt->fetchColumn();

            // Retourne true si la recette existe, false sinon.
            return ['success' => true, 'datas'=>$count > 0];
        } catch (PDOException $e) {
            return ['success' => false];
        }
    }
}
?>