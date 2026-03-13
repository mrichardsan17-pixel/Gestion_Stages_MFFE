<?php

$host   = 'localhost';
$dbname = 'mffe_gestion';
$user   = 'root';
$pass   = '';
$port   = '3306'; 
$charset = 'utf8mb4'; 


$dsn = "mysql:host=$host;dbname=$dbname;port=$port;charset=$charset";


$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    PDO::ATTR_EMULATE_PREPARES   => false,                  
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    
    error_log($e->getMessage()); 
    die("Désolé, une erreur de connexion est survenue. Veuillez réessayer plus tard.");
}
?>