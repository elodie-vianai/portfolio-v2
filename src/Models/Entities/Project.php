<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 02/05/2017
 * Time: 10:47
 */

namespace Portfolio\Models\Entities;


class Project
{
#region /************************************************** ATTRIBUTES **************************************************/
    protected $id;
    protected $name;
    protected $year;
    protected $description;
    protected $image_path;
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
        $this->year = $data['year'];
        $this->description = $data['description'];
        $this->image_path = $data['image_path'];
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
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getImage_Path()
    {
        return $this->image_path;
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
     * @param integer $year
     */
    public function setYear($year)
    {
        if ($year > 2010) {
            $this->year = $year;
        }
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param mixed $image_path
     */
    public function setImage_path($image_path)
    {
        $this->image_path = $image_path;
    }
#endregion
}