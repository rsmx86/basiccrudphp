-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17-Dez-2025 às 19:23
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `basic_cruc_di3`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cadastros`
--

CREATE TABLE `cadastros` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `cadastros`
--

INSERT INTO `cadastros` (`id`, `nome`, `endereco`, `bairro`, `usuario_id`) VALUES
(3, 'Rodrigo', 'RUA 1', 'BAIRRO 2', 1),
(4, 'Jose', 'RUA 2', 'BAIRRO 1', 4),
(10, 'Fernando', 'Rua 12', '13', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel_acesso` enum('admin','user') NOT NULL DEFAULT 'user',
  `admin_criador_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `nivel_acesso`, `admin_criador_id`) VALUES
(1, 'Admin Teste 1', 'admin@teste.com', '$2y$10$MkPdMzfoB6E2nL/d5lLZbO.1nDPUeXs9dyusIutNLEUYIaCv2dsl2', 'admin', NULL),
(4, 'Rodrigo', 'rodrigo.admin@email.com', '$2y$10$A8XKnLRfEzBK2YTrMaf64OjrPZaB9hEGMU4CRnttIraHZYzLh.OwS', 'admin', NULL),
(5, 'Teste da Silva Sauro', 'teste@email.com', '$2y$10$QzErm7nojPjcP7BwZaBrFeyqa4DrXYJ8VSiG7x0qpRT61HuKMPuMG', '', NULL),
(6, 'testando mais uma vez', 'testando2@teste.com', '$2y$10$nTsZVVf8sjHR8EH13ZLwAeQgbJivdT35t.y9pF.o1.UX3bS.eLN5u', '', 1),
(7, 'TESTE CRIADO COM LOGIN USUARIO INICIAL', 'login2@teste.com', '$2y$10$CzRSJ11pt7xj4h7lWFxTK.MppI4g3vfPb03qHACOOoLhPZSowvYEy', '', 1),
(8, 'Rodrigo Filho', 'rodrigo.santos@email.com', '$2y$10$6Kaq2zJMYifaOgeLyIj.G.dcWuSPGPioAoAudrqehnsg2eB7uJ0PG', 'admin', 4);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cadastros`
--
ALTER TABLE `cadastros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario` (`usuario_id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cadastros`
--
ALTER TABLE `cadastros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `cadastros`
--
ALTER TABLE `cadastros`
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
