-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 31 mai 2025 à 01:06
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `medicare`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `carte_vitale` varchar(100) DEFAULT NULL,
  `adresse` text,
  `moyen_paiement` text,
  `role` enum('client','admin') NOT NULL DEFAULT 'client',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `carte_vitale`, `adresse`, `moyen_paiement`, `role`) VALUES
(1, 'Admin', 'Medicare', 'admin@medicare.com', '1234', '0', '0', '0', 'admin'),
(2, 'Mourali', 'Hedi', 'hedimourali3@gmail.com', '12', '1111111111', 'Qur Pré-Fontaine 52, chambre 392', 'carte', 'client'),
(3, 'Dupont', 'Jean', 'jean.dupont@gmail.com', 'abcd', '1234567890', '5 avenue Victor Hugo, Lyon', 'carte', 'client'),
(4, 'Durand', 'Lucie', 'lucie.durand@gmail.com', 'lucie123', '2345678901', '18 rue de Nantes, Lille', 'espèces', 'client'),
(5, 'Martin', 'Sophie', 'sophie.martin@gmail.com', 'sophie', '3456789012', '89 rue de Bordeaux, Marseille', 'virement', 'client');

-- --------------------------------------------------------

--
-- Structure de la table `medecin`
--

DROP TABLE IF EXISTS `medecin`;
CREATE TABLE IF NOT EXISTS `medecin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `specialite` varchar(100) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `bureau` varchar(100) NOT NULL,
  `cv` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `medecin`
--

INSERT INTO `medecin` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `specialite`, `photo`, `bureau`, `cv`) VALUES
(1, 'Martin', 'Lucie', 'lucie.martin@medicare.com', '1234', 'généraliste', 'medecin/medecin1.jpg', 'Bureau C3', 'cv/martin.xml'),
(2, 'Dubois', 'Julien', 'julien.dubois@medicare.com', '1234', 'andrologie', 'medecin/medecin1.jpg', 'Bureau A5', 'cv/dubois.xml'),
(3, 'Zhang', 'Mei', 'mei.zhang@medicare.com', '1234', 'addictologie', 'medecin/medecin1.jpg', 'Bureau B4', 'cv/zhang.xml'),
(4, 'Kone', 'Amadou', 'amadou.kone@medicare.com', '1234', 'ostéopathie', 'medecin/medecin1.jpg', 'Bureau D2', 'cv/kone.xml'),
(5, 'Morel', 'Isabelle', 'isabelle.morel@medicare.com', '1234', 'dermatologie', 'medecin/medecin1.jpg', 'Bureau A3', 'cv/morel.xml'),
(6, 'Benali', 'Sami', 'sami.benali@medicare.com', '1234', 'gastro', 'medecin/medecin1.jpg', 'Bureau C1', 'cv/benali.xml'),
(7, 'Nguyen', 'Thierry', 'thierry.nguyen@medicare.com', '1234', 'gynécologie', 'medecin/medecin1.jpg', 'Bureau B2', 'cv/nguyen.xml'),
(8, 'Dupont', 'Claire', 'claire.dupont@medicare.com', '1234', 'cardiologie', 'medecin/medecin1.jpg', 'Bureau A1', 'cv/dupont.xml'),
(9, 'Lopez', 'Carlos', 'carlos.lopez@medicare.com', '1234', 'ist', 'medecin/medecin1.jpg', 'Bureau D1', 'cv/lopez.xml'),
(10, 'Fischer', 'Anna', 'anna.fischer@medicare.com', '1234', 'généraliste', 'medecin/medecin1.jpg', 'Bureau B1', 'cv/fischer.xml');

-- --------------------------------------------------------

--
-- Structure de la table `laboratoire`
--

DROP TABLE IF EXISTS `laboratoire`;
CREATE TABLE IF NOT EXISTS `laboratoire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `salle` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `horaires` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `laboratoire`
--

INSERT INTO `laboratoire` (`id`, `nom`, `email`, `telephone`, `salle`, `photo`, `adresse`, `horaires`) VALUES
(1, 'Laboratoire Pasteur', 'contact@pasteurlab.fr', '0145234567', 'Salle 201', 'labo/labo1.jpg', '123', '12H-17H'),
(2, 'Laboratoire BioSanté', 'infos@biosante.fr', '0176543210', 'Salle 105', 'labo/labo1.jpg', '423', '8H-10H'),
(3, 'Laboratoire MedTech', 'rdv@medtech.fr', '0167894523', 'Salle 310', 'labo/labo1.jpg', '344', '8H-17H');

-- --------------------------------------------------------

--
-- Structure de la table `disponibilites`
--

CREATE TABLE IF NOT EXISTS `disponibilites` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_medecin` INT NOT NULL,
    `jour_semaine` ENUM('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi') NOT NULL,
    `heure_debut` TIME NOT NULL,
    `heure_fin` TIME NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`id_medecin`) REFERENCES `medecin`(`id`)
);

INSERT INTO disponibilites (id_medecin, jour_semaine, heure_debut, heure_fin) VALUES
(1, 'Lundi', '08:00:00', '12:00:00'),
(1, 'Mardi', '14:00:00', '20:00:00'),
(1, 'Jeudi', '08:00:00', '16:00:00'),
(1, 'Vendredi', '08:00:00', '18:00:00'),

(2, 'Lundi', '09:00:00', '13:00:00'),
(2, 'Mardi', '09:00:00', '13:00:00'),
(2, 'Mercredi', '09:00:00', '13:00:00'),
(2, 'Jeudi', '09:00:00', '13:00:00'),
(2, 'Vendredi', '09:00:00', '13:00:00'),

(3, 'Lundi', '10:00:00', '17:00:00'),
(3, 'Mardi', '10:00:00', '17:00:00'),
(3, 'Mercredi', '10:00:00', '17:00:00'),
(3, 'Jeudi', '10:00:00', '17:00:00'),
(3, 'Vendredi', '10:00:00', '17:00:00'),

(4, 'Mercredi', '09:00:00', '16:30:00'),
(4, 'Jeudi', '09:00:00', '16:30:00'),
(4, 'Vendredi', '09:00:00', '16:30:00'),

(5, 'Mercredi', '06:00:00', '16:30:00'),
(5, 'Jeudi', '07:00:00', '16:30:00'),
(5, 'Vendredi', '08:00:00', '16:30:00'),

(6, 'Lundi', '07:00:00', '16:30:00'),
(6, 'Mardi', '08:00:00', '16:30:00'),

(7, 'Mercredi', '07:00:00', '15:00:00'),
(7, 'Jeudi', '12:30:00', '18:30:00'),

(8, 'Vendredi', '09:00:00', '18:00:00'),
(8, 'Samedi', '09:00:00', '18:30:00'),

(9, 'Lundi', '10:00:00', '17:00:00'),
(9, 'Mardi', '10:00:00', '17:30:00'),

(10, 'Mercredi', '07:00:00', '18:30:00'),
(10, 'Samedi', '08:00:00', '13:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_client` int NOT NULL,
  `id_medecin` int NOT NULL,
  `expediteur` enum('client','medecin') NOT NULL,
  `contenu` text NOT NULL,
  `date_envoi` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_client`) REFERENCES `client`(`id`),
  FOREIGN KEY (`id_medecin`) REFERENCES `medecin`(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `id_client`, `id_medecin`, `expediteur`, `contenu`, `date_envoi`) VALUES
(1, 2, 3, 'client', 'fdeff', '2025-05-31 01:49:38'),
(2, 2, 3, 'client', 'ggg', '2025-05-31 02:00:26'),
(3, 2, 3, 'medecin', 'ggg', '2025-05-31 02:46:14');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- --------------------------------------------------------

--
-- Structure de la table `rdv`
--

DROP TABLE IF EXISTS `rdv`;
CREATE TABLE IF NOT EXISTS `rdv` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_medecin` INT NOT NULL,
  `id_client` INT NOT NULL,
  `date_rdv` DATE NOT NULL,
  `heure_rdv` TIME NOT NULL,
  `statut` ENUM('en_attente', 'confirme', 'refuse') DEFAULT 'en_attente',
  FOREIGN KEY (`id_medecin`) REFERENCES `medecin`(`id`),
  FOREIGN KEY (`id_client`) REFERENCES `client`(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `rdv` (`id`, `id_medecin`, `id_client`, `date_rdv`, `heure_rdv`, `statut`) VALUES
(1, '6', '3', '2025-06-02', '15:00:00', 'en_attente'),
(2, '7', '4', '2025-05-30', '12:00:00', 'confirme'),
(3, '8', '5', '2025-06-01', '14:15:00', 'confirme');