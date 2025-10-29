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

---

###### Niveau 0
#### Api Platform
## Comment ça le fait concrètement ?

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


_Test_