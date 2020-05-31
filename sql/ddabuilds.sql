-- 
-- Disable foreign keys
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Set SQL mode
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Set character set the client will use to send SQL statements to the server
--
SET NAMES 'utf8';

--
-- Set default database
--
USE ddabuilds;

--
-- Create table `votes`
--
CREATE TABLE votes (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  steamid BIGINT(20) NOT NULL,
  fk_build INT(11) NOT NULL,
  vote INT(11) NOT NULL,
  date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
)
ENGINE = INNODB;

--
-- Create table `towers`
--
CREATE TABLE towers (
  id INT(11) UNSIGNED NOT NULL,
  mu TINYINT(1) DEFAULT NULL,
  unitcost INT(11) NOT NULL,
  manacost INT(11) NOT NULL,
  fk_class INT(11) NOT NULL,
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB;

--
-- Create table `placed`
--
CREATE TABLE placed (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  fk_build INT(10) UNSIGNED NOT NULL,
  fk_tower INT(10) UNSIGNED NOT NULL,
  x INT(11) NOT NULL,
  y INT(11) NOT NULL,
  rotation INT(11) NOT NULL,
  fk_buildwave INT(11) NOT NULL DEFAULT 0,
  override_du INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
)
ENGINE = INNODB;

--
-- Create table `notifications`
--
CREATE TABLE notifications (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  steamid BIGINT(20) NOT NULL,
  seen INT(11) NOT NULL DEFAULT 0,
  data BIGINT(20) NOT NULL,
  type INT(11) NOT NULL,
  date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fk_build INT(11) NOT NULL,
  fk_comment INT(11) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB;

--
-- Create table `maps`
--
CREATE TABLE maps (
  id INT(10) UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  units INT(10) UNSIGNED NOT NULL,
  sort INT(11) NOT NULL,
  fk_mapcategory INT(11) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB;

--
-- Create table `mapcategories`
--
CREATE TABLE mapcategories (
  id INT(10) UNSIGNED NOT NULL,
  name VARCHAR(60) NOT NULL,
  text VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB;

--
-- Create table `difficulties`
--
CREATE TABLE difficulties (
  id INT(10) UNSIGNED NOT NULL,
  name VARCHAR(30) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB;

--
-- Create table `commentvotes`
--
CREATE TABLE commentvotes (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  steamid BIGINT(20) NOT NULL,
  fk_comment INT(11) NOT NULL,
  vote INT(11) NOT NULL,
  date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
)
ENGINE = INNODB;

--
-- Create table `comments`
--
CREATE TABLE comments (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  steamid BIGINT(20) UNSIGNED NOT NULL,
  comment VARCHAR(1000) BINARY CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  fk_build INT(11) NOT NULL,
  date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
)
ENGINE = INNODB;

--
-- Create table `classes`
--
CREATE TABLE classes (
  id INT(10) UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB;

--
-- Create table `buildwaves`
--
CREATE TABLE buildwaves (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  fk_build INT(11) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB;

--
-- Create table `buildstatuses`
--
CREATE TABLE buildstatuses (
  id INT(10) UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB;

--
-- Create table `builds`
--
CREATE TABLE builds (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  author VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  name VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  map INT(10) UNSIGNED NOT NULL,
  difficulty INT(10) UNSIGNED NOT NULL,
  description TEXT BINARY CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fk_user BIGINT(20) NOT NULL,
  fk_buildstatus INT(11) NOT NULL,
  campaign INT(11) DEFAULT NULL,
  survival INT(11) DEFAULT NULL,
  purestrategy INT(11) DEFAULT NULL,
  hardcore INT(11) DEFAULT NULL,
  challenge INT(11) DEFAULT NULL,
  mixmode INT(11) DEFAULT NULL,
  afkable INT(11) DEFAULT NULL,
  views INT(11) NOT NULL DEFAULT 0,
  timeperrun VARCHAR(20) BINARY CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  expperrun VARCHAR(20) BINARY CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  deleted INT(11) NOT NULL DEFAULT 0,
  squirehp INT(11) DEFAULT 0,
  squirerate INT(11) NOT NULL DEFAULT 0,
  squiredamage INT(11) NOT NULL DEFAULT 0,
  squirerange INT(11) NOT NULL DEFAULT 0,
  apprenticehp INT(11) NOT NULL DEFAULT 0,
  apprenticerate INT(11) NOT NULL DEFAULT 0,
  apprenticedamage INT(11) NOT NULL DEFAULT 0,
  apprenticerange INT(11) NOT NULL DEFAULT 0,
  huntresshp INT(11) NOT NULL DEFAULT 0,
  huntressrate INT(11) NOT NULL DEFAULT 0,
  huntressdamage INT(11) NOT NULL DEFAULT 0,
  huntressrange INT(11) NOT NULL DEFAULT 0,
  monkhp INT(11) NOT NULL DEFAULT 0,
  monkrate INT(11) NOT NULL DEFAULT 0,
  monkdamage INT(11) NOT NULL DEFAULT 0,
  monkrange INT(11) NOT NULL DEFAULT 0,
  evhp INT(11) NOT NULL DEFAULT 0,
  evrate INT(11) NOT NULL DEFAULT 0,
  evdamage INT(11) NOT NULL DEFAULT 0,
  evrange INT(11) NOT NULL DEFAULT 0,
  summonerhp INT(11) NOT NULL DEFAULT 0,
  summonerrate INT(11) NOT NULL DEFAULT 0,
  summonerdamage INT(11) NOT NULL DEFAULT 0,
  summonerrange INT(11) NOT NULL DEFAULT 0,
  jesterhp INT(11) NOT NULL DEFAULT 0,
  jesterrate INT(11) NOT NULL DEFAULT 0,
  jesterdamage INT(11) NOT NULL DEFAULT 0,
  jesterrange INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
)
ENGINE = INNODB;

-- 
-- Dumping data for table towers
--
INSERT INTO towers VALUES
(1, NULL, 3, 30, 1, 'Spiked Blockade'),
(2, NULL, 6, 80, 1, 'Harpoon Turret'),
(3, NULL, 4, 40, 1, 'Bouncer Blockade'),
(4, NULL, 7, 100, 1, 'Bowling Ball Turret'),
(5, NULL, 8, 140, 1, 'Slice N Dice Blockade'),
(6, NULL, 3, 40, 2, 'Magic Missile Tower'),
(7, NULL, 1, 20, 2, 'Elemental Barrier'),
(8, NULL, 5, 80, 2, 'Flameburst Tower'),
(9, NULL, 7, 120, 2, 'Lightning Tower'),
(10, NULL, 8, 150, 2, 'Deadly Striker Tower'),
(11, NULL, 3, 40, 3, 'Explosive Trap'),
(12, NULL, 3, 30, 3, 'Poison Gas Trap'),
(13, NULL, 4, 60, 3, 'Inferno Trap'),
(14, NULL, 3, 70, 3, 'Darkness Trap'),
(15, NULL, 3, 80, 3, 'Thunder Spike Trap'),
(16, NULL, 3, 30, 4, 'Ensnare Aura'),
(17, NULL, 5, 50, 4, 'Electric Aura'),
(18, NULL, 4, 40, 4, 'Healing Aura'),
(19, NULL, 5, 60, 4, 'Strength Drain Aura'),
(20, NULL, 5, 100, 4, 'Enrage Aura'),
(200, NULL, 0, 0, 20, 'Crystal Core'),
(211, NULL, 0, 0, 21, 'Hint 1'),
(212, NULL, 0, 0, 21, 'Hint 2'),
(213, NULL, 0, 0, 21, 'Hint 3'),
(214, NULL, 0, 0, 21, 'Hint 4'),
(215, NULL, 0, 0, 21, 'Hint 5'),
(222, NULL, 0, 0, 22, 'Black Arrow'),
(223, NULL, 0, 0, 22, 'Black Arrow Head'),
(224, NULL, 0, 0, 22, 'Black Arrow String'),
(225, NULL, 0, 0, 22, 'Green Arrow'),
(226, NULL, 0, 0, 22, 'Green Arrow Head'),
(227, NULL, 0, 0, 22, 'Green Arrow String'),
(228, NULL, 0, 0, 22, 'Blue Arrow'),
(229, NULL, 0, 0, 22, 'Blue Arrow Head'),
(230, NULL, 0, 0, 22, 'Blue Arrow String'),
(231, NULL, 0, 0, 22, 'Yellow Arrow'),
(232, NULL, 0, 0, 22, 'Yellow Arrow Head'),
(233, NULL, 0, 0, 22, 'Yellow Arrow String');

-- 
-- Dumping data for table maps
--
INSERT INTO maps VALUES
(1, 'The Deeper Well', 60, 0, 1),
(2, 'Ancient Mines', 80, 0, 1),
(3, 'Lava Mines', 60, 0, 1),
(4, 'Alchemical Laboratory', 85, 0, 1),
(5, 'Tornado Valley', 85, 0, 1),
(6, 'Tornado Highlands', 90, 0, 1),
(7, 'The Ramparts', 100, 0, 1),
(8, 'The Throne Room', 100, 0, 1),
(9, 'Arcane Library', 110, 0, 1),
(10, 'Royal Gardens', 130, 0, 1),
(11, 'The Promenade', 140, 0, 1),
(12, 'The Summit', 150, 0, 1),
(13, 'Magus Quarters', 90, 0, 2),
(14, 'Endless Spires', 110, 0, 2),
(15, 'Glitterhelm Caverns', 165, 0, 2);
-- 
-- Dumping data for table mapcategories
--
INSERT INTO mapcategories VALUES
(1, 'Campaign', ''),
(2, 'Encore', '');

-- 
-- Dumping data for table difficulties
--
INSERT INTO difficulties VALUES
(1, 'Easy'),
(2, 'Medium'),
(3, 'Hard'),
(4, 'Insane'),
(5, 'Nightmare'),
(6, 'Massacre');

-- 
-- Dumping data for table classes
--
INSERT INTO classes VALUES
(1, 'Squire'),
(2, 'Apprentice'),
(3, 'Huntress'),
(4, 'Monk'),
(20, 'World'),
(21, 'Hints'),
(22, 'Arrows');

-- 
-- Dumping data for table buildstatuses
--
INSERT INTO buildstatuses VALUES
(1, 'Public'),
(2, 'Unlisted'),
(3, 'Private');

-- 
-- Restore previous SQL mode
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Enable foreign keys
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;