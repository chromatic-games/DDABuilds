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