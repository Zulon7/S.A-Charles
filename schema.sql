-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10/08/2026 às 14:43
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sa` (o mesmo nome usado em acoes/db_con_init.php)
-- Crie antes de importar:
--   CREATE DATABASE `sa` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- Ao importar o seed.sql pela linha de comando, use --default-character-set=utf8mb4
--

-- --------------------------------------------------------


DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `user_participa`;
DROP TABLE IF EXISTS `user_modera`;
DROP TABLE IF EXISTS `turmas`;
DROP TABLE IF EXISTS `propostas`;
DROP TABLE IF EXISTS `projetos`;
DROP TABLE IF EXISTS `arquivos`;
DROP TABLE IF EXISTS `curtidas`;


--
-- Estrutura para tabela `turmas`
--

CREATE TABLE `turmas` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `data_criado` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `propostas`
-- Um professor cria uma proposta em uma turma (id_turma)
-- e os alunos da turma enviam projetos para ela
--

CREATE TABLE `propostas` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `id_turma` int(11) NOT NULL,
  `id_professor` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descricao` text,
  `data_criado` date NOT NULL DEFAULT current_timestamp(),
  `data_entrega` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `projetos`
-- Projeto que um aluno (id_aluno) envia para uma proposta (id_proposta)
-- Cada aluno só pode ter um projeto por proposta (UNIQUE)
-- O projeto começa como rascunho (data_enviado NULL) e só o aluno vê.
-- Quando o aluno clica em "Enviar projeto" (data_enviado preenchida),
-- o projeto fica público: aparece no feed para todos os usuários
--

CREATE TABLE `projetos` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `id_proposta` int(11) NOT NULL,
  `id_aluno` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descricao` text,
  `data_criado` datetime NOT NULL DEFAULT current_timestamp(),
  `data_enviado` datetime DEFAULT NULL,
  UNIQUE (`id_proposta`, `id_aluno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `arquivos`
-- Arquivos anexados a uma proposta (pelo professor) OU a um projeto (pelo aluno).
-- Só uma das duas colunas é preenchida: id_proposta ou id_projeto.
-- O arquivo fica na pasta uploads/ com um nome aleatório (nome_salvo);
-- nome_original é o nome que o usuário enviou
--

CREATE TABLE `arquivos` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `id_proposta` int(11) DEFAULT NULL,
  `id_projeto` int(11) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `nome_original` varchar(255) NOT NULL,
  `nome_salvo` varchar(64) NOT NULL UNIQUE,
  `tamanho` int(11) NOT NULL,
  `data_envio` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curtidas`
-- Um usuário (id_user) curtiu um projeto (id_projeto).
-- A chave primária com as duas colunas impede curtir o mesmo projeto duas vezes.
-- No feed, os projetos com mais curtidas aparecem primeiro
--

CREATE TABLE `curtidas` (
  `id_user` int(11) NOT NULL,
  `id_projeto` int(11) NOT NULL,
  `data_criado` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_user`, `id_projeto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `user-modera`
--

CREATE TABLE `user_modera` (
  `id_user` int(11) NOT NULL,
  `id_turma` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `user-participa`
--

CREATE TABLE `user_participa` (
  `id_user` int(11) NOT NULL,
  `id_turma` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL UNIQUE,
  `email` varchar(150) NOT NULL UNIQUE,
  `senha` varchar(150) NOT NULL,
  `data_criado` date NOT NULL DEFAULT current_timestamp(),
  `ativo` BIT NOT NULL DEFAULT 1,
  `nivel` ENUM('aluno', 'professor') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--






COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
