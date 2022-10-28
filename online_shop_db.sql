-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 27. Okt 2022 um 21:14
-- Server-Version: 10.4.24-MariaDB
-- PHP-Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `online_shop_db`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `name` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `category`
--

INSERT INTO `category` (`category_id`, `active`, `name`) VALUES
(2, 1, 'hello:)'),
(5, 1, 'ein gegenstand'),
(6, 1, 'ein gegenstand'),
(7, 1, 'ein gegenstand'),
(8, 1, 'ein gegenstand'),
(9, 1, 'ein gegenstand'),
(10, 1, 'ein gegenstand'),
(11, 1, 'ein gegenstand'),
(12, 1, 'ein gegenstand'),
(13, 1, 'ein gegenstand'),
(14, 1, 'ein gegenstand'),
(15, 1, 'ein gegenstand'),
(16, 1, 'ein gegenstand'),
(17, 1, 'ein gegenstand'),
(18, 1, 'ein gegenstand'),
(19, 1, 'ein gegenstand');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `id_category` int(11) DEFAULT NULL,
  `name` varchar(500) NOT NULL,
  `image` varchar(1000) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(65,2) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `product`
--

INSERT INTO `product` (`product_id`, `sku`, `active`, `id_category`, `name`, `image`, `description`, `price`, `stock`) VALUES
(5, 'no', 1, NULL, 'yes', 'idk', 'yes indeed', '2.95', 3),
(7, '1', 0, NULL, 'no', 'idk', 'yes indeed', '2.85', 3),
(8, '10', 0, NULL, 'no', 'idk', 'yes indeed', '2.95', 3),
(12, '120', 1, NULL, 'no', 'left one', 'no wrong', '2.85', 3),
(15, '180', 0, NULL, 'no', 'idk', 'yes indeed', '2.95', 3),
(16, '190', 0, 2, 'no', 'idk', 'yes indeed', '2.95', 3),
(17, '1900', 0, 2, 'no', 'idk', 'yes indeed', '2.95', 3),
(18, '100', 0, 2, 'no', 'idk', 'yes indeed', '2.95', 3),
(19, '102', 0, 2, 'no', 'idk', 'yes indeed', '2.95', 1),
(20, '1002', 0, 2, 'no', 'idk', 'yes indeed', '2.95', 2147483647),
(21, '981', 0, 2, 'no', 'idk', '6', '2.95', 5),
(22, 'kh3khfvk', 1, 18, 'Jev', '3wferge', 'das ist ein ding', '12.45', 234),
(23, '901', 0, 2, 'no', 'idk', 't7izj', '2.96', 5);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indizes für die Tabelle `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `product_category` (`id_category`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT für Tabelle `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_category` FOREIGN KEY (`id_category`) REFERENCES `category` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
