# Fixtures

## Les fixtures

il existe des outils qui permettent de faire du *data seeding*, c'est-à-dire de renseigner du contenu de manière automatisée dans notre base de données.

Les données générées, bien que fictives, seront proches des données finales, afin de simuler ce qui se passera une fois le site en production. Cela a comme avantages de pouvoir :

- tester le site en conditions réelles,
- tester les performances avec des bases de données très remplies.

## Installation

### DoctrineFixturesBundle

```bash
composer require --dev doctrine/doctrine-fixtures-bundle
```

[documentaion Symfony](https://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html)

### AliceBundle, générateur de donnée aléatoire

```bash
composer require --dev hautelook/alice-bundle
```

## Utilisation

L'utilisation d'AliceBundle permet non seulement d'utiliser [Faker](https://github.com/fzaninotto/Faker) mais permet de faire des choses plus complexes. Dans la majorité des cas les fixtures peuvent-ere écrite en yaml.

### Exemple simple

```yaml
App\Entity\Category:
  category_{1..10}:
    name: '<word()>'
```

### Lancer les fixtures

```bash
php bin/console hautelook:fixtures:load
```

[documentation AliceBundle](https://github.com/hautelook/AliceBundle)
