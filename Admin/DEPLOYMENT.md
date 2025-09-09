# Configuration de l'Interface Admin EcoRide

Cette documentation détaille la configuration spécifique de l'interface administrateur qui utilise MongoDB.

## Variables d'environnement

L'interface admin utilise les variables d'environnement suivantes :

### MongoDB
- `MONGODB_URI` : URI de connexion complète à MongoDB
  - Format : `mongodb://username:password@host:port/database`
  - Exemple Heroku : `mongodb://heroku_user:password@ds123456.mlab.com:12345/heroku_db`
  - Fallback local : `mongodb://localhost:27017`

### MySQL (pour certaines fonctions admin)
- `MYSQL_HOST` : Hôte MySQL
- `MYSQL_DATABASE` : Nom de la base de données
- `MYSQL_USER` : Utilisateur MySQL
- `MYSQL_PASSWORD` : Mot de passe MySQL

## Configuration Heroku

1. **Ajouter l'add-on MongoDB :**
   ```bash
   heroku addons:create mongolab:sandbox
   ```

2. **La variable `MONGODB_URI` est configurée automatiquement**

3. **Vérifier la configuration :**
   ```bash
   heroku config | grep MONGODB_URI
   ```

## Installation des dépendances

L'interface admin nécessite des dépendances PHP supplémentaires :

```bash
cd Admin
composer install
```

## Initialisation de la base de données MongoDB

Pour initialiser la base de données admin avec un compte par défaut :

```bash
php Admin/init_db.php
```

## Configuration locale (développement)

1. **Installer MongoDB localement**
2. **Démarrer le service MongoDB**
3. **Installer les dépendances Composer :**
   ```bash
   cd Admin && composer install
   ```
4. **Configurer les variables d'environnement (optionnel) :**
   ```bash
   export MONGODB_URI=mongodb://localhost:27017
   ```

## Sécurité

- Modifier les identifiants admin par défaut dans `login.php`
- Utiliser des mots de passe forts
- Configurer HTTPS en production
- Sécuriser l'accès aux variables d'environnement

## Fonctionnalités Admin

- Gestion des utilisateurs (CRUD)
- Gestion des employés (CRUD)
- Statistiques de covoiturage
- Paramètres système
- Tableau de bord analytique

---

**Interface admin sécurisée pour la gestion d'EcoRide**