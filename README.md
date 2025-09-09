# EcoRide - Application de Covoiturage

Application web de covoiturage écologique permettant aux utilisateurs de créer et rejoindre des trajets partagés.

## Architecture

L'application est structurée en deux parties principales :
- **Interface Utilisateur** : Gestion des comptes, covoiturages, historique (MySQL)
- **Interface Administrateur** : Gestion des utilisateurs, statistiques (MongoDB)

## Déploiement

### Déploiement sur Heroku (Recommandé)

Cette application est optimisée pour Heroku et utilise les variables d'environnement pour la configuration des bases de données.

#### Variables d'environnement requises :

**Pour MySQL (Interface Utilisateur) :**
- `MYSQL_HOST` : Hôte de la base de données MySQL
- `MYSQL_DATABASE` : Nom de la base de données MySQL  
- `MYSQL_USER` : Utilisateur MySQL
- `MYSQL_PASSWORD` : Mot de passe MySQL

**Pour MongoDB (Interface Admin) :**
- `MONGODB_URI` : URI de connexion MongoDB complet (ex: `mongodb://user:pass@host:port/database`)

#### Configuration Heroku :

1. **Ajouter les add-ons de base de données :**
   ```bash
   heroku addons:create jawsdb:kitefin  # MySQL
   heroku addons:create mongolab:sandbox  # MongoDB
   ```

2. **Les variables d'environnement sont configurées automatiquement par les add-ons**

3. **Vérifier la configuration :**
   ```bash
   heroku config
   ```

### Déploiement local (Développement)

#### Prérequis :
- **Serveur web** avec PHP (Apache/Nginx)
- **MySQL** installé et configuré
- **MongoDB** installé et en service  
- **Composer** installé

#### Installation :

1. **Cloner le projet :**
   ```bash
   git clone https://github.com/Chlomo55/EcoRide.git
   cd EcoRide
   ```

2. **Installer les dépendances PHP :**
   ```bash
   cd Admin
   composer install
   cd ..
   ```

3. **Configuration des bases de données :**
   - Créer une base MySQL nommée `ecoride`
   - Importer le schema SQL si disponible
   - S'assurer que MongoDB fonctionne sur le port par défaut (27017)

4. **Configuration des variables d'environnement (optionnel) :**
   Créer un fichier `.env` ou configurer les variables système :
   ```bash
   export MYSQL_HOST=localhost
   export MYSQL_DATABASE=ecoride
   export MYSQL_USER=root
   export MYSQL_PASSWORD=
   export MONGODB_URI=mongodb://localhost:27017
   ```

#### Configuration admin :

Les identifiants administrateur sont définis dans `Admin/login.php`. 
Modifier les variables `$admin_email` et `$admin_password` selon vos besoins.

## Accès à l'application

- **Interface Utilisateur** : `/index.php`
- **Interface Admin** : `/Admin/login.php`

## Fonctionnalités principales

### Interface Utilisateur :
- Inscription et connexion sécurisées
- Gestion du profil utilisateur
- Création et recherche de covoiturages
- Système de réservation et d'historique
- Gestion des avis et notations

### Interface Admin :
- Connexion sécurisée administrateur
- Gestion des utilisateurs (MongoDB)
- Gestion des employés
- Statistiques et tableaux de bord
- Outils d'administration

## Structure des fichiers

```
EcoRide/
├── Admin/              # Interface administrateur (MongoDB)
├── employé/            # Interface employé
├── vendor/             # Dépendances Composer
├── *.php               # Pages principales utilisateur
├── style.css           # Styles principaux
├── script.js           # Scripts JavaScript
├── Procfile            # Configuration Heroku
└── README.md           # Ce fichier
```

## Sécurité

- Mots de passe hashés avec `password_hash()`
- Protection contre les injections SQL avec requêtes préparées
- Sessions sécurisées pour l'authentification
- Variables d'environnement pour les données sensibles

## Support

Pour toute question ou problème, consulter la documentation ou contacter l'équipe de développement.

---

**Application développée avec ❤️ pour un transport plus écologique**