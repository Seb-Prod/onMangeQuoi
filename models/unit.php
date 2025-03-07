<?php
class Unit{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo=$pdo;
    }

    public function get():array{
        try{
            $sql = "SELECT * FROM unites_mesure";
            $stmt  = $this->pdo->prepare($sql);
            $stmt->execute();
            $unit = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($unit){
                return ['success' => true, 'datas'=>$unit];
            }else{
                return ['success' => true,
                'datas'=>[]];
            }
        }catch(PDOException $e){
            return ['success'=>false, 'message' => "Une erreur est survenue. Veuillez réesayer plus tard."];
        }
    }

}