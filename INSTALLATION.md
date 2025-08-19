# Installation

## Cloner le dépôt

Si vous avez bien renseigné une clé SSH dans vos paramètres GitHub, vous pouvez cloner le dépôt de Rodexal comme suit :

```bash
git clone git@github.com:alexdor-rpgmaker/rodexal.git
```

## Installer les dépendances

Avoir une version de php minimum 8.2, ainsi que Composer et Node.js installés sur votre machine.

```bash
# Installer les dépendances Composer
composer install

# Installer les dépendances NPM
npm install
```

## Mettre en place une base de données

Si vous n'avez pas encore Docker sur votre machine, il faut l'installer. [Télécharger Docker](https://www.docker.com/products/docker-desktop).

Lancer les serveurs avec ce docker-compose :

```bash
# Lancer le docker-compose de cette app
docker-compose -f dev/docker-compose.yml up --build

# Lancer le docker-compose de l'app de 2011
cd <path>/<to>/<alexdor_2011>
docker-compose -f dev/docker-compose.yml up --build
```

Cela démarre :
- Une base de données MySQL
- Une interface d'administration BDD (Phpmyadmin) sur http://localhost:8088
- Un faux serveur de mail (Maildev)

Il faut aussi se créer une base de données de test `rodexal_test`. (Voir une documentation mysql si besoin.)

```bash
# Lancer les migrations de base de données
php artisan migrate

# Ajouter des fausses données dans la base de données
php artisan db:seed
```
