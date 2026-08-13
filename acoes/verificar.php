<?php

  $conexao = new mysqli("127.0.0.1", "root", "", "samedia");

  if ($conexao->connect_errno) {
        die("Erro: " . $conexao->connect_error);
    }

  $usuario = mysql_real_escape_string($_POST["usuario"]);
  $senha = mysql_real_escape_string($_POST["senha"]);

  // Validação do usuário/senha digitados
  $sql = "SELECT `id`, `senha` FROM `users` WHERE (`id` = ".$_SESSION['id'] .") AND (`senha` = ". $_SESSION['senha'] .") AND (`ativo` = 1) LIMIT 1";
  $query = mysql_query($sql);
  if ((mysql_num_rows($query) != 1) || session_status == PHP_SESSION_NONE || PHP_SESSION_DISABLED)  {
      // Mensagem de erro quando os dados são inválidos e/ou o usuário não foi encontrado
      echo "Login inválido!"; $_SESSION = array(); exit;
  } else {
      // Salva os dados encontados na variável $resultado
      echo "Sessão verificada";
  }

  $conexao -> close();

  ?>