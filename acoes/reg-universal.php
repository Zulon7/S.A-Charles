<?php
    //Ação de registro para qualquer tabela relacional n para n
    
    require_once("db_con_init.php"); 

    $nome_tabela = $_POST["nome_tabela"];
    $nome_coluna1 = $_POST["nome_coluna1"];
    $nome_coluna2 = $_POST["nome_coluna2"];
    $info_coluna1 = $_POST["info_coluna1"];
    $info_coluna2 = $_POST["info_coluna2"];

    $sql = "
    INSERT INTO ? (?, ?)
    VALUES ('?', '?')
    ";

    $stmt = $conexao -> prepare($sql);
    $stmt -> execute([$nome_tabela ,$nome_coluna1, $nome_coluna2, $info_coluna1, $info_coluna2]);

   
?>