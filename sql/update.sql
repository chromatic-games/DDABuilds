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

-- update hero state @formatter:off
UPDATE classes SET isHero = 1 WHERE id IN (1, 2, 3, 4);
UPDATE builds SET votes = (SELECT IFNULL(SUM(vote), 0) FROM votes WHERE fk_build = builds.id);