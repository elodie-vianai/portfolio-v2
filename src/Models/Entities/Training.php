<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 02/05/2017
 * Time: 10:37
 */

namespace Portfolio\Models\Entities;


class Training
{
#region /************************************************** ATTRIBUTES **************************************************/
    protected $id;
    protected $name;
    protected $begin_at;
    protected $end_at;
    protected $type;
    protected $etablissement;
    protected $ville;
    protected $dep_id;
    protected $logo;
    protected $mention;
    protected $component;
#endregion


#region /************************************************ CONSTRUCTORS **************************************************/
    /**
     * Training constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        if(isset($data['id'])) {
            $this->id = $data['id'];
        }
        $this->name = $data['name'];
        $this->begin_at = $data['begin_at'];
        $this->end_at = $data['end_at'];
        $this->type = $data['type'];
        $this->etablissement = $data['etablissement'];
        $this->ville = $data['ville'];
        $this->dep_id = $data['dep_id'];
        $this->logo = $data['logo'];
        $this->mention = $data['mention'];
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getEtablissement()
    {
        return $this->etablissement;
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
     * @param string $type
     */
    public function setType($type)
    {
        if (is_string($type)) {
            $this->type = $type;
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