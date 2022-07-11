<?php 
$host="localhost";
$user="root";
$password="";
$dbname="todo_app";

// DSN
$dsn= "mysql:host=$host;dbname=$dbname";

//créer une instance PDO
try{
$pdo= new PDO($dsn, $user, $password,);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo " Exception message: " . 
    $e->getMessage();
}


