<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 13/04/2017
 * Time: 17:27
 */

namespace Portfolio\Controller\Admin;

use Portfolio\Models\TechnologiesModel;


// ENREGISTREMENT DE L'AUTOLOAD
spl_autoload('TechnologiesModel', '.php');

class TechnologiesControllerAdmin
{
#region /******************************* ATTRIBUTES *********************************************************************/
    private $technology;
#endregion


#region /******************************* CONSTRUCTOR ********************************************************************/
    /**
     * TechnologiesControllerAdmin constructor.
     */
    public function __construct()
    {
        $this->technology = new TechnologiesModel('mysql:host=localhost;dbname=portfolio');
    }
#endregion



#region /******************************* METHODS : CRUD *****************************************************************/
    #region -> Affichage de toutes les technologies
    /**
     * @return array
     */
    public function displayAllTechnologies()
    {
        $tableauTechnologies = $this->technology->getAllTechnologies();
        return $tableauTechnologies;
    }
    #endregion

    #region -> Affichage des technologies liées à un projet
    /**
     * @param $idProject
     * @return array
     */
    public function ProjectTechnologies($idProject)
    {
        $tableauTechnologies = $this->technology->getTechnologiesProject($idProject);
        return $tableauTechnologies;
    }
    #endregion
#endregion


#region /******************************* METHOD : Récupération d'un projet précis ***********************************/
    /**
     * @param $id
     * @return mixed
     */
    public function getTechnology($element)
    {
        $tableauTechno = $this->technology->getTechnology($element);
        return $tableauTechno;
    }
#endregion


#region /******************************* METHOD : ajouter une nouvelle technologie **************************************/
    /**
     * @param $name
     * @param $image_path
     * @return string
     */
    public function addTechnology($name, $image_path)
    {
        if ((!isset($name)) OR (!isset($image_path))) {
            if (!isset($name)) {
                $msgError = 'Erreur : veuillez rentrer un nom pour la technologie !';
                return $msgError;
            } elseif (!isset($$image_path)) {
                $msgError = 'Erreur : veuillez rentrer une image pour la technologie !';
                return $msgError;
            }
        }
        else {
            $tabError = $this->technology->addTechnology($name, $image_path);
            return $tabError;
        }
    }
#endregion


#region /******************************* METHOD : supprimer une technologie *********************************************/
    /**
     * @param $id
     * @return string
     */
    public function deleteTechnology($id)
    {
        if (isset($id)) {
            $techno = $this->technology->deleteTechnology($id);
            return $techno;
        }
        else {
            $msgError['error'] = 'Erreur : il semblerait qu\'il y ait un problème !';
            return $msgError;
        }
    }
#endregion


#region /******************************* METHOD : modifier une technologie **********************************************/
    #region --> Modification de la technologie
    public function updateTechnology($id, $name,$image_path)
    {
        if ((!isset($id)) OR (!isset($name)) OR (!isset($image_path))) {
            if (!isset($name)) {
                $msgError['error'] = 'Erreur : veuillez rentrer un nom pour la technologie !';
                return $msgError;
            } elseif (!isset($$image_path)) {
                $msgError['error'] = 'Erreur : veuillez rentrer une image pour la technologie !';
                return $msgError;
            }
        }
        else {
            $techUpdated = $this->technology->updateTechnology($id, $name, $image_path);
            return $techUpdated;
        }
    }
    #endregion
#endregion


#region /******************************* METHOD : ajout de l'association d'un projet et des technologies ****************/
    public function addProjectHasTechnology($idNewProject, $technologies)
    {
        $newProjectHasTechno = $this->technology->addProjectHasTechnology($idNewProject, $technologies);
        return $newProjectHasTechno;
        //}
    }
#endregion


#region /******************************* METHOD : modification de l'association d'un projet et des technologies *********/
    public function updateProjectHasTechnology($idProjet, $technologies)
    {
        $projectHasTechnoUpdated = $this->technology->updateProjectHasTechnology($idProjet, $technologies);
        return $projectHasTechnoUpdated;
        //}
    }
#endregion
}