<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 14/04/2017
 * Time: 15:44
 */

namespace Portfolio\Models\Entities;


class User
{
#region /************************************************** ATTRIBUTES **************************************************/
    protected $id;
    protected $username;
    protected $email;
    protected $mdp;
    protected $role;
#endregion


#region /************************************************ CONSTRUCTORS **************************************************/
    public function __construct(array $data)
    {
        if(isset($data['id'])) {
            $this->id = $data['id'];
        }
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->mdp = $data['mdp'];
        $this->role = $data['role'];
    }
#endregion


#region /************************************************** GETTERS *****************************************************/
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }
#endregion
}