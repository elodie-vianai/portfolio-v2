<?php

namespace Portfolio\Model;

use Portfolio\Portfolio\Model;

class Department extends Model
{
    protected $table = 'departement';


#region /******************************* METHOD : get all departments **************************************************/
    /**
     * Get an array of all departments from the database.
     *
     * @return array
     */
    public function getAll() {
        $table = $this->table;
        $sql = "SELECT $table.* FROM $table ORDER BY departement.code ASC";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
#endregion

}