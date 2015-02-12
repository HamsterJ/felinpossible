-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Jeu 12 Février 2015 à 21:16
-- Version du serveur: 5.5.41
-- Version de PHP: 5.4.36-0+deb7u3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `felinpossible`
--
CREATE DATABASE `felinpossible` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `felinpossible`;

GRANT ALL PRIVILEGES ON *.* TO 'felinpossible'@'localhost' identified by 'felinpossible' with grant option;

-- --------------------------------------------------------

--
-- Structure de la table `fp_ad_cadeau`
--

CREATE TABLE IF NOT EXISTS `fp_ad_cadeau` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_ad_cat`
--

CREATE TABLE IF NOT EXISTS `fp_ad_cat` (
  `idAd` int(11) NOT NULL,
  `idChat` int(11) NOT NULL,
  PRIMARY KEY (`idAd`,`idChat`),
  UNIQUE KEY `uniq_ad_cat` (`idChat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fp_ad_connu`
--

CREATE TABLE IF NOT EXISTS `fp_ad_connu` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_ad_demenagement`
--

CREATE TABLE IF NOT EXISTS `fp_ad_demenagement` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_ad_domine`
--

CREATE TABLE IF NOT EXISTS `fp_ad_domine` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_ad_fiche`
--

CREATE TABLE IF NOT EXISTS `fp_ad_fiche` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text COLLATE latin1_general_ci NOT NULL,
  `prenom` text COLLATE latin1_general_ci NOT NULL,
  `adresse` text COLLATE latin1_general_ci NOT NULL,
  `cp` text COLLATE latin1_general_ci NOT NULL,
  `ville` text COLLATE latin1_general_ci NOT NULL,
  `fixe` text COLLATE latin1_general_ci NOT NULL,
  `portable` text COLLATE latin1_general_ci NOT NULL,
  `email` text COLLATE latin1_general_ci NOT NULL,
  `idLogement` tinyint(4) NOT NULL,
  `idDependance` tinyint(4) NOT NULL,
  `securise` tinyint(1) NOT NULL DEFAULT '0',
  `chatiere` tinyint(1) NOT NULL DEFAULT '0',
  `etage` tinyint(2) DEFAULT '0',
  `personnes` tinyint(2) NOT NULL,
  `age` tinyint(2) NOT NULL DEFAULT '0',
  `enfants` tinyint(2) DEFAULT '0',
  `enfantsAge` text COLLATE latin1_general_ci,
  `heures` tinyint(1) NOT NULL DEFAULT '0',
  `allergies` tinyint(1) NOT NULL DEFAULT '0',
  `aimePasChat` tinyint(1) NOT NULL DEFAULT '0',
  `animaux` text COLLATE latin1_general_ci,
  `habitudeChat` tinyint(1) DEFAULT '0',
  `chats` text COLLATE latin1_general_ci,
  `motivations` text COLLATE latin1_general_ci,
  `criteres` text COLLATE latin1_general_ci NOT NULL,
  `repere` text COLLATE latin1_general_ci NOT NULL,
  `idCadeaux` tinyint(2) NOT NULL DEFAULT '0',
  `revenus` tinyint(1) NOT NULL DEFAULT '0',
  `frais` tinyint(1) NOT NULL DEFAULT '0',
  `idVacances` tinyint(2) NOT NULL DEFAULT '0',
  `idDemenagement` tinyint(2) NOT NULL DEFAULT '0',
  `idFonderFamille` tinyint(2) NOT NULL DEFAULT '0',
  `garderVie` tinyint(1) NOT NULL DEFAULT '0',
  `securFenetre` tinyint(1) NOT NULL DEFAULT '0',
  `survFenetre` tinyint(1) NOT NULL DEFAULT '0',
  `veto` tinyint(1) NOT NULL DEFAULT '0',
  `parasite` tinyint(1) NOT NULL DEFAULT '0',
  `rappelVaccin` tinyint(1) NOT NULL DEFAULT '0',
  `alimentation` tinyint(1) NOT NULL DEFAULT '0',
  `sterelise` tinyint(1) NOT NULL DEFAULT '0',
  `jouer` tinyint(1) NOT NULL DEFAULT '0',
  `litiere` tinyint(1) NOT NULL DEFAULT '0',
  `fichier` tinyint(1) NOT NULL DEFAULT '0',
  `nouvelle` tinyint(1) NOT NULL DEFAULT '0',
  `visite` tinyint(1) NOT NULL DEFAULT '0',
  `restitue` tinyint(1) NOT NULL DEFAULT '0',
  `autres` text COLLATE latin1_general_ci NOT NULL,
  `idConnu` tinyint(1) NOT NULL DEFAULT '0',
  `profession` text COLLATE latin1_general_ci,
  `heureJoignable` text COLLATE latin1_general_ci,
  `login` text COLLATE latin1_general_ci,
  `dateSubmit` date DEFAULT NULL,
  `superficie` text COLLATE latin1_general_ci,
  `connaissanceAssoDetail` text COLLATE latin1_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2790 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_ad_fonderfamille`
--

CREATE TABLE IF NOT EXISTS `fp_ad_fonderfamille` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_ad_nourriture`
--

CREATE TABLE IF NOT EXISTS `fp_ad_nourriture` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_ad_sterelise`
--

CREATE TABLE IF NOT EXISTS `fp_ad_sterelise` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_ad_vacance`
--

CREATE TABLE IF NOT EXISTS `fp_ad_vacance` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_cat_color`
--

CREATE TABLE IF NOT EXISTS `fp_cat_color` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE latin1_general_ci NOT NULL,
  `key_words` text COLLATE latin1_general_ci,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `name_2` (`name`,`key_words`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=101 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_cat_fiche`
--

CREATE TABLE IF NOT EXISTS `fp_cat_fiche` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text COLLATE latin1_general_ci NOT NULL,
  `idSexe` tinyint(4) NOT NULL,
  `date` date DEFAULT NULL,
  `race` text COLLATE latin1_general_ci NOT NULL,
  `idCouleur` tinyint(4) NOT NULL,
  `yeux` text COLLATE latin1_general_ci NOT NULL,
  `tests` text COLLATE latin1_general_ci NOT NULL,
  `vaccins` text COLLATE latin1_general_ci NOT NULL,
  `tatouage` text COLLATE latin1_general_ci NOT NULL,
  `caractere` text COLLATE latin1_general_ci NOT NULL,
  `commentaires` text COLLATE latin1_general_ci NOT NULL,
  `miniature` text COLLATE latin1_general_ci NOT NULL,
  `adopte` tinyint(1) NOT NULL DEFAULT '0',
  `reserve` tinyint(1) NOT NULL DEFAULT '0',
  `parrain` tinyint(1) NOT NULL DEFAULT '0',
  `disparu` tinyint(1) NOT NULL DEFAULT '0',
  `topic` text COLLATE latin1_general_ci NOT NULL,
  `topic_id` mediumint(8) unsigned DEFAULT NULL,
  `date_adoption` date DEFAULT NULL,
  `to_check` int(1) NOT NULL DEFAULT '0',
  `post_id` mediumint(8) DEFAULT NULL,
  `notesPrivees` text COLLATE latin1_general_ci,
  `datePriseEnCharge` date DEFAULT NULL,
  `dateAntiPuces` date DEFAULT NULL,
  `dateVermifuge` date DEFAULT NULL,
  `statutVisite` tinyint(1) NOT NULL DEFAULT '0',
  `visitePostPar` text COLLATE latin1_general_ci,
  `dateRappelVaccins` date DEFAULT NULL,
  `dateTests` date DEFAULT NULL,
  `dateSterilisation` date DEFAULT NULL,
  `declCession` tinyint(1) NOT NULL DEFAULT '0',
  `sterilise` int(11) NOT NULL DEFAULT '0',
  `dateEnvoiRappelVac` date DEFAULT NULL,
  `dateEnvoiRappelSte` date DEFAULT NULL,
  `dateContratAdoption` date DEFAULT NULL,
  `dateApproximative` int(11) DEFAULT '0',
  `renomme` text COLLATE latin1_general_ci NOT NULL,
  `papierIdRecu` int(11) DEFAULT '0',
  `okChats` tinyint(1) NOT NULL DEFAULT '0',
  `okChiens` tinyint(1) NOT NULL DEFAULT '0',
  `okApparts` tinyint(1) NOT NULL DEFAULT '0',
  `okEnfants` tinyint(1) NOT NULL DEFAULT '0',
  `chgtProprio` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1808 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_cat_sex`
--

CREATE TABLE IF NOT EXISTS `fp_cat_sex` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_dependance`
--

CREATE TABLE IF NOT EXISTS `fp_dependance` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_fa_cat`
--

CREATE TABLE IF NOT EXISTS `fp_fa_cat` (
  `idFa` int(11) NOT NULL,
  `idChat` int(11) NOT NULL,
  PRIMARY KEY (`idFa`,`idChat`),
  UNIQUE KEY `uniq_fa_cat` (`idChat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fp_fa_fiche`
--

CREATE TABLE IF NOT EXISTS `fp_fa_fiche` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text COLLATE latin1_general_ci NOT NULL,
  `prenom` text COLLATE latin1_general_ci NOT NULL,
  `adresse` text COLLATE latin1_general_ci NOT NULL,
  `cp` text COLLATE latin1_general_ci NOT NULL,
  `ville` text COLLATE latin1_general_ci NOT NULL,
  `fixe` text COLLATE latin1_general_ci NOT NULL,
  `portable` text COLLATE latin1_general_ci NOT NULL,
  `email` text COLLATE latin1_general_ci NOT NULL,
  `idLogement` tinyint(4) NOT NULL,
  `idDependance` tinyint(4) NOT NULL,
  `chatiere` tinyint(1) NOT NULL DEFAULT '0',
  `etage` tinyint(2) DEFAULT '0',
  `personnes` tinyint(2) NOT NULL,
  `enfants` tinyint(2) DEFAULT '0',
  `enfantsAge` text COLLATE latin1_general_ci,
  `animaux` text COLLATE latin1_general_ci,
  `chats` text COLLATE latin1_general_ci,
  `motivations` text COLLATE latin1_general_ci,
  `securFenetre` tinyint(1) NOT NULL DEFAULT '0',
  `survFenetre` tinyint(1) NOT NULL DEFAULT '0',
  `veto` tinyint(1) NOT NULL DEFAULT '0',
  `contact` tinyint(1) NOT NULL DEFAULT '0',
  `patience` tinyint(1) NOT NULL DEFAULT '0',
  `jouer` tinyint(1) NOT NULL DEFAULT '0',
  `croquette` tinyint(1) NOT NULL DEFAULT '0',
  `mere` tinyint(1) NOT NULL DEFAULT '0',
  `biberon` tinyint(1) NOT NULL DEFAULT '0',
  `chatons` tinyint(1) NOT NULL DEFAULT '0',
  `fiv` tinyint(1) NOT NULL DEFAULT '0',
  `felv` tinyint(1) NOT NULL DEFAULT '0',
  `soins` tinyint(1) NOT NULL DEFAULT '0',
  `quarantaine` tinyint(1) NOT NULL DEFAULT '0',
  `isoler` tinyint(1) NOT NULL DEFAULT '0',
  `idStatut` int(11) NOT NULL DEFAULT '7',
  `notes` text COLLATE latin1_general_ci,
  `login` text COLLATE latin1_general_ci,
  `dateSubmit` date DEFAULT NULL,
  `superficie` text COLLATE latin1_general_ci,
  `dateContratFa` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_fiche_statut` (`idStatut`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=781 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_fa_indispo`
--

CREATE TABLE IF NOT EXISTS `fp_fa_indispo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idFa` int(11) NOT NULL,
  `dateDeb` date NOT NULL,
  `dateFin` date NOT NULL,
  `idStatut` int(11) NOT NULL,
  `comment` text COLLATE latin1_general_ci,
  PRIMARY KEY (`id`),
  KEY `fk_indispo_fa` (`idFa`),
  KEY `fk_statut_indispo_fa` (`idStatut`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=59 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_fa_indispo_statut`
--

CREATE TABLE IF NOT EXISTS `fp_fa_indispo_statut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_fa_statut`
--

CREATE TABLE IF NOT EXISTS `fp_fa_statut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_logement`
--

CREATE TABLE IF NOT EXISTS `fp_logement` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_new`
--

CREATE TABLE IF NOT EXISTS `fp_new` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_soins_fiche`
--

CREATE TABLE IF NOT EXISTS `fp_soins_fiche` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateDemande` varchar(20) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `login` varchar(50) NOT NULL,
  `nomChat` varchar(50) NOT NULL,
  `dateVisite` varchar(20) NOT NULL,
  `idVeto` int(11) NOT NULL,
  `vetoCompl` varchar(255) NOT NULL,
  `soinIdent` int(11) NOT NULL,
  `soinTests` int(11) NOT NULL,
  `soinVaccins` int(11) NOT NULL,
  `soinSterilisation` int(11) NOT NULL,
  `soinVermifuge` int(11) NOT NULL,
  `soinAntiParasites` int(11) NOT NULL,
  `soinAutre` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `TraitementEnCoursPar` varchar(50) NOT NULL,
  `ficheGeneree` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=218 ;

-- --------------------------------------------------------

--
-- Structure de la table `fp_veto_fiche`
--

CREATE TABLE IF NOT EXISTS `fp_veto_fiche` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `raison` text COLLATE latin1_general_ci,
  `adresse` text COLLATE latin1_general_ci,
  `cp` text COLLATE latin1_general_ci,
  `ville` text COLLATE latin1_general_ci,
  `fixe` text COLLATE latin1_general_ci,
  `portable` text COLLATE latin1_general_ci,
  `email` text COLLATE latin1_general_ci,
  `siteInternet` text COLLATE latin1_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_users`
--

CREATE TABLE IF NOT EXISTS `phpbb_users` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` tinyint(2) NOT NULL DEFAULT '0',
  `group_id` mediumint(8) unsigned NOT NULL DEFAULT '3',
  `user_permissions` mediumtext COLLATE utf8_bin NOT NULL,
  `user_perm_from` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_regdate` int(11) unsigned NOT NULL DEFAULT '0',
  `username` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `username_clean` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_password` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_passchg` int(11) unsigned NOT NULL DEFAULT '0',
  `user_pass_convert` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_email` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_email_hash` bigint(20) NOT NULL DEFAULT '0',
  `user_birthday` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_lastvisit` int(11) unsigned NOT NULL DEFAULT '0',
  `user_lastmark` int(11) unsigned NOT NULL DEFAULT '0',
  `user_lastpost_time` int(11) unsigned NOT NULL DEFAULT '0',
  `user_lastpage` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_last_confirm_key` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_last_search` int(11) unsigned NOT NULL DEFAULT '0',
  `user_warnings` tinyint(4) NOT NULL DEFAULT '0',
  `user_last_warning` int(11) unsigned NOT NULL DEFAULT '0',
  `user_login_attempts` tinyint(4) NOT NULL DEFAULT '0',
  `user_inactive_reason` tinyint(2) NOT NULL DEFAULT '0',
  `user_inactive_time` int(11) unsigned NOT NULL DEFAULT '0',
  `user_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_lang` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_timezone` decimal(5,2) NOT NULL DEFAULT '0.00',
  `user_dst` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_dateformat` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT 'd M Y H:i',
  `user_style` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_rank` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_colour` varchar(6) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_new_privmsg` int(4) NOT NULL DEFAULT '0',
  `user_unread_privmsg` int(4) NOT NULL DEFAULT '0',
  `user_last_privmsg` int(11) unsigned NOT NULL DEFAULT '0',
  `user_message_rules` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_full_folder` int(11) NOT NULL DEFAULT '-3',
  `user_emailtime` int(11) unsigned NOT NULL DEFAULT '0',
  `user_topic_show_days` smallint(4) unsigned NOT NULL DEFAULT '0',
  `user_topic_sortby_type` varchar(1) COLLATE utf8_bin NOT NULL DEFAULT 't',
  `user_topic_sortby_dir` varchar(1) COLLATE utf8_bin NOT NULL DEFAULT 'd',
  `user_post_show_days` smallint(4) unsigned NOT NULL DEFAULT '0',
  `user_post_sortby_type` varchar(1) COLLATE utf8_bin NOT NULL DEFAULT 't',
  `user_post_sortby_dir` varchar(1) COLLATE utf8_bin NOT NULL DEFAULT 'a',
  `user_notify` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_notify_pm` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_notify_type` tinyint(4) NOT NULL DEFAULT '0',
  `user_allow_pm` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_allow_viewonline` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_allow_viewemail` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_allow_massemail` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_options` int(11) unsigned NOT NULL DEFAULT '230271',
  `user_avatar` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_avatar_type` tinyint(2) NOT NULL DEFAULT '0',
  `user_avatar_width` smallint(4) unsigned NOT NULL DEFAULT '0',
  `user_avatar_height` smallint(4) unsigned NOT NULL DEFAULT '0',
  `user_sig` mediumtext COLLATE utf8_bin NOT NULL,
  `user_sig_bbcode_uid` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_sig_bbcode_bitfield` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_from` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_icq` varchar(15) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_aim` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_yim` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_msnm` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_jabber` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_website` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_occ` text COLLATE utf8_bin NOT NULL,
  `user_interests` text COLLATE utf8_bin NOT NULL,
  `user_actkey` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_newpasswd` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_form_salt` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_new` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_reminded` tinyint(4) NOT NULL DEFAULT '0',
  `user_reminded_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username_clean` (`username_clean`),
  KEY `user_birthday` (`user_birthday`),
  KEY `user_email_hash` (`user_email_hash`),
  KEY `user_type` (`user_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3197 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_user_group`
--

CREATE TABLE IF NOT EXISTS `phpbb_user_group` (
  `group_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `group_leader` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_pending` tinyint(1) unsigned NOT NULL DEFAULT '1',
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`),
  KEY `group_leader` (`group_leader`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contraintes pour la table `fp_ad_cat`
--
ALTER TABLE `fp_ad_cat`
  ADD CONSTRAINT `fk_ad_cat_ad` FOREIGN KEY (`idAd`) REFERENCES `fp_ad_fiche` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ad_cat_cat` FOREIGN KEY (`idChat`) REFERENCES `fp_cat_fiche` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `fp_fa_cat`
--
ALTER TABLE `fp_fa_cat`
  ADD CONSTRAINT `fk_fa_cat_cat` FOREIGN KEY (`idChat`) REFERENCES `fp_cat_fiche` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_fa_cat_fa` FOREIGN KEY (`idFa`) REFERENCES `fp_fa_fiche` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `fp_fa_fiche`
--
ALTER TABLE `fp_fa_fiche`
  ADD CONSTRAINT `fk_fiche_statut` FOREIGN KEY (`idStatut`) REFERENCES `fp_fa_statut` (`id`);

--
-- Contraintes pour la table `fp_fa_indispo`
--
ALTER TABLE `fp_fa_indispo`
  ADD CONSTRAINT `fk_indispo_fa` FOREIGN KEY (`idFa`) REFERENCES `fp_fa_fiche` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_statut_indispo_fa` FOREIGN KEY (`idStatut`) REFERENCES `fp_fa_indispo_statut` (`id`);

EXIT
