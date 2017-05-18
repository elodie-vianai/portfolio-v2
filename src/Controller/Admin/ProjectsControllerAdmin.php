<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 13/04/2017
 * Time: 17:20
 */

namespace Portfolio\Controller\Admin;

use Portfolio\Models\ProjectsModel;
use Portfolio\Models\TechnologiesModel;


// ENREGISTREMENT DE L'AUTOLOAD
spl_autoload('ProjectsModel', '.php');

class ProjectsControllerAdmin
{
#region /******************************* ATTRIBUTES *********************************************************************/
    private $projet;
    private $technology;
#endregion


#region /******************************* CONSTRUCTOR ********************************************************************/
    /**
     * ProjectsControllerAdmin constructor.
     */
    public function __construct()
    {
        $this->projet = new ProjectsModel('mysql:host=localhost;dbname=portfolio');
        $this->technology = new TechnologiesModel('mysql:host=localhost;dbname=portfolio');
    }
#endregion



#region /******************************* METHOD : CRUD *****************************************************************/
    #region -> Affichage de tous les projets
    /**
     * @return array
     */
    public function displayAllProjects()
    {
        $tableauProjets = $this->projet->getAllProjects();
        $tableauProjets = $this->gestionImageProjets($tableauProjets);
        return $tableauProjets;
    }
    #endregion
#endregion


#region /******************************* METHODS : Récupération d'un ou des projet(s) ***********************************/
    #region --> Récupération du projet en fonction de son id
    /**
     * @param $element
     * @return array
     * @internal param $id
     */
    public function getProject($element)
    {
        $tableauProjet = $this->projet->getProject($element);
        return $tableauProjet;
    }
    #endregion

    #region --> récupération des projets selon l'expérience
    /**
     * @param $idExp
     * @return array
     */
    public function getExperienceHasProjects ($idExp) {
            $tableauProjetsExp = $this->projet->getprojectExp($idExp);
            return $tableauProjetsExp;
        }
    #endregion

    #region --> récupération des 4 derniers projets en date
    /**
     * @return array
     */
    public function get4LastProject () {
        $lastProjects = $this->projet->get4LastProjects();
        $lastProjects = $this->gestionImageProjets($lastProjects);
        return $lastProjects;
    }
    #endregion

    #region --> récupération et affichage des 4 derniers projets (diaporama)
    /**
     * @return array
     */
    public function get4LastProjects () {
        $tablastProjects = $this->projet->get4LastProjects();
        $tablastProjects = $this->gestionImageProjets($tablastProjects);
        $lastProject = $this->getLastProject();
        $lastProjects= [];
        foreach ($tablastProjects as $project) {
            if ($project['id'] == $lastProject['id']) {
                $project['last'] = 'oui';
            }
            $lastProjects[] = $project;
        }
        return $lastProjects;
    }
    #endregion

    #region --> récupération et affichage des 4 derniers projets (diaporama)
    /**
     * @return array
     */
    public function getLastProject () {
        $lastProject = $this->projet->getLastProject();
        $imageProjet = $lastProject['image_path'];
        $lastProject['image_path'] = $this->gestionImageProjet($imageProjet);
        return $lastProject;
    }
    #endregion
#endregion


#region /******************************* METHODS : affichage du détail d'un projet ***************************************/
    #region --> formatage de l'affichage
    /**
     * @param $id
     * @return mixed
     */
    public function formatageProjet($id)
    {
        $projet = $this->projet->getProject($id);
        $imageProjet = $projet['image_path'];
        $projet['image_path'] = $this->gestionImageProjet($imageProjet);
        return $projet;
    }
    #endregion

    #region --> affichage des technologies associées au projet
    /**
     * @param $id
     * @return array
     */
    public function formatageTechnologies($id)
    {
        $tableauTechnologies = $this->technology->getTechnologiesProject($id);
        return $tableauTechnologies;
    }
    #endregion
#endregion


#region /******************************* METHOD : ajouter un nouveau projet *********************************************/
    /**
     * @param $name
     * @param $year
     * @param $image_path
     * @param $description
     * @return string
     */
    public function addProject($name, $year, $image_path, $description)
    {
        if ((!isset($name)) OR (!isset($year))) {
            if (!isset($name)) {
                $msgError = 'Erreur : veuillez rentrer un nom pour le projet !';
                return $msgError;
            } elseif (!isset($year)) {
                $msgError = 'Erreur : veuillez rentrer une année pour le projet !';
                return $msgError;
            }
        }
        else {
            $results = $this->projet->addProject($name, $year, $image_path, $description);
            return $results;
        }
    }
#endregion


#region /******************************* METHODS : gestion du chemin des images ******************************************/
    #region --> Si plusieurs projets
    /**
     * @param $tableauProjets
     * @return array
     */
    public function gestionImageProjets($tableauProjets)
    {
        $tableauFinal = [];
        foreach ($tableauProjets as $tableauTransitoire) {
            if (!empty($tableauTransitoire['image_path'])) {
                $image = '/projets/'.$tableauTransitoire['image_path'];
            }
            else {
                $image = '/noImage.png';
            }
            $tableauTransitoire['image_path'] = $image;
            $tableauFinal[] = $tableauTransitoire;
        }
        return $tableauFinal;
    }
    #endregion

    #region --> Si un seul projet
    public function gestionImageProjet($imageProjet)
    {
        if (!empty($imageProjet)) {
            $image = '/projets/'.$imageProjet;
            return $image;
        } else {
            $image = '/noImage.png';
            return $image;
        }
    }
    #endregion
#endregion


#region /******************************* METHOD : modifier un projet ****************************************************/
    public function updateProject($id, $name, $year, $image_path, $description)
    {
        $projectUpdated = $this->projet->updateProject($id, $name, $year, $image_path, $description);
        return $projectUpdated;
    }
#endregion


#region /******************************* METHOD : supprimer un projet ***************************************************/
    /**
     * @param $id
     * @return string
     */
    public function deleteProject($id)
    {
        if (isset($id)) {
            $project = $this->projet->deleteProject($id);
            return $project;
        }
        else {
            $msgError = 'Erreur : il semblerait qu\'il y ait un problème !';
            return $msgError;
        }
    }
#endregion


#region /******************************* METHOD : ajout de l'association d'une expérience et des projets ****************/
    public function addExperienceHasProject($idNewExperience, $projects)
    {
        $newExperienceHasProject = $this->projet->addExperienceHasProject($idNewExperience, $projects);
        return $newExperienceHasProject;
    }
#endregion


#region /******************************* METHOD : modification de l'association d'une expérience et des projets *********/
    public function updateExperienceHasProject($idExperience, $projects)
    {
        $experienceHasProjectUpdated = $this->projet->updateExperienceHasProject($idExperience, $projects);
        return $experienceHasProjectUpdated;

    }
#endregion

}