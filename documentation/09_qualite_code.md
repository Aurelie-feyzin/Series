# Qualité du code

Pour s'assurer de la qualité du code produit, il existe de nombreux outils.  
Les outils décrit sont ceux mis en place pour ce projet.

## phpmd

Détecte certains défauts de programmation (nommage, variables inutilisées, etc...).  
[site officiel](https://phpmd.org/)

## phpstan

Détecte des défauts de programmation à l'aide du typage.
Il est nécessaire d'ajouter des extensions pour faire fonctionner phpstan avec symfony et doctrine.  
[dépôt](https://github.com/maglnet/ComposerRequireChecker/tree/6022d233935615c4454a462c0607aa5b834fd93c)

## lint

Vérifie la syntaxe des fichiers PHP, Yaml et Twig.  
[dépôt](https://github.com/JakubOnderka/PHP-Parallel-Lint)

## php-cs-fixer

Vérifie le formatage des fichiers PHP.  
[dépôt](https://github.com/FriendsOfPHP/PHP-CS-Fixer)

## phpcpd

Détecte les portions de codes très similaires (probablemnt des copiés-collés).  
[dépôt](https://github.com/sebastianbergmann/phpcpd)

## Security Checker

Vérifie qu'aucun paquet ne présente un risque de sécurité.  
[dépôt](https://github.com/sensiolabs/security-checker)

## ComposerRequireChecker

Vérifie que toutes les dépendances utilisées dans les sources sont bien listées dans le composer.json  
[dépôt](https://github.com/maglnet/ComposerRequireChecker)  
[code|design](https://codedesign.fr/tutorial/dependance-php-composer-require-checker/)

## phploc

Calcule des stats sur les sources.  
[dépôt](https://github.com/sebastianbergmann/phploc)
