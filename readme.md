# Projet Symfony

## Objectifs pédagogiques

* Explorer le bundle [Symfony AI](https://github.com/symfony/ai) et voir ce qu'il propose.
* Comprendre comment stocker et structurer les données de chat d’un LLM dans une base de données.
* Utiliser Symfony en tant que Rest API (avec API Platform) pour être utiliser avec Nuxt.

## Brief du projet

L’objectif de ce projet est de démontrer que les LLM (Large Language Models) ne font que traiter des calculs et ne possèdent aucun sentiment ou conscience. 
L’idée est de déconstruire l’idée reçue selon laquelle une IA « réfléchit » comme un humain.

Pour ce faire, le projet met en place des interactions entre un LLM et une simulation de vie, dans laquelle le rôle du créateur (humain) est central. 
Les personnages créés évolueront selon des règles définies et interagiront dans un environnement contrôlé.
Un composant Vue (ou Nuxt) sera intégré dans Symfony pour gérer les interfaces interactives comme le chat entre l’utilisateur et la simulation.

## Roadmap

### Phase 1 - Mise en place du projet et des dépendances

#### Symfony :
* Installer Symfony et initialiser le projet.
* Ajouter et configurer le composant Security pour gérer l’authentification.
* Définir les routes et contrôleurs pour l’authentification (login, logout, inscription).
* Installer les dépendances nécessaires pour l’API (ex: API Platform si besoin).

#### Front-end (Vue/Nuxt) :
* Installer Nuxt.js ou Vue.js dans le projet Symfony (via Webpack Encore ou Vite).
* Configurer la communication front-end/back-end (API REST ou GraphQL).
* Mettre en place la structure de base du composant chat.

### Phase 2 - Mise en place de l’authentification

* Créer le formulaire d’authentification côté front-end (login, signup, logout).
* Connecter les formulaires à Symfony Security via API.
* Gérer les sessions et les tokens JWT si nécessaire.
* Implémenter la validation côté front et côté back.
* Tester les flux d’authentification pour s’assurer que l’accès à la simulation est sécurisé.

### Phase 3 - Mise en place de la simulation

* Pouvoir mettre en place des batiments
* Pouvoir assigner des personnages dans ces batiments
* Création de personnages
* Mise en place de symfony AI
* Définir des intéractions entre personnages
* Implémenter une logique de base de la simulation

### Phase 4 – Création de personnages à partir de prompts

* Définir un format de prompt pour générer un personnage avec un caractère précis.
* Intégrer le LLM pour analyser le prompt et générer les caractéristiques du personnage.
* Ajouter la création automatique d’un historique ou d’une personnalité en fonction du prompt.
* Tester la génération de personnages et leur intégration dans la simulation.
* Prévoir la possibilité de modifier ou d’éditer manuellement les caractéristiques du personnage après génération.

## Schéma DB

```mermaid 

```