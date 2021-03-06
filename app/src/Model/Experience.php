<?php

namespace Portfolio\Model;

use Portfolio\Portfolio\Model;

class Experience extends Model
{
    protected $table            = 'experience';
    protected $departmentsTable = 'departement';
    protected $linkTable        = 'experience_has_project';


#region /******************************* METHOD : get all experiences ***************************************************************/
    /**
     * Get an array of all experiences from the database.
     *
     * @return mixed
     */
    public function getAll() {
        $sql = "SELECT $this->table.*, $this->departmentsTable.code FROM $this->table
          JOIN $this->departmentsTable ON $this->table.dep_id = $this->departmentsTable.id_dep
          ORDER BY $this->table.end_at DESC";
        $query = $this->db->prepare($sql);
        $query->execute();
        $results = $query->fetchAll();
        $results = $this->imagePathManager($results);
        return $this->contractDuration($results);
    }
#endregion


#region /******************************* METHOD : get one experience ****************************************************/
    #region --> Get an experience based on its id.
    /**
     * Get an experience based on its id.
     *
     * @param $id
     * @return array
     */
    public function getOne($id) {
        $sql = "SELECT $this->table.* FROM $this->table
            INNER JOIN $this->departmentsTable ON $this->table.dep_id = $this->departmentsTable.id_dep 
            WHERE $this->table.id= :id";
        $query = $this->db->prepare($sql);
        $query->execute([
            ':id'   => $id
        ]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }
    #endregion

    #region --> Get the last experience
    /**
     * Get the last experience from the database.
     *
     * @return array
     */
    public function getLastExperience() {
        $table           = $this->table;
        $sql = "SELECT $table.* FROM $table ORDER BY $table.id DESC LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetch(\PDO::FETCH_ASSOC);
    }
    #endregion

    #region --> get an experience based on a project
    /**
     * Get an experience based on the id of a project.
     *
     * @param $id_project
     * @return mixed
     */
    public function getExperienceProject($id_project) {
        $sql = 'SELECT * FROM experience 
          JOIN experience_has_project ON experience_has_project.experience_id = experience.id
          WHERE experience_has_project.project_id = :id_project';
        $query = $this->db->prepare($sql);
        $query->execute([
            ':id_project'   => $id_project
        ]);
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
    #endregion
#endregion


#region /******************************* METHOD : add a new experience in the database **********************************/
    /**
     * Add a new experience into the database.
     *
     * @param $params
     * @return array
     */
    public function add($params) {
        if (empty($params['end_at'])) {
            $dtz = new \DateTimeZone('Europe/Paris');
            $datetime = new \DateTime();
            $datetime->setTimezone($dtz);
            $params['end_at'] = $datetime->format('Y-m-d');
        }

        $sql = "SELECT $this->table.name, $this->table.begin_at FROM $this->table 
          WHERE name= :name AND begin_at= :begin_at";
        $query = $this->db->prepare($sql);
        $query->execute([
            ':name'         => $params['name'],
            ':begin_at'     => $params['begin_at']
        ]);
        $results = $query->fetch(\PDO::FETCH_ASSOC);
        $tabError = [];
        if ($results['name'] == $params['name'] AND $results['begin_at'] == $params['begin_at']) {
            $tabError['error'] = 'Erreur : cette expérience existe déjà dans la base de données';
            return $tabError;
        } else {
            $sql = "INSERT INTO $this->table(name, contrat, entreprise, ville, begin_at, end_at, image_path, dep_id)
                VALUES (:name, :contrat, :entreprise, :ville, :begin_at, :end_at, :image_path, :dep_id)";
            $query = $this->db->prepare($sql);
            $query->execute([
                ':name'             => $params['name'],
                ':contrat'          => $params['contrat'],
                ':entreprise'       => $params['entreprise'],
                ':ville'            => $params['ville'],
                ':begin_at'         => $params['begin_at'],
                ':end_at'           => $params['end_at'],
                ':image_path'       => $params['image_path'],
                ':dep_id'           => $params['dep_id']
            ]);
            if((isset($params['projects'])) AND (!empty($params['projects']))) {
                $experience =$this->getLastExperience();
                foreach ($params['projects'] as $item) {
                    $sql = "INSERT INTO $this->linkTable (experience_id, project_id)
                      VALUES (:experience_id, :project_id)";
                    $query = $this->db->prepare($sql);
                    $query->execute([
                        ':experience_id'    => $experience['id'],
                        ':project_id' => $item
                    ]);
                }
            }
        }
    }
#endregion


#region /******************************* METHOD : update an experience in the database **********************************/
    /**
     * Update an experience which is in the database.
     *
     * @param $params (id, name, contrat, entreprise, ville, begin_at, end_at, image_path, dep_id, projects[])
     * @return array
     */
    public function update($params) {
        // Update data of the experience (into the 'experience' table).
        $sql = "UPDATE $this->table 
          SET name = :name, contrat = :contrat, entreprise = :entreprise, ville = :ville, 
          begin_at = :begin_at, end_at = :end_at, image_path = :image_path, dep_id = :dep_id
            WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([
            ':name'         => $params['name'],
            ':contrat'      => $params['contrat'],
            ':entreprise'   => $params['entreprise'],
            ':ville'        => $params['ville'],
            ':begin_at'     => $params['begin_at'],
            ':end_at'       => $params['end_at'],
            'image_path'    => $params['image_path'],
            ':dep_id'       => $params['dep_id'],
            ':id'           => $params['id']
        ]);

        // Update data of projects related to the project (into the 'experience_has_project' table).
        $sql = "SELECT $this->table.name, $this->table.begin_at FROM $this->table 
          WHERE name = :name AND begin_at = :begin_at";
        $query = $this->db->prepare($sql);
        $query->execute([
            ':name'         => $params['name'],
            ':begin_at'     => $params['begin_at']
        ]);

        $sql = "SELECT * FROM $this->linkTable WHERE experience_id = :experience_id";
        $query = $this->db->prepare($sql);
        $query->execute([
            ':experience_id'   => $params['id']
        ]);
        $results = $query->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($results)) {
            foreach ($params['projects'] as $project) {
                $sql = "INSERT INTO $this->linkTable (experience_id, project_id)
                  VALUES (:experience_id, :project_id)";
                $query = $this->db->prepare($sql);
                $query->execute([
                    ':experience_id'    => $params['id'],
                    ':project_id'       => $project
                ]);
            }
        }
        else {
            foreach ($results as $result) {
                $sql = "DELETE FROM $this->linkTable
                  WHERE experience_id = :experience_id AND project_id = :project_id";
                $query = $this->db->prepare($sql);
                $query->execute([
                    ':experience_id'    => $params['id'],
                    ':project_id'       => $result['project_id']
                ]);
            }

            foreach ($params['projects'] as $project) {
                $sql = "INSERT INTO $this->linkTable (experience_id, project_id)
                      VALUES (:experience_id, :project_id)";
                $query = $this->db->prepare($sql);
                $query->execute([
                    ':experience_id'    => $params['id'],
                    ':project_id'       => $project,
                ]);
            }
        }
    }
#endregion


#region /******************************* METHOD : delete an experience in the database **********************************/
    /**
     * Delete an experience and relations with projects in the database based on its id.
     *
     * @param $id
     * @return array
     */
    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE id= :id";
        $query = $this->db->prepare($sql);
        $query->execute([
            ':id'   => $id
        ]);
        $sql = "DELETE FROM $this->linkTable WHERE experience_id = :id";
        $query = $this->db->prepare($sql);
        return $query->execute([
            ':id'   => $id
        ]);
    }
#endregion


#region /******************************* METHOD : calculation of contract duration **************************************/
    /**
     * Calculate the duration of the contract according to 'begin_at' and 'end_at' then format the display.
     *
     * @param $param
     * @return array
     */
    public function contractDuration($param)
    {
        $results = [];
        foreach ($param as $item) {
            $begin_at = new \DateTime($item['begin_at'], new \DateTimeZone('Europe/Paris'));
            $end_at = new \DateTime($item['end_at'], new \DateTimeZone('Europe/Paris'));
            $duration = date_diff($begin_at, $end_at);
            if ($duration->y == '0') {
                $duration = $duration->format('%m mois');
            }
            elseif ($duration->y == '1') {
                $duration = $duration->format('%y an et %m mois');
            }
            else {
                $duration = $duration->format('%y ans et %m mois');
            }
            $item['duration'] = $duration;
            $item['begin_at'] = $begin_at->format('m-Y');
            $item['end_at'] = $end_at->format('m-Y');
            $results[] = $item;
        }
        return $results;

    }
#endregion


#region /******************************* METHOD : image path management *************************************************/
    /**
     * Manage the path of images from an array.
     *
     * @param $param
     * @return array
     */
    public function imagePathManager($param)
    {
        $result = [];
        foreach ($param as $item) {
            if (!empty($item['image_path'])) {
                $image = '/logos/'.$item['image_path'];
            }
            else {
                $image = '/noImage.png';
            }
            $item['image_path'] = $image;
            $result[] = $item;
        }
        return $result;
    }
#endregion
}