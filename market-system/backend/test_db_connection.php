<?php
$dsn = 'mysql:host=localhost;port=3306;dbname=market_on';
$user = 'root'; 
$pass = '1234'; 

try {
    $pdo = new PDO($dsn, $user, $pass);
    echo "Conexão estabelecida com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao conectar: " . $e->getMessage();
}