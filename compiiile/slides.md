---
asSlides: true
---

# API Platform & Serializer

---

#### Api Platform
## C'est quoi API Platform ?

Api Platform est un framework centré sur le fait de faire une API construit au dessus de Symfony.

> Il est adapté uniquement à Symfony et Laravel.

---

###### Niveau 0
#### Api Platform
## Qu'est ce que ça fait concrètement ?

Api Platform permet d'exposer des Entités sur une Api Public avec une documentation swagger en quelques minutes.

![image|400vh|no-margin](image.png)

---

###### Niveau 0
##### Api Platform
## Comment on le fait concrètement ?

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