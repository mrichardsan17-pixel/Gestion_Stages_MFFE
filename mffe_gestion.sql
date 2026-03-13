-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 13 mars 2026 à 14:10
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mffe_gestion`
--

-- --------------------------------------------------------

--
-- Structure de la table `archives`
--

CREATE TABLE `archives` (
  `id_archive` int(11) NOT NULL,
  `nom_complet` varchar(255) DEFAULT NULL,
  `ecole` varchar(255) DEFAULT NULL,
  `direction` varchar(255) DEFAULT NULL,
  `maitre_stage` varchar(255) DEFAULT NULL,
  `theme_stage` varchar(255) DEFAULT NULL,
  `date_fin_reelle` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `demandes`
--

CREATE TABLE `demandes` (
  `id_demande` int(11) NOT NULL,
  `nom_etudiant` varchar(100) NOT NULL,
  `prenom_etudiant` varchar(150) NOT NULL,
  `email_etudiant` varchar(255) NOT NULL,
  `telephone_etudiant` varchar(20) DEFAULT NULL,
  `genre` enum('M','F') NOT NULL,
  `id_ecole` int(11) DEFAULT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `lettre_motivation` varchar(255) DEFAULT NULL,
  `statut_demande` enum('En attente','Validé','Refusé') DEFAULT 'En attente',
  `date_soumission` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `demandes`
--

INSERT INTO `demandes` (`id_demande`, `nom_etudiant`, `prenom_etudiant`, `email_etudiant`, `telephone_etudiant`, `genre`, `id_ecole`, `cv_path`, `lettre_motivation`, `statut_demande`, `date_soumission`) VALUES
(2, 'san', 'kouassi k. Richard', 'm.richardsan17@gmail.com', '0715166856', 'M', 1, '1770813508_CV_san.pdf', '1770813508_PHOTO_san.jpg', 'Validé', '2026-02-11 00:00:00'),
(4, 'kouadio ', 'affoué marie ange', 'marie@gmail.com', '0556280175', 'F', 2, '1771247734_CV_kouadio .pdf', '1771247734_PHOTO_kouadio .jpg', 'Validé', '2026-02-16 00:00:00'),
(6, 'san ', 'kouassi k. Richard', 'richard@gmail.com', '0107777788', 'M', 1, '1771324201_CV_san .pdf', '1771324201_PHOTO_san .jpg', 'Validé', '2026-02-17 00:00:00'),
(8, 'yao', 'roger', 'yao@gmail.com', '0720175658', 'M', 2, '1771377952_CV_yao.pdf', '1771377952_LETTRE_yao.pdf', 'Validé', '2026-02-18 00:00:00'),
(10, 'kone', 'fatouma zarah', 'kone@gmail.com', '0714071390', 'F', 4, '1772246575_CV_kone.pdf', '1772246575_LETTRE_kone.pdf', 'Validé', '2026-02-28 00:00:00'),
(11, 'yao', 'jonathan', 'jonathan@gmail.com', '0202050202', 'M', 4, '1772491877_CV_yao.pdf', '1772491877_LETTRE_yao.pdf', 'Validé', '2026-03-02 00:00:00'),
(12, 'koffi', 'ange Hervé', 'koffi@gmail.com', '0122111546', 'F', 3, '1772497220_CV_kkk.pdf', '1772497220_LETTRE_kkk.pdf', 'Validé', '2026-03-03 00:00:00'),
(13, 'Nour', 'salif', 'Nour@gmail.com', '0525245152', 'M', 1, '1772499181_CV_tour.pdf', '1772499181_LETTRE_tour.pdf', 'Refusé', '2026-03-03 00:00:00'),
(14, 'koffi', 'Emmanuel', 'kofemmanuel007@gmail.com', '0595364688', 'M', 4, '1773236329_CV_koffi.pdf', '1773236329_LETTRE_koffi.pdf', 'Validé', '2026-03-11 00:00:00'),
(15, 'Bini sonoh', 'Esther', 'Bini@gmail.com', '0504136558', 'F', 3, '1773240498_CV_Bini sonoh.pdf', '1773240498_LETTRE_Bini sonoh.pdf', 'Refusé', '2026-03-11 00:00:00'),
(16, 'kouakou', 'kader', 'k@gmail.com', '0122020202', 'M', 2, '1773278502_CV_kouakou.pdf', '1773278502_LETTRE_kouakou.pdf', 'Validé', '2026-03-12 00:00:00'),
(17, 'konate', 'lassina', 'lass@gmail.com', '0101010101', 'M', 3, '1773298093_CV_kk.pdf', '1773298093_LETTRE_kk.pdf', 'Refusé', '2026-03-12 00:00:00'),
(18, 'cisse', 'kady', 'c@gmail.com', '0102355645', 'F', 4, '1773298926_CV_cisse.pdf', '1773298926_LETTRE_cisse.pdf', 'Validé', '2026-03-12 00:00:00'),
(23, 'kone', 'rachide', 'k.@gmail.com', '0102034566', 'M', 3, '1773316759_CV_kone.pdf', '1773316759_LETTRE_kone.pdf', 'Refusé', '2026-03-12 00:00:00'),
(25, 'kobenan', 'charles', 'ch@gmail.com', '0102033456', 'M', 5, '1773358869_CV_kobenan.pdf', '1773358869_LETTRE_kobenan.pdf', 'Refusé', '2026-03-13 00:41:09');

-- --------------------------------------------------------

--
-- Structure de la table `directions`
--

CREATE TABLE `directions` (
  `id_direction` int(11) NOT NULL,
  `nom_direction` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `directions`
--

INSERT INTO `directions` (`id_direction`, `nom_direction`) VALUES
(1, 'Direction de la Protection de l\'Enfant'),
(2, 'Direction de la Promotion de la Femme'),
(3, 'Direction de la Famille'),
(4, 'Direction des systèmes d\'Information'),
(5, 'Direction des Ressources humaines'),
(7, 'Direction des affaires financières et du patrimoine');

-- --------------------------------------------------------

--
-- Structure de la table `ecoles`
--

CREATE TABLE `ecoles` (
  `id_ecole` int(11) NOT NULL,
  `nom_ecole` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ecoles`
--

INSERT INTO `ecoles` (`id_ecole`, `nom_ecole`) VALUES
(1, 'IFSM'),
(2, 'INP-HB'),
(3, 'ESATIC'),
(4, 'UFHB'),
(5, 'groupe loko');

-- --------------------------------------------------------

--
-- Structure de la table `rejets`
--

CREATE TABLE `rejets` (
  `id_rejet` int(11) NOT NULL,
  `id_demande` int(11) NOT NULL,
  `motif_rejet` varchar(255) NOT NULL,
  `date_decision` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `rejets`
--

INSERT INTO `rejets` (`id_rejet`, `id_demande`, `motif_rejet`, `date_decision`) VALUES
(1, 13, 'Dossier incomplet', '2026-03-03 01:19:14'),
(2, 15, 'Dossier incomplet', '2026-03-11 14:57:12'),
(3, 17, 'Dossier incomplet', '2026-03-12 06:54:30'),
(4, 25, 'Capacité d\'accueil atteinte', '2026-03-13 01:58:19'),
(5, 23, 'Période de stage non disponible', '2026-03-13 01:59:08');

-- --------------------------------------------------------

--
-- Structure de la table `stages`
--

CREATE TABLE `stages` (
  `id_stage` int(11) NOT NULL,
  `id_demande` int(11) DEFAULT NULL,
  `id_maitre` int(11) DEFAULT NULL,
  `id_direction` int(11) DEFAULT NULL,
  `theme_stage` varchar(255) DEFAULT NULL,
  `type_stage` enum('Stage de qualification (3 mois)','Stage de perfectionnement (6 mois)') NOT NULL DEFAULT 'Stage de qualification (3 mois)',
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `progression` int(11) DEFAULT 0,
  `etat_stage` enum('En cours','Terminé') DEFAULT 'En cours'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `stages`
--

INSERT INTO `stages` (`id_stage`, `id_demande`, `id_maitre`, `id_direction`, `theme_stage`, `type_stage`, `date_debut`, `date_fin`, `progression`, `etat_stage`) VALUES
(1, 2, 2, 1, 'APPLICATION DE GESTION DES COURRIERS MINISTÉRIEL ', 'Stage de qualification (3 mois)', '2026-02-12', '2026-05-12', 33, 'En cours'),
(2, 4, 3, 2, 'Mise en place d\'une application web dédiée à la gestion du personnel', 'Stage de qualification (3 mois)', '2026-02-16', '2026-05-15', 100, 'Terminé'),
(3, 6, 2, 4, 'LA MISE EN PLACE D\'UNE APPLICATION WEB DYNAMIQUE DEDIEE A LA GESTION DES STAGES', 'Stage de qualification (3 mois)', '2026-01-12', '2026-04-12', 100, 'Terminé'),
(4, 8, 4, 1, 'Digitalisation des moyens informatique ', 'Stage de qualification (3 mois)', '2026-02-18', '2026-05-18', 26, 'En cours'),
(5, 10, 5, 4, 'LA SÉCURITÉ RÉSEAU INFORMATIQUE', '', '2026-02-28', '2026-08-28', 15, 'En cours'),
(7, 11, 3, 2, 'une plate-forme e-Learning pour les orphelins ', '', '2026-03-03', '2026-09-03', 7, 'En cours'),
(8, 12, 5, 4, 'APPLICATION DE GESTION DES HOTELS 5 ÉTOILES ✨ ', '', '2026-03-11', '2026-06-11', 3, 'En cours'),
(9, 14, 5, 4, 'Application de gestion des cartes professionnelles ', '', '2026-03-11', '2026-06-11', 3, 'En cours'),
(10, 16, 7, 5, 'site e-commerce', '', '2026-03-12', '2026-09-12', 2, 'En cours'),
(11, 18, 2, 1, 'application de gestion d\'un parking auto', '', '2026-03-12', '2026-06-12', 2, 'En cours'),
(12, 23, 4, 1, 'salut au couleur', '', '2026-03-12', '2026-06-12', 2, 'En cours');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_user` int(11) NOT NULL,
  `nom_user` varchar(100) NOT NULL,
  `prenom_user` varchar(150) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('DRH','MAITRE') NOT NULL,
  `id_direction` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_user`, `nom_user`, `prenom_user`, `email`, `photo_url`, `mot_de_passe`, `role`, `id_direction`) VALUES
(1, 'SAN KOUASSI', 'Richard', 'admin@mffe.ci', NULL, '123456', 'DRH', NULL),
(2, 'TA LOU', 'Nadège', 'N.Ta@mffe.gouv.ci', NULL, 'maitre.mffe', 'MAITRE', 1),
(3, 'AMANI', 'Marie-claire', 'mc.amani@mffe.gouv.ci', NULL, 'maitre.mffe', 'MAITRE', 2),
(4, 'DIALLO', 'Bakary', 'b.diallo@mffe.gouv.ci', NULL, 'maitre.mffe', 'MAITRE', 1),
(5, 'GODE', 'Emmanuel', 'e.gode@mffe.gouv.ci', NULL, 'maitre.mffe', 'MAITRE', 4),
(7, 'TOURE', 'Mohamed', 'm.Toure@mffe.gouv.ci', NULL, 'maitre.mffe', 'MAITRE', 5);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `archives`
--
ALTER TABLE `archives`
  ADD PRIMARY KEY (`id_archive`);

--
-- Index pour la table `demandes`
--
ALTER TABLE `demandes`
  ADD PRIMARY KEY (`id_demande`),
  ADD KEY `id_ecole` (`id_ecole`);

--
-- Index pour la table `directions`
--
ALTER TABLE `directions`
  ADD PRIMARY KEY (`id_direction`);

--
-- Index pour la table `ecoles`
--
ALTER TABLE `ecoles`
  ADD PRIMARY KEY (`id_ecole`);

--
-- Index pour la table `rejets`
--
ALTER TABLE `rejets`
  ADD PRIMARY KEY (`id_rejet`),
  ADD KEY `id_demande` (`id_demande`);

--
-- Index pour la table `stages`
--
ALTER TABLE `stages`
  ADD PRIMARY KEY (`id_stage`),
  ADD UNIQUE KEY `id_demande` (`id_demande`),
  ADD KEY `id_maitre` (`id_maitre`),
  ADD KEY `id_direction` (`id_direction`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `archives`
--
ALTER TABLE `archives`
  MODIFY `id_archive` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `demandes`
--
ALTER TABLE `demandes`
  MODIFY `id_demande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `directions`
--
ALTER TABLE `directions`
  MODIFY `id_direction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `ecoles`
--
ALTER TABLE `ecoles`
  MODIFY `id_ecole` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `rejets`
--
ALTER TABLE `rejets`
  MODIFY `id_rejet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `stages`
--
ALTER TABLE `stages`
  MODIFY `id_stage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `demandes`
--
ALTER TABLE `demandes`
  ADD CONSTRAINT `demandes_ibfk_1` FOREIGN KEY (`id_ecole`) REFERENCES `ecoles` (`id_ecole`) ON DELETE SET NULL;

--
-- Contraintes pour la table `rejets`
--
ALTER TABLE `rejets`
  ADD CONSTRAINT `rejets_ibfk_1` FOREIGN KEY (`id_demande`) REFERENCES `demandes` (`id_demande`) ON DELETE CASCADE;

--
-- Contraintes pour la table `stages`
--
ALTER TABLE `stages`
  ADD CONSTRAINT `stages_ibfk_1` FOREIGN KEY (`id_demande`) REFERENCES `demandes` (`id_demande`) ON DELETE CASCADE,
  ADD CONSTRAINT `stages_ibfk_2` FOREIGN KEY (`id_maitre`) REFERENCES `utilisateurs` (`id_user`),
  ADD CONSTRAINT `stages_ibfk_3` FOREIGN KEY (`id_direction`) REFERENCES `directions` (`id_direction`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
