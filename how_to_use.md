# Guide d’utilisation – Interlocuteur Manager

Bienvenue ! Ce guide vous explique comment installer, configurer et utiliser chaque partie de l’application **Interlocuteur Manager**.

---

## 1. Prérequis

- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Node.js & npm (pour assets front si besoin)
- [XAMPP](https://www.apachefriends.org/fr/index.html) ou équivalent recommandé pour Windows

---

## 2. Installation

### a. Cloner le projet

```bash
git clone <votre-url-git> interlocuteurs-dashboard
cd interlocuteurs-dashboard
```

### b. Installer les dépendances PHP

```bash
composer install
```

### c. Installer les dépendances front (optionnel)

```bash
npm install
npm run build
```

---

## 3. Configuration de l’environnement

### a. Copier le fichier `.env.example`

```bash
cp .env.example .env
```

### b. Générer la clé d’application

```bash
php artisan key:generate
```

### c. Configurer la base de données

Dans `.env`, renseignez vos informations MySQL :

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=interlocuteurs_db
DB_USERNAME=root
DB_PASSWORD=
```

---

## 4. Lancer les migrations et seeders

### a. Migrer la base de données

```bash
php artisan migrate
```

### b. (Optionnel) Remplir avec des données de test

```bash
php artisan db:seed
```

---

## 5. Lancer le serveur de développement

```bash
php artisan serve
```

Accédez à [http://localhost:8000](http://localhost:8000)

---

## 6. Utilisation de l’application

### a. Page d’accueil

- Présente l’application et propose les boutons de connexion pour **Utilisateur** et **Admin**.

### b. Connexion

- **Utilisateur** :  
  Cliquez sur `Connexion Utilisateur` et connectez-vous avec vos identifiants.
- **Admin** :  
  Cliquez sur `Connexion Admin` et connectez-vous avec vos identifiants admin.

### c. Tableau de bord

- Après connexion, cliquez sur le bouton pour accéder à votre dashboard (Utilisateur ou Admin).
- L’admin peut accéder à toutes les fonctionnalités, les utilisateurs à leurs propres données.

### d. Gestion des interlocuteurs

- Ajoutez, modifiez ou supprimez des interlocuteurs via le menu dédié.
- Les interlocuteurs sont liés à chaque utilisateur.

### e. Gestion des échanges

- Ajoutez, modifiez ou supprimez des échanges (Appel, Email, Réunion).
- Chaque échange est lié à un interlocuteur.

### f. Statistiques & Rapports

- Visualisez les statistiques sur le dashboard.
- Exportez les échanges ou utilisateurs via les boutons d’export.

### g. Déconnexion

- Cliquez sur le bouton `Déconnexion` dans le header pour vous déconnecter.

---

## 7. Routes principales

- `/` : Page d’accueil
- `/login` : Connexion utilisateur
- `/admin/login` : Connexion admin
- `/dashboard` : Dashboard utilisateur
- `/admin/dashboard` : Dashboard admin

---

## 8. Commandes utiles

- **Vider le cache** :  
  `php artisan optimize:clear`
- **Créer un nouvel utilisateur admin** :  
  `php artisan tinker` puis :
  ```php
  \App\Models\User::create([
      'name' => 'Admin',
      'email' => 'admin@admin.com',
      'password' => bcrypt('votre_mot_de_passe'),
      'role_id' => 1 // ou l’ID du rôle admin
  ]);
  ```

---

## 9. Problèmes fréquents

- **Erreur de migration** : Vérifiez la connexion à la base de données dans `.env`.
- **Problème d’authentification** : Vérifiez que les rôles et utilisateurs existent bien en base.

---

## 10. Support

Pour toute question, ouvrez une issue sur le dépôt GitHub ou contactez