# Doctrine

## ORM

Doctrine est un **ORM** ( object-relational mapping ) c'est à dire qu'il fait le lien entre le monde de la base de données et le monde de la programmation objet. Il permet de transformer une table en un objet facilement manipulable via ses attributs.

## Configurer l'accès à la base de données

Il faut paramétrer la connexion à la base de données, afin que Doctrine puisse s'y connecter. Cela se passe dans le fichier `.env.local` à la racine du projet Symfony.
Le fichier n'existe pas, il faut le dupliquer à partir de `.env`.

```bash
# .env.local
DATABASE_URL="mysql://your_username:your_pwd@127.0.0.1:3306/db_name"
```

Il faut maintenant créer la base de données :

```bash
php bin/console doctrine:database:create
```

[Documentation Symfony du fichier .env](https://symfony.com/blog/new-in-symfony-4-2-define-env-vars-per-environment)

## Créer une entité

Il est possible de créer une entité manuellement ou d'utiliser un outil en ligne de commande

```bash
php bin/console make:entity
```

> Pour l'entité, il faut de préférence choisir un nom en anglais qi ne soit pas un mot clé reservé en SQL et surtout au singuler.

## Mettre à jour la base de données

Pour faire la migration, il faut d'abord générer un fichier qui contient la différence entre la base de données et les entités. Pour cela, il faut utiliser l'une ou l'autre des commandes:

```bash
php bin/console make:migration
php bin/console doctrine:migrations:diff
```

Effectue les changement dans le fichier généré si necessaire.
Applique les changements

```bash
php bin/console doctrine:migrations:migrate
```

[Documentation de Doctrine Migration](https://www.doctrine-project.org/projects/doctrine-migrations/en/1.7/reference/introduction.html#introduction)

## Doctrine cli

Pour lister la totalité des outils en mode cli disponible par Symfony il suffit de taper la commande :

```bash
php bin console
```

Les commandes doctrine suivantes sont utiles :

- `doctrine:database:create`
- `doctrine:database:drop`
- `make:entity --regenerate` génére les getters et les setters ainsi que d'autres méthodes utiles pour les *mappings* complexe. **Ne fait que de l'ajout.**
- `doctrine:mapping:info` les entités trouvées par Doctrine dans l'application.
- `doctrine:schema:validate`
- `doctrine:migrations:status`

Les migrations peuvent-être executé *unitairement* avec la commande `doctrine:migration:execute`, cela permet de choisir la méthode `up` ou `down`.

```bash
php bin/console doctrine:migrations:execute [nom de fichier de ta migration] --down
php bin/console doctrine:migrations:execute [nom de fichier de ta migration] --up
```

[Documentation Symfony sur Doctrine](https://symfony.com/doc/4.4/doctrine.html#migrations-adding-more-fields)
