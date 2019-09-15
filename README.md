# Alex d'or 2019

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

Il faut ensuite remplir le fichier `.env` sur la base du fichier `.env.example`, notamment les informations concernant la base de données.

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
```

## Lancement des tests unitaires

```bash
# Lancer les tests
composer test

# Lancer les tests juste pour la classe BBCode (par exemple)
composer test -- --filter BBCode

# Lancer les tests en continu
composer test:watch
```

## Lancement des tests de navigateur

Il faut créer un fichier .env.dusk en précisant une base de données différente (DB_DATABASE) pour éviter la suppression de données en local. Il faut aussi ajouter DUSK=true.

```bash
# Lancer les tests
composer test:e2e
```

S'il y a une erreur de type `session not created: Chrome version must be between 70 and 73`, lancer cette commande :

```bash
php artisan dusk:chrome-driver
```

S'il y a une erreur de type `session not created: This version of ChromeDriver only supports Chrome version XX`, il faut mettre à jour votre Chrome à la version XX. :)
