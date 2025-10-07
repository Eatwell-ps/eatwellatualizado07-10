<?php
$host = "localhost";       
$dbname = "EatWell"; 
$user = "root";           
$pass = "";                

try {
    
    $mysql = new mysql("mysql:host=$host;dbname=$dbname;EatWell", $user, $pass);

    $mysql->setAttribute(mysql::ATTR_ERRMODE, mysql::ERRMODE_EXCEPTION);
} catch (mysqlException $e) {
    die("Erro de conexão: " . $e->getMessage());
}