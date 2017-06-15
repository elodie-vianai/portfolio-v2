<?php

namespace Portfolio\Model;

use Portfolio\Portfolio\Model;

class Technology extends Model
{
    protected $table = 'technology';


#region /******************************* METHOD : get many technologies **************************************************/
    #region --> get all technologies
    /**
     * Get an array of all technologies from the database.
     *
     * @return array
     */
    public function getAll() {
        $sql = 'SELECT technology.* FROM technology ORDER BY technology.name ASC';
        $query = $this->db->prepare($sql);
        $query->execute();
        $results = $query->fetchAll();
        return $this->imagePathManager($results);
    }
    #endregion

    #region --> get technologies based on a project
    /**
     * Get one or many technologies based on the id of a project.
     *
     * @param $id_project
     * @return array
     */
    public function getTechnoProject($id_project) {
        $sql = 'SELECT * FROM project_has_technology RIGHT JOIN technology ON technology_id = technology.id 
          WHERE project_id= :id';
        $query = $this->db->prepare($sql);
        $query->execute([
            ':id'   => $id_project
        ]);
        $results = $query->fetchAll();
        return $this->imagePathManager($results);
    }
    #endregion
#endregion


#region /******************************* METHOD : get one technology with its id or name ********************************/
    /**
     * Get one technology based on its id or name.
     *
     * @param $param (id or name)
     * @return array
     */
    public function getOne($param) {
        $sql = 'SELECT * FROM technology WHERE id= :id OR name= :name';
        $query = $this->db->prepare($sql);
        $query->execute([
            ':id'   => $param,
            ':name' => $param
        ]);
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        return $this->imagePathManager($result);
    }
#endregion


#region /******************************* METHOD : add a new technology in the database **********************************/
    /**
     * Add a new technology in the database.
     *
     * @param $params
     * @return array
     */
    public function add($params) {
        $sql = 'SELECT technology.* FROM technology WHERE technology.name= :name';
        $query = $this->db->prepare($sql);
        $query->execute([
            ':name' => $params['name']
        ]);
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $tabError = [];
        if ($result['name'] == $params['name']) {
            $tabError['error'] = 'Erreur : cette technologie existe déjà dans la base de données';
            return $tabError;
        } else {
            $sql = 'INSERT INTO technology(name, image_path) VALUES (:name, :logo)';
            $query = $this->db->prepare($sql);
            return $query->execute([
                ':name' => $params['name'],
                ':logo' => $params['image_path']
            ]);
        }
    }
#endregion


#region /******************************* METHOD : update a technology in the database ***********************************/
    /**
     * Update a technology which is in the database.
     *
     * @param $params (id, name, image_path)
     * @return array
     */
    public function update($params) {
        $sql = 'UPDATE technology SET name = :name, image_path = :image_path WHERE id= :id';
        $query = $this->db->prepare($sql);
        return $query->execute([
            ':name'         => $params['name'],
            ':image_path'   => $params['image_path'],
            ':id'           => $params['id']
        ]);
    }
#endregion


#region /******************************* METHOD : delete a technology in the database ***********************************/
    /**
     * Delete a technology in the database based on its id.
     *
     * @param $id
     * @return array
     */
    public function delete($id) {
        $sql = 'DELETE FROM technology WHERE id= :id';
        $query = $this->db->prepare($sql);
        return $query->execute([
            ':id'   => $id
        ]);
    }
#endregion


#region /******************************* METHOD : image path management *************************************************/
    /**
     * Manage the path of image from an array.
     *
     * @param $param
     * @return array
     */
    public function imagePathManager($param)
    {
        $results = [];
        if (!empty($param)) {
            $nb = max(array_map('count', $param));
            if ($nb > 1) {
                foreach ($param as $item) {
                    if (!empty($item['image_path'])) {
                        $image = '/logos/' . $item['image_path'];
                    } else {
                        $image = '/noImage.png';
                    }
                    $item['image_path'] = $image;
                    $results[] = $item;
                }
                return $results;
            } else {
                if (!empty($param['image_path'])) {
                    $param['image_path'] = '/logos/' . $param['image_path'];
                } else {
                    $param['image_path'] = '/noImage.png';
                }
                return $param;
            }
        }
    }
#endregion

}