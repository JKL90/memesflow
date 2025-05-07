# MemesFlow – Projet d’admission SUPINFO

MemesFlow est un projet web développé dans le cadre de ma candidature à SUPINFO, démontrant mes compétences en développement web fullstack avec Laravel. Ce projet illustre ma capacité à concevoir une application interactive, moderne et orientée utilisateur.

## Objectif

Créer une application intuitive permettant à l’utilisateur de générer et partager des mèmes personnalisés avec des textes positionnables en temps réel sur des images.

## Fonctionnalités principales

- **Création de mèmes personnalisés** : téléversement d’image, ajout de textes dynamiques en majuscule.
- **Édition visuelle en temps réel** : positionnement libre des textes (drag & drop).
- **Téléchargement instantané** du mème généré (format PNG).
- **Partage sur les réseaux sociaux** du mème généré (format PNG).
- **Galerie publique** : gestion des mèmes déjà créés.

## Technologies utilisées

- **Laravel 10** (Framework PHP)
- **Bootstrap 5** (Interface responsive)
- **JavaScript + HTML/CSS** (Interface dynamique)
- **Canvas HTML5** pour l’export des images
- **XAMPP** pour le serveur local

## Lancement en local (facultatif)

Voici les étapes pour exécuter le projet MemesFlow en local :

1. **Extraire l’archive du projet** sur votre machine.
2. **Ouvrir le dossier du projet dans Visual Studio Code** (ou tout autre éditeur).
3. **Démarrer Apache et MySQL** via **XAMPP**.
4. **Ouvrir un terminal** dans VS Code et exécuter les commandes suivantes :

```bash
php artisan migrate
```

👉 Tapez `yes` si demandé — cette commande crée les tables nécessaires.

```bash
php artisan storage:link
```

👉 Crée un lien symbolique vers le dossier public.

```bash
php artisan serve
```

👉 Lance le serveur Laravel.
5. **Rendez-vous sur** `http://127.0.0.1:8000` dans votre navigateur pour tester l’application.

## Auteur

**Nom** : [Jordan Kevin Laurel LOKO]  
**Contact** : [jordanloko957@gmail.com]  
**Projet d’admission** : SUPINFO - Année 2025

---

Merci pour votre attention ! Ce projet témoigne de ma passion pour le développement web et ma volonté d'intégrer une école d'ingénierie innovante comme SUPINFO.
