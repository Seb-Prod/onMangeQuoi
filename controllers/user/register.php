<?php
session_start();
var_dump($_POST);
define('SECURE_ACCESS', true);
include '../../models/user.php';
include '../../includes/connection.php';
?>