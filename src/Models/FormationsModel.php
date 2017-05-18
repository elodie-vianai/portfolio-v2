<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 13/04/2017
 * Time: 10:00
 */

namespace Portfolio\Models;

use function FastRoute\TestFixtures\empty_options_cached;
use Portfolio\Models\Entities\Formation;

require_once 'Database.php';

class FormationsModel extends Database
{
#region /******************************* RÉCUPÉRATION DE L'ENSEMBLE DES FORMATIONS **************************************/
    /**
     * @return array
     */
    public function getAllFormations() {
        $sql = 'SELECT * FROM formation 
            JOIN departement ON dep_id = departement.id_dep
            ORDER BY end_at DESC ';
        $requete = $this->executerRequete($sql);;
        $formations = $requete->fetchAll(\PDO::FETCH_ASSOC);
        return $formations;
    }
#endregion


#region /******************************* RÉCUPÉRATION D'UNE FORMATION ***************************************************/
    /**
     * @param $id
     * @return mixed
     */
    public function getFormation($id) {
        $sql = 'SELECT * FROM formation WHERE id='.$id;
        $requete = $this->executerRequete($sql);
        $formation = $requete->fetch(\PDO::FETCH_ASSOC);
        return $formation;
    }
#endregion


#region /******************************* AJOUT D'UNE NOUVELLE FORMATION *************************************************/
    /**
     * @param $name
     * @param $type
     * @param $etablissement
     * @param $ville
     * @param $dep
     * @param $begin_at
     * @param $end_at
     * @param $logo
     * @param $mention
     * @return string
     */
    public function addFormation($name, $type, $etablissement, $ville, $begin_at, $end_at, $logo, $mention, $dep) {
        if ((!isset($name)) OR (!isset($type)) OR (!isset($etablissement)) OR (!isset($dep)) OR (!isset($begin_at))) {
            $msgError = 'Certains champs ne sont pas remplis correctement, merci d\'y remédier';
            return $msgError;
        }
        else {
            $sql = 'SELECT name, begin_at FROM formation WHERE name="'.$name.'"';
            $requete = $this->executerRequete($sql);
            $results = $requete->fetch(\PDO::FETCH_ASSOC);
            $tabError = [];
            if (($results['name'] == $name) AND ($results['begin_at']) == $begin_at) {
                $tabError['error'] = 'Erreur : cette formation existe déjà dans la base de données';
                return $tabError;
            }
            else {
                $sql = 'INSERT INTO formation(name, type, etablissement, ville, begin_at, end_at, logo, mention, dep_id)
                VALUES ("'.$name.'","'.$type.'","'.$etablissement.'","'.$ville.'","'.$begin_at.'","'.$end_at.'","'.$logo.'","'.
                    $mention.'","'.$dep.'")';
                $this->executerRequete($sql);
            }
        }
    }
#endregion


#region /******************************* SUPPRESSION D'UNE NOUVELLE FORMATION *******************************************/
    /**
     * @param $id
     */
    public function deleteFormation($id) {
        $sql = 'DELETE FROM formation WHERE id='.$id;
        $this->executerRequete($sql);
    }
#endregion


#region /******************************* MODIFICATION D'UNE FORMATION ***************************************************/
    public function updateFormation($id, $name, $type, $etablissement, $ville, $begin_at, $end_at, $logo, $mention, $dep)
    {
        $sql = 'UPDATE formation SET name="'.$name.'",type="'.$type.'",etablissement="'.$etablissement.'",
            ville="'.$ville.'",begin_at="'.$begin_at.'",end_at="'.$end_at.'",logo="'.$logo.'",mention="'.$mention.'",
            dep_id="'.$dep.'" WHERE id='.$id;
        $this->executerRequete($sql);
    }
#endregion


}