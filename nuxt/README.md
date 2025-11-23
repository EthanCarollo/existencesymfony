# Existence - Nuxt

Bienvenue dans **Existence**, l’application front-end développée avec Nuxt 3.  
Ce guide vous explique comment installer, lancer et construire l’application **uniquement avec npm**.

---

## Documentation

Pour en savoir plus sur Nuxt et ses fonctionnalités :  
[Nuxt Documentation](https://nuxt.com/docs/getting-started/introduction)

---

## 1. Installation

Installez les dépendances du projet avec npm :

```bash
npm install
````

> Assurez-vous d’avoir Node.js et npm installés sur votre machine. (ou utilisez nvm)

---

## 2. Lancer le serveur de développement

Pour démarrer l’application en mode développement et la tester localement :

```bash
npm run dev
```

L’application sera accessible sur :
[http://localhost:3000](http://localhost:3000)

Le mode développement permet le **rechargement automatique** à chaque modification.

---

## 3. Build pour la production

Pour créer la version optimisée pour la production :

```bash
npm run build
```

Puis pour **prévisualiser le build localement** :

```bash
npm run preview
```

> Cette version correspondra exactement à ce qui sera déployé en production.

---

## 4. Déploiement

Pour plus d’informations sur le déploiement de votre application Nuxt :
[Nuxt Deployment Guide](https://nuxt.com/docs/getting-started/deployment)

---

## Notes

* Utilisez **npm uniquement** pour toutes les commandes.
* Le serveur de développement écoute par défaut sur le port 3000.
* `npm run build` génère un dossier `.output` contenant le build prêt pour la production.

