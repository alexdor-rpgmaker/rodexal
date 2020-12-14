# Politique de Contribution

Les Alex d'or sont nés en 2001. Plusieurs sites se sont succédé :
- Première page de Booskaboo / Stormer en 2001
- Site de 2002-2007 par F le chat / Gratteur / Sephiroth XIII / Masthiks
- Site de 2007-2009 par Sephiroth XIII / Lolow
- Site de 2010-2020 par Lifaen / AlexRE
- Et ce nouveau site avec un vrai framework de développement web moderne et des tests automatisés !

Tous les volontaires peuvent participer au développement de Rodexal, tant que les développements sont validés et relus par l'équipe du projet.

Voici quelques règles de développement.

## Règles générales

### Consulter les boards

Un tableau liste des améliorations possibles du projet : le [Board Trello](https://trello.com/b/Tt4GYLKa/alex-dor-roadmap).

Avant de partir sur un sujet, il est important d'en discuter avec l'équipe du projet pour s'assurer que le besoin est toujours existant et avoir quelques pistes pour démarrer les développements.

### Créer et publier une branche

Au début des développements, il faut créer une _feature-branch_ (`git branch -d ma-nouvelle-fonctionnalite`), sur laquelle commiter des changements.

Avant d'envoyer du code sur le serveur distant (`git push`), il faut penser à lancer les tests automatisés (cf ci-dessous) pour s'assurer qu'il n'y a aucune régression. Et, bien sûr, réparer les tests qui échouent.

Une fois la branche poussée sur le serveur distant, il faut [créer une Pull Request](https://github.com/alexdor-rpgmaker/rodexal/compare) sur GitHub. Cela crée notamment une pipeline de tests automatisés (enfin dans l'idéal) et permet à la team Alex d'or de relire le code écrit (code review).
 
Une fois la Pull Request validée et recettée par la team, elle sera mergée sur master puis mise en production.
  
## Tests automatisés

### PhpUnit
### Dusk
### Jest
