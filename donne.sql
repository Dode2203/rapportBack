
INSERT INTO roles (id, name,created_at) VALUES (1, 'Admin', NOW());
INSERT INTO roles (id, name,created_at) VALUES (2, 'Utilisateur',NOW());
INSERT INTO roles (id, name,created_at) VALUES (3, 'Supervisor',NOW());

INSERT INTO type_calendriers (id, name,created_at) VALUES (1, 'Hebdomadaire', NOW());
INSERT INTO type_calendriers (id, name,created_at) VALUES (2, 'Semestriel',NOW());

INSERT INTO type_effect_impacts (id, name,created_at) VALUES (1, 'Impact', NOW());
INSERT INTO type_effect_impacts (id, name,created_at) VALUES (2, 'Effet',NOW());