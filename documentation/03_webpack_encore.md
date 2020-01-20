# Webpack Encore

## C'est quoi Webpack Encore

Webpack Encore est une librairie JavaScript permettant d'intégrer simplement [Webpack](https://webpack.js.org/) dans une application Symfony.
Webpack est un outil apportant beaucoup de fonctionnalités pour charger et traiter automatiquement les *assets* (Un asset est défini comme une ressource basique devant être affichée dans un navigateur web.).

## Installer Webpack Encore

### Condition

- nodejs
- yarn

### Installation

> Encore n'est pas une librairie PHP, mais une librairie Javascript.

```bash
composer require encore
```

Cela va créer un fichier webpack.config.js, un répertoire assets/ à la racine du projet, repertoire où mettre tous les assets du projet(CSS, JS, fonts, images statiques…)).
Il faut ensuite installer la partie JS Webpack:

```bash
yarn install
```

Le fichier webpack.config.js généré à la racine du projet contient toute la configuration de Webpack dont :

- `setOutputPath()` définit le chemin de sortie des fichiers. Par défaut dans un dossier public/build. Webpack va en effet compiler les assets et les déplacer ici ; ils seront donc bien accessibles par le navigateur car dans le dossier public/. Le dossier build ne doit cependant pas être versionné, Symfony l'a déjà ajouté dans son .gitignore.
- `addEntry()` définit le ou les points d'entrée de ton application. Les frameworks Javascript comme React, Vue ou Angular, favorisent la création de sites à un seul point d'entrée (Single Page Application ou S.P.A.), qui n'auront qu'un unique fichier JS en entrée. Les sites créés via des frameworks, comme Symfony, favorisent quant à eux les M.P.A (Multiple Pages Application), qui pourront avoir un fichier JS par page (car il serait inutile de charger du code JS non utilisé sur la page). Il faut donc créer plusieurs entrées (avec .addEntry()), une par fichier JS/CSS.
- `cleanupOutputBeforeBuild()` : supprime les précédents fichiers générés par Webpack avant de builder.
- `enableBuildNotifications()` : active des notifications système quand Webpack a réussi à compiler ou quand 'il y a des erreurs.
- `enableVersioning` permet d’activer certaines fonctionnalités quand le projet se trouve en production (minification des assets, versionnage des fichiers, etc.)

[MPA vs SPA](https://www.slideshare.net/MehmetAliTastan/spa-vs-mpa)  
[Documentation installation Webpack Encore](https://symfony.com/doc/4.4/frontend/encore/installation.html)
[Documentation webpack](https://webpack.js.org/)

## Utilisation

### Ligne de commande essentiel

- `yarn encore dev --watch` : compilation et observation des changements.

- `yarn encore production` : compilation pour un environnement de production (les fichiers sont notamment minifiés pour en limiter la taille).

Cela a pour effet de compiler les fichiers contenus dans 'assets/ et générer les fichiers finaux, utilisés par ton projet.

### Intégration dans Symfony

Un helper Twig est disponible pour charger les CSS et JS, respectivement {{ encore_entry_link_tags('app') }} et {{ encore_entry_script_tags('app') }} ou le app est le nom du fichier, sans l’extension .css ou .js

```twig
{# base.html.twig #}
{% block stylesheets %}
        {{ encore_entry_link_tags('app') }}
{% endblock %}

{% block javascripts %}
           {{ encore_entry_script_tags('app') }}
{% endblock %}
```

[limiter la duplication de code](https://symfony.com/doc/4.4/frontend/encore/split-chunks.html)  
[documentation de l'utilisation basique de Webpack Encore](https://symfony.com/doc/4.4/frontend/encore/simple-example.html)

### Installation de librairie tierces

Pour utiliser SCSS dans un projet, il faut d'abord décommenter l’option `.enableSassLoader()` dans le fichier webpack.config.js.
Puis il faut installer les dépendances requises (lecture du message d'erreur)

```bash
yarn add sass-loader@^7.0.1 node-sass --dev
```

[intégrer Boostrap](https://symfony.com/doc/4.4/frontend/encore/bootstrap.html)  
[intégrer SCSS](https://symfony.com/doc/4.4/frontend/encore/css-preprocessors.html)
