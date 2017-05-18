<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 13/04/2017
 * Time: 09:36
 */

namespace Portfolio\Models;

require_once 'Database.php';

class ProjectsModel extends Database
{
#region /*********************************** RÉCUPÉRATION DE L'ENSEMBLE DES PROJETS *************************************/
    /**
     * @return array
     */
    public function getAllProjects() {
        $sql = 'SELECT * FROM project ORDER BY year DESC ';
        $requete = $this->executerRequete($sql);;
        $projets = $requete->fetchAll(\PDO::FETCH_ASSOC);
        return $projets;
    }
#endregion


#region /*************************** RÉCUPÉRATION D'1 PROJET SPÉCIFIQUE *************************************************/
    #region --> récupérer un projet en fonction de son id
    /**
     * @param $id
     * @return mixed
     */
    public function getProject($element) {
        $sql = 'SELECT * FROM project WHERE id="'.$element.'" OR name="'.$element.'" ORDER BY year DESC ';
        $requete = $this->executerRequete($sql);;
        $projet = $requete->fetch(\PDO::FETCH_ASSOC);
        return $projet;
    }
    #endregion

    #region --> récupérer les 4 derniers projets rentrés dans la base de données
    /**
     *
     */
    public function get4LastProjects() {
        $sql = 'SELECT * FROM project ORDER BY year DESC LIMIT 4';
        $requete = $this->executerRequete($sql);
        $lastProjects = $requete->fetchAll(\PDO::FETCH_ASSOC);
        return $lastProjects;
       }
    #endregion

    #region --> récupérer le dernier projet rentré dans la base de données
    /**
     *
     */
    public function getLastProject() {
        $sql = 'SELECT * FROM project ORDER BY id DESC LIMIT 1';
        $requete = $this->executerRequete($sql);
        $lastProject = $requete->fetch(\PDO::FETCH_ASSOC);
        return $lastProject;
    }
    #endregion
#endregion


#region /*************************** RÉCUPÉRATION DES PROJETS EN FONCTION DES EXPÉRIENCES ******************************/
    public function getprojectExp($idExp) {

        $sql = 'SELECT * FROM project 
          JOIN experience_has_project ON experience_has_project.project_id = project.id
          WHERE experience_has_project.experience_id ='.$idExp;
        $requete = $this->executerRequete($sql);;
        $projet = $requete->fetchAll(\PDO::FETCH_ASSOC);
        return $projet;
    }
#endregion


#region /*************************************** AJOUT D'UN NOUVEAU PROJET **********************************************/
    #region --> ajout du projet
    /**
     * @param $name
     * @param $year
     * @param $image_path
     * @param $description
     * @return string
     */
    public function addProject($name, $year, $image_path, $description) {
        $sql = 'SELECT name, year FROM project WHERE name="'.$name.'" AND year="'.$year.'"';
        $requete = $this->executerRequete($sql);
        $results = $requete->fetch(\PDO::FETCH_ASSOC);
        $tabError = [];
        if (($results['name'] == $name) AND ($results['year']) == $year) {
            $tabError['error'] = 'Erreur : cette technologie existe déjà dans la base de données';
            return $tabError;
        }
        else {
            $sql = 'INSERT INTO project(name, description, image_path, year) VALUES("'.$name.'","'.$description.'","'.
                $image_path.'","'.$year.'")';
            $this->executerRequete($sql);
            $sql = 'SELECT id FROM project WHERE name="'.$name.'" AND year="'.$year.'"';
            $requete = $this->executerRequete($sql);
            $newProject = $result['id'] = $requete->fetch(\PDO::FETCH_ASSOC);
            return $newProject;
        }
    }
    #endregion
#endregion


#region /******************************* MODIFICATION D'UN PROJET *******************************************************/
    public function updateProject($id, $name, $year, $image_path, $description)
    {
        $sql = 'UPDATE project SET name="'.$name.'",year="'.$year.'",image_path="'.$image_path.'",
            description="'.$description.'" WHERE id='.$id;
        $this->executerRequete($sql);
    }
#endregion


#region /***************************************** SUPPRESSION D'UN PROJET **********************************************/
    /**
     * @param $id
     */
    public function deleteProject($id) {
        $sql = 'DELETE FROM project WHERE id='.$id;
        $this->executerRequete($sql);
    }
#endregion


#region /******************************* AJOUT D'UNE ASSOCIATION ENTRE 1 EXPÉRIENCE ET 1 PROJET (nouvelle expérience) ***/
    public function addExperienceHasProject($idNewExperience, $projects) {
        foreach ($projects as $project) {
            $sql = 'INSERT INTO experience_has_project(experience_id, project_id) VALUES('.$idNewExperience.','.$project.')';
            $this->executerRequete($sql);
        }
    }
#endregion


##region /******************************* MODIFICATION DE L'ASSOCIATION ENTRE 1 EXPÉRIENCE ET 1 PROJET ******************/
    public function updateExperienceHasProject($idExperience, $projects)
    {
        $sql = 'SELECT * FROM experience_has_project WHERE experience_id=' . $idExperience;
        $requete = $this->executerRequete($sql);
        $tabresults = $requete->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($tabresults)) {
            foreach ($projects as $project) {
                $sql = 'INSERT INTO experience_has_project(experience_id, project_id) 
                    VALUES('.$idExperience.','.$project.')';
                $this->executerRequete($sql);
            }
        }
        else {;
            foreach ($tabresults as $result) {
                $sql = 'DELETE FROM experience_has_project
                  WHERE experience_id="'.$result['experience_id'].'" AND project_id="'.$result['project_id'].'"';
                $this->executerRequete($sql);
            }
            foreach ($projects as $project) {
                $sql = 'INSERT INTO experience_has_project(experience_id, project_id) 
                    VALUES('.$idExperience.','.$project.')';
                $this->executerRequete($sql);
            }
        }
    }
#endregion
}