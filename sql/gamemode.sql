CREATE TABLE gamemode (
	gamemodeID INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	name VARCHAR(64) NOT NULL,
	PRIMARY KEY (gamemodeID)
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