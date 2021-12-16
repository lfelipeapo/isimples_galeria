-- phpMyAdmin SQL Dump
-- version 2.11.9.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: Set 26, 2008 as 11:56 PM
-- Versão do Servidor: 5.0.51
-- Versão do PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Banco de Dados: `isimples_isimples`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `Admin`
--

CREATE TABLE IF NOT EXISTS `Admin` (
  `IDAdmin` int(11) NOT NULL auto_increment,
  `Usuario` varchar(255) NOT NULL,
  `Senha` varchar(255) NOT NULL,
  PRIMARY KEY  (`IDAdmin`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `Admin`
--

INSERT INTO `Admin` (`IDAdmin`, `Usuario`, `Senha`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Estrutura da tabela `Albuns`
--

CREATE TABLE IF NOT EXISTS `Albuns` (
  `IDAlbum` int(11) NOT NULL auto_increment,
  `Capa` varchar(255) NOT NULL,
  `Desc` varchar(255) NOT NULL,
  PRIMARY KEY  (`IDAlbum`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Extraindo dados da tabela `Albuns`
--

INSERT INTO `Albuns` (`IDAlbum`, `Capa`, `Desc`) VALUES
(10, 'capa_album.jpg', 'Album A');

-- --------------------------------------------------------

--
-- Estrutura da tabela `Fotos`
--

CREATE TABLE IF NOT EXISTS `Fotos` (
  `IDFoto` int(11) NOT NULL auto_increment,
  `IDAlbum` varchar(255) NOT NULL,
  `Foto` varchar(255) NOT NULL,
  `Desc` varchar(255) NOT NULL,
  PRIMARY KEY  (`IDFoto`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Extraindo dados da tabela `Fotos`
--

INSERT INTO `Fotos` (`IDFoto`, `IDAlbum`, `Foto`, `Desc`) VALUES
(23, '10', 'fotos_album.jpg', 'sdc');
