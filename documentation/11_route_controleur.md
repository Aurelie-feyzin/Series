# Route et Contrôleur

## Créer un contrôleur

Un contrôleur doit étendre la classe `AbstractController`.
Par convention le nom de la classe doit être suffixé par *Controller*

```php
<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class WildController extends AbstractController
{
    public function index() :Response
    {
        return new Response(
            '<html><body>Wild Series Index</body></html>'
        );
    }
}
```

L'objet `Response` représente une **réponse HTTP** complète.

**Le travail d'un contrôleur de Symfony est de retourner un objet de la classe Response !**

[Symfony and HTTP Fundamentals](https://symfony.com/doc/4.4/introduction/http_fundamentals.html)  
[Create your First Page in Symfony](https://symfony.com/doc/4.4/page_creation.html)

## Implémenter une Route

Une route, finalement, ce n'est que de la *configuration*.

[Vidéo sur le routing](https://symfonycasts.com/screencast/symfony/route-controller)

[Symfony Routing](https://symfony.com/doc/4.4/routing.html)  

### Les annotations

Les **annotations** sont des commentaires un peu particuliers, avec un format bien spécifique pour être reconnus comme tels. Symfony est capable d'aller lire ces annotations (elles doivent être écrites à des endroits bien particuliers) et de "se configurer" en fonction d'elles.

 ```php
// src/Controller/WildController.php

use Symfony\Component\Routing\Annotation\Route;

Class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
    */
    public function index() : Response
    {
        return new Response(
            '<html><body>Wild Series Index</body></html>'
        );
    }
}
```

`http://localhost:8000/wild` doit renvoyer une page blanche avec écrit "Wild Series index".

#### Définir des paramètres

```php
   /**
   * @Route("/wild/show/{page}", name="wild_show")
   */
   public function show(int $page): Response
   {
 return $this->render('wild/show.html.twig', ['page' => $page]);
   }
```

Pour ajouter un paramètre il suffit de le mettre entre accolades.

#### Contraintes sur les routes

- option *requirements* : permet de s'assurer que le paramètre est bien formaté à l'aide d'une regex

```php
 /**
  * @Route("/wild/show/{page}", requirements={"page"="\d+"}, name="wild_show")
  */
  public function show(int $page): Response
  {
      // ...
  }
```

- option *methods*: Permet de limiter la route aux méthodes HTTP indiquée

```php
@Route("/wild/new", methods={"POST"}, name="wild_new")
```

[Rappel méthodes HTTP](https://fr.wikipedia.org/wiki/Hypertext_Transfer_Protocol#M%C3%A9thodes)

#### Valeurs par défaut

```php
/**
* @Route("/wild/show/{page}",
*     requirements={"page"="\d+"},
*     defaults={"page"=1},
*     name="wild_show"
* )
*/
```

Ou directement dans la méthode:

```php
 /**
  * @Route("/wild/show/{page}", requirements={"page"="\d+"}, name="wild_show")
  */
  public function show(int $page = 1): Response
  {
      // ...
  }
  ```

#### Préfixes

Il est possible de préfixer toutes les noms de routes d'un contrôleur

```php
/**
* @Route("/wild", name="wild_")
*/
class WildController extends AbstractController
```

### Le param converter

Le param converter est une fonctionnalité qui permet de convertir directement des paramètres passés à travers une route en objets.

```php
/**
* @Route("/wild/{id}", name="wild_show")
*/

public function show(Program $program): Response
{
  return $this->render('program.html.twig', ['program'=>$program];
}
```

> Pour que cela fonctionne, il faut que le paramètre corresponde à un champ unique!

[Symfony @ParamConverter](https://symfony.com/doc/curent/bundles/SensioFrameworkExtraBundle/annotations/converters.html)  
[Symfony Best Practice](https://symfony.com/doc/4.4/best_practices.html#use-paramconverters-if-they-are-convenient)

## Appeler un *template* Twig

```php
// src/Controller/WildController.php

public function index() :Response
{
    return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
    ]);
}
```

```twig
{# templates/wild/index.html.twig}
<h1>{{ website }} index</h1>
```

## Utiliser les routes

### Dans les contrôleurs

```php
  public function new(): Response
   {
       // traitement d'un formulaire par exemple

       // redirection vers la page 'wild_show', correspondant à l'url wild/show/5
       return $this->redirectToRoute('wild_show', ['page' => 5]);
   }
```

[Symfony redirecting](https://symfony.com/doc/4.4/controller.html#redirecting)

### Dans Twig

```php
<a href="{{ path('wild_show', {'page':5}) }}">Afficher la page 5</a>
```

[Symfony templating](http://symfony.com/doc/4.4/templating.html#linking-to-pages)

## Router Symfony

Lorsque le router de Symfony reçoit une requête vers une route, il va regarder dans toutes les routes configurées dans l'application (techniquement,il utilise des expressions régulières pour faire cela.). Si l'une d'entre elles correspond à ce motif, il exécute la méthode associée à l'annotation sinon il envoi un code  404.

Si plusieurs définitions de route correspondent au même chemin, c'est la **première route** rencontrée qui est prioritaire. Le routeur s'arrête dès qu'il a trouvé une correspondance.

> L'annotation `@Route` peut également prendre un name en paramètre. Même si cela est optionnel, il est préférable de  systématiquement nommer les routes.

Il existe une commande symfony qui permet de lister les routes : `php bin/console debug:router`
