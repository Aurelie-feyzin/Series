# Authentification et securité

## Création des utilisateurs

Symfony permet de créer la class *User* facilement pour cela il faut installer le composant `symfony/security-bundle` puis utiiser la commande `php bin/console make:user`.
Une série de question s'affiche:

```bash
The name of the security user class (e.g. User) [User]:
> User

Do you want to store user data in the database (via Doctrine)? (yes/no) [yes]:
> yes

Enter a property name that will be the unique "display" name for the user (e.g.
email, username, uuid [email]
> email

Does this app need to hash/check user passwords? (yes/no) [yes]:
> yes

created: src/Entity/User.php
created: src/Repository/UserRepository.php
updated: src/Entity/User.php
updated: config/packages/security.yaml
 ```

[Symfony User Class](https://symfony.com/doc/4.4/security.html#security-installation)

## Configuration de la gestion du login

Pour cela on utilise la commande `php bin/console make:auth` qui npose quelques questions :

```bash
What style of authentication do you want? [Empty authenticator]:
 [0] Empty authenticator
 [1] Login form authenticator
> 1

The class name of the authenticator to create (e.g. AppCustomAuthenticator):
> LoginFormAuthenticator

Choose a name for the controller class (e.g. SecurityController) [SecurityController]:
> SecurityController

Do you want to generate a '/logout' URL? (yes/no) [yes]:
> yes

 created: src/Security/LoginFormAuthenticator.php
 updated: config/packages/security.yaml
 created: src/Controller/SecurityController.php
 created: templates/security/login.html.twig
 ```

 Dans la méthode `onAuthenticationSuccess` de la classe `LoginFormAuthenticator.php` il est necessaire de remlacer le  `throw new` par une redirection vers la page souhaitée.

 ```php
 public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }
```

Il se peux qu'il y ait d'autre *TODO* a modifier dans ce fichier.

[Symfony Login Form](https://symfony.com/doc/4.4/security/form_login_setup.html)

### Chiffrement mot de passe

Il est possible de chiffrer un mot de passe avec la commande `php bin/console security:encode-password` (par exemple pour les fixtures)

[Chiffer un mdp](https://www.youtube.com/watch?v=wAiSu6oiK-Q&feature=youtu.be)

### Logout

Si le logout n'a pas été fait automatiquement suivre la [documentation](https://symfony.com/doc/4.4/security.html#logging-out)

### ajout dans la navbar

```twig
{% if app.user %}
    {{ app.user.email }}
    <a href="{{ path('app_logout') }}"> Se déconnecter</a>
{% else %}
    <a href="{{ path('app_login') }}"> Se connecter</a>
{% endif %}
```

## Gestion des rôles

Le nombre de rôles est libre, la nomenclature a respecter est UPPER_SNAKE_CASE, préfixés avec ROLE_.

Les rôles sont definis dans le fichier security.yaml:

```yaml
# config/packages/security.yaml
security:
    # ...

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: [ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]
```

[Symfony User logger](https://symfony.com/doc/4.4/security.html#checking-to-see-if-a-user-is-logged-in-is-authenticated-fully)
[Symfony Rôles](https://symfony.com/doc/4.4/security.html#hierarchical-roles)

## Sécurisation

### security.yaml

C'est dans la partie access_control du fichier que la configuration se fait.

```yaml
# config/packages/security.yaml
security:
    # ...

    access_control:
        # - { path: ^/admin, roles: ROLE_ADMIN }
        # - { path: ^/profile, roles: ROLE_USER }

```

[Symfony Securité Config Ref](https://symfony.com/doc/4.4/reference/configuration/security.html)

### Contrôlleur

Il est possible d'utiliser l'annotation `@IsGranted("ROLE_")`

[Symfony @Security & @IsGranted](https://symfony.com/doc/4.4/bundles/SensioFrameworkExtraBundle/annotations/security.html)

### Template

```twig
{% if is_granted(ROLE_ADMIN) %}
    <a href="{{ path('program_edit', { 'id': program.id} ) }}">Éditer</a>
{%endif%}
```

### Complément

[Symfony & Voters](https://symfony.com/doc/4.4/security/voters.html)
