-- Active: 1783172722720@@127.0.0.1@3306@vite_et_gourmand
USE vite_et_gourmand;

CREATE TABLE user(
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL,
  prenom VARCHAR(50) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  mot_de_passe VARCHAR(255) NOT NULL,
  telephone VARCHAR(20),
  ville VARCHAR(255),
  code_postal VARCHAR(255),
  adresse VARCHAR(255),
  role_id INT NOT NULL
);

CREATE TABLE role(
  role_id INT AUTO_INCREMENT PRIMARY KEY,
  libelle VARCHAR(255) NOT NULL
);

ALTER TABLE user 
ADD CONSTRAINT fk_rl FOREIGN KEY(role_id) REFERENCES role (role_id);

CREATE TABLE commande(
  commande_id INT AUTO_INCREMENT PRIMARY KEY,
  nb_commande INT UNIQUE NOT NULL,
  date_commande DATETIME NOT NULL,
  date_prestation DATETIME NOT NULL,
  nb_personne INT NOT NULL,
  heure_Livraison TIME,
  lieu_livraison VARCHAR(255),
  prix_livraison DOUBLE NOT NULL,
  prix_total DOUBLE NOT NULL,
  user_id INT NOT NULL,
  status_id INT NOT NULL
);

CREATE TABLE status(
  status_id INT AUTO_INCREMENT PRIMARY KEY,
  libelle VARCHAR(255) NOT NULL
);

ALTER table commande
ADD CONSTRAINT fk_us FOREIGN KEY (user_id) REFERENCES user (user_id),
ADD CONSTRAINT fk_st FOREIGN KEY (status_id) REFERENCES status (status_id);

CREATE table avis(
  avis_id INT AUTO_INCREMENT PRIMARY KEY,
  note VARCHAR(20) NOT NULL,
  commentaire TEXT NOT NULL,
  date_publication DATE NOT NULL,
  publie BOOL,
  commande_id INT NOT NULL
);

CREATE TABLE prestation(
  prestation_id INT AUTO_INCREMENT PRIMARY KEY,
  #type_presta VARCHAR(255) NOT NULL,
  nom_presta VARCHAR(255) NOT NULL,
  prix_presta DOUBLE NOT NULL,
  description_presta TEXT,
  img_presta VARCHAR(255),
  necessite_retour BOOL,
  prestation_actif BOOL,
  type_presta_id INT
);

ALTER TABLE prestation CHANGE type_presta type_presta_id INT;

ALTER TABLE prestation ADD COLUMN contenu_presta JSON;
ALTER TABLE prestation
ADD CONSTRAINT fk_tp FOREIGN KEY (type_presta_id) REFERENCES type_presta (type_presta_id);

CREATE TABLE type_presta(
  type_presta_id INT AUTO_INCREMENT PRIMARY KEY,
  libelle VARCHAR(255) NOT NULL
);

CREATE TABLE commande_prestation(
  prix_total_presta DOUBLE NOT NULL,
  date_presta DATETIME NOT NULL,
  date_retour_prevu DATETIME NOT NULL,
  date_retour DATETIME,
  taux_retard DOUBLE,
  montant_penalité DOUBLE,
  commande_id INT,
  prestation_id INT,
  PRIMARY KEY(commande_id, prestation_id)
); 
ALTER TABLE commande_prestation
ADD CONSTRAINT fk_cmd Foreign Key (commande_id) REFERENCES commande (commande_id),
ADD CONSTRAINT fk_prt Foreign Key (prestation_id) REFERENCES prestation (prestation_id);

ALTER TABLE avis
ADD CONSTRAINT fk_avs Foreign Key (commande_id) REFERENCES commande (commande_id);

CREATE TABLE boisson(
  boisson_id INT AUTO_INCREMENT PRIMARY KEY,
  nom_boisson VARCHAR(255) NOT NULL,
  photo_boisson VARCHAR(255) NOT NULL,
  prix_boisson DOUBLE NOT NULL,
  alcool BOOL,
  stock_boisson INT
);

CREATE TABLE commande_boisson(
  commande_id INT,
  boisson_id INT,
  quantite INT NOT NULL,
  PRIMARY KEY(commande_id, boisson_id)
); 

ALTER TABLE commande_boisson
ADD CONSTRAINT fk_com Foreign Key (commande_id) REFERENCES commande (commande_id),
ADD CONSTRAINT fk_bss Foreign Key (boisson_id) REFERENCES boisson (boisson_id);

CREATE TABLE menu(
  menu_id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(255) NOT NULL,
  prix_personne DOUBLE NOT NULL,
  nombre_personne_min INT NOT NULL,
  conditions TEXT,
  stock_dispo INT NOT NULL,
  menu_actif BOOL
);

ALTER TABLE menu CHANGE stockt_dispo stock_dispo INT;

CREATE TABLE commande_menu(
  nb_personne_menu INT NOT NULL,
  commande_id INT,
  menu_id INT,
  PRIMARY KEY(commande_id, menu_id)
); 

ALTER TABLE commande_menu
ADD CONSTRAINT fk_cm Foreign Key (commande_id) REFERENCES commande (commande_id),
ADD CONSTRAINT fk_mn Foreign Key (menu_id) REFERENCES menu (menu_id);

CREATE TABLE plat(
  plat_id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(255) NOT NULL,
  image_plat VARCHAR(255),
  description_plat TEXT,
  prix_personne DOUBLE NOT NULL,
  stock_plat INT NOT NULL,
  plat_actif BOOL,
  type_id INT
);

ALTER table plat ADD COLUMN type_id INT;
ALTER table plat ADD COLUMN image_plat VARCHAR(255);
ALTER table plat ADD COLUMN description_plat VARCHAR(255);
ALTER table plat DELETE COLUMN description_plat VARCHAR(255);

CREATE TABLE type_de_plat(
  type_id INT AUTO_INCREMENT PRIMARY KEY,
  libelle VARCHAR(255) NOT NULL
);

ALTER TABLE plat
ADD CONSTRAINT fk_typ FOREIGN KEY (type_id) REFERENCES type_de_plat (type_id);


CREATE TABLE menu_plat(
  menu_id INT,
  plat_id INT,
  PRIMARY KEY(menu_id, plat_id)
); 

ALTER TABLE menu_plat
ADD CONSTRAINT fk_mnu Foreign Key (menu_id) REFERENCES menu (menu_id),
ADD CONSTRAINT fk_plt Foreign Key (plat_id) REFERENCES plat (plat_id);

CREATE TABLE regime(
  regime_id INT AUTO_INCREMENT PRIMARY KEY,
  libelle VARCHAR(255) NOT NULL
);

CREATE TABLE menu_regime(
  menu_id INT,
  regime_id INT,
  PRIMARY KEY(menu_id, regime_id)
); 

ALTER TABLE menu_regime
ADD CONSTRAINT fk_m Foreign Key (menu_id) REFERENCES menu (menu_id),
ADD CONSTRAINT fk_rgm Foreign Key (regime_id) REFERENCES regime (regime_id);

CREATE TABLE theme(
  theme_id INT AUTO_INCREMENT PRIMARY KEY,
  libelle VARCHAR(255) NOT NULL
);

CREATE TABLE menu_theme(
  menu_id INT,
  theme_id INT,
  PRIMARY KEY(menu_id, theme_id)
); 

ALTER TABLE menu_theme
ADD CONSTRAINT fk_mu Foreign Key (menu_id) REFERENCES menu (menu_id),
ADD CONSTRAINT fk_th Foreign Key (theme_id) REFERENCES theme (theme_id);

CREATE TABLE evenement(
  evenement_id INT AUTO_INCREMENT PRIMARY KEY,
  libelle VARCHAR(255) NOT NULL
);

CREATE TABLE menu_evenement(
  menu_id INT,
  evenement_id INT,
  PRIMARY KEY(menu_id, evenement_id)
); 

ALTER TABLE menu_evenement
ADD CONSTRAINT fk_me Foreign Key (menu_id) REFERENCES menu (menu_id),
ADD CONSTRAINT fk_evn Foreign Key (evenement_id) REFERENCES evenement (evenement_id);

CREATE TABLE allergene(
  allergene_id INT AUTO_INCREMENT PRIMARY KEY,
  libelle VARCHAR(255) NOT NULL
);

CREATE TABLE plat_allergene(
  allergene_id INT,
  plat_id INT,
  PRIMARY KEY(allergene_id, plat_id)
); 

ALTER TABLE plat_allergene
ADD CONSTRAINT fk_pla Foreign Key (plat_id) REFERENCES plat (plat_id),
ADD CONSTRAINT fk_al Foreign Key (allergene_id) REFERENCES allergene (allergene_id);

CREATE Table equipe(
  membre_id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL,
  prenom VARCHAR(50) NOT NULL,
  photo VARCHAR(255) NOT NULL,
  poste VARCHAR(255) NOT NULL,
  description TEXT,
  actif BOOLEAN
);

CREATE TABLE information_vg(
  info_id INT AUTO_INCREMENT PRIMARY KEY,
  jour_ouverture VARCHAR(255) NOT NULL,
  heure_ouverture VARCHAR(255) NOT NULL,
  heure_fermeture VARCHAR(255) NOT NULL,
  adresse VARCHAR(255) NOT NULL,
  telephone VARCHAR(20) NOT NULL,
  email VARCHAR(255) NOT NULL
);

CREATE TABLE image_site(
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom_img VARCHAR(100) NOT NULL,
  chemin VARCHAR(255) NOT NULL
);

INSERT INTO role(libelle) VALUES('Utilisateur'), ('Employé'), ('Admin');
INSERT INTO regime(libelle) VALUES('Végétarien'), ('Vegan'), ('Sans gluten'), ('Halal'), ('Casher');
INSERT INTO evenement(libelle) VALUES('Mariage'), ('Anniversaire'), ('Baptême'), ('Cocktail'), ('Brunch'), ('Séminaire d\'entreprise'), 
  ('Noel'), ('Paques'), ('Aid'),
  ('Nouvel an chinois'), ('Pessah'), ('Roch Hachana'), ('Hanoucca') ;
INSERT INTO theme(libelle) VALUES('Terroir'), ('Europe'), ('Afrique'), ('Asie'), ('Amerique');
INSERT INTO allergene (libelle) VALUES 
('Gluten'), ('Crustacés'), ('Œufs'), ('Poisson'),
('Arachides'), ('Soja'), ('Lait'), ('Fruits à coque'),
('Céleri'), ('Moutarde'), ('Graines de sésame'), ('Sulfites'),
('Lupin'), ('Mollusques');
INSERT INTO status (libelle) VALUES 
('Transmise'), ('Acceptée'), ('En preparation'), ('En cours de livraison'),
('Livrée'), ('En attente du retour de matériel'), ('Terminée');

INSERT INTO type_de_plat(libelle) VALUES('Entrée'), ('Plat'), ('Dessert');
INSERT INTO type_presta(libelle) VALUES('Location de materiel'), ('Service professionnel');

--INSERT INTO user(nom, prenom, email, mot_de_passe, role_id) VALUES 
--('Garcia', 'José', 'jose@vg.fr', '$2y$12$hQxzhce4Qe7DelRyrhyOtO40hVA35QMA5VuqWbtvzM4L4DpsVSLdy', 3);

--DELETE FROM menu WHERE menu_id IN (1,2,3,4,5)