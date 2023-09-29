# EventLinker

EventLinker est une application web Symfony qui permet aux utilisateurs de créer, gérer et s'inscrire à des événements. Cette application a été développée en suivant les bonnes pratiques de Symfony.

## Utilisation de Webpack Encore
Utilisation de Webpack Encore pour gérer l'intégration du système de gestion des actifs (assets). Webpack Encore facilite la gestion des fichiers CSS, JavaScript et d'autres ressources dans Symfony en simplifiant leur compilation et leur inclusion dans les templates Twig. Cette approche permet de bénéficier d'une organisation claire des ressources front-end et de tirer parti de nombreuses fonctionnalités modernes pour la création de sites web dynamiques.

## Controller Security à Part
Séparation de la gestion de la sécurité de l'application en utilisant un contrôleur dédié, SecurityController. Concentration de toutes les fonctionnalités liées à l'authentification et à l'autorisation dans un seul endroit. Le contrôleur de sécurité gère les actions d'inscription, de connexion et de déconnexion, ce qui facilite la gestion de ces fonctionnalités essentielles.

## Fonction Twig isUserRegistered()
Création d'une fonction Twig personnalisée appelée isUserRegistered() pour vérifier si un utilisateur est inscrit à un événement spécifique. Cette fonction facilite la génération conditionnelle de contenu dans les templates Twig en fonction de l'état d'inscription de l'utilisateur. Par exemple, affichage d'un bouton "S'inscrire" ou "Se désinscrire" en fonction de cette vérification.

## Installation

1. Clonez ce dépôt sur votre machine locale :

   ```bash
   git clone https://github.com/votre-utilisateur/event-linker.git

2. Accédez au répertoire du projet :

    ```bash
    cd event-linker

3. Installez les dépendances avec Composer :

    ```bash
    composer install

4. Créez la base de données et chargez les fixtures (assurez-vous que la configuration de la base de données est correcte dans .env ou .env.local.php en mode production) :

    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    php bin/console doctrine:fixtures:load

5. Lancez le serveur de développement :

    ```bash
    symfony serve
    npm run watch

6. Accédez à l'application dans votre navigateur à l'adresse http://localhost:8000.

## Fonctionnalités
Création, modification et suppression d'événements.
Inscription et désinscription à des événements.
Filtrage des événements par date et catégorie.
Gestion des utilisateurs (inscription, connexion, etc.).
Tests Unitaires

## Tests Unitaires
L'application EventLinker est livrée avec des tests unitaires pour garantir la stabilité et la fiabilité du code. Vous pouvez exécuter les tests avec PHPUnit :

    ```bash
    php bin/phpunit

## Contributeurs

    - quentin Henno

