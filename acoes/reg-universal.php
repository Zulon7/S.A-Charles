<?php
    //Ação de registro para qualquer tabela relacional n para n
    
    include("db_con_init.php"); 

    $nome_tabela = $_POST["nome_tabela"];
    $nome_coluna1 = $_POST["nome_coluna1"];
    $nome_coluna2 = $_POST["nome_coluna2"];
    $info_coluna1 = $_POST["info_coluna1"];
    $info_coluna2 = $_POST["info_coluna2"];

    $sql = "
    INSERT INTO $nome_tabela ($nome_coluna1, $nome_coluna2)
    VALUES ('$info_coluna1', '$info_coluna2')
    ";

    $conexao->close();
?>