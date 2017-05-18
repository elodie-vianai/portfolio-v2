<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 13/04/2017
 * Time: 17:27
 */

namespace Portfolio\Models;


use Portfolio\Models\Entities\Technology;

class TechnologiesModel extends Database
{

#region /******************************* RÉCUPÉRATION DE L'ENSEMBLE DES TECHNOLOGIES ************************************/
    /**
     * @return array
     */
    public function getAllTechnologies() {
        $sql = 'SELECT * FROM technology ORDER BY name ASC ';
        $requete = $this->executerRequete($sql);;
        $technologies = $requete->fetchAll(\PDO::FETCH_ASSOC);
        return $technologies;
    }
#endregion


#region /******************************* RÉCUPÉRATION D'1 OU PLUSIEURS TECHNOLOGIES EN FONCTION D'UN PROJET *************/
    /**
     * @param $id
     * @return array
     */
    public function getTechnologiesProject($id) {
        $sql = 'SELECT * FROM project_has_technology 
          RIGHT JOIN technology ON technology_id = technology.id 
          WHERE project_id='.$id;
        $requete = $this->executerRequete($sql);
        $technologies = $requete->fetchAll(\PDO::FETCH_NAMED);
        return $technologies;
    }
#endregion


#region /******************************* RÉCUPÉRATION D'UNE TECHNOLOGIE *************************************************/
    public function getTechnology($element) {
        $sql = 'SELECT * FROM technology WHERE id="'.$element.'" OR name="'.$element.'"';
        $requete = $this->executerRequete($sql);;
        $technology = $requete->fetch(\PDO::FETCH_ASSOC);
        return $technology;
    }
#endregion


#region /******************************* AJOUT D'UNE NOUVELLE TECHNOLOGIE ***********************************************/
    /**
     * @param $name
     * @param $image_path
     * @return array
     */
    public function addTechnology($name, $image_path) {
        $sql = 'SELECT * FROM technology WHERE name="'.$name.'"';
        $requete = $this->executerRequete($sql);
        $results = $requete->fetch(\PDO::FETCH_ASSOC);
        $tabError = [];
        if ($results['name'] == $name) {
           $tabError['error'] = 'Erreur : cette technologie existe déjà dans la base de données';
           return $tabError;
        }
       else {
           $sql = 'INSERT INTO technology(name, image_path) VALUES(?,?)';
           $this->executerRequete($sql, array($name, $image_path));
       }
    }
#endregion


#region /******************************* SUPPRESSION D'UNE NOUVELLE TECHNOLOGIE *****************************************/
    /**
     * @param $id
     */
    public function deleteTechnology($id) {
        $sql = 'DELETE FROM technology WHERE id='.$id;
        $this->executerRequete($sql);
    }
#endregion


#region /******************************* MODIFICATION D'UNE TECHNOLOGIE *************************************************/
    public function updateTechnology($id, $name, $image_path) {
        $sql = 'UPDATE technology SET name = :name, image_path = :image_path WHERE id='.$id;
        $this->executerRequete($sql, ['name'=>$name, 'image_path'=>$image_path]);
    }
#endregion


#region /******************************* AJOUT D'UNE ASSOCIATION ENTRE 1 PROJET ET 1 TECHNOLOGIE (nouveaux projets) *****/
    public function addProjectHasTechnology($idNewProject, $technologies) {
        foreach ($technologies as $technology) {
            $sql = 'INSERT INTO project_has_technology(project_id, technology_id) VALUES(' . $idNewProject . ',' . $technology . ')';
            $this->executerRequete($sql);
        }
}
#endregion


#region /******************************* MODIFICATION DE L'ASSOCIATION ENTRE 1 PROJET ET 1 TECHNOLOGIE ******************/
    /**
     * @param $idProject
     * @param $technologies
     */
    public function UpdateProjectHasTechnology($idProject, $technologies)
    {
        $sql = 'SELECT * FROM project_has_technology WHERE project_id=' . $idProject;
        $requete = $this->executerRequete($sql);
        $tabresults = $requete->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($tabresults)) {
            foreach ($technologies as $technology) {
                $sql = 'INSERT INTO project_has_technology(project_id, technology_id) VALUES(' . $idProject . ',' . $technology . ')';
                $this->executerRequete($sql);
            }
        }
        else {;
            foreach ($tabresults as $result) {
                $sql = 'DELETE FROM project_has_technology 
                  WHERE project_id="' . $result['project_id'] . '" AND technology_id="' . $result['technology_id'] . '"';
                $this->executerRequete($sql);
            }
            foreach ($technologies as $technology) {
                $sql = 'INSERT INTO project_has_technology(project_id, technology_id) VALUES(' . $idProject . ',' . $technology . ')';
                $this->executerRequete($sql);
            }
        }
    }
#endregion
}