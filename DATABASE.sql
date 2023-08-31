-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 31. Aug 2023 um 20:54
-- Server-Version: 10.6.12-MariaDB-0ubuntu0.22.04.1
-- PHP-Version: 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `gut`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `shop`
--

CREATE TABLE `shop` (
  `username` varchar(32) NOT NULL,
  `discord` text NOT NULL,
  `nextcoins` int(11) NOT NULL,
  `coins` int(11) NOT NULL DEFAULT 0,
  `randomcoins` int(11) NOT NULL DEFAULT 100,
  `noti_sent` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `shop`
--

INSERT INTO `shop` (`username`, `discord`, `nextcoins`, `coins`, `randomcoins`, `noti_sent`) VALUES
('user1', 'user1#3542', 1685291844, 274, 43, 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
