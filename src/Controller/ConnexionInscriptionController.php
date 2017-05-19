<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 18/04/2017
 * Time: 09:27
 */

namespace Portfolio\Controller;

use Portfolio\Models\UsersModel;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

// ENREGISTREMENT DE L'AUTOLOAD
spl_autoload('UsersModel', '.php');

class ConnexionInscriptionController
{
#region /******************************* ATTRIBUTES *********************************************************************/
    private $user;
    private $username;
    private $mdp;
    private $mdpC;
    private $email;
#endregion


#region /******************************* CONSTRUCTOR ********************************************************************/
    /**
     * ConnexionInscriptionController constructor.
     */
    public function __construct()
    {
        $this->user = new UsersModel('mysql:host=localhost;dbname=portfolio');
        $this->username = $_POST['username'];
        if (isset($_POST['mdp'])) {
            $this->mdp = $_POST['mdp'];
        }
        if (isset($_POST['mdpC'])) {
            $this->mdpC = $_POST['mdpC'];
        }
        if (isset($_POST['email'])) {
            $this->email = $_POST['email'];
        }
    }
#endregion


#region /******************************* METHOD : authentification API Spotify ******************************************/
    public function connexionApiSpotify()
    {
        $session = new Session(
            'd62cbda4b2a64b9ebf36a72765c6a77e',
            '47671cc1ddbd4711af4dd407d006af10',
            'http://portfolio.dev:8080/spotify'
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


#region /******************************* METHOD : gestion de la connexion ***********************************************/
    /**
     * @param $username
     * @param $mdp
     * @return array|string
     */
    public function gestionConnexion($username, $mdp)
    {
        if (!isset($username)) {
            $msgError = 'Erreur : veuillez rentrer un nom d\'utilisateur !';
            return $msgError;
        } elseif (!isset($mdp)) {
            $msgError = 'Erreur : veuillez rentrer un mot de passe !';
            return $msgError;
        } else {
            $user = $this->user->getUser($username, $mdp);
            if ($user == true){
                if ($user['roles'] == 'admin') {
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['roles'] = $user['roles'];
                    $_SESSION['chemin'] = '/admin';
                    $this->connexionApiSpotify();
                    return $user;
                } else {
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['roles'] = 'user';
                    $_SESSION['header'] = 'Layout/publicHeaderConnected.html.twig';
                    $_SESSION['chemin'] = '/' . $_SESSION['id'];
                    $this->connexionApiSpotify();
                    return $user;
                }
            } else {
                unset($user);
                $msgError = 'Erreur : dans les identifiants !';
                return $msgError;
            }
        }
    }
#endregion


#region /******************************* METHOD : gestion de l'inscription *****************************************/
    /**
     * @param $username
     * @param $mdp
     * @param $mdpC
     * @param $email
     * @return array|string
     */
    public function gestionInscription($username, $mdp, $mdpC, $email)
    {
        if (!isset($username)) {
            $messageErreur = 'Erreur : veuillez rentrer un nom d\'utilisateur !';
            return $messageErreur;
        } elseif ((!isset($mdp) AND (!isset($mdpC))) AND ($mdp !== $mdpC)) {
            if (!isset($mdp)) {
                $messageErreur = 'Erreur : veuillez rentrer un mot de passe !';
                return $messageErreur;
            } elseif (!isset($mdpC)) {
                $messageErreur = 'Erreur : veuillez rentrer la confirmation du mot de passe !';
                return $messageErreur;
            } elseif ($mdp !== $mdpC) {
                $messageErreur = 'Erreur : veuillez rentrer le même mot de passe dans les champs "mot de passe" et "confirmation mot de passe !';
                return $messageErreur;
            }
        } elseif (!isset($email)) {
            $messageErreur = 'Erreur : veuillez rentrer une adresse email !';
            return $messageErreur;
        } else {
            $result = $this->user->addUser($username, $mdp, $email);
            return $result;
        }
    }
#endregion


}