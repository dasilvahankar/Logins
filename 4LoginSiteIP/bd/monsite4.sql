-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Ven 04 Avril 2014 à 08:17
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `monsite4`
--
CREATE DATABASE IF NOT EXISTS `monsite4` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `monsite4`;

-- --------------------------------------------------------

--
-- Structure de la table `discussion`
--

CREATE TABLE IF NOT EXISTS `discussion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur` int(11) NOT NULL,
  `datemessage` datetime NOT NULL,
  `message` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Contenu de la table `discussion`
--

INSERT INTO `discussion` (`id`, `utilisateur`, `datemessage`, `message`) VALUES
(1, 5, '2014-04-03 11:27:05', 'Bonjour le monde !'),
(2, 7, '2014-04-03 11:29:53', 'Bonjour toi :)');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) COLLATE utf8_bin NOT NULL,
  `prenom` varchar(20) COLLATE utf8_bin NOT NULL,
  `sexe` char(1) COLLATE utf8_bin NOT NULL,
  `datenaissance` date NOT NULL,
  `pays` varchar(30) COLLATE utf8_bin NOT NULL,
  `login` varchar(10) COLLATE utf8_bin NOT NULL,
  `mdp` char(32) COLLATE utf8_bin NOT NULL,
  `dateinscription` date NOT NULL,
  `email` varchar(40) COLLATE utf8_bin NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `ip` varchar(39) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=15 ;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `sexe`, `datenaissance`, `pays`, `login`, `mdp`, `dateinscription`, `email`, `admin`, `ip`) VALUES
(4, 'Simpson', 'Homie', 'M', '1968-10-02', 'Belgique', 'homie', '4d35bf04c806885c619b724d6a86d548', '2014-03-27', 'homie@monsite.be', 0, ''),
(5, 'Indiana', 'Jones', 'M', '1968-06-02', 'Belgique', 'indiana', 'c82138c7e01ad922b0a58fdd33c3a91c', '2014-03-27', 'indiana@monsite.be', 0, '127.0.0.1'),
(6, 'Batman', 'Bruce', 'M', '1953-10-05', 'Belgique', 'batman', 'ec0e2603172c73a8b644bb9456c1ff6e', '2014-03-27', 'batman@monsite.be', 0, ''),
(7, 'Catwoman', 'Jessie', 'F', '1978-10-08', 'Belgique', 'catwoman', 'e99d7ed5580193f36a51f597bc2c0210', '2014-03-28', 'catwoman@monsite.be', 1, '127.0.0.1'),
(8, 'Spiderman', 'Pedro', 'M', '1963-02-05', 'Belgique', 'spiderman', 'fb08f6cd1ba103fee30a2b8dac963043', '2014-03-28', 'spiderman@monsite.be', 1, ''),
(9, 'Bob', 'Xavier', 'M', '1973-12-06', 'Belgique', 'bob', '9f9d51bc70ef21ca5c14f307980a29d8', '2014-03-31', 'bob@monsite.be', 0, ''),
(10, 'Melissa', 'Alice', 'F', '1978-10-08', 'Belgique', 'melissa', 'ff5390bde5a4cf0aa2006cf2198efd29', '2014-03-31', 'melissa@monsite.be', 0, ''),
(11, 'Dupond', 'Michelle', 'F', '1956-05-16', 'Belgique', 'dupond', 'e18164fb58a9c2921f8def70b4d6ab47', '2014-03-31', 'dupond@monsite.be', 0, ''),
(12, 'Gonzalez', 'Carmen', 'F', '1965-04-05', 'Belgique', 'gonzalez', 'e796e897b03dfa33388c5e26154376de', '2014-03-31', 'gonzalez@monsite.be', 0, ''),
(13, 'Kumba', 'Peter', 'M', '1956-02-06', 'Belgique', 'kumba', 'bfaba9012b445ab3587056a61c46bc46', '2014-03-31', 'kumba@monsite.be', 0, ''),
(14, 'Dupuis', 'Barbara', 'F', '1986-01-12', 'Belgique', 'dupuis', '461c01749ecf17b56a8e7c42abbba48a', '2014-04-01', 'dupuis@monsite.be', 0, 'fe80::59ec:87c0:5e78:5ccc');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
