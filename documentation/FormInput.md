# Documentation de la classe FormInput

## **Description**
La classe `FormInput` permet de générer des champs de formulaire HTML de manière dynamique et sécurisée. Elle prend en charge la validation, la sécurisation des entrées contre les attaques XSS et permet la configuration du type d'entrée (texte, email, mot de passe, etc.).

## **Utilisation**
1️⃣**Création d'un champ de formulaire**
```php
$nomInput = new FormInput("nom", "Nom");
```
2️⃣**Définir une valeur et une validation**
```php
$nomInput->setValue("Dupont", true); // La valeur est "Dupont" avec une validation réussie
```
3️⃣**Changer le type du champ**
```php
$mailInput = (new FormInput("email", "Email"))->isMail();
$passwordInput = (new FormInput("password", "Mot de passe"))->isPassword();
```
4️⃣**Ajouter une liste de suggestions (datalist)**
```php
$villeInput = new FormInput("ville", "Ville");
$villeInput->addList("listeVilles");
```
5️⃣**Rendu HTML du champ**
```php
echo $nomInput->render();
```
---
## **Classe `FormInput`**
**Propriétés**
| Nom     | Type     | Description                    |
|---------|----------|--------------------------------|
| `$name` | `string` | Nom du champ (attribut `name`) |
| `$label` | `string` | Texte affiché dans le label |
| `$value` | `string` | Valeur du champ |
| `$type` | `string` | Type de champ (`text`, `email`, `'password`, etc.) |
| `$listName` | `?string` | Nom de la liste associée `datalist`) |
| `$validation` | `string` | Classe CSS pour la validation (`is-valid`, `is-invalid`) |

**Méthodes**
```php
__construct(string $name, string $label)
```
Crée une nouvelle instance de FormInput.
```php
render(): string
```
Retourne le HTML du champ de formulaire.
```php
addList(string $listName): void
```
Associe une liste de suggestions au champ (datalist).
```php
setType(string $type): self
```
Définit le type du champ (text, number, email, etc.).
```php
setValue(string $value, bool $validation): self
```
Définit la valeur du champ et applique une classe CSS de validation (is-valid ou is-invalid).

---
## **Sécurité 🔒**
- **Protection XSS** : Les valeurs affichées sont échappées avec `htmlspecialchars()`.

- **Validation des entrées** : Les champs peuvent être validés et affichés avec une classe CSS (`is-valid` ou `is-invalid`).

---

## **Exemple complet**
```php
$nomInput = new FormInput("nom", "Nom");
$nomInput->setValue("Dupont", true);
$mailInput = (new FormInput("email", "Email"))->isMail();
$passwordInput = (new FormInput("password", "Mot de passe"))->isPassword();

echo $nomInput->render();
echo $mailInput->render();
echo $passwordInput->render();
```