# ![php](./img/logo.png)  On Mange Quoi ?

## 📝 **Description**
Cette application web permet aux utilisateurs de planifier leurs repas, ajouter des recettes et générer une liste de courses en fonction d'une période sélectionnée. Développée en PHP, MySQL, Bootstrap, CSS et JavaScript, elle offre une interface intuitive et responsive.

## 🚀 **Fonctionnalités**

- 🔐Inscription et Connexion : Création de compte et authentification des utilisateurs.

- 📝Ajout de Recettes : Formulaire pour ajouter, modifier et supprimer des recettes avec leurs ingrédients.

- 📅Planification des Repas : Sélection des repas pour une période donnée.

- 🛒Génération de Liste de Courses : Calcul automatique des ingrédients nécessaires en fonction des repas sélectionnés.

- 📱Interface Responsive : Utilisation de Bootstrap pour une compatibilité mobile et desktop.

## 🛠️**Technologies Utilisées**

![php](./img/logo_php.png) 
![MySQL](./img/logo_mysql.png)  ![php](./img/logo_web.png) ![php](./img/logo_bootstrap.png) 

## 🏗️**Installation**
1️⃣**Cloner le projet**
```batch
git clone https://github.com/Seb-Prod/onMangeQuoi.git
cd planificateur-repas
```
2️⃣**Configurer la base de données**
- Importer le fichier `database.sql` dans MySQL.
- Modifier le fichier `config.php` (dans le répertoire /config) pour renseigner les informations de connexion à la base de données.
    ```php
    <?php
    define('ENVIRONMENT', 'development'); // Changez en 'production' quand vous déployez
    if (ENVIRONMENT === 'development') {
    // Configuration pour le développement local
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'omq');

    // Affichage des erreurs
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    } else {
    // Configuration pour la production
    define('DB_HOST', 'adresse');
    define('DB_USER', 'user'); 
    define('DB_PASS', 'pass');
    define('DB_NAME', 'db_name');

    // Masquer les erreurs
    error_reporting(0);
    ini_set('display_errors', 0);
    }    
    ```

3️⃣**Lancer le server local**
- Utiliser un serveur local comme XAMPP ou WAMP.
- Placer le projet dans le dossier `htdocs`(pour XAMPP).
- Démarrer Apache et MySQL

4️⃣**Accéder à l'application**
- Ouvrir un navigateur et aller à :
    ```
    http://localhost/onmangequoi
    ```
## 📂**Structure du Projet**

📦onMangeQuoi/
 ┣ 📂 class/ - Classes utilitaires pour l'application
 ┃ ┗ Inclut la classe FormInput pour la génération de formulaires
 ┣ 📂 config/ - Fichiers de configuration de l'application
 ┃ ┗ Paramètres de connexion à la base de données et configuration globale
 ┣ 📂 controllers/ - Logique de contrôle de l'application
 ┃ ┣ 📂 recipe/ - Contrôleurs pour la gestion des recettes
 ┃ ┗ 📂 user/ - Contrôleurs pour la gestion des utilisateurs
 ┣ 📂 css/ - Fichiers de style de l'application
 ┃ ┗ Styles par composant et styles globaux
 ┣ 📂 documentation/ - Documentation technique du projet
 ┃ ┗ Documentation détaillée des classes et fonctionnalités
 ┣ 📂 fonts/ - Polices utilisées dans l'application
 ┣ 📂 img/ - Images et logos du projet
 ┣ 📂 includes/ - Fichiers inclus dans plusieurs pages
 ┃ ┗ En-têtes, pieds de page, connexion à la base de données
 ┣ 📂 js/ - Scripts JavaScript pour les fonctionnalités côté client
 ┣ 📂 models/ - Modèles de données pour interagir avec la base de données
 ┃ ┗ Inclut le modèle User pour la gestion des utilisateurs
 ┣ 📂 views/ - Templates et fichiers d'affichage
 ┃ ┣ 📂 user/ - Vues liées à la gestion des utilisateurs
 ┃ ┗ Pages principales de l'application
 ┣ 📄 .gitignore - Fichiers à ignorer par Git
 ┣ 📄 .htaccess - Configuration Apache
 ┣ 📄 error.php - Page d'erreur
 ┣ 📄 index.php - Point d'entrée de l'application
 ┗ 📄 README.md - Documentation globale

## 📖 **Documentation Technique**
Retouve la documentation détaillée du projet dans les fichiers suivants :
- 📌 [class/fromInput.php](./documentation/FormInput.md) - Générer des champs de formulaire
- 📌 [model/user.php](./documentation/User.md) - Gestion des users (création, connection, suppression et édition).

## 👤**Auteur**

Sébastien Drillaud
(Seb-Prod) 2025

## ⚖️**Licence**
"La reproduction, la distribution ou toute autre utilisation de ce projet est interdite sans autorisation expresse."

## 🔨**Statut du projet**
🚧 Ce projet est actuellement en cours de développement.
Certaines fonctionnalités ne sont pas encore implémentées ou peuvent ne pas fonctionner correctement.

📌 Prochaines étapes :
- Gestion de compte 
    - [x] Formulaire de création de compte
    - [x] Soumission du formulaire de création de compte
    - [x] Docmentation de la classe user
    - [x] Formulaire de connection
    - [x] Soumission du formulaire de connection
    - [ ] Maj documentation de la classe
    - [x] Formulaire de déconnection
    - [x] Déconnection
    - [ ] Formulaire modification du mot de passe
    - [ ] Soumission du formulaire de modification
    - [ ] Mise à jour de la documentation
- Gestion des recetes
    - [ ] Formulaire d'ajout de recette
    - [ ] Soumission du formulaire d'ajout
    - [ ] Formulaire modification d'une recette
    - [ ] Soumission du formulaire de modification
- Affichage des recettes
    - [ ] Affichage des 5 dernières recettes
    - [ ] affichage de 5 recettes aléatoire
    - [ ] formulaire pour chercher une recette avec son nom
    - [ ] formulaire pour chercher une recette avec des ingrédients
- Page a propos
    - [x] Page a propos
- Page d'acceuil
    - [x] Page d'acceuil
- autre
    - [ ] Planification des repas
    - [ ] Création d'une liste de courses
    - [x] Création de la base de données
    - [x] Connection à la base de donnée
    - [x] Page user
    - [ ] Voir pour refaire le logo
    - [ ] Documentation de la classe user.php
    - [x] Footer
    - [ ] Formulaire pour écrir un message
    - [ ] Afficher des messages utilisateur sur l'acceuil
    - [ ] Afficher des new du dévelopeur
    - [ ] Formulaire du dévelopeur
    - [ ] Et bien d'autre....
