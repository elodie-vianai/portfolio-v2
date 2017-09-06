<?php
#region /******************************* 1) CHARGEMENT DE L'AUTOLOADER DE PHP *******************************************/
require '../vendor/autoload.php';
#endregion


#region /******************************* 2)APPEL DES STANDARD PHP POUR LA MESSAGERIE HTTP *******************************/
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
#endregion


#region /******************************* 3) SETTING POUR BIEN AFFICHER LES ERREURS **************************************/
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);
#endregion


#region /******************************* 4) INITIALISATION DE L'APPLICATION *********************************************/

session_start();

$app = new \Slim\App($c);

$container = $app->getContainer();                          // Appel $src->get() est le premier itinéraire, ici on obtient le conteneur

$container['view'] = function ($container) {                // création des vues PHP (Views)
    $view = new \Slim\Views\Twig('../src/Views', [
        'cache' => false
    ]);
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');  // Instancier et ajouter une extension spécifique Slim, ici modifie l'url
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    $view->getEnvironment()->addGlobal('session', $_SESSION);
    return $view;       // Indique que la configuration est terminée et qu'on peut passer à l'évènement principal
};
#endregion


#region /******************************* 5) AJOUT DES ROUTES ************************************************************/
$app->get('/', 'Portfolio\Controller\PagesController:indexAction');


#region --------- PARTIE PUBLIQUE ---------
$app->get('/experiences', 'Portfolio\Controller\PagesController:experiencesAction');
$app->get('/formation', 'Portfolio\Controller\PagesController:formationAction');
$app->get('/detailprojet', 'Portfolio\Controller\PagesController:detailProjetAction');
$app->get('/inscription', 'Portfolio\Controller\PagesController:inscriptionPage');
$app->post('/connexion', 'Portfolio\Controller\PagesController:inscriptionAction');
$app->get('/connexion', 'Portfolio\Controller\PagesController:connexionPage');
$app->get('/spotify', 'Portfolio\Controller\PagesController:spotifyPage');
$app->get('/instagram', 'Portfolio\Controller\PagesController:InstagramPage');
$app->post('/connecting', 'Portfolio\Controller\PagesController:connexionAction');
//$app->get('/contact', 'Portfolio\Controller\PagesController:contactPage');
$app->map(['get', 'post'], '/contact', 'Portfolio\Controller\PagesController:contactAction');
#endregion


#region --------- PARTIE PUBLIQUE CONNECTÉE ---------
$app->group('/user', function () {
    $this->get('/{id}', 'Portfolio\Controller\PagesController:indexAction');
    $this->get('/{id}/experiences', 'Portfolio\Controller\PagesController:experiencesAction');
    $this->get('/{id}/formation', 'Portfolio\Controller\PagesController:formationAction');
    $this->get('/{id}/detailprojet', 'Portfolio\Controller\PagesController:detailProjetAction');
    // Afficher les informations de son compte utilisateur
    $this->get('/{id}/moncompte', 'Portfolio\Controller\PagesController:monCompteAction');
    // Modifier les informations du compte utilisateur
    $this->map(['get', 'post'], '/{id}/moncompte/modifier', 'Portfolio\Controller\PagesController:modifierUtilisateurAction');
    // Supprimer un utilisateur
    $this->get('/{id}/supprimer', 'Portfolio\Controller\PagesController:supprimerUtilisateurAction');
    $this->get('/{id}/playlist/{idPlaylist}', 'Portfolio\Controller\PagesController:playlistAction');
    $this->get('/{id}/playlist/{idPlaylist}/{idTrack}', 'Portfolio\Controller\PagesController:TrackAction');
});
#endregion


#region --------- PARTIE ADMINSITRATION ---------
$app->group('/admin', function () {
    $this->get('', 'Portfolio\Controller\PagesController:adminAction');
    $this->get('/playlist/{idPlaylist}', 'Portfolio\Controller\PagesController:playlistAction');
    $this->get('/playlist/{idPlaylist}/{idTrack}', 'Portfolio\Controller\PagesController:TrackAction');

    #region --> Gestion des expériences
    //CRUD
    $this->get('/gestiondesexperiences', 'Portfolio\Controller\PagesController:CRUDExperiences');
    // Ajouter une nouvelle expérience
    $this->get('/gestiondesexperiences/ajouter', 'Portfolio\Controller\PagesController:formulaireAjoutExperience');
    $this->post('/gestiondesexperiences', 'Portfolio\Controller\PagesController:ajouterExperienceAction');
    // Modifier une expérience
    $this->map(['get', 'post'], '/gestiondesexperiences/modifier', 'Portfolio\Controller\PagesController:modifierExperienceAction');
    // Supprimer une expérience
    $this->get('/gestiondesexperiences/supprimer/{id}', 'Portfolio\Controller\PagesController:supprimerExperienceAction');
    #endregion

    #region --> Gestion des formations
    //CRUD
    $this->get('/gestiondesformations', 'Portfolio\Controller\PagesController:CRUDFormations');
    // Ajouter une nouvelle formation
    $this->get('/gestiondesformations/ajouter', 'Portfolio\Controller\PagesController:formulaireAjoutFormation');
    $this->post('/gestiondesformations', 'Portfolio\Controller\PagesController:ajouterFormationAction');
    // Modifier une formation
    $this->map(['get', 'post'], '/gestiondesformations/modifier', 'Portfolio\Controller\PagesController:modifierFormationAction');
    // Supprimer une formation
    $this->get('/gestiondesformations/supprimer/{id}', 'Portfolio\Controller\PagesController:supprimerFormationAction');
    #endregion

    #region --> Gestion des projets
    //CRUD
    $this->get('/gestiondesprojets', 'Portfolio\Controller\PagesController:CRUDProjets');
    // Détail projet
    $this->get('/gestiondesprojets/detail_projet', 'Portfolio\Controller\PagesController:AdmindetailProjet');
    // Ajouter un nouveau projet
    $this->get('/gestiondesprojets/ajouter', 'Portfolio\Controller\PagesController:formulaireAjoutProjet');
    $this->post('/gestiondesprojets', 'Portfolio\Controller\PagesController:ajouterProjetAction');
    // Modifier un projet
    $this->map(['get', 'post'], '/gestiondesprojets/modifier', 'Portfolio\Controller\PagesController:modifierProjetAction');
    // Supprimer un projet
    $this->get('/gestiondesprojets/supprimer/{id}', 'Portfolio\Controller\PagesController:supprimerProjetAction');
#endregion

    #region --> Gestion des technologies
    //CRUD
    $this->get('/gestiondestechnologies', 'Portfolio\Controller\PagesController:CRUDTechnologies');
    // Ajouter une nouvelle technologie
    $this->get('/gestiondestechnologies/ajouter', 'Portfolio\Controller\PagesController:formulaireAjoutTechnologie');
    $this->post('/gestiondestechnologies', 'Portfolio\Controller\PagesController:ajouterTechnologieAction');
    // Modifier une technologie
    $this->map(['get', 'post'], '/gestiondestechnologies/modifier', 'Portfolio\Controller\PagesController:modifierTechnologieAction');
    // Supprimer une technologie
    $this->get('/gestiondestechnologies/supprimer/{id}', 'Portfolio\Controller\PagesController:supprimerTechnologieAction');
    #endregion

    #region --> Gestion des utilisateurs
    //CRUD
    $this->get('/gestiondesutilisateurs', 'Portfolio\Controller\PagesController:CRUDUtilisateurs');
    // Modifier un utilisateur
    $this->map(['get', 'post'], '/gestiondesutilisateurs/modifier', 'Portfolio\Controller\PagesController:modifierUtilisateurAdminAction');
    // Supprimer un utilisateur
    $this->get('/gestiondesutilisateurs/supprimer/{id}', 'Portfolio\Controller\PagesController:supprimerUtilisateurAdminAction');
    #endregion
})
    /*->add(function ($request, Response $respones, $next) {
        if(!empty($_SESSION['user'])) {
            return $next; // faire la redirection
        }
    return $response-> //redirect ou redirect vers page 403
    })*/;
#endregion

$app->get('/disconnect', 'Portfolio\Controller\PagesController:deconnexionAction');

#endregion


#region /******************************* 6) LANCEMENT DE L'APPLICATION **************************************************/
$app->run();
#endregion