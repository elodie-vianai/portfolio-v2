<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 14/04/2017
 * Time: 15:44
 */

namespace Portfolio\Models\Entities;


class Technology
{
#region /************************************************** ATTRIBUTES **************************************************/
    protected $id;
    protected $name;
    protected $image_path;
    protected $component;
#endregion


#region /************************************************ CONSTRUCTORS **************************************************/
    /**
     * Technology constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        if(isset($data['id'])) {
            $this->setId($data['id']);
        }
        $this->setNameTechno($data['name']);
        $this->setImagePath($data['image_path']);
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
    public function getImagePath()
    {
        return $this->image_path;
    }
#endregion


#region /************************************************** SETTERS *****************************************************/
    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $id = (int) $id;
        if ($id > 0) {
            $this->id = $id;
        }
    }

    /**
     * @param mixed $name
     */
    public function setNameTechno($name)
    {
        if (is_string($name)){
            $this->name = $name;
        }
    }

    /**
     * @param mixed $image_path
     */
    public function setImagePath($image_path)
    {
        $this->image_path = $image_path;
    }
#endregion
}