# ![php](./img/logo.png)  Menu Zen

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
 ┃ ┣ 📂 recipe/ - Vues liées à la gestion des recettes
 ┃ ┗ Pages principales de l'application
 ┣ 📄 .gitignore - Fichiers à ignorer par Git
 ┣ 📄 .htaccess - Configuration Apache
 ┣ 📄 error.php - Page d'erreur
 ┣ 📄 index.php - Point d'entrée de l'application
 ┗ 📄 README.md - Documentation globale

## 📖 **Documentation Technique**
Retrouvez la documentation détaillée du projet dans les fichiers suivants :

### 📑 Documents de Planification
- [🌟 Présentation du Projet](./documentation/project/00 - presentation-projet.md) - Vision globale et objectifs de l'application
- [👥 Personas](./documentation/project/01 - personas.md) - Profils détaillés des utilisateurs cibles
- [📋 User Stories](./documentation/project/02 - user-stories.md) - Fonctionnalités et besoins des utilisateurs
- [🎫 Tickets de Développement](./documentation/project/03 - tickets.md) - Détail des tâches de développement

### 📌 Documentation Technique
- [📋 Gestion des Formulaires](./documentation/FormInput.md) - Détails sur la classe FormInput
- [👤 Gestion des Utilisateurs](./documentation/User.md) - Détails sur la gestion des utilisateurs (création, connexion, suppression, édition)

## 👤**Auteur**

Sébastien Drillaud
(Seb-Prod) 2025

## ⚖️**Licence**
"La reproduction, la distribution ou toute autre utilisation de ce projet est interdite sans autorisation expresse."

## 🔨**Statut du projet**
🚧 Ce projet est actuellement en cours de développement.
Certaines fonctionnalités ne sont pas encore implémentées ou peuvent ne pas fonctionner correctement.
