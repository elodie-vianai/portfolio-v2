<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 18/04/2017
 * Time: 09:36
 */

namespace Portfolio\Models;


use Portfolio\Models\Database;

class UsersModel extends Database
{
#region /******************************* RÉCUPÉRATION DE L'ENSEMBLE DES UTILISATEURS ************************************/
    /**
     * @return array
     */
    public function getAllUsers() {
        $sql = 'SELECT * FROM user ORDER BY username DESC';
        $requete = $this->executerRequete($sql);
        $users = $requete->fetchAll(\PDO::FETCH_ASSOC);
        return $users;
    }
#endregion


#region /******************************* RÉCUPÉRATION D'UN UTILISATEUR SELON CONDITIONS SPÉCIFIQUES *********************/
    /**
     * @return array
     */
    public function getUser($param1, $param2 = 'null') {
        $sql = 'SELECT * FROM user WHERE id="'.$param1.'" OR username="'.$param1.'" AND password="'.$param2.'" OR email="'.$param1.'"';
        $requete = $this->executerRequete($sql);
        $user = $requete->fetch(\PDO::FETCH_ASSOC);
        return $user;
    }
#endregion


#region /******************************* AJOUT D'UN NOUVEL UTILISATEUR **************************************************/
    public function addUser($username, $mdp, $email) {
        $sql = 'SELECT email, username FROM user WHERE email="'.$email.'"';
        $requete = $this->executerRequete($sql);
        $results = $requete->fetch(\PDO::FETCH_ASSOC);
        $tabError = [];
        if (($results['email'] == $email) AND ($results['username'] == $username)) {
            $tabError['error'] = 'Erreur : ce nom d\'utilisateur associé à cette adresse mail existent déjà, veuillez vous connecter.';
            return $tabError;
        }
        elseif (($results['email'] == $email) AND ($results['username'] !== $username)) {
            $tabError['error'] = 'Erreur : cette adresse mail est déjà utilisée.';
            return $tabError;
        }
        else {
            $sql = 'INSERT INTO user(username, email, password, roles) VALUES("'.$username.'","'.$email.'","'.$mdp.'","user")';
            $this->executerRequete($sql);
        }
    }
#endregion


#region /******************************* SUPPRESSION D'UN UTILISATEUR ***************************************************/
    /**
     * @param $id
     */
    public function deleteUser($id) {
        $sql = 'DELETE FROM user WHERE id='.$id;
        $this->executerRequete($sql);
    }
#endregion


#region /******************************* MODIFICATION D'UN UTILISATEUR **************************************************/
    public function updateUser($id, $username, $email, $password) {
        $sql = 'UPDATE user SET username = :username, email = :email, password = :password WHERE id='.$id;
        $this->executerRequete($sql, ['username'=>$username, 'email'=>$email, 'password'=>$password]);
    }
#endregion


}