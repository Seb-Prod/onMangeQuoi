<?php
function monVarDump($var){
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

function checkRequiredFields($requiredFields, $var)
{
    foreach ($requiredFields as $field) {
        if (!isset($var[$field]) || empty(trim($var[$field]))) {
            return false;
        }
    }
    return true;
}

function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
 }