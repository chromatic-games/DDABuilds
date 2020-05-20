ALTER TABLE classes
	ADD COLUMN isDisabled TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER name;

ALTER TABLE builds
	ADD COLUMN votes INT(10) UNSIGNED NOT NULL DEFAULT 0 AFTER views;

ALTER TABLE classes
	ADD COLUMN isHero TINYINT(1) UNSIGNED NOT NULL AFTER name;

CREATE TABLE build_stats (
	buildID INT(10) UNSIGNED NOT NULL,
	classID INT(10) UNSIGNED NOT NULL,
	hp INT(10) UNSIGNED NULL,
	damage INT(10) UNSIGNED NULL,
	rate INT(10) UNSIGNED NULL,
	`range` INT(10) UNSIGNED NULL,
	PRIMARY KEY (buildID, classID)
);

-- change steam ids to strings
ALTER TABLE builds
	MODIFY COLUMN fk_user VARCHAR(20) NOT NULL AFTER date;
ALTER TABLE comments
	MODIFY COLUMN steamid VARCHAR(20) NOT NULL AFTER id;
ALTER TABLE notifications
	MODIFY COLUMN steamid VARCHAR(20) NOT NULL AFTER id;



ALTER TABLE builds
	CHANGE COLUMN timeperrun timePerRun VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' AFTER votes,
	CHANGE COLUMN expperrun expPerRun VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' AFTER timePerRun;

ALTER TABLE builds
	ADD COLUMN comments INT(10) UNSIGNED DEFAULT 0 NOT NULL AFTER votes;

ALTER TABLE comments
	ADD COLUMN likes INT(10) UNSIGNED NOT NULL DEFAULT 0 AFTER date,
	ADD COLUMN dislikes INT(10) UNSIGNED NOT NULL DEFAULT 0 AFTER likes;

ALTER TABLE builds
	CHANGE COLUMN votes likes INT(10) UNSIGNED NOT NULL DEFAULT 0 AFTER views;

CREATE TABLE `like` (
	objectType VARCHAR(32) NULL,
	objectID INT(10) UNSIGNED NULL,
	steamID VARCHAR(20) NULL,
	likeValue TINYINT(2) NOT NULL,
	date TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
	UNIQUE INDEX (objectType, objectID, steamID) USING BTREE
);

ALTER TABLE `like`
	MODIFY COLUMN objectType VARCHAR(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '' FIRST,
	ADD PRIMARY KEY (objectType, objectID, steamID);

-- update hero state @formatter:off
UPDATE classes SET isHero = 1 WHERE id IN (1, 2, 3, 4);
UPDATE builds SET votes = (SELECT COUNT(vote) FROM votes WHERE fk_build = builds.id);
UPDATE builds SET comments = (SELECT COUNT(id) FROM comments WHERE fk_build = builds.id);

update comments SET likes = (SELECT IFNULL(SUM(vote),0) FROM commentvotes WHERE fk_comment = comments.id and vote = 1);
update comments SET dislikes = (SELECT IFNULL(SUM(vote) * -1, 0) FROM commentvotes WHERE fk_comment = comments.id and vote = -1);

-- migrate old vote tables to new like/vote table
INSERT INTO `like` SELECT 'build' as objectType, fk_build as objectID, steamid as steamID, vote as likeValue, date FROM votes;
INSERT INTO `like` SELECT 'comment' as objectType, fk_comment as objectID, steamid as steamID, vote as likeValue, date FROM commentvotes;

INSERT INTO build_stats SELECT id as buildID, 1 as classID, squirehp as hp, squiredamage as damage, squirerange as `range`, squirerate as rate FROM builds WHERE squirerate > 0 or squiredamage > 0 or squirehp > 0 or squirerange > 0;
-- apprentice
INSERT INTO build_stats SELECT id as buildID, 2 as classID, apprenticehp as hp, apprenticedamage as damage, apprenticerange as `range`, apprenticerate as rate FROM builds WHERE apprenticerate > 0 or apprenticedamage > 0 or apprenticehp > 0 or apprenticerange > 0;
-- huntress
INSERT INTO build_stats SELECT id as buildID, 3 as classID, huntresshp as hp, huntressdamage as damage, huntressrange as `range`, huntressrate as rate FROM builds WHERE huntressrate > 0 or huntressdamage > 0 or huntresshp > 0 or huntressrange > 0;
-- monk
INSERT INTO build_stats SELECT id as buildID, 4 as classID, monkhp as hp, monkdamage as damage, monkrange as `range`, monkrate as rate FROM builds WHERE monkrate > 0 or monkdamage > 0 or monkhp > 0 or monkrange > 0;

-- IMPORTANT run this at the end after migration and testing (to prevent data lose)
DROP TABLE commentvotes;
DROP TABLE votes;

ALTER TABLE `builds`
	DROP COLUMN `squirehp`,
	DROP COLUMN `squirerate`,
	DROP COLUMN `squiredamage`,
	DROP COLUMN `squirerange`,
	DROP COLUMN `apprenticehp`,
	DROP COLUMN `apprenticerate`,
	DROP COLUMN `apprenticedamage`,
	DROP COLUMN `apprenticerange`,
	DROP COLUMN `huntresshp`,
	DROP COLUMN `huntressrate`,
	DROP COLUMN `huntressdamage`,
	DROP COLUMN `huntressrange`,
	DROP COLUMN `monkhp`,
	DROP COLUMN `monkrate`,
	DROP COLUMN `monkdamage`,
	DROP COLUMN `monkrange`,
	DROP COLUMN `evhp`,
	DROP COLUMN `evrate`,
	DROP COLUMN `evdamage`,
	DROP COLUMN `evrange`,
	DROP COLUMN `summonerhp`,
	DROP COLUMN `summonerrate`,
	DROP COLUMN `summonerdamage`,
	DROP COLUMN `summonerrange`,
	DROP COLUMN `jesterhp`,
	DROP COLUMN `jesterrate`,
	DROP COLUMN `jesterdamage`,
	DROP COLUMN `jesterrange`;