<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 06/04/2017
 * Time: 14:46
 */

namespace Portfolio\Controller;

use Interop\Container\ContainerInterface;
use Portfolio\Controller\Admin\ExperiencesControllerAdmin;
use Portfolio\Controller\Admin\FormationsControllerAdmin;
use Portfolio\Controller\Admin\ProjectsControllerAdmin;
use Portfolio\Controller\Admin\TechnologiesControllerAdmin;
use Portfolio\Controller\Admin\UtilisateursControllerAdmin;
use Portfolio\Models\UsersModel;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use MetzWeb\Instagram\Instagram;


class PagesController
{
#region /******************************* ATTRIBUTS **********************************************************************/
    protected $container;
#endregion


#region /******************************* CONSTRUCTORS *******************************************************************/
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
#endregion




#region /*******************************  INDEX *************************************************************************/
    public function indexAction(Request $request, Response $response)
    {
        $projetsController = new ProjectsController();
        $projets = $projetsController->get4LastProjects();
        $UsersModel = new UsersModel();
        if (!empty($_SESSION)) {
// API Spotify
            $api = new SpotifyWebAPI();
            $api->setAccessToken($_SESSION['spotify_token']);
            // récupère la liste des playlists publiques
            $listPlaylists = $api->getUserPlaylists('1157591261', ['limit' => 5]);
            $playlists = [];
            foreach ($listPlaylists->items as $item) {
                $temp = [];
                $tempId[] = $item->id;
                foreach ($tempId as $i) {
                    $temp['idPlaylist'] = $i;
                }
                $tempName[] = $item->name;
                foreach ($tempName as $i) {
                    $temp['namePlaylist'] = $i;
                }
                $tempHref[] = $item->href;
                foreach ($tempHref as $i) {
                    $temp['hrefPlaylist'] = $i;
                }
                $playlists[] = $temp;
            }
            return $this->container->get('view')->render($response, 'Pages/index.html.twig',
                ['projets' => $projets, 'playlists' => $playlists]);
        }
        else {
            return $this->container->get('view')->render($response, 'Pages/index.html.twig',
                ['projets' => $projets]);
        }
    }

    /*Définit le chemin de notre application :
                - $this fait référence à $src,
                - view aux Vues PHP,
                - render nécessite 3 arguments : la $response à utiliser, le fichier modèle (répertoire) et les données que nous devons lui transmettre
                - le chemin et nom du fichier qui est dans le dossier "Pages"*/
#endregion


#region /*******************************  PARTIE PUBLIQUE : expériences *************************************************/
    public function experiencesAction(Request $request, Response $response)
    {
        $experiencesController = new ExperiencesController();
        $experiences = $experiencesController->formatageExperiences();
        $projetsController = new ProjectsController();
        $projets = $projetsController->formatageProjets();
        return $this->container->get('view')->render($response, 'Pages/experiences.html.twig',
            ['experiences' => $experiences, 'projets' => $projets]);
    }
#endregion


#region /*******************************  PARTIE PUBLIQUE : détail d'un projet ******************************************/
    public function detailProjetAction(Request $request, Response $response)
    {
        $idProjet = $request->getQueryParam('id');
        $projetsController = new ProjectsController();
        $projet = $projetsController->formatageProjet($idProjet);
        $technologies = $projetsController->formatageTechnologies($idProjet);
        return $this->container->get('view')->render($response, 'Pages/detailProjet.html.twig',
            ['projet' => $projet, 'technologies' => $technologies]);
    }
#endregion


#region /*******************************  PARTIE PUBLIQUE : formations **************************************************/
    public function formationAction(Request $request, Response $response)
    {
        $formationsController = new FormationsController();
        $formations = $formationsController->displayAllFormations();
        return $this->container->get('view')->render($response, 'Pages/formation.html.twig',
            ['formations' => $formations]);
    }
#endregion


#region /*******************************  PARTIE PUBLIQUE : inscriptions ************************************************/
    public function inscriptionPage(Request $request, Response $response)
    {
        return $this->container->get('view')->render($response, 'Pages/inscription.html.twig');
    }

    public function inscriptionAction(Request $request, Response $response)
    {
        $_POST = $request->getParsedBody();
        $inscriptionController = new ConnexionInscriptionController();
        $results = $inscriptionController->gestionInscription($_POST['username'], $_POST['mdp'], $_POST['mdpC'], $_POST['email']);
        if (isset($results['error'])) {
            return $this->container->get('view')->render($response, 'Pages/inscription.html.twig', ['tabError' => $results]);
        } else {
            return $this->container->get('view')->render($response, 'Pages/connexion.html.twig');
        }
    }
#endregion


#region /*******************************  PARTIE PUBLIQUE : contact *****************************************************/
    public function contactAction(Request $request, Response $response)
    {
        /*$HTTP_POST_VARS = $request->getParsedBody();
        if (!empty($_POST)) {
            if ((empty($_POST['nom'])) OR (empty($_POST['prenom'])) OR (empty($_POST['email'])) OR (empty($_POST['message']))) {
                $tabError = [];
                if (empty($_POST['nom'])) {
                    $tabError['error'] = 'Erreur : vous n\'avez pas renseigné votre nom !';
                }
                if (empty($_POST['prenom'])) {
                    $tabError['error'] = 'Erreur : vous n\'avez pas renseigné votre prénom !';
                }
                if (empty($_POST['email'])) {
                    $tabError['error'] = 'Erreur : vous n\'avez pas renseigné votre email !';
                }
                if (empty($_POST['message'])) {
                    $tabError['error'] = 'Erreur : vous n\'avez pas rien écrit dans la zone de texte !';
                }
                var_dump($tabError);die;
            } else {
                $nom = $HTTP_POST_VARS['nom'];
                $prenom = $HTTP_POST_VARS['prenom'];
                $email = $HTTP_POST_VARS['email'];
                $telephone = $HTTP_POST_VARS['phone'];
                $message_content = str_replace("\n.", "\n..", $HTTP_POST_VARS['message']);
                $subject = 'Un message vous est envoyé depuis Portfolio';
                $to = 'elodie.vianai@gmail.com';
                $headers = 'MIME-Version: 1.0'."\r\n";
                $headers .= 'Content-type: text/plain; charset=iso-8859-1'."\r\n";
                $headers .= 'De la part de : '.htmlspecialchars($prenom).' '.
                    htmlspecialchars($nom).' ('.htmlspecialchars($telephone).') <'.htmlspecialchars($email).'>';
                if (mail($to, $subject, $message_content, $headers)){
                    var_dump('le mail est parti !');die;
                } else {
                    var_dump('erreur'); die;
                }
            }
        }*/
        return $this->container->get('view')->render($response, 'Pages/contact.html.twig');
    }
#endregion


#region /*******************************  PARTIE PUBLIQUE : connexion ***************************************************/
    public function connexionPage(Request $request, Response $response)
    {
        return $this->container->get('view')->render($response, 'Pages/connexion.html.twig');
    }

    public function connexionAction(Request $request, Response $response, $args)
    {
        $_POST = $request->getParsedBody();
        $connexionController = new ConnexionInscriptionController();
        $user = $connexionController->gestionConnexion($_POST['username'], $_POST['mdp']);

        $projetsController = new ProjectsController();
        $projetsController->formatageProjets();

        if (is_array($user)) {
            if ($user['roles'] == 'admin') {
                $username = $user['username'];
                return $response->withRedirect('/admin');
            } else {
                $username = $user['username'];
                $header = $_SESSION['roles'];
                $args['id'] = $_SESSION['id'];
                return $response->withRedirect('/user/' . $args['id']);
            }
        } else {
            $_SESSION['error'] = $user;
            return $response->withRedirect('/connexion');
        }
    }
#endregion


#region /*******************************  PARTIE CONNECTÉE **************************************************************/
    #region --> Modifier un utilisateur
    public function monCompteAction(Request $request, Response $response)
    {
        $id = $_SESSION['id'];        //récupération de l'id pour le formulaire depuis l'URL
        $usersControllerAdmin = new UtilisateursControllerAdmin();
        $user = $usersControllerAdmin->getUser($id);

        return $this->container->get('view')->render($response, 'Pages/moncompte.html.twig', ['user'=>$user]);
    }
    #endregion

    #region --> Modifier un utilisateur
    /**
     * @param Request $request
     * @param Response $response
     * @param $argumentsURL
     * @return mixed
     */
    public function modifierUtilisateurAction(Request $request, Response $response, $argumentsURL)
    {
        $id = $request->getQueryParam('id');        //récupération de l'id pour le formulaire depuis l'URL
        $_POST = $request->getParsedBody();             // initialisation du POST + préparation pour récupérer les données
        $usersControllerAdmin = new UtilisateursControllerAdmin();
        $user = $usersControllerAdmin->getUser($id);       // affichage des infos selon l'ID
        if (isset($_POST)) {        // si le POST existe
            $usersControllerAdmin->updateUser($id, $_POST['username'], $_POST['email'], $_POST['password']);  // modification des données (3 paramètres)
            return $response->withRedirect('/user/'.$id.'/moncompte');       //redirection vers le CRUD
        }
        return $this->container->get('view')->render($response, 'Pages/modifier_utilisateur.html.twig',
            ['user' => $user]);
    }
    #endregion

    #region --> Supprimer son compte
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return mixed
     */
    public function supprimerUtilisateurAction(Request $request, Response $response, $args)
    {
        $_POST = $request->getParsedBody();
        $idUser = $args['id'];
        $usersControllerAdmin = new UtilisateursControllerAdmin();
        $usersControllerAdmin->deleteUser($idUser);
        session_destroy();
        return $response->withRedirect('/');
    }
    #endregion
#endregion


#region /*******************************  PARTIE PUBLIQUE : déconnexion ***************************************************/
    public function deconnexionAction(Request $request, Response $response)
    {
        session_destroy();
        return $response->withRedirect('/');
    }
#endregion




#region /*******************************  PARTIE ADMINISTRATION : accueil ***********************************************/
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function AdminAction(Request $request, Response $response)
    {
        $projetsControllerAdmin = new ProjectsControllerAdmin();
        $projets = $projetsControllerAdmin->get4LastProjects();
// API Spotify
        $api = new SpotifyWebAPI();
        $api->setAccessToken($_SESSION['spotify_token']);
        // récupère la liste des playlists publiques
        $listPlaylists = $api->getUserPlaylists('1157591261', ['limit' => 5]);
        $playlists = [];
        foreach ($listPlaylists->items as $item) {
            $temp = [];
            $tempId[] = $item->id;
            foreach ($tempId as $i) {
                $temp['idPlaylist'] = $i;
            }
            $tempName[] = $item->name;
            foreach ($tempName as $i) {
                $temp['namePlaylist'] = $i;
            }
            $tempHref[] = $item->href;
            foreach ($tempHref as $i) {
                $temp['hrefPlaylist'] = $i;
            }
            $playlists[] = $temp;
        }
        return $this->container->get('view')->render($response, 'Admin/admin.html.twig',
            ['projets' => $projets, 'playlists' => $playlists]);
    }
#endregion


#region /*******************************  PARTIE ADMINISTRATION : expériences *******************************************/
    #region --> CRUD
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function CRUDExperiences(Request $request, Response $response)
    {
        // répcupération de toutes les expériences
        $experiencesControllerAdmin = new ExperiencesControllerAdmin();
        $tabExperiences = $experiencesControllerAdmin->displayAllExperiences();
        //récupération de tous les projets en fonction des expériences
        $projectsControllerAdmin = new ProjectsControllerAdmin();
        $experiences = [];
        foreach ($tabExperiences as $exp) {
            $projets = $projectsControllerAdmin->getExperienceHasProjects($exp['id']);
            $exp['projets'] = $projets;
            $experiences[] = $exp;
        }
        return $this->container->get('view')->render($response, 'Admin/Experiences/CRUD_experiences.html.twig',
            ['experiences' => $experiences]);
    }
    #endregion

    #region --> Ajout d'une nouvelle expérience
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function formulaireAjoutExperience(Request $request, Response $response)
    {
        // affichage du select des départements
        $departementsController = new DepartementsController();
        $departements = $departementsController->displayAllDep();
        // affichage du select des projets
        $projectsControllerAdmin = new ProjectsControllerAdmin();
        $projets = $projectsControllerAdmin->displayAllProjects();
        return $this->container->get('view')->render($response, 'Admin/Experiences/ajouter_experience.html.twig',
            ['departements' => $departements, 'projets' => $projets]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function ajouterExperienceAction(Request $request, Response $response)
    {
        $_POST = $request->getParsedBody();
        if (isset($_POST)) {
            if (empty($_POST['end_at'])) {
                $dtz = new \DateTimeZone('Europe/Paris');
                $datetime = new \DateTime();
                $datetime->setTimezone($dtz);
                $_POST['end_at'] = $datetime->format('Y-m-d');
            }
            $experiencesControllerAdmin = new ExperiencesControllerAdmin();
            $projectsControllerAdmin = new ProjectsControllerAdmin();
            // Ajout de la nouvelle expérience puis de ses projets
            $results = $experiencesControllerAdmin->addExperience($_POST['begin_at'], $_POST['end_at'], $_POST['contrat'],
                $_POST['name'], $_POST['entreprise'], $_POST['ville'], $_FILES['logo']['name'], $_POST['dep_id']);
            $projets = $_POST['projets'];
            if (isset($results['id'])) {
                $projectsControllerAdmin->addExperienceHasProject($results['id'], $projets);
            }
            // Récupération de toutes les expériences dans la BDD pour le CRUD à venir
            $tabExperiences = $experiencesControllerAdmin->displayAllExperiences();
            // Récupération des projets associés à chaque expérience
            $experiences = [];
            foreach ($tabExperiences as $experience) {
                $projets = $projectsControllerAdmin->getExperienceHasProjects($experience['id']);
                $experience['projets'] = $projets;
                $experiences[] = $experience;
            }
            return $this->container->get('view')->render($response, 'Admin/Experiences/CRUD_experiences.html.twig',
                ['experiences' => $experiences, 'tabError' => $results]);
        } else {
            $departementsController = new DepartementsController();
            $departements = $departementsController->displayAllDep();
            $projectsControllerAdmin = new ProjectsControllerAdmin();
            $projets = $projectsControllerAdmin->displayAllProjects();
            return $this->container->get('view')->render($response, 'Admin/Experiences/ajouter_experience.html.twig',
                ['departements' => $departements, 'projets' => $projets]);
        }
    }
    #endregion

    #region --> Modifier une expérience
    /**
     * @param Request $request
     * @param Response $response
     * @return string
     */
    public function modifierExperienceAction(Request $request, Response $response)
    {
        $id = $request->getQueryParam('id');
// initialisation du POST + préparation pour récupérer les données
        $_POST = $request->getParsedBody();
// récupération de tous les départements pour le select
        $departementsController = new DepartementsController();
        $departements = $departementsController->displayAllDep();
// affichage du select des projets
        $projectsControllerAdmin = new ProjectsControllerAdmin();
        $Allprojects = $projectsControllerAdmin->displayAllProjects();
// récupération des informations de l'expérience
        $experienceControllerAdmin = new ExperiencesControllerAdmin();
        $experience = $experienceControllerAdmin->getExperience($id);
        $tabProjectsExperience = $experienceControllerAdmin->getExperienceHasProjects($id);
// Récupération des id des projets associées à l'expérience pour les sélectionner automatiquement
        $projectsExperience = [];
        foreach ($tabProjectsExperience as $exp) {
            $projectsExperience[] = $exp['id'];
        }

        if (isset($_POST)) {        // si le POST existe
            $_POST['id'] = $id;
            if (empty($_POST['end_at'])) {
                $dtz = new \DateTimeZone('Europe/Paris');
                $datetime = new \DateTime();
                $datetime->setTimezone($dtz);
                $_POST['end_at'] = $datetime->format('Y-m-d');
            }
// Modification des informations du projet
            var_dump($_POST);
            $experienceControllerAdmin->updateExperience($_POST['id'], $_POST['name'], $_POST['contrat'], $_POST['entreprise'],
                $_POST['ville'], $_POST['dep_id'], $_POST['begin_at'], $_POST['end_at'], $_FILES['logo']['name']);
// Modification des projets asscociés
            $projets = $_POST['projets'];
            $projectsControllerAdmin->updateExperienceHasProject($id, $projets);
            return $response->withRedirect('/admin/gestiondesexperiences');       //redirection vers le CRUD
        }
        return $this->container->get('view')->render($response, 'Admin/Experiences/modifier_experience.html.twig',
            ['departements' => $departements, 'experience' => $experience, 'Allprojects' => $Allprojects, 'projectsExperience' => $projectsExperience]);
    }
    #endregion

    #region --> Supprimer une expérience
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function supprimerExperienceAction(Request $request, Response $response, $args)
    {
        $_POST = $request->getParsedBody();
        $id = $args['id'];
        $experiencesControllerAdmin = new ExperiencesControllerAdmin();
        $experiencesControllerAdmin->deleteExperience($id);
        return $response->withRedirect('/admin/gestiondesexperiences');
    }
    #endregion
#endregion


#region /*******************************  PARTIE ADMINISTRATION : formations ********************************************/
    #region --> CRUD
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function CRUDFormations(Request $request, Response $response)
    {
        $formationsControllerAdmin = new FormationsControllerAdmin();
        $formations = $formationsControllerAdmin->displayAllFormations();
        return $this->container->get('view')->render($response, 'Admin/Formations/CRUD_formations.html.twig',
            ['formations' => $formations]);
    }
    #endregion

    #region --> Ajouter une formation
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function formulaireAjoutFormation(Request $request, Response $response)
    {
        // affichage du select des départements
        $departementsController = new DepartementsController();
        $departements = $departementsController->displayAllDep();
        return $this->container->get('view')->render($response, 'Admin/Formations/ajouter_formation.html.twig',
            ['departements' => $departements]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function ajouterFormationAction(Request $request, Response $response)
    {
        $_POST = $request->getParsedBody();
        if (isset($_POST)) {
            if (empty($_POST['end_at'])) {
                $dtz = new \DateTimeZone('Europe/Paris');
                $datetime = new \DateTime();
                $datetime->setTimezone($dtz);
                $_POST['end_at'] = $datetime->format('Y-m-d');
            }
            $formationsControllerAdmin = new FormationsControllerAdmin();
            $results = $formationsControllerAdmin->addFormation($_POST['name'], $_POST['type'], $_POST['etablissement'], $_POST['ville'],
                $_POST['begin_at'], $_POST['end_at'], $_FILES['logo']['name'], $_POST['mention'], $_POST['dep']);
            $formations = $formationsControllerAdmin->displayAllFormations();
            return $this->container->get('view')->render($response, 'Admin/Formations/CRUD_formations.html.twig',
                ['formations' => $formations, 'tabError' => $results]);
        } else {
            $departementsController = new DepartementsController();
            $departements = $departementsController->displayAllDep();
            return $this->container->get('view')->render($response, 'Admin/Formations/ajouter_formation.html.twig',
                ['departements' => $departements]);
        }
    }
    #endregion

    #region --> Modifier une formation
    public function modifierFormationAction(Request $request, Response $response, $argumentsURL)
    {
        $id = $request->getQueryParam('id');        //récupération de l'id pour le formulaire depuis l'URL
        $_POST = $request->getParsedBody();             // initialisation du POST + préparation pour récupérer les données
        $departementsController = new DepartementsController();
        $departements = $departementsController->displayAllDep();
        $formationsControllerAdmin = new FormationsControllerAdmin();
        $formation = $formationsControllerAdmin->getFormation($id);       // affichage des infos selon l'ID
        if (isset($_POST)) {        // si le POST existe
            $_POST['id'] = $id;
            if (empty($_POST['end_at'])) {
                $dtz = new \DateTimeZone('Europe/Paris');
                $datetime = new \DateTime();
                $datetime->setTimezone($dtz);
                $_POST['end_at'] = $datetime->format('Y-m-d');
            }
            $formationsControllerAdmin->updateFormation($_POST['id'], $_POST['name'], $_POST['type'],
                $_POST['etablissement'], $_POST['ville'], $_POST['begin_at'], $_POST['end_at'], $_FILES['logo']['name'],
                $_POST['mention'], $_POST['dep_id']);
            return $response->withRedirect('/admin/gestiondesformations');       //redirection vers le CRUD
        }
        return $this->container->get('view')->render($response, 'Admin/Formations/modifier_formation.html.twig',
            ['departements' => $departements, 'formation' => $formation]);
    }
    #endregion

    #region --> Supprimer une formation
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function supprimerFormationAction(Request $request, Response $response, $args)
    {
        $_POST = $request->getParsedBody();
        $id = $args['id'];
        $formationsControllerAdmin = new FormationsControllerAdmin();
        $formationsControllerAdmin->deleteFormation($id);
        return $response->withRedirect('/admin/gestiondesformations');
    }
    #endregion
#endregion


#region /*******************************  PARTIE ADMINISTRATION : projets ***********************************************/
    #region --> CRUD
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function CRUDProjets(Request $request, Response $response)
    {
        $projectsControllerAdmin = new ProjectsControllerAdmin();
        $tabProjets = $projectsControllerAdmin->displayAllProjects();
        $technologiesControllerAdmin = new TechnologiesControllerAdmin();
        $projets = [];
        foreach ($tabProjets as $projet) {
            $technologies = $technologiesControllerAdmin->ProjectTechnologies($projet['id']);
            $projet['technologies'] = $technologies;
            $projets[] = $projet;
        }
        return $this->container->get('view')->render($response, 'Admin/Projets/CRUD_projets.html.twig',
            ['projets' => $projets]);
    }
    #endregion

    #region --> Détail d'un projet
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function AdmindetailProjet(Request $request, Response $response)
    {
        $idProjet = $request->getQueryParam('id');
        $projectsControllerAdmin = new ProjectsControllerAdmin();
        $projet = $projectsControllerAdmin->formatageProjet($idProjet);
        $technologiesControllerAdmin = new TechnologiesControllerAdmin();
        $technologies = $technologiesControllerAdmin->ProjectTechnologies($idProjet);
        return $this->container->get('view')->render($response, 'Admin/Projets/admin_detail_projet.html.twig',
            ['projet' => $projet, 'technologies' => $technologies]);
    }
    #endregion

    #region --> Ajouter un projet
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function formulaireAjoutProjet(Request $request, Response $response)
    {
        // affichage du select des technologies
        $technologiesControllerAdmin = new TechnologiesControllerAdmin();
        $technologies = $technologiesControllerAdmin->displayAllTechnologies();
        return $this->container->get('view')->render($response, 'Admin/Projets/ajouter_projet.html.twig',
            ['technologies' => $technologies]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function ajouterProjetAction(Request $request, Response $response)
    {
        $_POST = $request->getParsedBody();
        if (isset($_POST)) {
            $projectsControllerAdmin = new ProjectsControllerAdmin();
            $technologiesControllerAdmin = new TechnologiesControllerAdmin();
            // Ajout du nouveau projet puis de ses technologies
            $results = $projectsControllerAdmin->addProject($_POST['name'], $_POST['year'], $_FILES['image_path']['name'],
                $_POST['description']);
            $technologies = $_POST['technologies'];
            if (isset($results['id'])) {
                $technologiesControllerAdmin->addProjectHasTechnology($results['id'], $technologies);
            }
            // Récupération de tous les projets dans la BDD pour le CRUD à venir
            $tabProjets = $projectsControllerAdmin->displayAllProjects();
            // Récupération des technologies associées à chaque projet
            $projets = [];
            foreach ($tabProjets as $projet) {
                $technologies = $technologiesControllerAdmin->ProjectTechnologies($projet['id']);
                $projet['technologies'] = $technologies;
                $projets[] = $projet;
            }
            return $this->container->get('view')->render($response, 'Admin/Projets/CRUD_projets.html.twig',
                ['projets' => $projets, 'tabError' => $results]);
        } else {
            $technologiesControllerAdmin = new TechnologiesControllerAdmin();
            $technologies = $technologiesControllerAdmin->displayAllTechnologies();
            return $this->container->get('view')->render($response, 'Admin/Projets/ajouter_projet.html.twig',
                ['technologies' => $technologies]);
        }

    }
    #endregion

    #region --> Modifier un projet
    /**
     * @param Request $request
     * @param Response $response
     * @return string
     */
    public function modifierProjetAction(Request $request, Response $response)
    {
        $id = $request->getQueryParam('id');
// initialisation du POST + préparation pour récupérer les données
        $_POST = $request->getParsedBody();
// récupération du projet
        $projetControllerAdmin = new ProjectsControllerAdmin();
        $projet = $projetControllerAdmin->getProject($id);
// affichage du select des technologies
        $technologiesControllerAdmin = new TechnologiesControllerAdmin();
        $Alltechnologies = $technologiesControllerAdmin->displayAllTechnologies();
// récupération des id des technologies associées au projet pour les sélectionner automatiquement
        $tabTechnologiesProject = $projetControllerAdmin->formatageTechnologies($id);
        $technologiesProject = [];
        foreach ($tabTechnologiesProject as $techno) {
            $technologiesProject[] = $techno['id'];
        }
        if (isset($_POST)) {        // si le POST existe
            $_POST['id'] = $id; //
// modification des informations du projet
            $projetControllerAdmin->updateProject($_POST['id'], $_POST['name'], $_POST['year'], $_FILES['image_path']['name'],
                $_POST['description']);
// modification des technologies asscociées
            $technologies = $_POST['technologies'];
            $technologiesControllerAdmin->UpdateProjectHasTechnology($id, $technologies);
            return $response->withRedirect('/admin/gestiondesprojets');       //redirection vers le CRUD
        }
        return $this->container->get('view')->render($response, 'Admin/Projets/modifier_projet.html.twig',
            ['projet' => $projet, 'Alltechnologies' => $Alltechnologies, 'technologiesProject' => $technologiesProject]);
    }
    #endregion

    #region --> Supprimer un projet
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function supprimerProjetAction(Request $request, Response $response, $args)
    {
        $_POST = $request->getParsedBody();
        $id = $args['id'];
        $projectsControllerAdmin = new ProjectsControllerAdmin();
        $projectsControllerAdmin->deleteProject($id);
        return $response->withRedirect('/admin/gestiondesprojets');
    }
    #endregion
#endregion


#region /*******************************  PARTIE ADMINISTRATION : technologies ******************************************/
    #region --> CRUD
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function CRUDTechnologies(Request $request, Response $response)
    {
        $technologiesControllerAdmin = new TechnologiesControllerAdmin();
        $technologies = $technologiesControllerAdmin->displayAllTechnologies();
        return $this->container->get('view')->render($response, 'Admin/Technologies/CRUD_technologies.html.twig',
            ['technologies' => $technologies]);
    }
    #endregion

    #region --> Ajouter une technologie
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function formulaireAjoutTechnologie(Request $request, Response $response)
    {
        return $this->container->get('view')->render($response, 'Admin/Technologies/ajouter_technologie.html.twig');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function ajouterTechnologieAction(Request $request, Response $response)
    {
        $_POST = $request->getParsedBody();
        if (isset($_POST)) {
            $technologiesController = new TechnologiesControllerAdmin();
            $taberror = $technologiesController->addTechnology($_POST['nameTechno'], $_FILES['image_path']['name']);
            $technologies = $technologiesController->displayAllTechnologies();
            return $this->container->get('view')->render($response, 'Admin/Technologies/CRUD_technologies.html.twig',
                ['technologies' => $technologies, 'tabError' => $taberror]);
        }

    }
    #endregion

    #region --> Modifier une technologie
    /**
     * @param Request $request
     * @param Response $response
     * @param $argumentsURL
     * @return static
     */
    public function modifierTechnologieAction(Request $request, Response $response, $argumentsURL)
    {
        $id = $request->getQueryParam('id');        //récupération de l'id pour le formulaire depuis l'URL
        $_POST = $request->getParsedBody();             // initialisation du POST + préparation pour récupérer les données
        $technologiesControllerAdmin = new TechnologiesControllerAdmin();
        $technology = $technologiesControllerAdmin->getTechnology($id);       // affichage des infos selon l'ID
        if (isset($_POST)) {        // si le POST existe
            $technologiesControllerAdmin->updateTechnology($id, $_POST['name'], $_FILES['image_path']['name']);  // modification des données (3 paramètres)
            return $response->withRedirect('/admin/gestiondestechnologies');       //redirection vers le CRUD
        }
        return $this->container->get('view')->render($response, 'Admin/Technologies/modifier_utilisateur.html.twig',
            ['technology' => $technology]);
    }
    #endregion

    #region --> Supprimer une technologie
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return string
     */
    public function supprimerTechnologieAction(Request $request, Response $response, $args)
    {
        $_POST = $request->getParsedBody();
        $idTechno = $args['id'];
        $technologiesControllerAdmin = new TechnologiesControllerAdmin();
        $technologiesControllerAdmin->deleteTechnology($idTechno);
        return $response->withRedirect('/admin/gestiondestechnologies');
    }
    #endregion
#endregion


#region /*******************************  PARTIE ADMINISTRATION : utilisateurs ******************************************/
    #region --> CRUD
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function CRUDUtilisateurs(Request $request, Response $response)
    {
        $usersControllerAdmin = new UtilisateursControllerAdmin();
        $users = $usersControllerAdmin->displayAllUsers();
        return $this->container->get('view')->render($response, 'Admin/Utilisateurs/CRUD_utilisateurs.html.twig',
            ['users' => $users]);
    }
    #endregion

    #region --> Modifier un utilisateur
    /**
     * @param Request $request
     * @param Response $response
     * @param $argumentsURL
     * @return mixed
     */
    public function modifierUtilisateurAdminAction(Request $request, Response $response, $argumentsURL)
    {
        $id = $request->getQueryParam('id');        //récupération de l'id pour le formulaire depuis l'URL
        $_POST = $request->getParsedBody();             // initialisation du POST + préparation pour récupérer les données
        $usersControllerAdmin = new UtilisateursControllerAdmin();
        $user = $usersControllerAdmin->getUser($id);       // affichage des infos selon l'ID
        if (isset($_POST)) {        // si le POST existe
            $usersControllerAdmin->updateUser($id, $_POST['username'], $_POST['email'], $_POST['password']);  // modification des données (3 paramètres)
            return $response->withRedirect('/admin/gestiondesutilisateurs');       //redirection vers le CRUD
        }
        return $this->container->get('view')->render($response, 'Admin/Utilisateurs/modifier_utilisateur.html.twig',
            ['user' => $user]);
    }
    #endregion

    #region --> Supprimer un utilisateur
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return mixed
     */
    public function supprimerUtilisateurAdminAction(Request $request, Response $response, $args)
    {
        $_POST = $request->getParsedBody();
        $idUser = $args['id'];
        $usersControllerAdmin = new UtilisateursControllerAdmin();
        $usersControllerAdmin->deleteUser($idUser);
        return $response->withRedirect('/admin/gestiondesutilisateurs');
    }
    #endregion
#endregion




#region /*******************************  API SPOTIFY *******************************************************************/
    #region --> établir la connexion avec Spotify et récupérer un token
    public function spotifyPage(Request $request, Response $response)
    {
        $session = new Session(
            'd62cbda4b2a64b9ebf36a72765c6a77e',
            '47671cc1ddbd4711af4dd407d006af10',
            'http://portfolio.dev:8080/spotify'
        );
        if (isset($_GET['code'])) {
            $session->requestAccessToken($_GET['code']);
            $_SESSION['spotify_token'] = $session->getAccessToken();
        }
        if ($_SESSION['roles'] == 'admin'){
            return $response->withRedirect('/admin');
        }
        else {
            return $response->withRedirect('/user'.$_SESSION['chemin']);
        }

    }
    #endregion

    #region --> Afficher la liste des playlists et de ses titres
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function playlistAction(Request $request, Response $response, array $args)
    {
        $api = new SpotifyWebAPI();
        $api->setAccessToken($_SESSION['spotify_token']);
        // récupère le nom de la playlist
        $playlist = $api->getUserPlaylist('1157591261', $args['idPlaylist']);
        $namePlaylist = $playlist->name;
        // récupère la liste des titres d'une playlist
        $tracksPlaylist = $api->getUserPlaylistTracks('1157591261', $args['idPlaylist']);
        $tracks = [];
        foreach ($tracksPlaylist->items as $item) {
            $temp = [];
            $tempId[] = $item->track->id;
            foreach ($tempId as $id) {
                $temp['idTrack'] = $id;
            }
            $tempTracks[] = $item->track->name;
            foreach ($tempTracks as $track) {
                $temp['nameTrack'] = $track;
            }
            $temp['artist'] = $item->track->artists[0]->name;
            $temp['album'] = $item->track->album->name;
            $temp['previewUrl'] = $item->track->preview_url;
            $tracks[] = $temp;
        }
        return $response->withJson([
            'namePlaylist' => $namePlaylist,
            'tracks' => $tracks,
        ]);
    }
    #endregion

    #region --> Afficher les informations sur un titre particulier
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function TrackAction(Request $request, Response $response, array $args)
    {
        $api = new SpotifyWebAPI();
        $api->setAccessToken($_SESSION['spotify_token']);
        // récupère la liste des titres d'une playlist
        $trackInfos = $api->getTrack($args['idTrack']);
        $track['idTrack'] = $trackInfos->id;
        $track['nameTrack'] = $trackInfos->name;
        $track['artistTrack'] = $trackInfos->album->artists[0]->name;
        $track['albumTrack'] = $trackInfos->album->name;
        $track['previewUrl'] = $trackInfos->preview_url;
        $track['albumImgTrack'] = $trackInfos->album->images[1]->url;

        return $response->withJson([
            'track' => $track,
        ]);
    }
    #endregion
#endregion

}