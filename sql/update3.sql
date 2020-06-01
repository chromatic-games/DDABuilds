CREATE TABLE build_watch (
	steamID VARCHAR(20) NOT NULL,
	buildID INT(10) UNSIGNED NOT NULL,
	PRIMARY KEY (steamID, buildID),
	KEY buildID(buildID),
	CONSTRAINT build_watch_ibfk_1 FOREIGN KEY (buildID) REFERENCES builds(id)
);

ALTER TABLE build_watch
	ADD FOREIGN KEY (buildID) REFERENCES builds(id);