ALTER TABLE towers
	ADD COLUMN maxUnitCost SMALLINT(5) UNSIGNED NOT NULL AFTER unitcost;

INSERT INTO classes (id, name, isHero)
	VALUES ('5', 'Series EV', '1');

INSERT INTO towers (id, mu, manacost, fk_class, name, unitcost, maxUnitCost)
	VALUES (21, 0, 0, 5, 'Proton Beam', 2, 5),
	       (22, 0, 0, 5, 'Neutron Field', 2, 5),
	       (23, 0, 0, 5, 'Reflect Beam', 1, 3),
	       (24, 0, 0, 5, 'Shock Beam', 2, 6),
	       (25, 0, 0, 5, 'Buff Beam', 4, 6);