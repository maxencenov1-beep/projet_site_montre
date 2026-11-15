-- Structure de la base catalogue_montres (TiDB Cloud)

CREATE DATABASE IF NOT EXISTS catalogue_montres;

USE catalogue_montres;

CREATE TABLE marques (
  id_marque INT NOT NULL AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  pays_origine VARCHAR(100) DEFAULT NULL,
  annee_creation SMALLINT DEFAULT NULL,
  PRIMARY KEY (id_marque),
  UNIQUE KEY nom (nom)
);

CREATE TABLE categories (
  id_categorie INT NOT NULL AUTO_INCREMENT,
  nom VARCHAR(50) NOT NULL,
  PRIMARY KEY (id_categorie),
  UNIQUE KEY nom (nom)
);

CREATE TABLE mouvements (
  id_mouvement INT NOT NULL AUTO_INCREMENT,
  type VARCHAR(50) NOT NULL,
  PRIMARY KEY (id_mouvement),
  UNIQUE KEY type (type)
);

CREATE TABLE montres (
  id_montre INT NOT NULL AUTO_INCREMENT,
  reference VARCHAR(50) NOT NULL,
  nom VARCHAR(150) NOT NULL,
  id_marque INT NOT NULL,
  id_categorie INT DEFAULT NULL,
  id_mouvement INT DEFAULT NULL,
  prix DECIMAL(10,2) DEFAULT NULL,
  description TEXT,
  matiere_boitier VARCHAR(100) DEFAULT NULL,
  matiere_bracelet VARCHAR(100) DEFAULT NULL,
  couleur_cadran VARCHAR(50) DEFAULT NULL,
  diametre_mm DECIMAL(5,2) DEFAULT NULL,
  etancheite_m INT DEFAULT NULL,
  image_url VARCHAR(255) DEFAULT NULL,
  date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_montre),
  UNIQUE KEY reference (reference),
  FOREIGN KEY (id_marque) REFERENCES marques (id_marque) ON DELETE CASCADE,
  FOREIGN KEY (id_categorie) REFERENCES categories (id_categorie) ON DELETE SET NULL,
  FOREIGN KEY (id_mouvement) REFERENCES mouvements (id_mouvement) ON DELETE SET NULL
);
