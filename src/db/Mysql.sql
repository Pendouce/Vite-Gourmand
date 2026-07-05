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
  libelle VARCHAR(255)
);

ALTER TABLE user 
ADD CONSTRAINT fk_rl FOREIGN KEY(role_id) REFERENCES role (role_id);

CREATE TABLE commande(
  commande_id INT AUTO_INCREMENT PRIMARY KEY,
  nb_commande INT UNIQUE NOT NULL,
  date_commande DATETIME,
  date_prestation DATETIME,
  nb_personne INT,
  heure_Livraison TIME,
  lieu_livraison VARCHAR(255),
  prix_livraison DOUBLE,
  prix_total DOUBLE,
  user_id INT,
  status_id INT
);

CREATE TABLE status(
  status_id INT AUTO_INCREMENT PRIMARY KEY,
  libelle VARCHAR(255)
);

ALTER table commande
ADD CONSTRAINT fk_us FOREIGN KEY (user_id) REFERENCES user (user_id),
ADD CONSTRAINT fk_st FOREIGN KEY (status_id) REFERENCES status (status_id);

CREATE table avis(
  avis_id INT AUTO_INCREMENT PRIMARY KEY,
  note VARCHAR(20),
  commentaire TEXT,
  date_publication DATE,
  publie BOOL,
  commande_id INT
);

CREATE TABLE prestation(
  prestation_id INT AUTO_INCREMENT PRIMARY KEY,
  type_presta VARCHAR(255),
  nom_presta VARCHAR(255),
  prix_presta DOUBLE,
  description_presta TEXT,
  img_presta VARCHAR(255),
  necessite_retour BOOL,
  prestation_actif BOOL
);
CREATE TABLE commande_prestation(
  prix_total_presta DOUBLE,
  date_presta DATETIME,
  date_retour_prevu DATETIME,
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
  nom_boisson VARCHAR(255),
  photo_boisson VARCHAR(255),
  prix_boisson DOUBLE,
  alcool BOOL,
  stock_boisson INT
);

CREATE TABLE commande_boisson(
  commande_id INT,
  boisson_id INT,
  quantite INT,
  PRIMARY KEY(commande_id, boisson_id)
); 

ALTER TABLE commande_boisson
ADD CONSTRAINT fk_com Foreign Key (commande_id) REFERENCES commande (commande_id),
ADD CONSTRAINT fk_bss Foreign Key (boisson_id) REFERENCES boisson (boisson_id);

CREATE TABLE menu(
  menu_id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(255),
  prix_personne DOUBLE,
  nombre_personne_min INT,
  conditions TEXT,
  stockt_dispo INT,
  menu_actif BOOL
);

CREATE TABLE commande_menu(
  nb_personne_menu INT,
  commande_id INT,
  menu_id INT,
  PRIMARY KEY(commande_id, menu_id)
); 

ALTER TABLE commande_menu
ADD CONSTRAINT fk_cm Foreign Key (commande_id) REFERENCES commande (commande_id),
ADD CONSTRAINT fk_mn Foreign Key (menu_id) REFERENCES menu (menu_id);

CREATE TABLE plat(
  plat_id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(255),
  prix_personne DOUBLE,
  stockt_plat INT,
  plat_actif BOOL
);

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
  libelle VARCHAR(255)
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
  libelle VARCHAR(255)
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
  libelle VARCHAR(255)
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
  libelle VARCHAR(255)
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
  nom VARCHAR(50),
  prenom VARCHAR(50),
  photo VARCHAR(255),
  poste VARCHAR(255),
  description TEXT,
  actif BOOLEAN
);

CREATE TABLE information_vg(
  info_id INT AUTO_INCREMENT PRIMARY KEY,
  jour_ouverture VARCHAR(255),
  heure_ouverture VARCHAR(255),
  heure_fermeture VARCHAR(255),
  adresse VARCHAR(255),
  telephone VARCHAR(20),
  email VARCHAR(255)
);

CREATE TABLE image_site(
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom_img VARCHAR(100),
  chemin VARCHAR(255)
);