-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 01 Juin 2017 à 16:26
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `tournois`
--

-- --------------------------------------------------------

--
-- Structure de la table `championnat`
--

CREATE TABLE IF NOT EXISTS `championnat` (
  `idchamp` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(25) NOT NULL DEFAULT '',
  `categorie` varchar(20) NOT NULL DEFAULT '',
  `variable` enum('aller','retour') NOT NULL DEFAULT 'aller',
  `nbequipes` int(50) NOT NULL,
  `idutilisateur` int(11) NOT NULL,
  `sport` varchar(100) NOT NULL,
  `format` varchar(1000) NOT NULL,
  `ptsvic` int(11) NOT NULL,
  `ptsnul` int(100) NOT NULL,
  `ptsdef` int(100) NOT NULL,
  `nbpoule` int(11) NOT NULL,
  `nb_parpoule` int(11) NOT NULL,
  `nb_qualif` int(11) NOT NULL,
  PRIMARY KEY (`idchamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Structure de la table `equipes`
--

CREATE TABLE IF NOT EXISTS `equipes` (
  `idequipe` int(11) NOT NULL AUTO_INCREMENT,
  `nomequipe` varchar(30) NOT NULL DEFAULT '',
  `nbvictoire` int(11) NOT NULL DEFAULT '0',
  `nbnul` int(11) NOT NULL DEFAULT '0',
  `nbdefaite` int(11) NOT NULL DEFAULT '0',
  `bp` int(11) NOT NULL DEFAULT '0',
  `bc` int(11) NOT NULL DEFAULT '0',
  `pts` int(11) NOT NULL DEFAULT '0',
  `nbmatch` int(11) NOT NULL DEFAULT '0',
  `poule` int(11) NOT NULL,
  `ptselim` int(11) NOT NULL DEFAULT '0',
  `idchamp` int(11) NOT NULL,
  PRIMARY KEY (`idequipe`),
  KEY `idchamp` (`idchamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=111 ;

-- --------------------------------------------------------

--
-- Structure de la table `matchs`
--

CREATE TABLE IF NOT EXISTS `matchs` (
  `idmatchs` int(11) NOT NULL AUTO_INCREMENT,
  `equipe1` varchar(100) NOT NULL,
  `equipe2` varchar(100) NOT NULL,
  `resultat1` smallint(6) NOT NULL DEFAULT '0',
  `resultat2` smallint(6) NOT NULL DEFAULT '0',
  `idjournee` int(11) NOT NULL DEFAULT '0',
  `idchamp` int(11) NOT NULL,
  PRIMARY KEY (`idmatchs`),
  KEY `equipe1` (`equipe1`,`equipe2`,`idjournee`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1351 ;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE IF NOT EXISTS `membre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` text NOT NULL,
  `pass_md5` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `membre`
--

INSERT INTO `membre` (`id`, `login`, `pass_md5`) VALUES
(3, 'test', 'aa'),
(4, 'quentin', 'aa');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
