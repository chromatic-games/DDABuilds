CREATE TABLE gamemode (
	gamemodeID INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	name VARCHAR(64) NOT NULL,
	PRIMARY KEY (gamemodeID)
);

CREATE TABLE bug_report (
	reportID INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	steamID VARCHAR(20) NOT NULL,
	time INT(10) UNSIGNED DEFAULT 0 NOT NULL,
	title VARCHAR(64) NOT NULL,
	description TEXT NOT NULL,
	status TINYINT(1) UNSIGNED DEFAULT 0 NOT NULL,
	PRIMARY KEY (reportID)
);

ALTER TABLE builds
	ADD COLUMN gamemodeID INT(10) UNSIGNED NOT NULL AFTER fk_buildstatus;

-- @formatter:off

UPDATE builds SET gamemodeID = 1 where campaign = 1;
UPDATE builds SET gamemodeID = 2 where survival = 1;
UPDATE builds SET gamemodeID = 3 where challenge = 1;
UPDATE builds SET gamemodeID = 4 where purestrategy = 1;
UPDATE builds SET gamemodeID = 5 where mixmode = 1;

INSERT INTO gamemode (gamemodeID, name) VALUES (1, 'Campaign');
INSERT INTO gamemode (gamemodeID, name) VALUES (2, 'Survival');
INSERT INTO gamemode (gamemodeID, name) VALUES (3, 'Challenge');
INSERT INTO gamemode (gamemodeID, name) VALUES (4, 'Pure Strategy');
INSERT INTO gamemode (gamemodeID, name) VALUES (5, 'Mix Mode');

-- drop old game mode columns
ALTER TABLE `builds`
	DROP COLUMN `campaign`,
	DROP COLUMN `survival`,
	DROP COLUMN `purestrategy`,
	DROP COLUMN `challenge`,
	DROP COLUMN `mixmode`;