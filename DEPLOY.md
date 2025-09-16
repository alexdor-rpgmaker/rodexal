# Mise en production

## Environnements

Rodexal possède deux environnements, hébergés sur Heroku. L'accès aux panels d'administration Heroku est à demander à la team Alex d'or.

### Staging
- Identifiant Heroku : new-alexdor-staging
- URL de l'application : https://new-alexdor-staging.herokuapp.com
- Panel d'admin : https://dashboard.heroku.com/apps/new-alexdor-staging

### Production
- Identifiant Heroku : new-alexdor
- URL de l'application : https://new-alexdor.herokuapp.com
- Panel d'admin : https://dashboard.heroku.com/apps/new-alexdor

## Utiliser le CLI Heroku

Il est possible de faire des actions sur ces environnements en ligne de commande en utilisant le CLI Heroku.

Si vous avez Homebrew, vous pouvez installer le CLI comme ceci :

```bash
brew tap heroku/brew && brew install heroku
```

Une fois installé, vous pouvez vous connecter à votre compte Heroku avec la commande suivante :

```bash
heroku login
```

Par exemple, il est possible d'avoir accès aux logs de l'application :

```bash
heroku logs -t -a new-alexdor-staging
# ou
heroku logs -t -a new-alexdor
```

## Déclencher les tâches à la main

Les rake tasks peuvent être déclenchées manuellement :

```bash
heroku run php artisan podcast:update -a new-alexdor-staging
```

## Configurer ses environnements git distants

Ajouter l'environnement de production dans vos remotes git :

```bash
heroku git:remote --remote heroku-demo -a new-alexdor-staging
heroku git:remote --remote heroku-production -a new-alexdor
```

Cela va ajouter deux remotes, appelés `heroku-demo` et `heroku-production`.

Il est possible de consulter la liste de ses environnements git distants avec la commande `git remote`, et consulter l'URL spécifique d'un environnement avec `git remote get-url heroku-production`.

## Déployer sur un environnement

_/!\ Le code déployé en production doit être du code qui a été mergé sur `origin/master`. Il faut penser à pousser d'abord sur GitHub, qui reste le référentiel, avant de mettre en production._

Une fois que tout est bien configuré, il est possible de déployer en faisant un simple `git push` :

```bash
git push heroku-demo master
# ou
git push heroku-production master
```

Dans certains cas, il est nécessaire d'accompagner une mise en production d'une migration de la base de données (`rails db:migrate`) ou de certaines actions de migration spécifiques via les rake tasks (`rails ma_task_1`).

Il peut donc être intéressant de mettre l'application en mode maintenance, lancer ces actions, puis retirer le mode maintenance :

```bash
git push heroku-production master && 
heroku maintenance:on -a new-alexdor && 
heroku run php artisan migrate podcast:update -a new-alexdor &&
heroku restart -a new-alexdor && 
heroku maintenance:off -a new-alexdor
```
