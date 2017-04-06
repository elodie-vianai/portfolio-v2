<?php
// 1) CHARGEMENT DE L'AUTOLOADER DE PHP
    require '../vendor/autoload.php';

// 2) APPEL DES STANDARD PHP POUR LA MESSAGERIE HTTP
    use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// 3) SETTING POUR BIEN AFFICHER LES ERREURS
    $configuration = [
        'settings' => [
            'displayErrorDetails' => true,
        ],
    ];
    $c = new \Slim\Container($configuration);

// 4) INITIALISATION DE L'APPLICATION
    $app = new \Slim\App($c);

// APPEL $src->get() EST LE PREMIER ITINÉRAIRE, ici on obtient le conteneur
    $container = $app->getContainer();
    $container['view'] = // Register component on container
        // création des vues PHP (Views)
    $container['view'] = function ($container) {
        $view = new \Slim\Views\Twig('../src/Views', [
            'cache' => false
        ]);

// INSTANCIER ET AJOUTER UNE EXTENSION SPÉCIFIQUE Slim, ici modifie l'url
        $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
        $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

// INDIQUE QUE LA CONFIGURATION EST TERMINÉE ET QU'ON PEUT PASSER A L'ÉVENEMENT PRINCIPAL
        return $view;
    };

// AJOUT DES ROUTES (chemin/url), apparaît dans l'ordre dans lequel c'est décrit mais le plus spécifique en premier
        /*
            - get() = la route est uniquement disponible pour une requête GET
            - $request contient toutes les informations sur la demande entrante (en-têtes, variables...)
            - $response = permet de transformer la sortie en réponse HTTP
        */
    $app->get('/', function (Request $request, Response $response) {
        /*Définit le chemin de notre application :
            - $this fait référence à $src,
            - view aux Vues PHP,
            - render nécessite 3 arguments : la $response à utiliser, le fichier modèle (répertoire) et les données que nous devons lui transmettre
            - le chemin et nom du fichier qui est dans le dossier "Pages"
        */
        return $this->view->render($response, 'Pages/index.html.twig');

        return $response;
    });


// PARTIE PUBLIQUE
    $app->get('/experiences', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/experiences.html.twig');
        return $response;
    });

    $app->get('/formation', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/formation.html.twig');
        return $response;
    });

    $app->get('/detailprojet', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/detailProjet.html.twig');
        return $response;
    });

    $app->get('/inscription', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/inscription.html.twig');
        return $response;
    });

    $app->get('/connexion', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/connexion.html.twig');
        return $response;
    });



// PARTIE ADMINISTRATION

    $app->get('/admin', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/admin.html.twig');
        return $response;
    });

    // Gestion des expériences
    $app->get('/admin/gestiondesexperiences', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/gestiondesexperiences.html.twig');
        return $response;
    });
    $app->get('/admin/gestiondesexperiences/ajouter', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/gestiondesexperiences_ajouter.html.twig');
        return $response;
    });
    $app->get('/admin/gestiondesexperiences/modifier', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/gestiondesexperiences_modifier.html.twig');
        return $response;
    });

    // Gestion des formations
    $app->get('/admin/gestiondesformations', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/gestiondesformations.html.twig');
        return $response;
    });
    $app->get('/admin/gestiondesformations/ajouter', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/gestiondesformations_ajouter.html.twig');
        return $response;
    });
    $app->get('/admin/gestiondesformations/modifier', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/gestiondesformations_modifier.html.twig');
        return $response;
    });

    // Gestion des projets
    $app->get('/admin/gestiondesprojets', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/gestiondesprojets.html.twig');
        return $response;
    });
    $app->get('/admin/gestiondesprojets/ajouter', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/gestiondesprojets_ajouter.html.twig');
        return $response;
    });
    $app->get('/admin/gestiondesprojets/modifier', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/gestiondesprojets_modifier.html.twig');
        return $response;
    });

    // Gestion des technologies
    $app->get('/admin/gestiondestechnologies', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/gestiondestechnologies.html.twig');
        return $response;
    });
    $app->get('/admin/gestiondestechnologies/ajouter', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/gestiondestechnologies_ajouter.html.twig');
        return $response;
    });
    $app->get('/admin/gestiondestechnologies/modifier', function (Request $request, Response $response) {
        return $this->view->render($response, 'Pages/gestiondestechnologies_modifier.html.twig');
        return $response;
    });

// SIGNIFIE QU'IL FAUT LANCER L'APPLICATION
    $app->run();