<?php
class RecipeType{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get(){
        try{
            $sql = "SELECT nom_type FROM types_plat";
            $stmt  = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_NUM);

            if($result){
                $datas = array_map(function($row){
                    return $row[0];
                }, $result);
                return ['success' => true, 'datas'=>$datas];
            }else{
                return ['success' => true,
                'datas'=>[]];
            }
        }catch(PDOException $e){
            return ['success'=>false, 'message' => "Une erreur est survenue. Veuillez réesayer plus tard."];
        }
    }
}