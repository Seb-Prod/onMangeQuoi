<?php
class Ingredient{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo=$pdo;
    }

    public function get():array{
        try{
            $sql = "SELECT * FROM ingredints";
            $stmt  = $this->pdo->prepare($sql);
            $stmt->execute();
            $ingredient = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($ingredient){
                return ['success' => true, 'datas'=>$ingredient];
            }else{
                return ['success' => true,
                'datas'=>[]];
            }
        }catch(PDOException $e){
            return ['success'=>false, 'message' => "Une erreur est survenue. Veuillez réesayer plus tard."];
        }
    }

}