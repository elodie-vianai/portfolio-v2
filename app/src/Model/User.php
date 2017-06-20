<?php

namespace Portfolio\Model;

use Portfolio\Portfolio\Model;

class User extends Model
{
    protected $table = 'user';

#region /******************************* METHOD : get all users *********************************************************/
    /**
     * Get an array of all users from the database.
     *
     * @return array
     */
    public function getAll()
    {
        $sql = "SELECT $this->table.* FROM $this->table ORDER BY username DESC";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
#endregion


#region /******************************* METHOD : get one user **********************************************************/
    /**
     * Get one user based on specific conditions.
     *
     * @param $param1 (id or username)
     * @param $password
     * @return array
     */
    public function getUser($param1, $password = 'null')
    {
        $sql = "SELECT $this->table.* FROM $this->table 
          WHERE id= :id OR username= :username AND password= :password";
        $query = $this->db->prepare($sql);
        $query->execute([
            ':id'       => $param1,
            ':username' => $param1,
            ':password' => $password
        ]);
        return $query->fetch();
    }
#endregion


#region /******************************* METHOD : add a user ************************************************************/
    public function add($params) {
        // Vérification pour éviter un doublon dans la base de données
        $sql = "SELECT $this->table.email, $this->table.username FROM $this->table 
          WHERE email = :email";
        $query = $this->db->prepare($sql);
        $query->execute([
            ':email'    => $params['email']
        ]);
        $results = $query->fetch(\PDO::FETCH_ASSOC);
        $tabError = [];
        if (($results['email'] == $params['email']) AND ($results['username'] == $params['username'])) {
            $tabError['error'] = 'Erreur : ce nom d\'utilisateur associé à cette adresse mail existent déjà, veuillez vous connecter.';
            return $tabError;
        }
        elseif (($results['email'] == $params['email']) AND ($results['username'] !== $params['username'])) {
            $tabError['error'] = 'Erreur : cette adresse mail est déjà utilisée.';
            return $tabError;
        }
        else {
        // S'il n'y a pas une même adresse mail et un même nom d'utilisateur déjà existant, alors l'ajout peut se faire
            $sql = "INSERT INTO $this->table(username, email, password, roles) 
              VALUES(:username, :email, :mdp, 'user')";
            $query = $this->db->prepare($sql);
            $results = $query->execute([
                ':username' => $params['username'],
                ':email' => $params['email'],
                ':mdp' => $params['mdp']
            ]);

            return $results;
        }
    }
#endregion


#region /******************************* METHOD : update user's data ****************************************************/
    /**
     * Update a user identified by his id.
     *
     * @param $params (id, username, email, password)
     * @return boolean
     */
    public function update($params)
    {
        $sql = "UPDATE $this->table 
          SET username = :username, email = :email, password = :password, roles = :roles 
          WHERE id= :id";
        $query = $this->db->prepare($sql);
        return $query->execute([
            'username'  => $params['username'],
            'email'     => $params['email'],
            'password'  => $params['password'],
            ':roles'    => $params['roles'],
            'id'        => $params['id']
        ]);
    }
#endregion


#region /******************************* METHOD : delete a user *********************************************************/
    /**
     * Delete a user's data into the database, based on his id.
     *
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE $this->table.id = :id";
        $query = $this->db->prepare($sql);
        return $query->execute([
            ':id'   => $id
        ]);
    }
#endregion
}