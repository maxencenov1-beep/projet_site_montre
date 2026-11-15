# Heure Juste — site vitrine de montres

Site PHP (PDO) relié à la base `catalogue_montres` hébergée sur TiDB Cloud.

- `index.php` : redirige vers l'accueil
- `pages/accueil.php` : page d'accueil + collection
- `pages/detail.php?id_montre=X` : fiche d'une montre
- `includes/` : en-tête et pied de page communs
- `config/database.php` : connexion à la BDD (SSL)
- `public/css/style.css` : styles
- `public/Requêtes SQL/` : structure de la base et ajout des images

## Installation

1. `composer install`
2. Créer un fichier `.env` à la racine avec les identifiants TiDB Cloud (cluster → Connect) :
   `DB_HOST`, `DB_PORT` (4000), `DB_NAME` (catalogue_montres), `DB_USERNAME`, `DB_PASSWORD`
3. La base `catalogue_montres` existe déjà sur TiDB ; `Ajout des images.sql` renseigne les photos des montres
4. Lancer le serveur PHP depuis la racine du projet :
   `C:\wamp64\bin\php\php8.2.0\php.exe -S localhost:8000`
5. Ouvrir `http://localhost:8000` dans le navigateur (Ctrl+C dans le terminal pour arrêter)
