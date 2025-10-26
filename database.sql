-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 26, 2025 at 09:47 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital_matlhovele`
--
CREATE DATABASE IF NOT EXISTS `hospital_matlhovele` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `hospital_matlhovele`;

-- --------------------------------------------------------

--
-- Table structure for table `agendamentos`
--

DROP TABLE IF EXISTS `agendamentos`;
CREATE TABLE IF NOT EXISTS `agendamentos` (
  `ID_Agendamento` int NOT NULL AUTO_INCREMENT,
  `ID_Paciente` int NOT NULL,
  `ID_Medico` int DEFAULT NULL,
  `Data_Agendamento` date NOT NULL,
  `Hora_Agendamento` time DEFAULT NULL,
  `Motivo` varchar(255) DEFAULT NULL,
  `Status` enum('Confirmado','Pendente','Cancelado') NOT NULL DEFAULT 'Pendente',
  `Criado_Em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Agendamento`),
  KEY `ID_Paciente` (`ID_Paciente`),
  KEY `ID_Medico` (`ID_Medico`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `agendamentos`
--

INSERT INTO `agendamentos` (`ID_Agendamento`, `ID_Paciente`, `ID_Medico`, `Data_Agendamento`, `Hora_Agendamento`, `Motivo`, `Status`, `Criado_Em`, `created_at`) VALUES
(1, 1, NULL, '2025-09-23', '10:00:00', 'Consulta de rotina', 'Confirmado', '2025-09-22 06:19:37', '2025-10-23 16:32:24'),
(2, 1, NULL, '2025-09-23', '10:00:00', 'Consulta de rotina', 'Confirmado', '2025-09-22 06:24:07', '2025-10-23 16:32:24'),
(18, 10, 1345365, '2025-10-23', '10:00:00', NULL, 'Cancelado', '2025-10-22 12:06:39', '2025-10-23 16:32:24'),
(16, 10, 1345374, '2025-10-23', '17:00:00', 'Dores de cabeça muito fortes', 'Pendente', '2025-10-22 10:01:49', '2025-10-23 16:32:24'),
(9, 1, 3, '2025-10-22', '13:00:00', 'Dor de cabeça crônica', 'Pendente', '2025-10-20 07:46:40', '2025-10-23 16:32:24'),
(10, 1, 3, '2025-10-26', '08:30:00', 'Problemas de memória', 'Pendente', '2025-10-20 07:46:40', '2025-10-23 16:32:24'),
(11, 1, 3, '2025-10-27', '16:00:00', 'Exame neurológico', 'Pendente', '2025-10-20 07:46:40', '2025-10-23 16:32:24'),
(12, 1, 4, '2025-10-23', '11:30:00', 'Dor nas costas', 'Pendente', '2025-10-20 07:46:40', '2025-10-23 16:32:24'),
(13, 1, 4, '2025-10-28', '14:30:00', 'Lesão no joelho', 'Pendente', '2025-10-20 07:46:40', '2025-10-23 16:32:24'),
(14, 1, 4, '2025-10-29', '09:00:00', 'Fisioterapia', 'Pendente', '2025-10-20 07:46:40', '2025-10-23 16:32:24'),
(15, 9, 1345360, '2025-10-24', '08:30:00', NULL, 'Pendente', '2025-10-22 10:00:31', '2025-10-23 16:32:24'),
(19, 9, 1345352, '2025-10-29', '09:30:00', NULL, 'Pendente', '2025-10-24 06:05:00', '2025-10-24 06:05:00');

-- --------------------------------------------------------

--
-- Table structure for table `configuracoes`
--

DROP TABLE IF EXISTS `configuracoes`;
CREATE TABLE IF NOT EXISTS `configuracoes` (
  `ID_Configuracao` int NOT NULL AUTO_INCREMENT,
  `Chave` varchar(100) NOT NULL,
  `Valor` text NOT NULL,
  `Criado_Em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Configuracao`),
  UNIQUE KEY `Chave` (`Chave`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `configuracoes`
--

INSERT INTO `configuracoes` (`ID_Configuracao`, `Chave`, `Valor`, `Criado_Em`) VALUES
(1, 'max_consultas_dia', '20', '2025-09-20 22:24:59'),
(2, 'horario_funcionamento', '07:30-16:30', '2025-09-20 22:24:59');

-- --------------------------------------------------------

--
-- Table structure for table `consultas`
--

DROP TABLE IF EXISTS `consultas`;
CREATE TABLE IF NOT EXISTS `consultas` (
  `ID_Consulta` int NOT NULL AUTO_INCREMENT,
  `ID_Paciente` int NOT NULL,
  `ID_Medico` int NOT NULL,
  `Data_Consulta` datetime NOT NULL,
  `Motivo` text,
  `Sala` varchar(50) DEFAULT NULL,
  `Status` enum('Agendada','Concluida','Cancelada') DEFAULT 'Agendada',
  `Criado_Em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Consulta`),
  KEY `ID_Paciente` (`ID_Paciente`),
  KEY `ID_Medico` (`ID_Medico`),
  KEY `idx_consulta_data` (`Data_Consulta`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `consultas`
--

INSERT INTO `consultas` (`ID_Consulta`, `ID_Paciente`, `ID_Medico`, `Data_Consulta`, `Motivo`, `Sala`, `Status`, `Criado_Em`) VALUES
(1, 1, 1, '2025-09-16 10:00:00', 'Consulta de rotina', 'Sala 101', 'Agendada', '2025-09-20 22:24:59'),
(2, 2, 2, '2025-09-17 09:30:00', 'Avaliação pediátrica', 'Sala 102', 'Agendada', '2025-09-20 22:24:59');

-- --------------------------------------------------------

--
-- Table structure for table `departamentos`
--

DROP TABLE IF EXISTS `departamentos`;
CREATE TABLE IF NOT EXISTS `departamentos` (
  `ID_Departamento` int NOT NULL AUTO_INCREMENT,
  `Nome` varchar(100) NOT NULL,
  `Chefe_ID` int DEFAULT NULL,
  `Criado_Em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Departamento`),
  UNIQUE KEY `Nome` (`Nome`),
  KEY `fk_departamento_chefe` (`Chefe_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `departamentos`
--

INSERT INTO `departamentos` (`ID_Departamento`, `Nome`, `Chefe_ID`, `Criado_Em`) VALUES
(1, 'Emergência', 1, '2025-09-20 22:24:59'),
(2, 'Clínica Geral', NULL, '2025-09-20 22:24:59');

-- --------------------------------------------------------

--
-- Table structure for table `especialidades`
--

DROP TABLE IF EXISTS `especialidades`;
CREATE TABLE IF NOT EXISTS `especialidades` (
  `ID_Especialidade` int NOT NULL AUTO_INCREMENT,
  `Nome` varchar(100) NOT NULL,
  `Descricao` text,
  `Criado_Em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Especialidade`),
  UNIQUE KEY `Nome` (`Nome`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `especialidades`
--

INSERT INTO `especialidades` (`ID_Especialidade`, `Nome`, `Descricao`, `Criado_Em`) VALUES
(1, 'Cardiologia', 'Especialidade em doenças do coração', '2025-09-20 22:24:59'),
(2, 'Pediatria', 'Cuidados médicos para crianças', '2025-09-20 22:24:59'),
(3, 'Ortopedia', 'Tratamento de ossos e articulações', '2025-09-20 22:24:59');

-- --------------------------------------------------------

--
-- Table structure for table `horarios`
--

DROP TABLE IF EXISTS `horarios`;
CREATE TABLE IF NOT EXISTS `horarios` (
  `ID_Horario` int NOT NULL AUTO_INCREMENT,
  `ID_Medico` int NOT NULL,
  `Dia_Semana` enum('Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo') NOT NULL,
  `Hora_Inicio` time NOT NULL,
  `Hora_Fim` time NOT NULL,
  PRIMARY KEY (`ID_Horario`),
  KEY `ID_Medico` (`ID_Medico`)
) ENGINE=MyISAM AUTO_INCREMENT=238 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `horarios`
--

INSERT INTO `horarios` (`ID_Horario`, `ID_Medico`, `Dia_Semana`, `Hora_Inicio`, `Hora_Fim`) VALUES
(1, 1, 'Segunda', '08:00:00', '12:00:00'),
(2, 1, 'Quarta', '14:00:00', '18:00:00'),
(3, 2, 'Terça', '09:00:00', '13:00:00'),
(4, 1, 'Segunda', '08:00:00', '12:00:00'),
(5, 1, 'Segunda', '14:00:00', '18:00:00'),
(6, 1, 'Terça', '08:00:00', '12:00:00'),
(7, 1, 'Terça', '14:00:00', '18:00:00'),
(8, 1, 'Quarta', '08:00:00', '12:00:00'),
(9, 1, 'Quarta', '14:00:00', '18:00:00'),
(10, 1, 'Quinta', '08:00:00', '12:00:00'),
(11, 1, 'Quinta', '14:00:00', '18:00:00'),
(12, 1, 'Sexta', '08:00:00', '12:00:00'),
(13, 1, 'Sexta', '14:00:00', '18:00:00'),
(14, 2, 'Terça', '09:00:00', '17:00:00'),
(15, 2, 'Quinta', '09:00:00', '17:00:00'),
(16, 1345352, 'Segunda', '10:00:00', '13:00:00'),
(17, 1345352, 'Terça', '10:00:00', '13:00:00'),
(18, 1345352, 'Quarta', '10:00:00', '13:00:00'),
(19, 1345354, 'Quarta', '15:00:00', '19:00:00'),
(20, 1345354, 'Quinta', '15:00:00', '19:00:00'),
(21, 1345354, 'Sexta', '15:00:00', '19:00:00'),
(22, 1345354, 'Sábado', '09:00:00', '13:00:00'),
(23, 1345352, 'Segunda', '08:00:00', '12:00:00'),
(24, 1345352, 'Terça', '14:00:00', '18:00:00'),
(25, 1345352, 'Quarta', '08:00:00', '12:00:00'),
(26, 1345352, 'Quinta', '14:00:00', '18:00:00'),
(27, 1345352, 'Sexta', '08:00:00', '12:00:00'),
(28, 1345353, 'Segunda', '14:00:00', '18:00:00'),
(29, 1345353, 'Terça', '08:00:00', '12:00:00'),
(30, 1345353, 'Quarta', '14:00:00', '18:00:00'),
(31, 1345353, 'Quinta', '08:00:00', '12:00:00'),
(32, 1345353, 'Sexta', '14:00:00', '18:00:00'),
(33, 1345354, 'Segunda', '08:00:00', '12:00:00'),
(34, 1345354, 'Terça', '14:00:00', '18:00:00'),
(35, 1345354, 'Quarta', '08:00:00', '12:00:00'),
(36, 1345354, 'Quinta', '14:00:00', '18:00:00'),
(37, 1345354, 'Sexta', '08:00:00', '12:00:00'),
(38, 1345355, 'Segunda', '14:00:00', '18:00:00'),
(39, 1345355, 'Terça', '08:00:00', '12:00:00'),
(40, 1345355, 'Quarta', '14:00:00', '18:00:00'),
(41, 1345355, 'Quinta', '08:00:00', '12:00:00'),
(42, 1345355, 'Sexta', '14:00:00', '18:00:00'),
(43, 1345356, 'Segunda', '08:00:00', '12:00:00'),
(44, 1345356, 'Terça', '14:00:00', '18:00:00'),
(45, 1345356, 'Quarta', '08:00:00', '12:00:00'),
(46, 1345356, 'Quinta', '14:00:00', '18:00:00'),
(47, 1345356, 'Sexta', '08:00:00', '12:00:00'),
(48, 1345357, 'Segunda', '14:00:00', '18:00:00'),
(49, 1345357, 'Terça', '08:00:00', '12:00:00'),
(50, 1345357, 'Quarta', '14:00:00', '18:00:00'),
(51, 1345357, 'Quinta', '08:00:00', '12:00:00'),
(52, 1345357, 'Sexta', '14:00:00', '18:00:00'),
(53, 1345358, 'Segunda', '08:00:00', '12:00:00'),
(54, 1345358, 'Terça', '14:00:00', '18:00:00'),
(55, 1345358, 'Quarta', '08:00:00', '12:00:00'),
(56, 1345358, 'Quinta', '14:00:00', '18:00:00'),
(57, 1345358, 'Sexta', '08:00:00', '12:00:00'),
(58, 1345359, 'Segunda', '14:00:00', '18:00:00'),
(59, 1345359, 'Terça', '08:00:00', '12:00:00'),
(60, 1345359, 'Quarta', '14:00:00', '18:00:00'),
(61, 1345359, 'Quinta', '08:00:00', '12:00:00'),
(62, 1345359, 'Sexta', '14:00:00', '18:00:00'),
(63, 1345360, 'Segunda', '08:00:00', '12:00:00'),
(64, 1345360, 'Terça', '14:00:00', '18:00:00'),
(65, 1345360, 'Quarta', '08:00:00', '12:00:00'),
(66, 1345360, 'Quinta', '14:00:00', '18:00:00'),
(67, 1345360, 'Sexta', '08:00:00', '12:00:00'),
(68, 1345361, 'Segunda', '14:00:00', '18:00:00'),
(69, 1345361, 'Terça', '08:00:00', '12:00:00'),
(70, 1345361, 'Quarta', '14:00:00', '18:00:00'),
(71, 1345361, 'Quinta', '08:00:00', '12:00:00'),
(72, 1345361, 'Sexta', '14:00:00', '18:00:00'),
(73, 1345362, 'Segunda', '09:00:00', '13:00:00'),
(74, 1345362, 'Terça', '15:00:00', '19:00:00'),
(75, 1345362, 'Quarta', '09:00:00', '13:00:00'),
(76, 1345362, 'Quinta', '15:00:00', '19:00:00'),
(77, 1345362, 'Sexta', '09:00:00', '13:00:00'),
(78, 1345363, 'Segunda', '15:00:00', '19:00:00'),
(79, 1345363, 'Terça', '09:00:00', '13:00:00'),
(80, 1345363, 'Quarta', '15:00:00', '19:00:00'),
(81, 1345363, 'Quinta', '09:00:00', '13:00:00'),
(82, 1345363, 'Sexta', '15:00:00', '19:00:00'),
(83, 1345364, 'Segunda', '09:00:00', '13:00:00'),
(84, 1345364, 'Terça', '15:00:00', '19:00:00'),
(85, 1345364, 'Quarta', '09:00:00', '13:00:00'),
(86, 1345364, 'Quinta', '15:00:00', '19:00:00'),
(87, 1345364, 'Sexta', '09:00:00', '13:00:00'),
(88, 1345365, 'Segunda', '15:00:00', '19:00:00'),
(89, 1345365, 'Terça', '09:00:00', '13:00:00'),
(90, 1345365, 'Quarta', '15:00:00', '19:00:00'),
(91, 1345365, 'Quinta', '09:00:00', '13:00:00'),
(92, 1345365, 'Sexta', '15:00:00', '19:00:00'),
(93, 1345366, 'Segunda', '09:00:00', '13:00:00'),
(94, 1345366, 'Terça', '15:00:00', '19:00:00'),
(95, 1345366, 'Quarta', '09:00:00', '13:00:00'),
(96, 1345366, 'Quinta', '15:00:00', '19:00:00'),
(97, 1345366, 'Sexta', '09:00:00', '13:00:00'),
(98, 1345367, 'Segunda', '15:00:00', '19:00:00'),
(99, 1345367, 'Terça', '09:00:00', '13:00:00'),
(100, 1345367, 'Quarta', '15:00:00', '19:00:00'),
(101, 1345367, 'Quinta', '09:00:00', '13:00:00'),
(102, 1345367, 'Sexta', '15:00:00', '19:00:00'),
(103, 1345368, 'Segunda', '09:00:00', '13:00:00'),
(104, 1345368, 'Terça', '15:00:00', '19:00:00'),
(105, 1345368, 'Quarta', '09:00:00', '13:00:00'),
(106, 1345368, 'Quinta', '15:00:00', '19:00:00'),
(107, 1345368, 'Sexta', '09:00:00', '13:00:00'),
(108, 1345369, 'Segunda', '15:00:00', '19:00:00'),
(109, 1345369, 'Terça', '09:00:00', '13:00:00'),
(110, 1345369, 'Quarta', '15:00:00', '19:00:00'),
(111, 1345369, 'Quinta', '09:00:00', '13:00:00'),
(112, 1345369, 'Sexta', '15:00:00', '19:00:00'),
(113, 1345370, 'Segunda', '09:00:00', '13:00:00'),
(114, 1345370, 'Terça', '15:00:00', '19:00:00'),
(115, 1345370, 'Quarta', '09:00:00', '13:00:00'),
(116, 1345370, 'Quinta', '15:00:00', '19:00:00'),
(117, 1345370, 'Sexta', '09:00:00', '13:00:00'),
(118, 1345371, 'Segunda', '15:00:00', '19:00:00'),
(119, 1345371, 'Terça', '09:00:00', '13:00:00'),
(120, 1345371, 'Quarta', '15:00:00', '19:00:00'),
(121, 1345371, 'Quinta', '09:00:00', '13:00:00'),
(122, 1345371, 'Sexta', '15:00:00', '19:00:00'),
(123, 1345352, 'Segunda', '08:00:00', '12:00:00'),
(124, 1345352, 'Terça', '14:00:00', '18:00:00'),
(125, 1345352, 'Quarta', '08:00:00', '12:00:00'),
(126, 1345352, 'Quinta', '14:00:00', '18:00:00'),
(127, 1345352, 'Sexta', '08:00:00', '12:00:00'),
(128, 1345353, 'Segunda', '14:00:00', '18:00:00'),
(129, 1345353, 'Terça', '08:00:00', '12:00:00'),
(130, 1345353, 'Quarta', '14:00:00', '18:00:00'),
(131, 1345353, 'Quinta', '08:00:00', '12:00:00'),
(132, 1345353, 'Sexta', '14:00:00', '18:00:00'),
(133, 1345354, 'Segunda', '08:00:00', '12:00:00'),
(134, 1345354, 'Terça', '14:00:00', '18:00:00'),
(135, 1345354, 'Quarta', '08:00:00', '12:00:00'),
(136, 1345354, 'Quinta', '14:00:00', '18:00:00'),
(137, 1345354, 'Sexta', '08:00:00', '12:00:00'),
(138, 1345355, 'Segunda', '14:00:00', '18:00:00'),
(139, 1345355, 'Terça', '08:00:00', '12:00:00'),
(140, 1345355, 'Quarta', '14:00:00', '18:00:00'),
(141, 1345355, 'Quinta', '08:00:00', '12:00:00'),
(142, 1345355, 'Sexta', '14:00:00', '18:00:00'),
(143, 1345356, 'Segunda', '08:00:00', '12:00:00'),
(144, 1345356, 'Terça', '14:00:00', '18:00:00'),
(145, 1345356, 'Quarta', '08:00:00', '12:00:00'),
(146, 1345356, 'Quinta', '14:00:00', '18:00:00'),
(147, 1345356, 'Sexta', '08:00:00', '12:00:00'),
(148, 1345357, 'Segunda', '14:00:00', '18:00:00'),
(149, 1345357, 'Terça', '08:00:00', '12:00:00'),
(150, 1345357, 'Quarta', '14:00:00', '18:00:00'),
(151, 1345357, 'Quinta', '08:00:00', '12:00:00'),
(152, 1345357, 'Sexta', '14:00:00', '18:00:00'),
(153, 1345358, 'Segunda', '08:00:00', '12:00:00'),
(154, 1345358, 'Terça', '14:00:00', '18:00:00'),
(155, 1345358, 'Quarta', '08:00:00', '12:00:00'),
(156, 1345358, 'Quinta', '14:00:00', '18:00:00'),
(157, 1345358, 'Sexta', '08:00:00', '12:00:00'),
(158, 1345359, 'Segunda', '14:00:00', '18:00:00'),
(159, 1345359, 'Terça', '08:00:00', '12:00:00'),
(160, 1345359, 'Quarta', '14:00:00', '18:00:00'),
(161, 1345359, 'Quinta', '08:00:00', '12:00:00'),
(162, 1345359, 'Sexta', '14:00:00', '18:00:00'),
(163, 1345360, 'Segunda', '08:00:00', '12:00:00'),
(164, 1345360, 'Terça', '14:00:00', '18:00:00'),
(165, 1345360, 'Quarta', '08:00:00', '12:00:00'),
(166, 1345360, 'Quinta', '14:00:00', '18:00:00'),
(167, 1345360, 'Sexta', '08:00:00', '12:00:00'),
(168, 1345361, 'Segunda', '14:00:00', '18:00:00'),
(169, 1345361, 'Terça', '08:00:00', '12:00:00'),
(170, 1345361, 'Quarta', '14:00:00', '18:00:00'),
(171, 1345361, 'Quinta', '08:00:00', '12:00:00'),
(172, 1345361, 'Sexta', '14:00:00', '18:00:00'),
(173, 1345362, 'Segunda', '09:00:00', '13:00:00'),
(174, 1345362, 'Terça', '15:00:00', '19:00:00'),
(175, 1345362, 'Quarta', '09:00:00', '13:00:00'),
(176, 1345362, 'Quinta', '15:00:00', '19:00:00'),
(177, 1345362, 'Sexta', '09:00:00', '13:00:00'),
(178, 1345363, 'Segunda', '15:00:00', '19:00:00'),
(179, 1345363, 'Terça', '09:00:00', '13:00:00'),
(180, 1345363, 'Quarta', '15:00:00', '19:00:00'),
(181, 1345363, 'Quinta', '09:00:00', '13:00:00'),
(182, 1345363, 'Sexta', '15:00:00', '19:00:00'),
(183, 1345364, 'Segunda', '09:00:00', '13:00:00'),
(184, 1345364, 'Terça', '15:00:00', '19:00:00'),
(185, 1345364, 'Quarta', '09:00:00', '13:00:00'),
(186, 1345364, 'Quinta', '15:00:00', '19:00:00'),
(187, 1345364, 'Sexta', '09:00:00', '13:00:00'),
(188, 1345365, 'Segunda', '15:00:00', '19:00:00'),
(189, 1345365, 'Terça', '09:00:00', '13:00:00'),
(190, 1345365, 'Quarta', '15:00:00', '19:00:00'),
(191, 1345365, 'Quinta', '09:00:00', '13:00:00'),
(192, 1345365, 'Sexta', '15:00:00', '19:00:00'),
(193, 1345366, 'Segunda', '09:00:00', '13:00:00'),
(194, 1345366, 'Terça', '15:00:00', '19:00:00'),
(195, 1345366, 'Quarta', '09:00:00', '13:00:00'),
(196, 1345366, 'Quinta', '15:00:00', '19:00:00'),
(197, 1345366, 'Sexta', '09:00:00', '13:00:00'),
(198, 1345367, 'Segunda', '15:00:00', '19:00:00'),
(199, 1345367, 'Terça', '09:00:00', '13:00:00'),
(200, 1345367, 'Quarta', '15:00:00', '19:00:00'),
(201, 1345367, 'Quinta', '09:00:00', '13:00:00'),
(202, 1345367, 'Sexta', '15:00:00', '19:00:00'),
(203, 1345368, 'Segunda', '09:00:00', '13:00:00'),
(204, 1345368, 'Terça', '15:00:00', '19:00:00'),
(205, 1345368, 'Quarta', '09:00:00', '13:00:00'),
(206, 1345368, 'Quinta', '15:00:00', '19:00:00'),
(207, 1345368, 'Sexta', '09:00:00', '13:00:00'),
(208, 1345369, 'Segunda', '15:00:00', '19:00:00'),
(209, 1345369, 'Terça', '09:00:00', '13:00:00'),
(210, 1345369, 'Quarta', '15:00:00', '19:00:00'),
(211, 1345369, 'Quinta', '09:00:00', '13:00:00'),
(212, 1345369, 'Sexta', '15:00:00', '19:00:00'),
(213, 1345370, 'Segunda', '09:00:00', '13:00:00'),
(214, 1345370, 'Terça', '15:00:00', '19:00:00'),
(215, 1345370, 'Quarta', '09:00:00', '13:00:00'),
(216, 1345370, 'Quinta', '15:00:00', '19:00:00'),
(217, 1345370, 'Sexta', '09:00:00', '13:00:00'),
(218, 1345371, 'Segunda', '15:00:00', '19:00:00'),
(219, 1345371, 'Terça', '09:00:00', '13:00:00'),
(220, 1345371, 'Quarta', '15:00:00', '19:00:00'),
(221, 1345371, 'Quinta', '09:00:00', '13:00:00'),
(222, 1345371, 'Sexta', '15:00:00', '19:00:00'),
(223, 1345372, 'Segunda', '09:30:00', '12:30:00'),
(224, 1345372, 'Terça', '15:30:00', '18:30:00'),
(225, 1345372, 'Quarta', '09:30:00', '12:30:00'),
(226, 1345372, 'Quinta', '15:30:00', '18:30:00'),
(227, 1345372, 'Sexta', '09:30:00', '12:30:00'),
(228, 1345373, 'Segunda', '15:30:00', '18:30:00'),
(229, 1345373, 'Terça', '09:30:00', '12:30:00'),
(230, 1345373, 'Quarta', '15:30:00', '18:30:00'),
(231, 1345373, 'Quinta', '09:30:00', '12:30:00'),
(232, 1345373, 'Sexta', '15:30:00', '18:30:00'),
(233, 1345374, 'Segunda', '09:30:00', '12:30:00'),
(234, 1345374, 'Terça', '15:30:00', '18:30:00'),
(235, 1345374, 'Quarta', '09:30:00', '12:30:00'),
(236, 1345374, 'Quinta', '15:30:00', '18:30:00'),
(237, 1345374, 'Sexta', '09:30:00', '12:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `medicos`
--

DROP TABLE IF EXISTS `medicos`;
CREATE TABLE IF NOT EXISTS `medicos` (
  `ID_Medico` int NOT NULL AUTO_INCREMENT,
  `Nome` varchar(50) NOT NULL,
  `Sobrenome` varchar(50) NOT NULL,
  `Especialidade` varchar(100) NOT NULL,
  `ID_Especialidade` int NOT NULL,
  `ID_Departamento` int DEFAULT NULL,
  `Telefone` varchar(20) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Data_Inicio` date DEFAULT NULL,
  `Numero_Licenca` varchar(50) DEFAULT NULL,
  `Criado_Em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Medico`),
  UNIQUE KEY `Email` (`Email`),
  UNIQUE KEY `Numero_Licenca` (`Numero_Licenca`),
  KEY `idx_medico_email` (`Email`),
  KEY `fk_medico_especialidade` (`ID_Especialidade`),
  KEY `fk_medico_departamento` (`ID_Departamento`)
) ENGINE=MyISAM AUTO_INCREMENT=1345424 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `medicos`
--

INSERT INTO `medicos` (`ID_Medico`, `Nome`, `Sobrenome`, `Especialidade`, `ID_Especialidade`, `ID_Departamento`, `Telefone`, `Email`, `Data_Inicio`, `Numero_Licenca`, `Criado_Em`) VALUES
(1, 'João', 'Silva', '', 1, 1, '+258 84 1234567', 'joao.silva@hospital.com', NULL, 'LIC123', '2025-09-20 22:24:59'),
(2, 'Maria', 'Fernandes', '', 2, 2, '+258 85 7654321', 'maria.fernandes@hospital.com', NULL, 'LIC456', '2025-09-20 22:24:59'),
(1345352, 'Archer', '', 'Medicina Geral', 0, NULL, '871914705', 'archer@gmail.com', NULL, '8739275023', '2025-10-18 20:09:23'),
(1345353, 'João', 'Mario', 'Cardiologia', 1, 1, '+258841234567', 'joao.mario@hospital.com', '2020-01-15', 'LIC-001', '2025-10-20 07:35:35'),
(1345354, 'Maria', 'Oliveira', 'Pneumologia', 2, 1, '+258842345678', 'maria.oliveira@hospital.com', '2018-06-20', 'LIC-002', '2025-10-20 07:35:35'),
(1345355, 'Pedro', 'Santos', 'Neurologia', 3, 2, '+258843456789', 'pedro.santos@hospital.com', '2021-03-10', 'LIC-003', '2025-10-20 07:35:35'),
(1345356, 'Ana', 'Costa', 'Ortopedia', 4, 2, '+258844567890', 'ana.costa@hospital.com', '2019-09-05', 'LIC-004', '2025-10-20 07:35:35'),
(1345357, 'Joana', 'Oliveira', 'Medicina Geral', 1, 1, '+258842345678', 'joana.oliveira@hospital.com', '2019-06-20', 'LIC-0011', '2025-10-22 09:21:46'),
(1345358, 'Joana', 'Oliveira', 'Medicina Geral', 1, 1, '+258842345678', 'joana.oliveira4@hospital.com', '2019-06-20', 'LIC-071', '2025-10-22 09:23:00'),
(1345359, 'Pedro', 'Antonio', 'Medicina Geral', 1, 1, '+258843456789', 'pedro.antonio@hospital.com', '2021-03-10', 'LIC-072', '2025-10-22 09:23:35'),
(1345360, 'Luisa', 'Rodrigues', 'Medicina Geral', 1, 1, '+258846789012', 'luisa.rodrigues@hospital.com', '2020-11-18', 'LIC-006', '2025-10-22 09:24:08'),
(1345361, 'Miguel', 'Lopes', 'Medicina Geral', 1, 1, '+258847890123', 'miguel.lopes@hospital.com', '2017-04-25', 'LIC-007', '2025-10-22 09:24:08'),
(1345362, 'Sofia', 'Martins', 'Medicina Geral', 1, 1, '+258848901234', 'sofia.martins@hospital.com', '2021-07-30', 'LIC-008', '2025-10-22 09:24:08'),
(1345363, 'Rafael', 'Almeida', 'Medicina Geral', 1, 1, '+258849012345', 'rafael.almeida@hospital.com', '2019-12-08', 'LIC-009', '2025-10-22 09:24:08'),
(1345364, 'Isabel', 'Pereira', 'Medicina Geral', 1, 1, '+258840123456', 'isabel.pereira@hospital.com', '2023-01-22', 'LIC-010', '2025-10-22 09:24:08'),
(1345365, 'Fernando', 'Sousa', 'Cardiologia', 2, 1, '+258841234568', 'fernando.sousa@hospital.com', '2018-05-14', 'LIC-011', '2025-10-22 09:24:08'),
(1345366, 'Clara', 'Gonçalves', 'Cardiologia', 2, 1, '+258842345679', 'clara.goncalves@hospital.com', '2020-08-21', 'LIC-012', '2025-10-22 09:24:08'),
(1345367, 'Ricardo', 'Vieira', 'Cardiologia', 2, 1, '+258843456780', 'ricardo.vieira@hospital.com', '2019-10-03', 'LIC-013', '2025-10-22 09:24:08'),
(1345368, 'Beatriz', 'Castro', 'Cardiologia', 2, 1, '+258844567891', 'beatriz.castro@hospital.com', '2022-04-17', 'LIC-014', '2025-10-22 09:24:08'),
(1345369, 'Hugo', 'Nunes', 'Cardiologia', 2, 1, '+258845678902', 'hugo.nunes@hospital.com', '2017-11-29', 'LIC-015', '2025-10-22 09:24:08'),
(1345370, 'Laura', 'Mendes', 'Cardiologia', 2, 1, '+258846789013', 'laura.mendes@hospital.com', '2021-02-05', 'LIC-016', '2025-10-22 09:24:08'),
(1345371, 'Tiago', 'Ribeiro', 'Cardiologia', 2, 1, '+258847890124', 'tiago.ribeiro@hospital.com', '2020-07-12', 'LIC-017', '2025-10-22 09:24:08'),
(1345372, 'Diana', 'Barbosa', 'Cardiologia', 2, 1, '+258848901235', 'diana.barbosa@hospital.com', '2018-12-19', 'LIC-018', '2025-10-22 09:24:08'),
(1345373, 'Vitor', 'Carvalho', 'Cardiologia', 2, 1, '+258849012346', 'vitor.carvalho@hospital.com', '2023-03-28', 'LIC-019', '2025-10-22 09:24:08'),
(1345374, 'Gabriela', 'Morais', 'Cardiologia', 2, 1, '+258840123457', 'gabriela.morais@hospital.com', '2019-01-06', 'LIC-020', '2025-10-22 09:24:08'),
(1345375, 'Eduardo', 'Teixeira', 'Pediatria', 3, 1, '+258841234569', 'eduardo.teixeira@hospital.com', '2021-09-14', 'LIC-021', '2025-10-22 09:24:08'),
(1345376, 'Patricia', 'Silveira', 'Pediatria', 3, 1, '+258842345680', 'patricia.silveira@hospital.com', '2016-03-22', 'LIC-022', '2025-10-22 09:24:08'),
(1345377, 'Andre', 'Fonseca', 'Pediatria', 3, 1, '+258843456781', 'andre.fonseca@hospital.com', '2022-06-08', 'LIC-023', '2025-10-22 09:24:08'),
(1345378, 'Catarina', 'Araujo', 'Pediatria', 3, 1, '+258844567892', 'catarina.araujo@hospital.com', '2019-11-15', 'LIC-024', '2025-10-22 09:24:08'),
(1345379, 'Diogo', 'Monteiro', 'Pediatria', 3, 1, '+258845678903', 'diogo.monteiro@hospital.com', '2020-04-27', 'LIC-025', '2025-10-22 09:24:08'),
(1345380, 'Elisa', 'Borges', 'Pediatria', 3, 1, '+258846789014', 'elisa.borges@hospital.com', '2018-08-31', 'LIC-026', '2025-10-22 09:24:08'),
(1345381, 'Francisco', 'Cruz', 'Pediatria', 3, 1, '+258847890125', 'francisco.cruz@hospital.com', '2021-12-04', 'LIC-027', '2025-10-22 09:24:08'),
(1345382, 'Helena', 'Dias', 'Pediatria', 3, 1, '+258848901236', 'helena.dias@hospital.com', '2017-05-18', 'LIC-028', '2025-10-22 09:24:08'),
(1345383, 'Ivan', 'Esposito', 'Pediatria', 3, 1, '+258849012347', 'ivan.esposito@hospital.com', '2023-02-11', 'LIC-029', '2025-10-22 09:24:08'),
(1345384, 'Julia', 'Figueiredo', 'Pediatria', 3, 1, '+258840123458', 'julia.figueiredo@hospital.com', '2020-10-26', 'LIC-030', '2025-10-22 09:24:08'),
(1345385, 'Klaus', 'Gomes', 'Ortopedia', 4, 2, '+258841234570', 'klaus.gomes@hospital.com', '2019-07-09', 'LIC-031', '2025-10-22 09:24:08'),
(1345386, 'Lidia', 'Henriques', 'Ortopedia', 4, 2, '+258842345681', 'lidia.henriques@hospital.com', '2021-01-23', 'LIC-032', '2025-10-22 09:24:08'),
(1345387, 'Marcos', 'Iglesias', 'Ortopedia', 4, 2, '+258843456782', 'marcos.iglesias@hospital.com', '2018-02-17', 'LIC-033', '2025-10-22 09:24:08'),
(1345388, 'Natasha', 'Jordão', 'Ortopedia', 4, 2, '+258844567893', 'natasha.jordao@hospital.com', '2022-09-05', 'LIC-034', '2025-10-22 09:24:08'),
(1345389, 'Oscar', 'Kuhn', 'Ortopedia', 4, 2, '+258845678904', 'oscar.kuhn@hospital.com', '2017-06-30', 'LIC-035', '2025-10-22 09:24:08'),
(1345390, 'Paula', 'Lima', 'Ortopedia', 4, 2, '+258846789015', 'paula.lima@hospital.com', '2020-03-12', 'LIC-036', '2025-10-22 09:24:08'),
(1345391, 'Quim', 'Mota', 'Ortopedia', 4, 2, '+258847890126', 'quim.mota@hospital.com', '2019-11-28', 'LIC-037', '2025-10-22 09:24:08'),
(1345392, 'Rita', 'Nobre', 'Ortopedia', 4, 2, '+258848901237', 'rita.nobre@hospital.com', '2021-05-16', 'LIC-038', '2025-10-22 09:24:08'),
(1345393, 'Sergio', 'Oliveira', 'Ortopedia', 4, 2, '+258849012348', 'sergio.oliveira@hospital.com', '2018-08-04', 'LIC-039', '2025-10-22 09:24:08'),
(1345394, 'Teresa', 'Pinto', 'Ortopedia', 4, 2, '+258840123459', 'teresa.pinto@hospital.com', '2023-04-20', 'LIC-040', '2025-10-22 09:24:08'),
(1345395, 'Ursula', 'Queiroz', 'Ginecologia', 5, 1, '+258841234571', 'ursula.queiroz@hospital.com', '2020-12-07', 'LIC-041', '2025-10-22 09:24:08'),
(1345396, 'Victor', 'Ramos', 'Ginecologia', 5, 1, '+258842345682', 'victor.ramos@hospital.com', '2019-09-25', 'LIC-042', '2025-10-22 09:24:08'),
(1345397, 'Wanda', 'Santos', 'Ginecologia', 5, 1, '+258843456783', 'wanda.santos@hospital.com', '2022-07-13', 'LIC-043', '2025-10-22 09:24:08'),
(1345398, 'Xavier', 'Tavares', 'Ginecologia', 5, 1, '+258844567894', 'xavier.tavares@hospital.com', '2017-10-19', 'LIC-044', '2025-10-22 09:24:08'),
(1345399, 'Yara', 'Urbano', 'Ginecologia', 5, 1, '+258845678905', 'yara.urbano@hospital.com', '2021-03-31', 'LIC-045', '2025-10-22 09:24:08'),
(1345400, 'Zeca', 'Vasco', 'Ginecologia', 5, 1, '+258846789016', 'zeca.vasco@hospital.com', '2018-01-08', 'LIC-046', '2025-10-22 09:24:08'),
(1345401, 'Amanda', 'Wesley', 'Ginecologia', 5, 1, '+258847890127', 'amanda.wesley@hospital.com', '2020-06-14', 'LIC-047', '2025-10-22 09:24:08'),
(1345402, 'Bruno', 'Xavier', 'Ginecologia', 5, 1, '+258848901238', 'bruno.xavier@hospital.com', '2019-04-02', 'LIC-048', '2025-10-22 09:24:08'),
(1345403, 'Carla', 'Yates', 'Ginecologia', 5, 1, '+258849012349', 'carla.yates@hospital.com', '2023-02-27', 'LIC-049', '2025-10-22 09:24:08'),
(1345404, 'David', 'Zimmermann', 'Ginecologia', 5, 1, '+258840123460', 'david.zimmermann@hospital.com', '2021-11-10', 'LIC-050', '2025-10-22 09:24:08'),
(1345405, 'Eva', 'Alves', 'Neurologia', 6, 2, '+258841234572', 'eva.alves@hospital.com', '2018-07-21', 'LIC-051', '2025-10-22 09:24:08'),
(1345406, 'Fabio', 'Barros', 'Neurologia', 6, 2, '+258842345683', 'fabio.barros@hospital.com', '2020-10-29', 'LIC-052', '2025-10-22 09:24:08'),
(1345407, 'Gina', 'Correia', 'Neurologia', 6, 2, '+258843456784', 'gina.correia@hospital.com', '2019-02-06', 'LIC-053', '2025-10-22 09:24:08'),
(1345408, 'Henrique', 'Duarte', 'Neurologia', 6, 2, '+258844567895', 'henrique.duarte@hospital.com', '2022-05-23', 'LIC-054', '2025-10-22 09:24:08'),
(1345409, 'Ines', 'Elias', 'Neurologia', 6, 2, '+258845678906', 'ines.elias@hospital.com', '2017-09-12', 'LIC-055', '2025-10-22 09:24:08'),
(1345410, 'Joaquim', 'Freitas', 'Neurologia', 6, 2, '+258846789017', 'joaquim.freitas@hospital.com', '2021-08-19', 'LIC-056', '2025-10-22 09:24:08'),
(1345411, 'Katia', 'Guerreiro', 'Neurologia', 6, 2, '+258847890128', 'katia.guerreiro@hospital.com', '2018-12-27', 'LIC-057', '2025-10-22 09:24:08'),
(1345412, 'Luis', 'Horta', 'Neurologia', 6, 2, '+258848901239', 'luis.horta@hospital.com', '2020-03-04', 'LIC-058', '2025-10-22 09:24:08'),
(1345413, 'Marta', 'Inacio', 'Neurologia', 6, 2, '+258849012350', 'marta.inacio@hospital.com', '2023-01-15', 'LIC-059', '2025-10-22 09:24:08'),
(1345414, 'Nuno', 'Junqueira', 'Neurologia', 6, 2, '+258840123461', 'nuno.junqueira@hospital.com', '2019-06-22', 'LIC-060', '2025-10-22 09:24:08'),
(1345415, 'Otavia', 'Keller', 'Cirurgia Geral', 7, 2, '+258841234573', 'otavia.keller@hospital.com', '2021-04-11', 'LIC-061', '2025-10-22 09:24:08'),
(1345416, 'Paulo', 'Lencastre', 'Cirurgia Geral', 7, 2, '+258842345684', 'paulo.lencastre@hospital.com', '2017-11-18', 'LIC-062', '2025-10-22 09:24:08'),
(1345417, 'Quintino', 'Macedo', 'Cirurgia Geral', 7, 2, '+258843456785', 'quintino.macedo@hospital.com', '2022-07-26', 'LIC-063', '2025-10-22 09:24:08'),
(1345418, 'Raul', 'Neto', 'Cirurgia Geral', 7, 2, '+258844567896', 'raul.neto@hospital.com', '2019-01-03', 'LIC-064', '2025-10-22 09:24:08'),
(1345419, 'Sara', 'Oliveira', 'Cirurgia Geral', 7, 2, '+258845678907', 'sara.oliveira@hospital.com', '2020-09-10', 'LIC-065', '2025-10-22 09:24:08'),
(1345420, 'Tomas', 'Pires', 'Cirurgia Geral', 7, 2, '+258846789018', 'tomas.pires@hospital.com', '2018-05-17', 'LIC-066', '2025-10-22 09:24:08'),
(1345421, 'Ursula', 'Quintela', 'Cirurgia Geral', 7, 2, '+258847890129', 'ursula.quintela@hospital.com', '2021-12-24', 'LIC-067', '2025-10-22 09:24:08'),
(1345422, 'Vasco', 'Ribeiro', 'Cirurgia Geral', 7, 2, '+258848901240', 'vasco.ribeiro@hospital.com', '2017-02-09', 'LIC-068', '2025-10-22 09:24:08'),
(1345423, 'Wilma', 'Santos', 'Cirurgia Geral', 7, 2, '+258849012351', 'wilma.santos@hospital.com', '2023-03-18', 'LIC-069', '2025-10-22 09:24:08');

-- --------------------------------------------------------

--
-- Table structure for table `pacientes`
--

DROP TABLE IF EXISTS `pacientes`;
CREATE TABLE IF NOT EXISTS `pacientes` (
  `ID_Paciente` int NOT NULL AUTO_INCREMENT,
  `Nome` varchar(50) NOT NULL,
  `Sobrenome` varchar(50) NOT NULL,
  `Data_Nascimento` date NOT NULL,
  `Genero` enum('Masculino','Feminino','Outro') NOT NULL,
  `Endereco` varchar(255) DEFAULT NULL,
  `Telefone` varchar(20) NOT NULL,
  `Contato_Emergencia` varchar(100) DEFAULT NULL,
  `BI` varchar(50) NOT NULL,
  `Criado_Em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ID_Usuario` int DEFAULT NULL,
  PRIMARY KEY (`ID_Paciente`),
  UNIQUE KEY `BI` (`BI`),
  KEY `idx_paciente_bi` (`BI`)
) ENGINE=MyISAM AUTO_INCREMENT=1234568 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pacientes`
--

INSERT INTO `pacientes` (`ID_Paciente`, `Nome`, `Sobrenome`, `Data_Nascimento`, `Genero`, `Endereco`, `Telefone`, `Contato_Emergencia`, `BI`, `Criado_Em`, `ID_Usuario`) VALUES
(1, 'Teste', 'Usuario', '1990-01-01', 'Masculino', 'Rua Exemplo, Maputo', '+258 87 1914380', '+258 84 9999999', '123456789TEST', '2025-09-20 22:24:59', NULL),
(9, 'Archer', 'Gomes', '2003-04-01', 'Masculino', NULL, '84747545', NULL, '2345444', '2025-10-20 05:29:18', NULL),
(7, 'Archer', 'Gomes', '2003-05-01', 'Masculino', NULL, '(+258) 871914705', NULL, '754688936s', '2025-09-25 07:36:18', NULL),
(6, 'Janna', 'Sheinil', '2025-09-19', 'Outro', '7 poste', '8747647447', NULL, '1234554323', '2025-09-22 06:45:00', NULL),
(8, 'Archer', 'Gomes', '2003-05-01', 'Masculino', NULL, '(+258) 871914705', NULL, '257985229875', '2025-10-08 06:52:24', NULL),
(10, 'Janna', 'Sheinil', '2004-05-04', 'Feminino', NULL, '865757465', NULL, '6275628562', '2025-10-20 07:04:12', NULL),
(1234567, 'Maria', 'Santos', '1995-05-15', 'Feminino', 'Maputo', '+258841234567', NULL, '', '2025-10-26 17:30:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `relatorios`
--

DROP TABLE IF EXISTS `relatorios`;
CREATE TABLE IF NOT EXISTS `relatorios` (
  `ID_Relatorio` int NOT NULL AUTO_INCREMENT,
  `Tipo_Relatorio` enum('Atendimentos','Financeiro','Desempenho') NOT NULL,
  `Data_Geracao` date NOT NULL,
  `Conteudo` json DEFAULT NULL,
  `Criado_Em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Relatorio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `secretarios`
--

DROP TABLE IF EXISTS `secretarios`;
CREATE TABLE IF NOT EXISTS `secretarios` (
  `ID_Secretario` int NOT NULL AUTO_INCREMENT,
  `Nome` varchar(50) NOT NULL,
  `Sobrenome` varchar(50) NOT NULL,
  `Telefone` varchar(20) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Criado_Em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Secretario`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=MyISAM AUTO_INCREMENT=123456790 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `secretarios`
--

INSERT INTO `secretarios` (`ID_Secretario`, `Nome`, `Sobrenome`, `Telefone`, `Email`, `Criado_Em`) VALUES
(79573352, 'Archer', 'Gomes', '8738738', 'archer3@gmail.com', '2025-10-08 15:33:19');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `ID_Usuario` int NOT NULL AUTO_INCREMENT,
  `Email` varchar(100) NOT NULL,
  `Senha` varchar(255) NOT NULL,
  `Tipo_Usuario` enum('Paciente','Medico','Secretario','Admin') NOT NULL,
  `ID_Referencia` int NOT NULL,
  `Criado_Em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Usuario`),
  UNIQUE KEY `Email` (`Email`),
  KEY `idx_usuario_email` (`Email`),
  KEY `fk_usuario_paciente` (`ID_Referencia`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`ID_Usuario`, `Email`, `Senha`, `Tipo_Usuario`, `ID_Referencia`, `Criado_Em`) VALUES
(1, 'teste@exemplo.com', '$2y$10$exemplo_senha_hash', 'Paciente', 1, '2025-09-20 22:24:59'),
(2, 'joao.silva@hospital.com', '$2y$10$exemplo_senha_hash', 'Medico', 1, '2025-09-20 22:24:59'),
(3, 'ana.costa@hospital.com', '$2y$10$exemplo_senha_hash', 'Secretario', 1, '2025-09-20 22:24:59'),
(4, 'admin@hospital.com', '$2y$10$exemplo_senha_hash', 'Admin', 0, '2025-09-20 22:24:59'),
(7, 'archer3@gmail.com', '$2y$10$qYxdPnuP95KRZMyQ0BWhk.ebWmxnrFliHIMY5D7h3m853GAJ3tpTG', 'Paciente', 8, '2025-10-08 06:52:24'),
(6, 'beatriz.lima@exemplo.com', 'TEMPORARY_HASH', 'Paciente', 2, '2025-09-22 06:32:02'),
(8, 'archergomes@gmail.com', '$2y$10$dTCWz43cN/71ydI09YgThugPmGWl9pEUyyfQlB4l/i3/oZF2.BwRO', 'Paciente', 9, '2025-10-20 05:29:18'),
(9, 'janna3@gmail.com', '$2y$10$VTWuaEKjIK1q74GfJNpL.OAnJb9.jS1aBhBnNMFtrErSIOkI2R2tu', 'Paciente', 10, '2025-10-20 07:04:12');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
