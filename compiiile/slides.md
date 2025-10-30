---
asSlides: true
---

# API Platform & Serializer

> Jocelyn & Ethan

---

###### Niveau -1
#### Avant de parler d'Api Platform
## C'est quoi une Api Rest ?

Une API REST est une interface qui permet à des applications de communiquer sur le web via le protocole HTTP en utilisant des URL et des méthodes HTTP.

> En gros : c’est une manière standard de rendre des ressources accessibles via HTTP.

---

###### Niveau 0
#### Api Platform
## C'est quoi API Platform ?

Api Platform est un framework conçu pour créer des API REST, construit au dessus de Symfony. Il est adapté uniquement à Symfony et Laravel.

> Durant ces slides, nous allons nous concentré sur la partie Rest de API Platform, nous n'avons pas les compétences nécessaires pour parler de la partie GraphQL

---

###### Niveau 0
#### Api Platform
## Philosophie

* Permettre aux développeurs de pouvoir connecter leur techno front préféré sur Symfony en passant par une API Rest
* Création automatique de documentation et respect des standards
* Automatisation, productivité et Extensibilité

<a href="https://api-platform.com/docs/extra/philosophy/" style="font-size:16px; margin-top:8px;">sources</a>

---

###### Niveau 0
#### Api Platform
## Qu'est ce que ça fait concrètement ?

Api Platform permet d’exposer des entités via une API publique et de générer une documentation "Swagger" en quelques minutes.

![image|400vh|no-margin](image.png)

<div style="font-size:18px;">
(Swagger est un format qui sert à décrire, documenter et tester une API Rest)
</div>

---

###### Niveau 0
#### Api Platform
## Comment on l'installe ?

L'installation d'API Platform dans Symfony se résume à une commande

```sh
composer require api
```

<a href="https://api-platform.com/docs/core/getting-started/" style="font-size:16px; margin-top:8px;">sources</a>

---

###### Niveau 0
##### Api Platform
## Comment on l'utilise ?

Il suffit d'un simple changement sur une entité pour la rendre publique dans l'api.

<div style="display: flex; justify-content: space-between;">

<div style="width: 45%;">

> Avant

```php
#[ORM\Entity(...)]
class User{

}
# 
```

</div>

<div style="width: 45%;">

> Après

```php
#[ORM\Entity(...)]
#[ApiResource]
class User{

}
```

</div>

</div>

<a href="https://api-platform.com/docs/core/getting-started/" style="font-size:16px; margin-top:8px;">sources</a>

---

###### Niveau 0
##### Api Platform
## Les possibilités et les attributs

```php
#[ApiResource(
    paginationItemsPerPage: 30 # <-- Pagination
    )]
class Book{

}
```

--- 

###### Niveau 1
#### Api Platform
## Sélectionner les opérations possibles.

Il est possible de sélectionner les opérations qu'on souhaite pouvoir réaliser depuis l'API.

```php
#[ORM\Entity(...)]
#[ApiResource(operations: [
    new Get() # <-------- Permet d'ajouter uniquement 
    #                    l'opération GET dans l'entity
    ])] 
class User{

}
```

--- 

###### Niveau 1
#### Api Platform
## Paramétrer les opérations possibles.

Il est possible de paramétrer les opérations qu'on souhaite pouvoir réaliser depuis l'API.

```php
#[ORM\Entity(...)]
#[ApiResource(operations: [
    new Get(
        uriTemplate: '/user/{email}', 
        uriVariables: ['email' => 'email'],
        # Permet de mapper l'opération get à la propriété email de l'entité
    )
    ])] 
class User{
    public ?string $email = null;
    ...
}
```

--- 

###### Niveau 1
#### Api Platform
## Custom Controller - Explication

Il est possible aussi d'insérer un controller custom pour modifier le comportement d'une opération.

<a href="https://api-platform.com/docs/symfony/controllers/" style="font-size:16px; margin-top:8px;">sources<a>

--- 

###### Niveau 1
#### Api Platform
## Custom Controller - Exemple
<div style="font-size:32px">

```php
#[AsController]
class RegisterController extends AbstractController
{
    public function __invoke(Request $request, ...) { ... }
}

#[ORM\Entity(...)]
#[ApiResource(operations: [
    new Post(
        uriTemplate: '/register',
        controller: RegisterController::class, # <-- Utilisera le controller déclaré plus haut
        name: 'register'
    )])]
class User {
    ...
}
```

</div>

---

###### Niveau 2
#### Api Platform
## Les Data Providers - Explication

Un Data Provider est un service qui récupère les données pour une ressource donnée lorsqu’une requête API est faite.

> Il peut être particulièrement utile dans le cas où on veut récuperer des données en fonction d'un utilisateur

<a href="https://api-platform.com/docs/v2.6/core/data-providers/" style="font-size:16px; margin-top:8px;">sources<a>

---

###### Niveau 2
#### Api Platform
## Les Data Providers - Exemple

```php
class BookProvider implements ProviderInterface # <-- Déclare le provider
{
    public function __construct(...) {}

    public function provide(...): object|array|null
    {
        $user = $this->security->getUser();
        return $this->entityManager->getRepository(Book::class)
            ->findBy(['owner' => $user]);
    }
}

#[ApiResource(operations: [
    new GetCollection(provider: BookProvider::class) # <-- Utilise le provider
])]
class Book {
    public User $owner = null;
}
```


---

###### Niveau 2
#### Api Platform
## Les Processors

A l'inverse du providers, le processor lui va intervenir au moment de traiter la donnée et de la modifier.

```php
class BookProcessor implements ProcessorInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function process(...)
    {
        $data->setUpdatedAt(new \DateTimeImmutable());
        // ... enregistre en base etc ...
        return $data;
    }
}
```

<a href="https://api-platform.com/docs/core/state-processors/" style="font-size:16px; margin-top:8px;">sources<a>

--- 

###### Niveau 2
#### Api Platform
## Entités et DTO - Explication

Exposer votre Entité Doctrine (l'objet lié à la BDD) est rapide, mais risqué. Dans certains cas, on peut accidentellement exposer au public le mot de passe si on configure mal nos `groups` (*sujet qu'on abordera juste après*).

```php
// src/Entity/User.php
#[ORM\Entity]
#[ApiResource] # <-- L'exposer de cette manière
class User{
    private ?string $password;
    public string $email;
    ...
}
```

<a href="https://api-platform.com/docs/core/dto/" style="font-size:16px; margin-top:8px;">sources</a>

--- 


###### Niveau 2
#### Api Platform
## Entités et DTO - Exemple

La meilleure pratique est d'utiliser un DTO (Data Transfer Object). C'est un simple objet PHP qui sert de "façade" ou de "masque" public. *Ainsi, il est impossible de fuiter $password ou $balance.*

```php
// src/ApiResource/UserResource.php
#[ApiResource(
    stateOptions: new Options(
        entityClass: User::class
    )
)]
class UserResource{
    public string $email;
}
```

--- 

###### Niveau 3
#### Api Platform - Serializer
## Qu'est ce que c'est que la Serialisation ?

Le Serializer est un composant Symfony qui transforme des données dans les deux sens.

* Sérialisation = transformer un objet PHP en format que l’ordinateur peut envoyer ou stocker, comme JSON ou XML.

* Désérialisation = l’inverse : transformer des données reçues (JSON, XML…) en objet PHP.

<a href="https://api-platform.com/docs/core/serialization/" style="font-size:16px; margin-top:8px;">sources</a>

--- 

###### Niveau 3
#### Api Platform - Serializer
## Api Platform et Serialisation

Comprendre le flow de serialisation avant de comprendre comment API Platform l'utilise

![image|300px|no-margin](mermaid.png)

--- 

###### Niveau 3
#### Api Platform - Serializer
## Api Platform et Serialisation

API Platform s’appuie sur le Symfony Serializer pour :

1. La normalization
* Transformer les entités (objets) en format standardisé quand on fait une requête de récupération.
2. La denormalization
* Transformer le JSON reçu en objet quand on fait une requête de modification. 

---

###### Niveau 3
#### Serializer
## `normalizationContext` - Exemple

```php
#[ApiResource( operations: [
        new Get(
            # On ne montre que les champs tagués "user:read"
            normalizationContext: ['groups' => 'user:read']
        ),
        new Get(
            uriTemplate: '/users/me',
            # On montre "user:read" ET "user:read:self"
            normalizationContext: ['groups' => ['user:read', 'user:read:self']]
        )])]
class User {
    # -> `GET /api/users/{id}` & `GET /api/users/me` renverra cette propriété
    #[Groups(['user:read'])] 
    private string $email; 
    # -> `GET /api/users/me` renverra cette propriété
    #[Groups(['user:read:self'])] 
    private float $balance; 
}
```


---

###### Niveau 3
#### Serializer
## `denormalizationContext` - Exemple 1/2

```php
#[ApiResource(operations: [
        new Post(
            // N'accepte que les champs tagués "user:write"
            denormalizationContext: [
                'groups' => 'user:write'
            ]
        )
    ])]
class User {
    #[Groups(['user:write'])] 
    private string $email; 

    private float $balance; 
    private string[] $roles;
}
```

---

###### Niveau 3
#### Serializer
## `denormalizationContext` - Exemple 2/2

Si un client envoie ce JSON pour s'inscrire :

```json
{
    "email": "hacker@test.com",
    "balance": 999999,      // <-- Tentative d'injection
    "roles": ["ROLE_ADMIN"] // <-- Tentative d'injection
}
```

Le Serializer ignorera `balance` et `roles` car ils n'ont pas le groupe `user:write`.

--- 

###### Niveau 3
#### Api Platform - Serializer
## Les Groups - Exemple Complet

<div style="font-size:34px">

```php
#[ApiResource(
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']]
)]
class User
{
    #[Groups(['user:read'])] # Apparait en GET
    private int $id;

    #[Groups(['user:read', 'user:write'])]  # Apparait en GET et accépté en entrée (ex: Post, Put,...)
    private string $email;

    #[Groups(['user:write'])]  # Accépté en entrée (ex: Post, Put,...)
    private string $password;
}
```

</div>

> API Platform se sert de ces étapes pour contrôler ce qu’on récupère (normalization) et ce qu’on modifie (denormalization) via les groupes.

--- 

#### Api Platform - Annexes
## La securité

Api Platform fonctionne de pair avec la sécurité, il est possible de parametrer certaines routes de modification (PUT, par exemple) afin d'uniquement accepté 

```php 
#[ApiResource(security: "is_granted('ROLE_USER')")]
#[Put(security: "is_granted('ROLE_ADMIN') or object.owner == user")]
class Book
{
    #[ORM\ManyToOne]
    public User $owner;
}
```

<a href="https://api-platform.com/docs/symfony/security/" style="font-size:16px; margin-top:8px;">sources</a>

--- 

#### Api Platform - Annexes
## Liste de bundles

Des bundles souvent utilisé avec API Platform

<div style="display: flex; justify-content: center;">

| Bundle                              | Description                                                                      |
| ----------------------------------- | -------------------------------------------------------------------------------- |
| **lexik/jwt-authentication-bundle** | Gère l’authentification via JSON Web Tokens (JWT) pour sécuriser l’API.          |
| **vich/uploader-bundle**            | Simplifie l’upload et la gestion des fichiers liés aux entités.                  |
| **easycorp/easyadmin-bundle**       | Fournit une interface d’administration rapide pour gérer les entités.            |
| **stof/doctrine-extensions-bundle** | Ajoute des fonctionnalités avancées à Doctrine (sluggable, timestampable, etc.). |

</div>