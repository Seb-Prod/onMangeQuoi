<?php
function getList(PDO $pdo, string $modelName) {
    $className = ucfirst($modelName);
    if(class_exists($className)){
        $model = new $className($pdo);
        $data = $model->get();
        return $data['success'] ? (new FormDataList($modelName, $data['datas']))->render() : '';
    }else{
        return '';
    }
}