# Lancement de l'application (développement)

Pour lancer l'application en mode développement, utilisez la commande suivante :

```sh
APP_ENV=dev symfony server:start
```

## Identifiants de test

Pour tester l'application côté front-end et profiter pleinement des fonctionnalités de discussion et autres, utilisez ces identifiants :

```json
{
    "username": "Crystal",
    "email": "test123@gmail.com",
    "password": "test"
}
```

> Ces identifiants vous permettent de vous connecter directement et de tester toutes les fonctionnalités sans restrictions.

## Commandes utiles

### Créer une migration

```sh
symfony console make:migration
```

### Lancer une migration

```sh
symfony console d:m:m
```

## Ressources supplémentaires

Pour voir un exemple d'intégration avec Symfony AI :
[Exemple Chat Symfony AI](https://github.com/symfony/ai/blob/main/examples/lmstudio/chat.php)
> L'exemple marche pas, la lib est encore trop peu stable...