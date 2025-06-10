<?php
$host = 'localhost';     // ou 127.0.0.1
$dbname = 'thander';     // nome do seu banco
$user = 'root';          // nome de usuário do MySQL
$pass = '';              // senha do MySQL (vazia no XAMPP por padrão)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
