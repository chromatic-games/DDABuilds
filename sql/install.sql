-- ----------------------------
-- Table structure for `bug_report`
-- ----------------------------
DROP TABLE IF EXISTS `bug_report`;
CREATE TABLE `bug_report` (
  `reportID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `steamID` varchar(20) NOT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`reportID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `build_stats`
-- ----------------------------
DROP TABLE IF EXISTS `build_stats`;
CREATE TABLE `build_stats` (
  `buildID` int(10) unsigned NOT NULL,
  `classID` int(10) unsigned NOT NULL,
  `hp` int(10) unsigned DEFAULT NULL,
  `damage` int(10) unsigned DEFAULT NULL,
  `range` int(10) unsigned DEFAULT NULL,
  `rate` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`buildID`,`classID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `builds`
-- ----------------------------
DROP TABLE IF EXISTS `builds`;
CREATE TABLE `builds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` varchar(20) CHARACTER SET utf8 NOT NULL,
  `name` varchar(128) CHARACTER SET utf8 NOT NULL,
  `map` int(10) unsigned NOT NULL,
  `difficulty` int(10) unsigned NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `fk_user` varchar(20) NOT NULL,
  `fk_buildstatus` int(11) NOT NULL,
  `gamemodeID` int(10) unsigned NOT NULL,
  `hardcore` int(11) DEFAULT NULL,
  `afkable` int(11) DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `likes` int(10) unsigned NOT NULL DEFAULT 0,
  `comments` int(10) unsigned NOT NULL DEFAULT 0,
  `timePerRun` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
  `expPerRun` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
  `deleted` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `buildstatuses`
-- ----------------------------
DROP TABLE IF EXISTS `buildstatuses`;
CREATE TABLE `buildstatuses` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of buildstatuses
-- ----------------------------
INSERT INTO `buildstatuses` VALUES ('1', 'Public');
INSERT INTO `buildstatuses` VALUES ('2', 'Unlisted');
INSERT INTO `buildstatuses` VALUES ('3', 'Private');

-- ----------------------------
-- Table structure for `buildwaves`
-- ----------------------------
DROP TABLE IF EXISTS `buildwaves`;
CREATE TABLE `buildwaves` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `fk_build` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `classes`
-- ----------------------------
DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `isHero` tinyint(1) unsigned NOT NULL,
  `isDisabled` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of classes
-- ----------------------------
INSERT INTO `classes` VALUES ('1', 'Squire', '1', '0');
INSERT INTO `classes` VALUES ('2', 'Apprentice', '1', '0');
INSERT INTO `classes` VALUES ('3', 'Huntress', '1', '0');
INSERT INTO `classes` VALUES ('4', 'Monk', '1', '0');
INSERT INTO `classes` VALUES ('20', 'World', '0', '0');
INSERT INTO `classes` VALUES ('21', 'Hints', '0', '0');
INSERT INTO `classes` VALUES ('22', 'Arrows', '0', '0');

-- ----------------------------
-- Table structure for `comments`
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `steamid` varchar(20) NOT NULL,
  `comment` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `fk_build` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `likes` int(10) unsigned NOT NULL DEFAULT 0,
  `dislikes` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `difficulties`
-- ----------------------------
DROP TABLE IF EXISTS `difficulties`;
CREATE TABLE `difficulties` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of difficulties
-- ----------------------------
INSERT INTO `difficulties` VALUES ('1', 'Easy');
INSERT INTO `difficulties` VALUES ('2', 'Medium');
INSERT INTO `difficulties` VALUES ('3', 'Hard');
INSERT INTO `difficulties` VALUES ('4', 'Insane');
INSERT INTO `difficulties` VALUES ('5', 'Nightmare');
INSERT INTO `difficulties` VALUES ('6', 'Massacre');

-- ----------------------------
-- Table structure for `gamemode`
-- ----------------------------
DROP TABLE IF EXISTS `gamemode`;
CREATE TABLE `gamemode` (
  `gamemodeID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`gamemodeID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of gamemode
-- ----------------------------
INSERT INTO `gamemode` VALUES ('1', 'Campaign');
INSERT INTO `gamemode` VALUES ('2', 'Survival');
INSERT INTO `gamemode` VALUES ('3', 'Challenge');
INSERT INTO `gamemode` VALUES ('4', 'Pure Strategy');
INSERT INTO `gamemode` VALUES ('5', 'Mix Mode');

-- ----------------------------
-- Table structure for `like`
-- ----------------------------
DROP TABLE IF EXISTS `like`;
CREATE TABLE `like` (
  `objectType` varchar(16) NOT NULL DEFAULT '',
  `objectID` int(10) unsigned NOT NULL,
  `steamID` varchar(20) NOT NULL,
  `likeValue` tinyint(2) NOT NULL,
  `date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`objectType`,`objectID`,`steamID`),
  UNIQUE KEY `objectType` (`objectType`,`objectID`,`steamID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `mapcategories`
-- ----------------------------
DROP TABLE IF EXISTS `mapcategories`;
CREATE TABLE `mapcategories` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(60) NOT NULL,
  `text` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of mapcategories
-- ----------------------------
INSERT INTO `mapcategories` VALUES ('1', 'Campaign', '');
INSERT INTO `mapcategories` VALUES ('2', 'Encore', '');

-- ----------------------------
-- Table structure for `maps`
-- ----------------------------
DROP TABLE IF EXISTS `maps`;
CREATE TABLE `maps` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `units` int(10) unsigned NOT NULL,
  `sort` int(11) NOT NULL,
  `fk_mapcategory` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of maps
-- ----------------------------
INSERT INTO `maps` VALUES ('1', 'The Deeper Well', '60', '0', '1');
INSERT INTO `maps` VALUES ('2', 'Ancient Mines', '80', '0', '1');
INSERT INTO `maps` VALUES ('3', 'Lava Mines', '60', '0', '1');
INSERT INTO `maps` VALUES ('4', 'Alchemical Laboratory', '85', '0', '1');
INSERT INTO `maps` VALUES ('5', 'Tornado Valley', '85', '0', '1');
INSERT INTO `maps` VALUES ('6', 'Tornado Highlands', '90', '0', '1');
INSERT INTO `maps` VALUES ('7', 'The Ramparts', '100', '0', '1');
INSERT INTO `maps` VALUES ('8', 'The Throne Room', '100', '0', '1');
INSERT INTO `maps` VALUES ('9', 'Arcane Library', '110', '0', '1');
INSERT INTO `maps` VALUES ('10', 'Royal Gardens', '130', '0', '1');
INSERT INTO `maps` VALUES ('11', 'The Promenade', '140', '0', '1');
INSERT INTO `maps` VALUES ('12', 'The Summit', '150', '0', '1');
INSERT INTO `maps` VALUES ('13', 'Magus Quarters', '90', '0', '2');
INSERT INTO `maps` VALUES ('14', 'Endless Spires', '110', '0', '2');
INSERT INTO `maps` VALUES ('15', 'Glitterhelm Caverns', '165', '0', '2');

-- ----------------------------
-- Table structure for `notifications`
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `steamid` varchar(20) NOT NULL,
  `seen` int(11) NOT NULL DEFAULT 0,
  `data` bigint(20) NOT NULL,
  `type` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `fk_build` int(11) NOT NULL,
  `fk_comment` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `placed`
-- ----------------------------
DROP TABLE IF EXISTS `placed`;
CREATE TABLE `placed` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_build` int(10) unsigned NOT NULL,
  `fk_tower` int(10) unsigned NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `rotation` int(11) NOT NULL,
  `fk_buildwave` int(11) NOT NULL DEFAULT 0,
  `override_du` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


-- ----------------------------
-- Table structure for `towers`
-- ----------------------------
DROP TABLE IF EXISTS `towers`;
CREATE TABLE `towers` (
  `id` int(11) unsigned NOT NULL,
  `mu` tinyint(1) DEFAULT NULL,
  `unitcost` int(11) NOT NULL,
  `manacost` int(11) NOT NULL,
  `fk_class` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of towers
-- ----------------------------
INSERT INTO `towers` VALUES ('1', null, '3', '30', '1', 'Spiked Blockade');
INSERT INTO `towers` VALUES ('2', null, '6', '80', '1', 'Harpoon Turret');
INSERT INTO `towers` VALUES ('3', null, '4', '40', '1', 'Bouncer Blockade');
INSERT INTO `towers` VALUES ('4', null, '7', '100', '1', 'Bowling Ball Turret');
INSERT INTO `towers` VALUES ('5', null, '8', '140', '1', 'Slice N Dice Blockade');
INSERT INTO `towers` VALUES ('6', null, '3', '40', '2', 'Magic Missile Tower');
INSERT INTO `towers` VALUES ('7', null, '1', '20', '2', 'Elemental Barrier');
INSERT INTO `towers` VALUES ('8', null, '5', '80', '2', 'Flameburst Tower');
INSERT INTO `towers` VALUES ('9', null, '7', '120', '2', 'Lightning Tower');
INSERT INTO `towers` VALUES ('10', null, '8', '150', '2', 'Deadly Striker Tower');
INSERT INTO `towers` VALUES ('11', null, '3', '40', '3', 'Explosive Trap');
INSERT INTO `towers` VALUES ('12', null, '3', '30', '3', 'Poison Gas Trap');
INSERT INTO `towers` VALUES ('13', null, '4', '60', '3', 'Inferno Trap');
INSERT INTO `towers` VALUES ('14', null, '3', '70', '3', 'Darkness Trap');
INSERT INTO `towers` VALUES ('15', null, '3', '80', '3', 'Thunder Spike Trap');
INSERT INTO `towers` VALUES ('16', null, '3', '30', '4', 'Ensnare Aura');
INSERT INTO `towers` VALUES ('17', null, '5', '50', '4', 'Electric Aura');
INSERT INTO `towers` VALUES ('18', null, '4', '40', '4', 'Healing Aura');
INSERT INTO `towers` VALUES ('19', null, '5', '60', '4', 'Strength Drain Aura');
INSERT INTO `towers` VALUES ('20', null, '5', '100', '4', 'Enrage Aura');
INSERT INTO `towers` VALUES ('200', null, '0', '0', '20', 'Crystal Core');
INSERT INTO `towers` VALUES ('211', null, '0', '0', '21', 'Hint 1');
INSERT INTO `towers` VALUES ('212', null, '0', '0', '21', 'Hint 2');
INSERT INTO `towers` VALUES ('213', null, '0', '0', '21', 'Hint 3');
INSERT INTO `towers` VALUES ('214', null, '0', '0', '21', 'Hint 4');
INSERT INTO `towers` VALUES ('215', null, '0', '0', '21', 'Hint 5');
INSERT INTO `towers` VALUES ('222', null, '0', '0', '22', 'Black Arrow');
INSERT INTO `towers` VALUES ('223', null, '0', '0', '22', 'Black Arrow Head');
INSERT INTO `towers` VALUES ('224', null, '0', '0', '22', 'Black Arrow String');
INSERT INTO `towers` VALUES ('225', null, '0', '0', '22', 'Green Arrow');
INSERT INTO `towers` VALUES ('226', null, '0', '0', '22', 'Green Arrow Head');
INSERT INTO `towers` VALUES ('227', null, '0', '0', '22', 'Green Arrow String');
INSERT INTO `towers` VALUES ('228', null, '0', '0', '22', 'Blue Arrow');
INSERT INTO `towers` VALUES ('229', null, '0', '0', '22', 'Blue Arrow Head');
INSERT INTO `towers` VALUES ('230', null, '0', '0', '22', 'Blue Arrow String');
INSERT INTO `towers` VALUES ('231', null, '0', '0', '22', 'Yellow Arrow');
INSERT INTO `towers` VALUES ('232', null, '0', '0', '22', 'Yellow Arrow Head');
INSERT INTO `towers` VALUES ('233', null, '0', '0', '22', 'Yellow Arrow String');

CREATE TABLE map_available_unit (
	mapID INT(10) UNSIGNED NOT NULL,
	difficultyID INT(10) UNSIGNED NOT NULL,
	units SMALLINT(5) UNSIGNED NOT NULL,
	PRIMARY KEY (mapID, difficultyID, units)
);

-- add lava mines available units in specific difficulties
INSERT INTO map_available_unit (mapID, difficultyID, units)
	VALUES (3, 4, 90), -- insane
	       (3, 5, 90), -- nightmare
	       (3, 6, 90); -- massacre