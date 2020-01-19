# Initialisation d'un projet Symfony 4

## Condition

+ PHP 7.1
+ Composer

## Installation de Symfony cli

``` bash
# Téléchargement
wget https://get.symfony.com/cli/installer -O - | bash

# Déplacer le fichier pour l'installer globalement
sudo mv /home/TonNomUtilisateurLinux/.symfony/bin/symfony /usr/local/bin/symfony

# vérification
symfony -v
```

## Initialisation

```bash
symfony new --full name_project --version=lts
```

[Installing & Setting up the Symfony Framework](https://symfony.com/doc/4.4/setup.html)  
[Checking out the Project Structure](https://symfony.com/doc/4.4/page_creation.html#checking-out-the-project-structure)

## Démarrer / arrêter  le serveur

```bash
# start en mode detaché
symfony server:start -d

# stop
symfony server:stop
```

[Symfony Local Web Server](https://symfony.com/doc/4.4/setup/symfony_server.html)
