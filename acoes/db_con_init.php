<?php

// O php.ini do XAMPP vem com o fuso de Berlim; usa o horário de Brasília
// (importante para saber se o prazo de entrega já passou)
date_default_timezone_set('America/Sao_Paulo');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "s.a";

try {
$conexao = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}  catch(PDOException $e) {
    die("Erro ao connectar no db: ". $e->getMessage());
}

?>