# Introduction Tests

## Type de Test

Il existe plusieurs type de test :

- test unitaires : valider une unité de code, ce sera souvent une méthode en isolant la méthode du reste du monde.
- test fonctionnels : tester une classe dans un contexte spécifique.
- test end-to-end : tester l'application comme un utilisateur.

## Pourquoi Tester

Les tests permettent de s'assurer que le code fonctionne comme attendu et d'éviter les régressions.

## Comment tester

### Tester pour vérifier

Les tests sont écrits après le code. Ce n'est pas la méthode recommandée, car il y a un risque que le code écrit par les tests soient influencés par la logique déjà écrite.  

### Test Driven Development (TDD)

**Développements pilotés par les tests**, c'est une méthode de développement qui consiste à écrire chaque test avant d'écrire le code de façon itérative.

Pour faire du TDD, il faut respecter les 3 lois suivantes :

1 Loi no 1 : Vous devez écrire un test qui échoue avant de pouvoir écrire le code de production correspondant.
2 Loi no 2 : Vous devez écrire une seule assertion à la fois, qui fait échouer le test ou qui échoue à la compilation.
3 Loi no 3 : Vous devez écrire le minimum de code de production pour que l'assertion du test actuellement en échec soit satisfaite.

Le processus préconisé par TDD comporte cinq étapes :

1 écrire un seul test qui décrit une partie du problème à résoudre ;
2 vérifier que le test échoue, autrement dit qu'il est valide, c'est-à-dire que le code se rapportant à ce test n'existe pas ;
3 écrire juste assez de code pour que le test réussisse ;
4 vérifier que le test passe, ainsi que les autres tests existants ;
5 puis remanier le code, c'est-à-dire l'améliorer sans en altérer le comportement.

Ce processus est répété en plusieurs cycles, jusqu'à résoudre le problème d'origine dans son intégralité. Ces cycles itératifs de développement sont appelés des micro-cycles de TDD.

![Une représentation graphique du cycle de la méthode Développements Pilotés par les Tests (TDD)](https://upload.wikimedia.org/wikipedia/commons/thumb/0/0e/Cycle-global-tdd.png/220px-Cycle-global-tdd.png)

[wikipedia TDD](https://fr.wikipedia.org/wiki/Test_driven_development)

[Test F.I.R.S.T](https://hackernoon.com/test-f-i-r-s-t-65e42f3adc17)

## Outils

- PHPUnit
- Behat / Puppeteer / Cypress

[grafikart](https://www.grafikart.fr/tutoriels/tests-symfony-introduction-1213)