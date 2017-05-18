<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 18/05/2017
 * Time: 11:05
 */

namespace Portfolio\Controller\Admin;
use Portfolio\Models\UsersModel;

// ENREGISTREMENT DE L'AUTOLOAD
spl_autoload('UsersModel', '.php');

class UtilisateursControllerAdmin
{
#region /******************************* ATTRIBUTES *********************************************************************/
    private $user;
#endregion


#region /******************************* CONSTRUCTOR ********************************************************************/
    /**
     * UtilisateursControllerAdmin constructor.
     */
    public function __construct()
    {
        $this->user = new UsersModel('mysql:host=localhost;dbname=portfolio');
    }
#endregion



#region /******************************* METHOD : CRUD *****************************************************************/
    #region -> Affichage de tous les utilisateurs enregistré
    /**
     * @return array
     */
    public function displayAllUsers()
    {
        $tableauUsers = $this->user->getAllUsers();
        return $tableauUsers;
    }
    #endregion
#endregion


#region /******************************* METHOD : Récupération d'un utilisateur précis ***********************************/
    /**
     * @param $element
     * @return mixed
     */
    public function getUser($element)
    {
        $tableauUser = $this->user->getUser($element);
        return $tableauUser;
    }
#endregion


#region /******************************* METHOD : modifier un utilisateur ***********************************************/
    public function updateUser($id, $username,$email, $password)
    {
        if ((!isset($id)) OR (!isset($username)) OR (!isset($email)) OR (!isset($password))) {
            if (!isset($username)) {
                $msgError['error'] = 'Erreur : veuillez rentrer un nom d\'utilisateur !';
                return $msgError;
            } elseif (!isset($email)) {
                $msgError['error'] = 'Erreur : veuillez rentrer une adresse mail !';
                return $msgError;
            } elseif (!isset($password)) {
                $msgError['error'] = 'Erreur : veuillez rentrer un mot de passe !';
                return $msgError;
            }
        }
        else {
            $userUpdated = $this->user->updateUser($id, $username, $email, $password);
            return $userUpdated;
        }
    }
#endregion



#region /******************************* METHOD : supprimer un utilisateur *********************************************/
    /**
     * @param $id
     * @return string
     */
    public function deleteUser($id)
    {
        if (isset($id)) {
            $user = $this->user->deleteUser($id);
            return $user;
        }
        else {
            $msgError['error'] = 'Erreur : il semblerait qu\'il y ait un problème !';
            return $msgError;
        }
    }
#endregion
}