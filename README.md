# Inflow2.0

Site web pour le projet Inflow

## Frameworks

- [Symfony](https://symfony.com/)

## Languages

- [PHP 8.2.0](https://www.php.net/)
- [JS](https://developer.mozilla.org/fr/docs/Web/JavaScript)


### Others

- [Composer](https://getcomposer.org/)
- [Twig](https://twig.symfony.com/doc/)
- [Scoop](https://scoop.sh/)


### Installation

Installer Scoop :
```
Set-ExecutionPolicy RemoteSigned -Scope CurrentUser
irm get.scoop.sh | iex
```
Installer Symfony (besoin de Scoop) :
```
scoop install symfony-cli
```

Se placer en premier lieu dans le dossier contenant la racine du projet et cloner le projet  :

```
cd projects/
gh repo clone eraflo/Inflow2.0
```

Puis, utilisation de composer (et se placer dans le projet) :
```
cd my-project/
composer install
```

Besoin de :
- Webpack Encore bundle : ```composer require symfony/webpack-encore-bundle```
- Maker Bundle : ```composer require --dev symfony/maker-bundle```
- Form Validator : ```composer require form validator```


Si composer n'est pas installé, voici le lien de l'exécutable : [Composer.exe](https://getcomposer.org/Composer-Setup.exe)

## Utile
### Lancement du server via Symfony
```
symfony server:start
```
### Arrêter le server
```
symfony server:stop
```

### Récupérer les routes
```
php bin/console debug:router
```

### Importer Moteur de Template Twig
```
composer require twig
```

### Important : Ne pas supprimer ou renommer via vs code, le faire en ligne de commande

- Supprimer
```
del \chemin\fichierasupprimer
```

- Renommer
```
ren ancienNom nouveauNom
```

### Tester une requête SQL
```
php bin/console dbal:run-sql 'SELECT * FROM users'
```

### Création d'un formulaire
```
symfony console make:form InscriptionFormType Users
```
avec InscriptionFormType le nom de la classe du formulaire (créée dans le dossier src/Form) et Users le nom de la classe Entity que l'on va remplir avec les données du formulaire

### Création du login
```
php bin/console make:auth 
```
