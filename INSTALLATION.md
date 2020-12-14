# Installation

## Cloner le dépôt

Si vous avez bien renseigné une clé SSH dans vos paramètres GitHub, vous pouvez cloner le dépôt de Rodexal comme suit :

```shell script
git clone git@github.com:alexdor-rpgmaker/rodexal.git
```

## Installer les dépendances

# Installer les dépendances Composer
composer install

# Installer les dépendances NPM
npm install

## Mettre en place une base de données

### a) Installer et démarrer MySQL

MySQL est le SGBD utilisé pour le projet. Il faut donc tout d'abord l'avoir installé sur sa machine.

Via Homebrew : `brew install mysql`

Une fois installé, il est possible de démarrer le démon mysql de deux manières :
- En automatique : `brew services start mysql` (sera toujours en fonctionnement tant qu'il n'est pas arrêté par `brew services stop mysql`)
