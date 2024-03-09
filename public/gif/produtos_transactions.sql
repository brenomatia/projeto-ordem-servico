-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07/03/2024 às 12:52
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `cgm_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos_transactions`
--

CREATE TABLE `produtos_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hash_transaction` varchar(255) DEFAULT NULL,
  `id_user` varchar(255) DEFAULT NULL,
  `desconto_porcentagem` varchar(255) DEFAULT NULL,
  `valorTotal` decimal(10,2) DEFAULT NULL,
  `valorPago` decimal(10,2) DEFAULT NULL,
  `parcelas` varchar(255) DEFAULT NULL,
  `tipo_pagamento` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `produtos_transactions`
--

INSERT INTO `produtos_transactions` (`id`, `hash_transaction`, `id_user`, `desconto_porcentagem`, `valorTotal`, `valorPago`, `parcelas`, `tipo_pagamento`, `created_at`, `updated_at`) VALUES
(7, 'af12d7ff18883aa34e35b88bfacb8780', '3', '10', 575.00, 517.50, NULL, 'PIX', '2024-03-05 13:02:53', '2024-03-05 13:02:53'),
(8, 'c31863fb2633ee01cc86d09b85f35e51', '3', '2', 515.00, 504.70, NULL, 'CARTAO_DEBITO', '2024-02-27 14:25:17', '2024-02-27 14:25:17'),
(9, 'eebcf456ffde16c4fd9fb87faef32d0c', '3', '5', 515.00, 489.25, NULL, 'DINHEIRO', '2024-03-06 13:50:10', '2024-03-06 13:50:10'),
(10, 'e624ff3edf6315e7299001f3ff7a5cd2', '3', NULL, 2060.00, 1751.00, '6', 'REVENDA', '2024-03-07 11:16:03', '2024-03-07 11:16:03');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `produtos_transactions`
--
ALTER TABLE `produtos_transactions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `produtos_transactions`
--
ALTER TABLE `produtos_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
