<?php

/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 13/04/2017
 * Time: 09:24
 */

namespace Portfolio\Controller;

use Portfolio\Models\ProjectsModel;
use Portfolio\Models\TechnologiesModel;

// ENREGISTREMENT DE L'AUTOLOAD
spl_autoload('ProjectsModel', '.php');
spl_autoload('TechnologiesModel', '.php');

class ProjectsController
{
#region /******************************* ATTRIBUTS **********************************************************************/
    private $projet;
    private $technology;
#endregion


#region /******************************* CONSTRUCTORS *******************************************************************/
    /**
     * ProjectsController constructor.
     */
    public function __construct()
    {
        $this->projet = new ProjectsModel('mysql:host=localhost;dbname=portfolio');
        $this->technology = new TechnologiesModel('mysql:host=localhost;dbname=portfolio');
    }
#endregion


#region /******************************* METHOD : affichage de tous les projets *****************************************/
    /**
     * @return array
     */
    public function formatageProjets()
    {
        $tableauProjets = $this->projet->getAllProjects();
        $tableauProjets = $this->gestionImageProjets($tableauProjets);
        return $tableauProjets;
    }
#endregion


#region /******************************* METHOD : affichage des 4 derniers projets (diaporama) **************************/
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


#region /******************************* METHOD : affichage des 4 derniers projets (diaporama) **************************/
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


#region /******************************* METHOD : affichage du détail d'un projet ***************************************/
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


#region /******************************* METHOD : gestion du chemin des images ******************************************/
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


}