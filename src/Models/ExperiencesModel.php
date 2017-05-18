<?php

/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 07/04/2017
 * Time: 17:04
 */

namespace Portfolio\Models;

use Portfolio\Models\Entities\Experience;

require_once 'Database.php';

class ExperiencesModel extends Database
{
#region /******************************* RÉCUPÉRATION DE L'ENSEMBLE DES EXPÉRIENCES *************************************/
    /**
     * @return array
     */
    public function getAllExperiences() {
        $sql = 'SELECT * FROM experience 
          JOIN departement ON dep_id = departement.id_dep
          ORDER BY end_at DESC';
        $requete = $this->executerRequete($sql);
        $experiences = $requete->fetchAll(\PDO::FETCH_ASSOC);
        return $experiences;
    }
#endregion


#region /******************************* RÉCUPÉRATION D'UNE EXPÉRIENCE **************************************************/
    #region -> récupération de l'expérience en fonction de l'id
    /**
     * @param $id
     * @return mixed
     */
    public function getExperience($id) {
        $sql = 'SELECT * FROM experience 
            INNER JOIN departement ON dep_id = departement.id_dep WHERE experience.id='.$id;
        $requete = $this->executerRequete($sql);
        $experience = $requete->fetch(\PDO::FETCH_ASSOC);
        return $experience;
    }
    #endregion
#endregion


#region /******************************* AJOUT D'UNE NOUVELLE EXPÉRIENCE ************************************************/
    public function addExperience($begin_at, $end_at, $contrat, $name, $entreprise, $ville, $logo, $dep_id) {
        $sql = 'SELECT name, begin_at, end_at, entreprise FROM experience 
          WHERE name="'.$name.'" AND begin_at="'.$begin_at.'" AND entreprise="'.$entreprise.'"';
        $requete = $this->executerRequete($sql);
        $results = $requete->fetch(\PDO::FETCH_ASSOC);
        $tabError = [];
        if (($results['name'] == $name) AND ($results['begin_at'] == $begin_at) AND ($results['entreprise'] == $entreprise)) {
            $tabError['error'] = 'Erreur : cette expérience existe déjà dans la base de données';
            return $tabError;
        }
        else {
            $sql = 'INSERT INTO experience(name, contrat, entreprise, ville, begin_at, end_at, logo, dep_id) 
              VALUES("'.$name.'","'.$contrat.'","'.$entreprise.'","'.$ville.'","'.$begin_at.'","'.$end_at.'","'.$logo.'", 
                (SELECT id_dep FROM departement WHERE id_dep='.$dep_id.'))';
              /*VALUES (?,?,?,?,?,?,?)';*/
            $this->executerRequete($sql/*, array($name, $contrat, $entreprise, $ville, $begin_at, $end_at, $logo)*/);
            $sql = 'SELECT id FROM experience WHERE name="'.$name.'" AND begin_at="'.$begin_at.'" AND end_at="'.$end_at.'" AND entreprise="'.$entreprise.'"';
            $requete = $this->executerRequete($sql);
            $newExperience = $results['id'] = $requete->fetch(\PDO::FETCH_ASSOC);
            return $newExperience;
        }
    }
#endregion


#region /******************************* SUPPRESSION D'UNE NOUVELLE EXPÉRIENCE ******************************************/
    /**
     * @param $id
     */
    public function deleteExperience($id) {
        $sql = 'DELETE FROM experience WHERE id='.$id;
        $this->executerRequete($sql);
    }
#endregion


#region /******************************* MODIFICATION D'UNE EXPÉRIENCE **************************************************/
    public function updateExperience($id, $name, $contrat, $entreprise, $ville, $dep_id, $begin_at, $end_at, $logo) {
        $sql = 'UPDATE experience SET name="'.$name.'", contrat="'.$contrat.'", entreprise="'.$entreprise.'",
        ville="'.$ville.'", dep_id ="'.$dep_id.'", begin_at="'.$begin_at.'", end_at="'.$end_at.'", logo="'.$logo.'" WHERE id='.$id;
        $this->executerRequete($sql);
        /* $sql = 'UPDATE experience SET name= :name, contrat= :contrat, entreprise= :entreprise,
        ville= :ville, begin_at= :begin_at, end_at= :end_at, logo= :logo WHERE id='.$id;
        $this->executerRequete($sql, ['name'=>$name, 'contrat'=>$contrat, 'entreprise'=>$entreprise, 'ville'=>$ville,
        'begin_at'=>$begin_at, 'end_at'=>$end_at, 'logo'=>$logo); */
    }
#endregion

}
