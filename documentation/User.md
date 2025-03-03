# Documentation de la classe User

## **Description**
La classe `User` permet de gérer les utilisateurs d'une application PHP avec des fonctionnalités d'inscription et de validation. Elle utilise PDO pour interagir avec la base de données et inclut des mécanismes de sécurité pour protéger les données utilisateur.

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
    'MotDePasse123'   // Confirmation
);
```

3️⃣ **Vérification du résultat**
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

---
## **Classe `User`**
**Propriétés**
| Nom | Type | Description |
|-----|------|-------------|
| `$pdo` | `PDO` | Instance PDO pour la connexion à la base de données |
| `$maxAttemps` | `int` | Nombre maximum de tentatives de connexion (défaut: 3) |
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
userExists(string $value, string $champ): bool
```
Vérifie si un utilisateur existe déjà avec une valeur donnée.

---
## **Règles de validation ✅**

| Champ | Règles |
|-------|--------|
| **Nom/Prénom** | • Non vide<br>• Entre 2 et 50 caractères<br>• Uniquement lettres, espaces, apostrophes, tirets |
| **Email** | • Format email valide<br>• Unique dans la base de données |
| **Mot de passe** | • Minimum 8 caractères<br>• Au moins une majuscule<br>• Au moins un chiffre<br>• Correspondance avec la confirmation |
| **Pseudo** | • Unique dans la base de données |

---
## **Sécurité 🔒**
- **Protection XSS** : Les entrées sont échappées avec `htmlspecialchars()`.
- **Hachage des mots de passe** : Utilisation de `password_hash()` avec l'algorithme BCRYPT.
- **Requêtes préparées** : Toutes les requêtes SQL utilisent PDO avec des requêtes préparées.
- **Validation des entrées** : Vérifications complètes des données avant insertion.

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
    created_at DATETIME NOT NULL
);
```

---

## **Exemple complet**
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
