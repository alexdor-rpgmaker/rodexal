# Alex d'or 2020

Construire collaborativement le nouveau site des [Alex d'or](https://www.alexdor.info).

Ce projet utilise les frameworks [Laravel](https://laravel.com) et [Vue](https://vuejs.org).

# Installation

```bash
# Cloner le dépot
git clone git@github.com:alexdor-rpgmaker/rodexal.git

# Installer les dépendances Composer
composer install

# Installer les dépendances NPM
npm install
```

Il faut avoir une instance mysql active, et se créer deux bases de données : rodexal et rodexal_test. (Voir une documentation mysql si besoin.)

Il faut ensuite créer et remplir le fichier `.env` sur la base du fichier `.env.example`, notamment les informations concernant la base de données (DB_DATABASE, DB_USERNAME, DB_PASSWORD, ...). Faire de même pour le fichier `.env.dusk` (utilisé par les tests de navigateur).

```bash
# Lancer les migrations de base de données
php artisan migrate

# Ajouter des fausses données dans la base de données
php artisan db:seed
```

# Développement

## Lancement du serveur

- Sur Windows, il est conseillé d'utiliser [Wamp](http://www.wampserver.com).
- Sur Mac, il est conseillé d'utiliser [Valet](https://laravel.com/docs/5.7/valet).

## Compilation des assets

```bash
# Lancer dans un autre terminal
npm run dev

# Sinon pour que la compilation se fasse en continu
npm run watch

# Sinon pour compiler et minifier les assets avant mise en production
npm run production
```

## Lancement des tests unitaires frontend

```bash
# Lancer les tests
npm run test

# Lancer les tests en continu
npm run test:watch
```

## Lancement des tests unitaires backend

```bash
# Lancer les tests
composer test

# Lancer les tests juste pour la classe BBCode (par exemple)
composer test -- --filter BBCode

# Lancer les tests en continu
composer test:watch
```

## Lancement des tests de navigateur

Le paramétrage des tests de navigateur se fait dans le fichier `.env.dusk`, notamment le nom de la base de données (DB_DATABASE=rodexal_test) qui est différent, pour éviter la suppression de données en local. Il contient aussi DUSK=true.

```bash
# Lancer les tests
composer test:e2e
```

S'il y a une erreur de type `session not created: Chrome version must be between 70 and 73`, lancer cette commande :

```bash
php artisan dusk:chrome-driver
```

S'il y a une erreur de type `session not created: This version of ChromeDriver only supports Chrome version XX`, il faut mettre à jour votre Chrome à la version XX. :)
