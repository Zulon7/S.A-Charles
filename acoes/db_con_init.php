<?php 

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