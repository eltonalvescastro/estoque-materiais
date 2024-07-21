CREATE DATABASE `controle-estoque`;

USE `controle-estoque`;

DROP TABLE IF EXISTS `controle-estoque`;

CREATE TABLE `cadmateriais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empresa` varchar(100) NOT NULL,
  `item` varchar(100) DEFAULT NULL,
  `codigomaterial` varchar(100) DEFAULT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `estoque` int(128) DEFAULT NULL,
  `saida` varchar(100) DEFAULT NULL,
  `qtdfinal` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

