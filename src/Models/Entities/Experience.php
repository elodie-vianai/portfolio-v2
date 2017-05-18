<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 02/05/2017
 * Time: 10:23
 */

namespace Portfolio\Models\Entities;


class Experience
{
#region /************************************************** ATTRIBUTES **************************************************/
    protected $id;
    protected $name;
    protected $begin_at;
    protected $end_at;
    protected $contrat;
    protected $entreprise;
    protected $ville;
    protected $dep_id;
    protected $logo;
    protected $component;
#endregion


#region /************************************************ CONSTRUCTORS **************************************************/
    public function __construct(array $data)
    {
        if(isset($data['id'])) {
            $this->id = $data['id'];
        }
        $this->name = $data['name'];
        $this->begin_at = $data['begin_at'];
        $this->end_at = $data['end_at'];
        $this->contrat = $data['contrat'];
        $this->entreprise = $data['entreprise'];
        $this->ville = $data['ville'];
        $this->dep_id = $data['dep_id'];
        $this->logo = $data['logo'];
        $this->component = $data['component'];
    }
#endregion


#region /************************************************** GETTERS *****************************************************/
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getBegin_at()
    {
        return $this->begin_at;
    }

    /**
     * @return mixed
     */
    public function getEnd_at()
    {
        return $this->end_at;
    }

    /**
     * @return string
     */
    public function getContrat()
    {
        return $this->contrat;
    }

    /**
     * @return mixed
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }

    /**
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @return integer
     */
    public function getDep_id()
    {
        return $this->dep_id;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }
#endregion


#region /************************************************** SETTERS *****************************************************/
    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $id = (int) $id;
        if ($id > 0) {
            $this->id = $id;
        }
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        if (is_string($name)){
            $this->name = $name;
        }
    }

    /**
     * @param mixed $begin_at
     */
    public function setBegin_at($begin_at)
    {
        $this->begin_at = $begin_at;
    }

    /**
     * @param mixed $end_at
     */
    public function setEnd_at($end_at)
    {
        $this->end_at = $end_at;
    }

    /**
     * @param string $contrat
     */
    public function setContrat($contrat)
    {
        if (is_string($contrat)) {
            $this->contrat = $contrat;
        }
    }

    /**
     * @param mixed $entreprise
     */
    public function setEntreprise($entreprise)
    {
        $this->entreprise = $entreprise;
    }

    /**
     * @param string $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }
#endregion
}