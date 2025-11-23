# Projet Symfony – Simulation LLM - Existence

> Notes : j'ai hosté un Ollama pour que cela puisse marcher même sans avoir de LLM en local.

## Objectifs pédagogiques

* Explorer le bundle [Symfony AI](https://github.com/symfony/ai) et découvrir ses fonctionnalités.
* Comprendre comment stocker et structurer les données de chat d’un LLM dans une base de données.
* Utiliser Symfony comme **API REST** (avec API Platform) pour communiquer avec un front-end Nuxt/Vue.

## Brief du projet

L’objectif de ce projet est de démontrer que les LLM (Large Language Models) ne font que traiter des calculs et **ne possèdent ni conscience ni sentiments**.
L’idée est de déconstruire l’idée reçue selon laquelle une IA « réfléchit » comme un humain.

Pour cela, le projet met en place des interactions entre un LLM et une **simulation de vie**, dans laquelle le rôle du créateur (humain) est central.
Les personnages créés évoluent selon des règles définies et interagissent dans un environnement contrôlé.
Un composant front-end (Nuxt ou Vue) sera intégré pour gérer les interfaces interactives, comme le chat entre l’utilisateur et la simulation.

---

## Roadmap du projet

### Phase 1 – Mise en place du projet et des dépendances

#### Symfony

* Installer Symfony et initialiser le projet.
* Ajouter et configurer le composant Security pour gérer l’authentification.
* Définir les routes et contrôleurs pour l’authentification (login, logout, inscription).
* Installer les dépendances nécessaires pour l’API (API Platform si besoin).

#### Front-end (Vue/Nuxt)

* Installer Nuxt.js ou Vue.js dans le projet Symfony (via Webpack Encore ou Vite).
* Configurer la communication front-end/back-end (API REST ou GraphQL).
* Mettre en place la structure de base du composant chat.

---

### Phase 2 – Authentification

* Créer le formulaire d’authentification côté front-end (login, signup, logout).
* Connecter les formulaires à Symfony Security via API.
* Gérer les sessions et les tokens JWT si nécessaire.
* Implémenter la validation côté front et côté back.
* Tester les flux d’authentification pour sécuriser l’accès à la simulation.
* Contrôler les règles de validation côté inscription.

---

### Phase 3 – Simulation

#### 3.1 – Système de construction

* Pouvoir construire des bâtiments.
* Empêcher la construction de deux bâtiments sur la même case.

#### 3.2 – Système d’IA

* À la création d’un bâtiment, attribuer automatiquement un personnage.
* Pouvoir assigner des personnages aux bâtiments.
* Intégrer Symfony AI pour les interactions.
* Pouvoir discuter avec une IA.

#### 3.3 – Interactions entre IA

* Définir des interactions entre personnages.

---

### Phase 4 – Création de personnages à partir de prompts (Bonus)

* Définir un format de prompt pour générer un personnage avec un caractère précis.
* Intégrer le LLM pour analyser le prompt et générer les caractéristiques du personnage.
* Ajouter la création automatique d’un historique ou d’une personnalité en fonction du prompt.
* Tester la génération et l’intégration des personnages dans la simulation.
* Prévoir la possibilité de modifier manuellement les caractéristiques après génération.

---

## Tester le projet en local

Pour profiter pleinement du projet, je recommande de le lancer **en local**, en combinant Symfony et Nuxt/Vue :

### Lancer Symfony

```sh
APP_ENV=dev symfony server:start
```

### Lancer Nuxt (front-end)

```sh
cd frontend
npm install
npm run dev
```

### Identifiants de test

Pour tester toutes les fonctionnalités (chat, interactions, simulation) :

```json
{
  "username": "Crystal",
  "email": "test123@gmail.com",
  "password": "test"
}
```

> Ces identifiants permettent de se connecter directement et d’accéder à toutes les fonctionnalités.

---

## Commandes utiles Symfony

* **Créer une migration**

```sh
symfony console make:migration
```

* **Exécuter une migration**

```sh
symfony console d:m:m
```

---

## Ressources supplémentaires

* [Exemple d’intégration Symfony AI](https://github.com/symfony/ai/blob/main/examples/lmstudio/chat.php)

## Conclusion

Ce projet m’a permis de découvrir les aspects positifs de Symfony. Malgré l’échec de ma tentative de créer un Docker Compose avec Franken PHP pour ensuite le déployer sur Coolify, je garde une perception légèrement positive d’API Platform. J’apprécie l’idée derrière cette techno et la manière dont elle simplifie certains processus de création d’API, mais je reste assez frustré. Honnêtement, je trouve Symfony peu agréable à utiliser sur plusieurs points : la documentation est insuffisante, le code complexe et la productivité limitée.

J’ai aussi pu constater à quel point le développement avec Symfony AI peut être compliqué : l’outil est instable et mal documenté, ce qui transforme l’expérience en un vrai mauvais plaisir. En définitive, bien que certains concepts d’API Platform soient intéressants, je doute fortement de réutiliser Symfony pour un futur projet, en raison des problèmes que j’ai rencontrés personnellement avec cette techno.

Je n'ai pas pu trop poussé certaines parties du projet dû à des complications de dev avec les différentes libs assez compliqués. (lib qui ne marche pas, mauvaise documentation)