# Documentation de la classe User

## **Description**
La classe `User` permet de gérer les utilisateurs d'une application PHP avec des fonctionnalités d'inscription, de connexion et de validation. Elle utilise PDO pour interagir avec la base de données et inclut des mécanismes de sécurité pour protéger les données utilisateur et prévenir les attaques par force brute.

## **Utilisation**
1️⃣ **Initialisation de la classe**
```php
$pdo = new PDO('mysql:host=localhost;dbname=ma_base', 'utilisateur', 'mot_de_passe');
$user = new User($pdo);
```

2️⃣ **Inscription d'un nouvel utilisateur**
```php
$result = $user->register(
    'Dupont',         // Nom
    'Jean',           // Prénom
    'jdupont',        // Pseudo
    'jean@email.com', // Email
    'MotDePasse123',  // Mot de passe
    'MotDePasse123',  // Confirmation
    false             // Statut admin (optionnel)
);
```

3️⃣ **Vérification du résultat d'inscription**
```php
if ($result['success']) {
    echo "Inscription réussie !";
} else {
    // Afficher les erreurs
    foreach ($result['values'] as $field => $data) {
        if ($data['value'] === false) {
            echo $data['message'] . "<br>";
        }
    }
}
```

4️⃣ **Connexion d'un utilisateur**
```php
$loginResult = $user->login('jdupont', 'MotDePasse123');
// ou
$loginResult = $user->login('jean@email.com', 'MotDePasse123');

if ($loginResult['success']) {
    echo "Connexion réussie !";
} else {
    echo $loginResult['message'];
}
```

---
## **Classe `User`**
**Propriétés**
| Nom | Type | Description |
|-----|------|-------------|
| `$pdo` | `PDO` | Instance PDO pour la connexion à la base de données |
| `$maxAttempts` | `int` | Nombre maximum de tentatives de connexion (défaut: 3) |
| `$lockoutTime` | `int` | Durée du verrouillage en secondes (défaut: 600) |

**Méthodes publiques**

```php
__construct(PDO $pdo)
```
Crée une nouvelle instance de User avec une connexion PDO.

```php
register(string $lastName, string $firstName, string $username, 
        string $email, string $password, string $confirmation, 
        bool $admin = false): array
```
Inscrit un nouvel utilisateur. Retourne un tableau avec `success` (bool) et `values` (tableau des résultats de validation).

```php
login(string $usernameOrEmail, string $password): array
```
Authentifie un utilisateur avec son pseudo ou email et son mot de passe. Retourne un tableau avec `success` (bool) et `message` (string).

**Méthodes privées**
```php
sanitize(string $data): string
```
Nettoie les données d'entrée contre les attaques XSS.

```php
validateName(string $name, int $minLength = 2, int $maxLength = 50): bool
```
Vérifie si un nom ou prénom est valide.

```php
validateEmail(string $email): bool
```
Vérifie si une adresse email est valide.

```php
validatePassword(string $password): bool
```
Vérifie si un mot de passe respecte les critères de sécurité.

```php
incrementAttempts(int $userId): void
```
Incrémente le compteur de tentatives de connexion échouées.

```php
resetAttempts(int $userId): void
```
Réinitialise le compteur de tentatives de connexion échouées.

```php
userExists(string $value, string $champ): bool
```
Vérifie si un utilisateur existe déjà avec une valeur donnée dans un champ spécifique.

---
## **Règles de validation ✅**

| Champ | Règles |
|-------|--------|
| **Nom/Prénom** | • Non vide<br>• Entre 2 et 50 caractères<br>• Uniquement lettres, espaces, apostrophes, tirets |
| **Email** | • Format email valide<br>• Unique dans la base de données |
| **Mot de passe** | • Minimum 8 caractères<br>• Au moins une majuscule<br>• Au moins un chiffre<br>• Correspondance avec la confirmation |
| **Pseudo** | • Unique dans la base de données<br>• Format valide (lettres, chiffres, tirets bas, tirets) |

---
## **Système anti-brute force 🔒**

La classe implémente un système de protection contre les attaques par force brute:

1. Après un nombre défini de tentatives échouées (`$maxAttempts`, par défaut 3), le compte est temporairement verrouillé
2. La durée du verrouillage est définie par `$lockoutTime` (par défaut 600 secondes ou 10 minutes)
3. Le système enregistre:
   - Le nombre de tentatives échouées (`failed_attempts`)
   - L'horodatage de la dernière tentative échouée (`last_failed_attempt`)
4. Une fois la période de verrouillage écoulée, le compteur est automatiquement réinitialisé

---
## **Sécurité 🔒**
- **Protection XSS** : Les entrées sont échappées avec `htmlspecialchars()` en utilisant l'option ENT_QUOTES.
- **Hachage des mots de passe** : Utilisation de `password_hash()` avec l'algorithme BCRYPT.
- **Requêtes préparées** : Toutes les requêtes SQL utilisent PDO avec des requêtes préparées.
- **Validation des entrées** : Vérifications complètes des données avant insertion.
- **Protection anti-brute force** : Limitation des tentatives de connexion échouées.
- **Gestion des sessions** : Démarrage automatique de session lors de la connexion réussie.

---

## **Structure de la base de données**
La classe nécessite une table `users` avec les colonnes suivantes :
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    pseudo VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    pass VARCHAR(255) NOT NULL,
    admin TINYINT(1) DEFAULT 0,
    failed_attempts INT DEFAULT 0,
    last_failed_attempt DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL
);
```

---

## **Exemples complets**

### Exemple d'inscription

```php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=ma_base', 'user', 'password', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Création de l'instance User
$user = new User($pdo);

// Tentative d'inscription
$result = $user->register(
    'Dupont',
    'Jean',
    'jdupont',
    'jean.dupont@example.com',
    'MotDePasse123',
    'MotDePasse123'
);

// Gestion du résultat
if ($result['success']) {
    echo "Inscription réussie !";
} else {
    echo "Erreurs d'inscription :<br>";
    foreach ($result['values'] as $field => $data) {
        if ($data['value'] === false) {
            echo "- " . $data['message'] . "<br>";
        }
    }
}
```

### Exemple de connexion

```php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=ma_base', 'user', 'password', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Création de l'instance User
$user = new User($pdo);

// Tentative de connexion
$loginResult = $user->login('jean.dupont@example.com', 'MotDePasse123');

// Gestion du résultat
if ($loginResult['success']) {
    echo "Connexion réussie! Bonjour " . $_SESSION['username'];
    
    // Vérification si l'utilisateur est admin
    if ($_SESSION['admin']) {
        echo "<br>Vous êtes connecté en tant qu'administrateur.";
    }
} else {
    echo "Erreur de connexion: " . $loginResult['message'];
}
```

## **Variables de session**

Lors d'une connexion réussie, les variables de session suivantes sont définies:

| Variable | Description |
|----------|-------------|
| `$_SESSION['user_id']` | ID de l'utilisateur dans la base de données |
| `$_SESSION['username']` | Pseudo de l'utilisateur |
| `$_SESSION['admin']` | Statut administrateur (1 pour admin, 0 sinon) |

---

## **Bonnes pratiques et conseils d'utilisation**

1. Assurez-vous que la table `users` contient tous les champs requis, particulièrement `failed_attempts` et `last_failed_attempt` pour le système anti-brute force
2. Utilisez toujours les méthodes de validation fournies pour vérifier les données avant de les manipuler
3. La méthode `login()` accepte à la fois le pseudo et l'email comme identifiant
4. Pour une sécurité maximale, considérez l'ajout d'une protection CSRF sur vos formulaires
5. Envisagez d'implémenter une méthode de déconnexion (logout) pour compléter la gestion de session
