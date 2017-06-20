<?php

namespace Portfolio\Model;

use Portfolio\Portfolio\Model;

class Formation extends Model
{
    protected $table            = 'formation';
    protected $departmentTable  = 'departement';


#region /******************************* METHOD : get all educations  ***************************************************************/
    /**
     * Get an array of all educations from the database.
     *
     * @return array
     */
    public function getAll()
    {
        $sql = "SELECT $this->table.*, $this->departmentTable.code FROM $this->table
          JOIN $this->departmentTable ON $this->table.dep_id = $this->departmentTable.id_dep
          ORDER BY $this->table.end_at DESC";
        $query = $this->db->prepare($sql);
        $query->execute();
        $temp = $query->fetchAll();
        $results = [];
        foreach ($temp as $item) {
            if ($item['mention'] == 'P') {
                $item['mention'] = '';
            }
            $results[] = $item;
        }
        $results = $this->imagePathManager($results);
        return $this->datesManager($results);
    }
#endregion


#region /******************************* METHOD : get one education with its id *****************************************/
    /**
     * Get an education based on its id.
     *
     * @param $id
     * @return array
     */
    public function getOne($id) {
        $sql = "SELECT $this->table.* FROM $this->table
            INNER JOIN $this->departmentTable 
            ON $this->table.dep_id = $this->departmentTable.id_dep 
            WHERE $this->table.id= :id";
        $query = $this->db->prepare($sql);
        $query->execute([
            ':id'   => $id
        ]);
        $results = $query->fetch(\PDO::FETCH_ASSOC);
        $results = $this->imagePathManager($results);
        return $this->datesManager($results);
    }
#endregion


#region /******************************* METHOD : add a new education in the database ***********************************/
    /**
     * Add a new education in the database.
     *
     * @param $params
     * @return array
     */
    public function add($params) {
        $sql = "SELECT $this->table.name, $this->table.begin_at FROM $this->table 
        WHERE name= :name";
        $query = $this->db->prepare($sql);
        $query->execute([
            ':name'   => $params['name']
        ]);
        $results = $query->fetch(\PDO::FETCH_ASSOC);
        $tabError = [];
        if ($results['name'] == $params['name'] AND $results['begin_at'] == $params['begin_at']) {
            $tabError['error'] = 'Erreur : cette formation existe déjà dans la base de données';
            return $tabError;
        } else {
            if (empty($params['end_at'])) {
                $params['end_at'] = '9999-01-01';
            }
            $sql = "INSERT INTO $this->table(name, type, etablissement, ville, begin_at, end_at, image_path, mention, dep_id)
                VALUES (:name, :type, :etablissement, :ville, :begin_at, :end_at, :image_path, :mention, :dep_id)";
            $query = $this->db->prepare($sql);
            return $query->execute([
                ':name'             => $params['name'],
                ':type'             => $params['type'],
                ':etablissement'    => $params['etablissement'],
                ':ville'            => $params['ville'],
                ':begin_at'         => $params['begin_at'],
                ':end_at'           => $params['end_at'],
                ':image_path'       => $params['image_path'],
                ':mention'          => $params['mention'],
                ':dep_id'           => $params['dep_id']
            ]);
        }
    }
#endregion



#region /******************************* METHOD : update an education in the database ***********************************/
    /**
     * Update an education which is in the database.
     *
     * @param $params (id, name, type, etablissement, ville, begin_at, end_at, image_path, mention, dep_id)
     * @return array
     */
    public function update($params) {
        if (empty($params['end_at'])) {
            $params['end_at'] = '9999-01-01';
        }
        $sql = "UPDATE $this->table SET name = :name, type = :type, etablissement = :etablissement, ville = :ville,
            begin_at = :begin_at, end_at = :end_at, image_path = :image_path, mention = :mention, dep_id = :dep_id
            WHERE id= :id";
        $query = $this->db->prepare($sql);
        return $query->execute([
            ':name'             => $params['name'],
            ':type'             => $params['type'],
            ':etablissement'    => $params['etablissement'],
            ':ville'            => $params['ville'],
            ':begin_at'         => $params['begin_at'],
            ':end_at'           => $params['end_at'],
            ':image_path'       => $params['image_path'],
            ':mention'          => $params['mention'],
            ':dep_id'           => $params['dep_id'],
            ':id'               => $params['id']
        ]);
    }
#endregion


#region /******************************* METHOD : delete an education in the database ***********************************/
    /**
     * Delete an education in the database based on its id.
     *
     * @param $id
     * @return array
     */
    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE id= :id";
        $query = $this->db->prepare($sql);
        return $query->execute([
            ':id'   => $id
        ]);
    }
#endregion


#region /******************************* METHOD : dates management *****************************************************/
    /**
     * Manage dates from an array.
     *
     * @param $param
     * @return array
     */
    public function datesManager($param)
    {
        $nb = max(array_map( 'count',$param));
        if ($nb > 1) {
            $results = [];
            foreach ($param as $item) {
                $begin_at = date_parse($item['begin_at']);
                $item['crud_begin_at'] = $begin_at['day'] . '/' . $begin_at['month'] . '/' . $begin_at['year'];
                $begin_at = $begin_at['year'];
                if ($item['end_at'] == '9999-01-01') {
                    $item['end_at'] = date('d-m-y');
                    $item['now'] = date('d-m-y');
                } else {
                    $end_at = date_parse($item['end_at']);
                    $item['crud_end_at'] = $end_at['day'] . '/' . $end_at['month'] . '/' . $end_at['year'];
                    $end_at = $end_at['year'];
                    $mention = $item['type'];
                    if ($mention == 'Formation') {
                        $item['date'] = $begin_at . ' - ' . $end_at;
                    } else {
                        $item['date'] = $end_at;
                    }
                }
                $results[] = $item;
            }
            return $results;
        } else {
            $begin_at = date_parse($param['begin_at']);
            $param['crud_begin_at'] = $begin_at['day'] . '/' . $begin_at['month'] . '/' . $begin_at['year'];
            $begin_at = $begin_at['year'];
            if ($param['end_at'] == '9999-01-01') {
                $param['end_at'] = date('d-m-y');
                $param['now'] = date('d-m-y');
            } else {
                $end_at = date_parse($param['end_at']);
                $param['crud_end_at'] = $end_at['day'] . '/' . $end_at['month'] . '/' . $end_at['year'];
                $end_at = $end_at['year'];
                $mention = $param['type'];
                if ($mention == 'Formation') {
                    $param['date'] = $begin_at . ' - ' . $end_at;
                } else {
                    $param['date'] = $end_at;
                }
            }
            return $param;
        }
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
        $nb = max(array_map( 'count',$param));
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
#endregion
}