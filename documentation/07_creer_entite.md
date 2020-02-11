# Créer une entité

## Qu'est-ce qu'une entité

Une entité est une simple classe représentant un objet métier. La plupart du temps, une entité représente une table de la base de données, et chaque propriété de l'entité, un champ de la table. Mais ça n'est pas obligatoirement le cas.

## Comment créer une entité

### Utilisation d'une commande Symfony

```bash
php bin/console make:entity

# La commande pause quelques questions
Class name of the entity to create or update:
> NameEntity

New property name (press <return> to stop adding fields):
> nameProperty

Field type (enter ? to see all types) [string]:
> string

Field length [255]:
> 255

Can this field be null in the database (nullable) (yes/no) [no]:
> no

(press enter again to finish)

```

Cela génère automatiquement une classe :

```php
// src/Entity/Product.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity(repositoryClass="App\Repository\NameEntityRepository")
*/
class NameEntity
{
/**
* @ORM\Id
* @ORM\GeneratedValue
* @ORM\Column(type="integer")
*/
private $id;

/**
* @ORM\Column(type="string", length=255)
*/
private $nameProperty;

public function getId()
{
return $this->id;
}

// ... getter and setter methods
}
```

> Le nom de l'entité doit-être en anglais et surtout au singulier.

### Sans commande

Il est également possible de créer une entité sans passer par la commande Symfony.

### Ajouter une nouvelle propriété

Il est possible d'ajouter la nouvelle propriété manuellement ou de refaire `make:entity`.
Il est également possible de créer la propriétés à la main et d'utiliser la commande pour générer les getters et setters.

```bash
php bin/console make:entity --regenerate
```

Pour régénérer tous les getters et setters ajouter l'option `--overwrite`.

[doc Symfony](https://symfony.com/doc/4.4/doctrine.html#creating-an-entity-class)

## Générer un CRUD

```bash
   php bin/console make:crud NameEntity

created: src/Controller/NameEntityController.php
created: src/Form/NameEntityType.php
created: templates/nameEntity/_delete_form.html.twig
created: templates/nameEntity/_form.html.twig
created: templates/nameEntity/edit.html.twig
created: templates/nameEntity/index.html.twig
created: templates/nameEntity/new.html.twig
created: templates/nameEntity/show.html.twig
```

Les vues _form et _delete_form sont des vues partielles, destinées à être incluses dans d'autres vues, et ne seront jamais appelées directement dans la méthode render de nos contrôleurs. C’est d’ailleurs la même convention pour le nommage des fichiers SCSS.

On utilise souvent des underscores devant ce type de vue, afin de pouvoir repérer d'un coup d’œil dans l'arborescence de fichiers, les vues destinées à être importées.

> La commande make:crud fait énormément de travail pour nous mais ne permets pas, par exemple, de générer des formulaires dynamiques qui s’actualisent en fonction des interactions de l’utilisateur.

Remarque : Quand on génère un CRUD, il y a le code de plusieurs User Story (US) différentes qui est créé. Pour éviter de générer le code via la console et de l'effacer tout de suite après, afin de coller à sa US, on peut tolérer la génération du code de tout le CRUD par Symfony. Mais attention, ce code généré n'est pas terminé (il manque les validations serveurs, la mise en page, le CSS, etc.). La première US qui a besoin d'un CRUD le génère donc, mais ne modifiera QUE le code touchant à cette US (par exemple, si la US est de faire une page "add", on génère tout le CRUD, donc aussi l'edit et le read) mais dans ce cas on n'y touche pas, et il ne sera modifié que lorsqu'on attaquera ces US. Le but ici est de garder le meilleur compromis possible entre respect du SCRUM et les outils mis en place par le framework pour te faire gagner du temps.
