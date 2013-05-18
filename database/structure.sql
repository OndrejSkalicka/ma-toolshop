-- phpMyAdmin SQL Dump
-- version 3.2.2-rc1
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Sobota 18. května 2013, 11:59
-- Verze MySQL: 5.1.66
-- Verze PHP: 5.3.3-7+squeeze15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `savannah_meliorannis`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `ali`
--

CREATE TABLE IF NOT EXISTS `ali` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_spravce` int(11) NOT NULL DEFAULT '0',
  `jmeno` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `tajna` set('0','1') COLLATE utf8_czech_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `jmeno` (`jmeno`),
  KEY `ID_spravce` (`ID_spravce`),
  KEY `tajna` (`tajna`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=160 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `Branky`
--

CREATE TABLE IF NOT EXISTS `Branky` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `cislo` int(11) NOT NULL DEFAULT '0',
  `strana` char(1) COLLATE utf8_czech_ci NOT NULL DEFAULT 'D',
  `obranci` text COLLATE utf8_czech_ci NOT NULL,
  `ID_veky` int(11) NOT NULL DEFAULT '0',
  `zobraz_prefix` int(11) NOT NULL DEFAULT '1',
  `zamcena` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `ID_veky` (`ID_veky`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=284 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `chatt`
--

CREATE TABLE IF NOT EXISTS `chatt` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_users` int(11) NOT NULL DEFAULT '0',
  `mistnost` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `datum` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `chatt_ban`
--

CREATE TABLE IF NOT EXISTS `chatt_ban` (
  `idchatt_ban` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idSpravce` int(10) unsigned NOT NULL DEFAULT '0',
  `idUsers` int(10) unsigned NOT NULL DEFAULT '0',
  `idMistnost` int(10) unsigned NOT NULL DEFAULT '0',
  `expire` int(10) unsigned NOT NULL DEFAULT '0',
  `reason` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`idchatt_ban`),
  KEY `idSpravce` (`idSpravce`),
  KEY `idUsers` (`idUsers`),
  KEY `idMistnost` (`idMistnost`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `chatt_last_visit`
--

CREATE TABLE IF NOT EXISTS `chatt_last_visit` (
  `idMistnost` int(10) unsigned NOT NULL DEFAULT '0',
  `idUsers` int(10) unsigned NOT NULL DEFAULT '0',
  `since` int(10) unsigned DEFAULT NULL,
  `heslo` varchar(32) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`idMistnost`,`idUsers`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `chatt_mistnost`
--

CREATE TABLE IF NOT EXISTS `chatt_mistnost` (
  `idMistnost` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idVlastnik` int(10) unsigned NOT NULL DEFAULT '0',
  `heslo` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `jmeno` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`idMistnost`),
  UNIQUE KEY `jmeno` (`jmeno`),
  KEY `idVlastnik` (`idVlastnik`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `chatt_prispevek`
--

CREATE TABLE IF NOT EXISTS `chatt_prispevek` (
  `idPrispevek` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idUsers` int(10) unsigned NOT NULL DEFAULT '0',
  `idMistnost` int(10) unsigned NOT NULL DEFAULT '0',
  `text` text COLLATE utf8_czech_ci,
  `time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idPrispevek`),
  KEY `id_mistnost_idx` (`idMistnost`),
  KEY `time_idx` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `chatt_spravce`
--

CREATE TABLE IF NOT EXISTS `chatt_spravce` (
  `idUsers` int(10) unsigned NOT NULL DEFAULT '0',
  `idMistnost` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`idUsers`,`idMistnost`),
  KEY `idMistnost` (`idMistnost`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `hlidka_hodiny`
--

CREATE TABLE IF NOT EXISTS `hlidka_hodiny` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `users_ID` int(11) NOT NULL DEFAULT '0',
  `hod_0` int(11) NOT NULL DEFAULT '0',
  `hod_1` int(11) NOT NULL DEFAULT '0',
  `hod_2` int(11) NOT NULL DEFAULT '0',
  `hod_3` int(11) NOT NULL DEFAULT '0',
  `hod_4` int(11) NOT NULL DEFAULT '0',
  `hod_5` int(11) NOT NULL DEFAULT '0',
  `hod_6` int(11) NOT NULL DEFAULT '0',
  `hod_7` int(11) NOT NULL DEFAULT '0',
  `hod_8` int(11) NOT NULL DEFAULT '0',
  `hod_9` int(11) NOT NULL DEFAULT '0',
  `hod_10` int(11) NOT NULL DEFAULT '0',
  `hod_11` int(11) NOT NULL DEFAULT '0',
  `hod_12` int(11) NOT NULL DEFAULT '0',
  `hod_13` int(11) NOT NULL DEFAULT '0',
  `hod_14` int(11) NOT NULL DEFAULT '0',
  `hod_15` int(11) NOT NULL DEFAULT '0',
  `hod_16` int(11) NOT NULL DEFAULT '0',
  `hod_17` int(11) NOT NULL DEFAULT '0',
  `hod_18` int(11) NOT NULL DEFAULT '0',
  `hod_19` int(11) NOT NULL DEFAULT '0',
  `hod_20` int(11) NOT NULL DEFAULT '0',
  `hod_21` int(11) NOT NULL DEFAULT '0',
  `hod_22` int(11) NOT NULL DEFAULT '0',
  `hod_23` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `users_ID` (`users_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=137 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `hlidka_log`
--

CREATE TABLE IF NOT EXISTS `hlidka_log` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `users_ID` int(11) NOT NULL DEFAULT '0',
  `cas` int(11) NOT NULL DEFAULT '0',
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `ma_cas` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=88450 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `iplog`
--

CREATE TABLE IF NOT EXISTS `iplog` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `login` int(11) NOT NULL DEFAULT '0',
  `server` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `koberce`
--

CREATE TABLE IF NOT EXISTS `koberce` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_vlastnik` int(11) NOT NULL DEFAULT '0',
  `expire` int(11) NOT NULL DEFAULT '0',
  `verejny` set('1','0') COLLATE utf8_czech_ci NOT NULL DEFAULT '0',
  `cil` int(11) NOT NULL DEFAULT '0',
  `cska` text COLLATE utf8_czech_ci NOT NULL,
  `poznamka` text COLLATE utf8_czech_ci NOT NULL,
  `cil_regent` varchar(64) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `cil_provincie` varchar(64) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `cil_povolani` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `cil_rasa` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `cil_pohlavi` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `cil_slava` int(11) NOT NULL DEFAULT '0',
  `cil_lidi` int(11) NOT NULL DEFAULT '0',
  `cil_hrady` int(11) NOT NULL DEFAULT '0',
  `cil_zlato` int(11) NOT NULL DEFAULT '0',
  `cil_mana` int(11) NOT NULL DEFAULT '0',
  `cil_rozloha` int(11) NOT NULL DEFAULT '0',
  `cil_presvedceni` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `cil_aliance` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `cil_sila` int(11) NOT NULL DEFAULT '0',
  `bounty1` int(11) NOT NULL DEFAULT '0',
  `bounty2` int(11) NOT NULL DEFAULT '0',
  `bounty3` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  `noob` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `koberce_users`
--

CREATE TABLE IF NOT EXISTS `koberce_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_users` int(11) NOT NULL DEFAULT '0',
  `ID_koberce` int(11) NOT NULL DEFAULT '0',
  `poradi` int(11) NOT NULL DEFAULT '0',
  `poznamka` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `MA_units`
--

CREATE TABLE IF NOT EXISTS `MA_units` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(64) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `druh` char(1) COLLATE utf8_czech_ci NOT NULL DEFAULT 'B',
  `typ` char(1) COLLATE utf8_czech_ci NOT NULL DEFAULT 'S',
  `phb` int(11) NOT NULL DEFAULT '1',
  `ini` int(11) NOT NULL DEFAULT '0',
  `dmg` int(11) NOT NULL DEFAULT '0',
  `brn` int(11) NOT NULL DEFAULT '0',
  `zvt` int(11) NOT NULL DEFAULT '0',
  `pwr` float NOT NULL DEFAULT '0',
  `brankar` int(1) NOT NULL DEFAULT '0',
  `ID_veky` int(11) NOT NULL DEFAULT '0',
  `cena_zl` float NOT NULL DEFAULT '0',
  `cena_mn` float NOT NULL DEFAULT '0',
  `cena_lidi` float NOT NULL DEFAULT '0',
  `plat_zl` float NOT NULL DEFAULT '0',
  `plat_mn` float NOT NULL DEFAULT '0',
  `plat_lidi` float NOT NULL DEFAULT '0',
  `barva` set('N','C','M','Z','S','B','F') COLLATE utf8_czech_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`ID`),
  KEY `ID_veky` (`ID_veky`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=8726 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `prava`
--

CREATE TABLE IF NOT EXISTS `prava` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_users` int(11) NOT NULL DEFAULT '0',
  `ID_pravo_text` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `ID_users` (`ID_users`),
  KEY `ID_pravo_text` (`ID_pravo_text`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=21624 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `prava_ali`
--

CREATE TABLE IF NOT EXISTS `prava_ali` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_ali` int(11) NOT NULL DEFAULT '0',
  `ID_pravo` int(11) NOT NULL DEFAULT '0',
  `typ` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `ID_ali` (`ID_ali`),
  KEY `ID_pravo` (`ID_pravo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=390 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `prava_skupiny`
--

CREATE TABLE IF NOT EXISTS `prava_skupiny` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_uziv_skupiny` int(11) NOT NULL DEFAULT '0',
  `ID_pravo` int(11) NOT NULL DEFAULT '0',
  `typ` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `pravo_text`
--

CREATE TABLE IF NOT EXISTS `pravo_text` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `pro_usery` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `prefixy`
--

CREATE TABLE IF NOT EXISTS `prefixy` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `branka` int(11) NOT NULL DEFAULT '0',
  `prefix` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_skupina` int(11) NOT NULL DEFAULT '0',
  `login` int(64) NOT NULL DEFAULT '0',
  `heslo` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `regent` varchar(64) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `provi` varchar(64) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `icq` int(11) NOT NULL DEFAULT '0',
  `popis` text COLLATE utf8_czech_ci NOT NULL,
  `zlato` int(11) NOT NULL DEFAULT '-1',
  `simul_count` int(11) NOT NULL DEFAULT '0',
  `aukce_count` int(11) NOT NULL DEFAULT '0',
  `chat_count` int(11) NOT NULL DEFAULT '0',
  `hlidka_count` int(11) NOT NULL DEFAULT '0',
  `overen` int(11) NOT NULL DEFAULT '0',
  `super` int(11) NOT NULL DEFAULT '0',
  `ID_ali_v` int(11) NOT NULL DEFAULT '0',
  `ID_ali_t` int(11) NOT NULL DEFAULT '0',
  `last_pwr` int(11) NOT NULL DEFAULT '0',
  `hlidka_pwr_abs` int(11) NOT NULL DEFAULT '0',
  `hlidka_pwr_rel` int(11) NOT NULL DEFAULT '0',
  `hlidka_pwr_need_both` int(11) NOT NULL DEFAULT '0',
  `hlidka_mail` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `hlidka_phone` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `hlidka_last_update` int(11) NOT NULL DEFAULT '0',
  `hlidka_pocet_zachran` int(11) NOT NULL DEFAULT '0',
  `vzdy_prozvonit` int(11) NOT NULL DEFAULT '0',
  `hlidka_od` int(11) NOT NULL DEFAULT '0',
  `hlidka_do` int(11) NOT NULL DEFAULT '23',
  `custom_hlidka_msg` varchar(150) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `chatt_ppp` int(11) NOT NULL DEFAULT '0',
  `chatt_refresh` int(11) NOT NULL DEFAULT '0',
  `prozvani` tinyint(1) NOT NULL DEFAULT '1',
  `potrebuje_prozvonit` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `login` (`login`),
  KEY `ID_ali_v` (`ID_ali_v`),
  KEY `ID_ali_t` (`ID_ali_t`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=11698 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `uziv_skupiny`
--

CREATE TABLE IF NOT EXISTS `uziv_skupiny` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `nazev` (`nazev`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `veky`
--

CREATE TABLE IF NOT EXISTS `veky` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `priorita` int(11) NOT NULL DEFAULT '0',
  `jmeno` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `title` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `zadatele_ali`
--

CREATE TABLE IF NOT EXISTS `zadatele_ali` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_users` int(11) NOT NULL DEFAULT '0',
  `ID_ali` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `ID_users` (`ID_users`),
  KEY `ID_ali` (`ID_ali`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=3157 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
