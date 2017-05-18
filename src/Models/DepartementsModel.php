<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 21/04/2017
 * Time: 15:37
 */

namespace Portfolio\Models;


require_once 'Database.php';

class DepartementsModel extends Database
{
#region /******************************* RÉCUPÉRATION DE L'ENSEMBLE DES DÉPARTEMENTS ************************************/
    /**
     * @return array
     */
    public function getAllDep() {
        $sql = 'SELECT * FROM departement ORDER BY code ASC';
        $requete = $this->executerRequete($sql);;
        $departements = $requete->fetchAll(\PDO::FETCH_ASSOC);
        return $departements;
    }
#endregion
}