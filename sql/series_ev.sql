ALTER TABLE towers
	ADD COLUMN maxUnitCost SMALLINT(5) UNSIGNED NOT NULL AFTER unitcost;

INSERT INTO classes (id, name, isHero)
	VALUES ('5', 'Series EV-A', '1');

INSERT INTO towers (id, mu, manacost, fk_class, name, unitcost, maxUnitCost)
	VALUES (21, 0, 40, 5, 'Proton Beam', 2, 5),
	       (22, 0, 30, 5, 'Blocking Field', 2, 5),
	       (23, 0, 20, 5, 'Reflect Beam', 1, 3),
	       (24, 0, 55, 5, 'Shock Beam', 2, 6),
	       (25, 0, 70, 5, 'Overclock Beam', 4, 6);