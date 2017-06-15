<?php

namespace Portfolio\Controller;

use Portfolio\Portfolio\Controller;
use Portfolio\Model\User as UserModel;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Portfolio\Portfolio\Flash;
use Portfolio\Portfolio\Validator;
use Slim\Http\Request;
use Slim\Http\Response;

class Authentification extends Controller
{
#region /******************************* METHOD : log in *****************************************************************/
    #region --> View corresponding to the login page
    /**
     * Returns the view corresponding to the login page.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response)
    {
        // si l'utilisateur est déjà connecté, redirection vers la page d'accueil de l'utilisateur
        if(!empty($_SESSION['user'])) {
            return $response->withRedirect($this->container->get('router')->pathFor('userHomepage'));
        }

        // tableau qui contient toutes les données dont la vue a besoin
        $data = [
            'params' => Flash::has('params') ? Flash::get('params') : [],
            'errors' => Flash::has('errors') ? Flash::get('errors') : []
        ];

        return $this->render($response, 'UsersZone/Authentifications/login.html.twig', $data);
    }
    #endregion

    #region --> connexion form processing
    /**
     * Connexion form processing.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function login(Request $request, Response $response)
{
    // récupération du routeur pour pouvoir rediriger
    $router = $this->container->get('router');

    // doc Slim ==> $_POST
    $params = $request->getParams();
    $result = [];
    $errors = '';

    $validator = new Validator($params);

    $validator->addRules([
        'username'     => [
            'required' => 'Le nom d\'utilisateur est obligatoire'
        ],
        'mdp' => [
            'required' => 'Le mot de passe est obligatoire'
        ]
    ]);

    // vérification de la validité du formulaire
    if ($validator->check()) {
        $user_model = new UserModel($this->container);
        $user       = $user_model->getUser($params['username'], $params['mdp']);

        if ($user) {
            // destruction du mot de passe pour des raisons de sécurité
            unset($user['password']);

            // récupération des informations relatives à l'utilisateur connecté dans la session
            $_SESSION['user'] = $user;

            // redirection selon le rôle
            if ($user['roles'] == 'admin') {
                $this->apiSpotifyAuthentification();
                return $response->withRedirect($router->pathFor('adminHomepage'));
            } else {
                $this->apiSpotifyAuthentification();
                return $response->withRedirect($router->pathFor('userHomepage'));
            }
        } else {
            $errors = [
                'global' => 'Identifiants incorrects.'
            ];
        }
    } else {
        $errors = $validator->getErrors();
    }
    Flash::set('params', $params);
    Flash::set('errors', $errors);
}
#endregion
#endregion


#region /******************************* METHOD : sign up ***************************************************************/
    /**
     * Sign up a new user.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function signUp(Request $request, Response $response)
    {
        // récupération du routeur pour pouvoir rediriger
        $router = $this->container->get('router');

        $errors = '';

        // initialisation du POST + préparation pour récupérer les données
        $_POST = $request->getParsedBody();

        if (isset($_POST)) {
            // doc Slim ==> $_POST
            $params = $request->getParams();
            $result = [];

            $validator = new Validator($params);

            $validator->addRules([
                'username' => [
                    'required' => 'Le nom d\'utilisateur est obligatoire'
                ],
                'mdp' => [
                    'required' => 'Le mot de passe est obligatoire'
                ],
                'mdpC' => [
                    'required' => 'La confirmation du mot de passe est obligatoire',
                    'confirmationMdp' => 'Le mot de passe et la confirmation du mot de passe doivent être identiques'
                ],
                'email' => [
                    'required' => 'L\'adresse mail est obligatoire'
                ]
            ]);

            // vérification de la validité du formulaire
            if ($validator->check()) {
                $user_model = new UserModel($this->container);
                $user_model->add($params);
                return $response->withRedirect($router->pathFor('login_page'));
            } else {
                $errors = $validator->getErrors();
            }

            Flash::set('params', $params);
            Flash::set('errors', $errors);
        }

        return $this->render($response, 'UsersZone/Authentifications/signup.html.twig');
    }
#endregion


#region /******************************* METHOD : log out ***************************************************************/
    /**
     * Disconnect the user.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function logout(Request $request, Response $response) {
        // récupération du routeur pour pouvoir rediriger
        $router = $this->container->get('router');

        // destruction de la session
        session_destroy();

        // redirection
        return $response->withRedirect($router->pathFor('publicHomepage'));
    }
#endregion


#region /******************************* METHOD : authentification to the API Spotify ***********************************/
    public function apiSpotifyAuthentification()
    {
        $session = new Session(
            'da8c033ac8bb4c16a9be33cbd7501e58',
            '97e33270196d42f5a953524381ea5cda',
            'http://portfolio-v2.dev:8080/spotify'
        );
        $api = new SpotifyWebAPI();
        if (isset($_GET['code'])) {
            $session->requestAccessToken($_GET['code']);
            $api->setAccessToken($session->getAccessToken());
            print_r($api->me());
            exit();
        } else {
            $options = [
                'scopes' => ['user-read-private',
                    'user-read-email', //accéder aux infos de l'utilisateur
                    'playlist-read-private', //accéder aux playlists
                    'playlist-read-collaborative', //accéder aux playlists
                    'user-read-currently-playing', //accéder au titre actuel
                    'user-modify-playback-state',
                ],
            ];
            header('Location: ' . $session->getAuthorizeUrl($options));
            die;
        }
    }
#endregion
}