<?php

namespace Portfolio\Model;

use Portfolio\Portfolio\Model;

class Project extends Model
{
    protected $table = 'project';


#region /******************************* METHODS : get many projects *****************************************************/
    #region --> Get all projects.
    /**
     * Get an array of all projects from the database.
     *
     * @return array
     */
    public function getAll() {
        $sql = 'SELECT project.* FROM project ORDER BY project.year DESC';
        $query = $this->db->prepare($sql);
        $query->execute();
        $resutls = $query->fetchAll();
        return $this->imagePathManager($resutls);
    }
    #endregion

    #region --> Get the last 4 projects
    /**
     * Get an array of the last 4 projects from the database.
     *
     * @return array
     */
    public function get4LastProjects() {
        $sql = 'SELECT project.* FROM project ORDER BY project.year DESC LIMIT 4';
        $query = $this->db->prepare($sql);
        $query->execute();
        $results = $query->fetchAll();
        return $this->imagePathManager($results);
    }
    #endregion

    #region --> get projects based on an experience
    /**
     * Get one or many project(s) based on the id of an experience.
     *
     * @param $id_experience
     * @return array
     */
    public function getprojectExperience($id_experience) {
        $sql = 'SELECT * FROM project 
          JOIN experience_has_project ON experience_has_project.project_id = project.id
          WHERE experience_has_project.experience_id = :id_experience';
        $query = $this->db->prepare($sql);
        $query->execute([
            ':id_experience'   => $id_experience
        ]);
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
    #endregion
#endregion


#region /******************************* METHODS : get one project  *****************************************************/
    #region --> Get a project based on its id or its name.
    /**
     * Get a project based on its id or its name.
     *
     * @param $param (id or name)
     * @return array
     */
    public function getOne($param) {
        $sql = 'SELECT project.* FROM project 
          WHERE project.id= :id OR project.name= :name ORDER BY project.year DESC';
        $query = $this->db->prepare($sql);
        $query->execute([
            ':id'   => $param,
            ':name' => $param
        ]);
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $result['image_path'] = $this->imagePathManager($result['image_path']);
        return $result;
    }
    #endregion

    #region --> Get the last project
    /**
     * Get the last project from the database.
     *
     * @return array
     */
    public function getLastProject() {
        $sql = 'SELECT project.* FROM project ORDER BY project.id DESC LIMIT 1';
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetch(\PDO::FETCH_ASSOC);
    }
    #endregion
#endregion


#region /******************************* METHOD : add a new project in the database ***********************************/
    /**
     * Add a new project in the database.
     *
     * @param $params
     * @return array
     */
    public function add($params) {
        $sql = 'SELECT project.name, project.year FROM project WHERE name= :name AND year= :year';
        $query = $this->db->prepare($sql);
        $query->execute([
            ':name'   => $params['name'],
            ':year'   => $params['year']
        ]);
        $results = $query->fetch(\PDO::FETCH_ASSOC);
        $tabError = [];
        if ($results['name'] == $params['name'] AND $results['year'] == $params['year']) {
            $tabError['error'] = 'Erreur : ce projet existe déjà dans la base de données';
            return $tabError;
        } else {
            $sql = 'INSERT INTO project(name, description, image_path, year)
                VALUES (:name, :description, :image_path, :year)';
            $query = $this->db->prepare($sql);
            $query->execute([
                ':name'             => $params['name'],
                ':description'      => $params['description'],
                ':image_path'       => $params['image_path'],
                ':year'             => $params['year']
            ]);
            if((isset($params['technologies'])) AND (!empty($params['technologies']))) {
               $project =$this->getLastProject();
               foreach ($params['technologies'] as $item) {
                   $sql = 'INSERT INTO project_has_technology (project_id, skill_id)
                      VALUES (:project_id, :skill_id)';
                   $query = $this->db->prepare($sql);
                   $query->execute([
                       ':project_id' => $project['id'],
                       ':skill_id' => $item
                   ]);
               }
           }
        }
    }
#endregion


#region /******************************* METHOD : update a project in the database **************************************/
    /**
     * Update a project which is in the database.
     *
     * @param $params (id, name, year, image_path, description, technologies[])
     * @return array
     */
    public function update($params) {
        // Update data of the project (into the 'project' table).
        $sql = 'UPDATE project SET name = :name, description = :description, image_path = :image_path, year = :year
            WHERE id = :id';
        $query = $this->db->prepare($sql);
        $query->execute([
            ':name'         => $params['name'],
            ':description'  => $params['description'],
            'image_path'    => $params['image_path'],
            ':year'         => $params['year'],
            ':id'           => $params['id']
        ]);

        // Update data of technologies related to the project (into the 'project_has_technology' table).
        $sql = 'SELECT project.name, project.year FROM project WHERE name= :name AND year= :year';
        $query = $this->db->prepare($sql);
        $query->execute([
            ':name'   => $params['name'],
            ':year'   => $params['year']
        ]);

        $sql = 'SELECT * FROM project_has_technology WHERE project_id= :project_id';
        $query = $this->db->prepare($sql);
        $query->execute([
            ':project_id'   => $params['id']
        ]);
        $results = $query->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($results)) {
            foreach ($params['technologies'] as $technology) {
                $sql = 'INSERT INTO project_has_technology (project_id, skill_id)
                  VALUES (:project_id, :technology_id)';
                $query = $this->db->prepare($sql);
                $query->execute([
                    ':project_id' => $params['id'],
                    ':technology_id' => $technology
                ]);
            }
        }
        else {
            foreach ($results as $result) {
                $sql = 'DELETE FROM project_has_technology
                  WHERE project_id = :project_id AND skill_id = :skill_id';
                $query = $this->db->prepare($sql);
                $r = $query->execute([
                    ':project_id' => $params['id'],
                    ':skill_id' => $result['skill_id']
                ]);
            }

            foreach ($params['technologies'] as $technology) {
                $sql = 'INSERT INTO project_has_technology (project_id, skill_id)
                      VALUES (:project_id, :technology_id)';
                $query = $this->db->prepare($sql);
                $query->execute([
                    ':project_id' => $params['id'],
                    ':technology_id' => $technology
                ]);
            }
        }
    }
#endregion


#region /******************************* METHOD : delete a project in the database ***********************************/
    /**
     * Delete a project and relations with technologies in the database based on its id.
     *
     * @param $id
     * @return array
     */
    public function delete($id) {
        $sql = 'DELETE FROM project_has_technology WHERE project_id= :id';
        $query = $this->db->prepare($sql);
        $query->execute([
            ':id'   => $id
        ]);
        $sql = 'DELETE FROM project WHERE id= :id';
        $query = $this->db->prepare($sql);
        return $query->execute([
            ':id'   => $id
        ]);
    }
#endregion


#region /******************************* METHODS : image path management ************************************************/
    /**
     * Manage the path of images from an array.
     *
     * @param $param
     * @return mixed
     */
    public function imagePathManager($param)
    {
        $nb = count($param);
        if ($nb > 1) {
            $results = [];
            foreach ($param as $item) {
                if (!empty($item['image_path'])) {
                    $item['image_path'] = '/projects/'.$item['image_path'];
                }
                else {
                    $item['image_path'] = '/noImage.png';
                }
                $results[] = $item;
            }
            return $results;
        } else {
           if (!empty($param)){
               return $result = '/projects/'.$param;
           }
           else {
               return $result['image_path'] = '/noImage.png';
           }
        }
    }
#endregion

}