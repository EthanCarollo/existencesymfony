# Symfony - Existence

Bienvenue dans **Symfony Existence** !  
Ce projet est une application Symfony où vous pouvez tester des interactions avec des personnages, discuter, et explorer les fonctionnalités front-end et back-end. Ce guide vous aidera à tout installer et configurer facilement.

---

## 1. Pré-requis

Avant de commencer, assurez-vous d’avoir installé :

- PHP >= 8.x
- Composer
- Symfony CLI
- Une base de données compatible avec Doctrine (MySQL, PostgreSQL, etc.)
- [LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle) pour la gestion des tokens JWT si vous utilisez des API sécurisées

> LexikJWT est utilisé pour sécuriser les endpoints API et gérer l’authentification par token. Vous devez suivre leur documentation pour générer votre clé secrète et configurer le bundle.

---

## 2. Installation

1. Installer les dépendances du projet :

```sh
composer install
````

2. Configurer le fichier `.env` ou `.env.local` pour votre base de données :

```env
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
```

3. Si vous utilisez LexikJWT, ajoutez également votre configuration JWT dans `config/packages/lexik_jwt_authentication.yaml` :

```yaml
lexik_jwt_authentication:
    secret_key: '%kernel.project_dir%/config/jwt/private.pem'
    public_key: '%kernel.project_dir%/config/jwt/public.pem'
    pass_phrase: 'votre_phrase_de_passe'
    token_ttl: 3600
```

---

## 3. Base de données

Avant de lancer l’application, il faut créer la base et appliquer les migrations :

### 3.1 Créer la base

```sh
symfony console doctrine:database:create
```

### 3.2 Créer une migration (si vous avez modifié des entités)

```sh
symfony console make:migration
```

### 3.3 Exécuter les migrations

```sh
symfony console doctrine:migrations:migrate
```

---

## 4. Chargement des fixtures

Pour peupler la base de données avec des personnages et des données de test, utilisez :

```sh
php bin/console doctrine:fixtures:load
```

> Cette étape est essentielle pour que vous puissiez tester toutes les fonctionnalités sans avoir à créer manuellement des comptes ou des personnages.

Si vous voulez **ajouter des fixtures sans supprimer les anciennes données**, utilisez :

```sh
php bin/console doctrine:fixtures:load --append
```

---

## 5. Lancement de l’application (mode développement)

Pour lancer le serveur local Symfony et tester l’application :

```sh
APP_ENV=dev symfony server:start
```

L’application sera accessible sur `http://127.0.0.1:8000`.

---

## 6. Identifiants de test

Pour tester rapidement le front-end et les fonctionnalités de chat, vous pouvez utiliser :

```json
{
    "email": "test123@gmail.com",
    "password": "test"
}
```

> Vous pouvez vous connecter directement avec ce compte et commencer à interagir avec les personnages sans créer de compte.

---

## 7. Commandes Symfony utiles

Voici quelques commandes pratiques pour travailler sur le projet :

* **Créer une migration** :

```sh
symfony console make:migration
```

* **Appliquer les migrations** :

```sh
symfony console doctrine:migrations:migrate
```

* **Vider et recharger les fixtures** :

```sh
php bin/console doctrine:fixtures:load --append
```

* **Vérifier que la configuration JWT fonctionne** :

```sh
php bin/console lexik:jwt:check
```

---

## 8. Ressources supplémentaires

Pour aller plus loin avec Symfony AI et l’intégration chat :

* [Exemple Chat Symfony AI](https://github.com/symfony/ai/blob/main/examples/lmstudio/chat.php)

> Attention : la librairie est encore jeune et certains exemples peuvent ne pas fonctionner parfaitement.

---

## 9. Conseils pratiques

* Après toute modification d’entités, **créez toujours une migration** avant de mettre à jour la base.
* Utilisez les fixtures pour tester rapidement vos fonctionnalités.
* Le mode développement (`APP_ENV=dev`) active le debug et le rechargement automatique, ce qui rend le développement plus fluide.
* Si vous utilisez LexikJWT, **générez vos clés** avant de tester les endpoints sécurisés.

---

Symfony Existence est maintenant prêt à être exploré ! Amusez-vous à découvrir tous les personnages et leurs interactions.

```
