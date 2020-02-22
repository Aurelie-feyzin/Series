# Test unitaires

Les tests unitaires consiste à valider une unité de code, ce sera souvent une méthode en isolant la méthode du reste du monde.

## Configurer env.test

Il est nécessaire d'avoir une base de données de test. Pour cela, on peut utiliser SQLite.

```bash
# env.test
DATABASE_URL=sqlite:///%kernel.cache_dir%/test.sqlite
```

Il faut ensuite créer la base de données de **test** et mettre à jour le schéma.

```bash
bin/console doctrine:database:create --env=test
bin/console d:s:update --env=test --force # A utiliser que pour les tests
```

## Tester une entité

Cela consiste essentiellement à tester les règles de validations.

Les tests sont a écrire après la création de l'entité mais avant de faire la migration. Test first, d'abord le test puis écrire la règle de validation.

[grafikart](https://www.grafikart.fr/tutoriels/tests-symfony-entity-1215)