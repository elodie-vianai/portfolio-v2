<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 06/04/2017
 * Time: 14:56
 */

namespace Portfolio\Controller\Admin;

use function FastRoute\simpleDispatcher;
use Portfolio\Models\ExperiencesModel;
use Portfolio\Models\ProjectsModel;


// ENREGISTREMENT DE L'AUTOLOAD
spl_autoload('ExperiencesModel', '.php');

class ExperiencesControllerAdmin
{
#region /******************************* ATTRIBUTES *********************************************************************/
    private $experience;
    private $projet;
#endregion


#region /******************************* CONSTRUCTOR ********************************************************************/
    /**
     * ExperiencesControllerAdmin constructor.
     */
    public function __construct()
    {
        $this->experience = new ExperiencesModel('mysql:host=localhost;dbname=portfolio');
        $this->projet = new ProjectsModel('mysql:host=localhost;dbname=portfolio');
    }
#endregion



#region /******************************* METHOD : CRUD *****************************************************************/
    /**
     * @return array
     */
    public function displayAllExperiences()
    {
        $tableauExperiences = $this->experience->getAllExperiences();
        $tableauExperiences = $this->formatageDates($tableauExperiences);
        $tableauExperiences = $this->gestionImage($tableauExperiences);
        return $tableauExperiences;
    }
#endregion


#region /******************************* METHOD : ajouter une nouvelle expérience ***************************************/
    public function addExperience($begin_at, $end_at, $contrat, $name, $entreprise, $ville, $logo, $dep_id)
    {
        if ((!isset($name)) OR (!isset($begin_at)) OR (!isset($contrat)) OR (!isset($entreprise)) OR (!isset($ville)) /*OR (!isset($dep_id))*/) {
            if (!isset($name)) {
                $msgError = 'Erreur : veuillez rentrer un nom pour la technologie !';
                return $msgError;
            } elseif (!isset($begin_at)) {
                $msgError = 'Erreur : veuillez rentrer une date de début !';
                return $msgError;
            } elseif (!isset($contrat)) {
                $msgError = 'Erreur : veuillez rentrer le type de contrat !';
                return $msgError;
            } elseif (!isset($entreprise)) {
                $msgError = 'Erreur : veuillez rentrer le nom de l\'entreprise !';
                return $msgError;
            } elseif (!isset($ville)) {
                $msgError = 'Erreur : veuillez rentrer le nom de la ville !';
                return $msgError;
            } elseif (!isset($dep_id)) {
                $msgError = 'Erreur : veuillez renseigner le département !';
                return $msgError;
            }
        }
        else {
            $results = $this->experience->addExperience($begin_at, $end_at, $contrat, $name, $entreprise, $ville, $logo, $dep_id);
            return $results;
        }
    }
#endregion


#region /******************************* METHOD : supprimer une expérience *********************************************/
    /**
     * @param $id
     * @return string
     */
    public function deleteExperience($id)
    {
        if (isset($id)) {
            $this->experience->deleteExperience($id);
        }
        else {
            $msgError = 'Erreur : il semblerait qu\'il y ait un problème !';
            return $msgError;
        }
    }
#endregion


#region /******************************* METHOD : modifier une expérience ***********************************************/
    #region --> Récupération d'une expérience précise
    /**
     * @param $id
     * @return mixed
     */
        public function getExperience($id)
    {
        $tableauInfosExperience = $this->experience->getExperience($id);
        $begin_at = new \DateTime($tableauInfosExperience['begin_at'], new \DateTimeZone('Europe/Paris'));
        $end_at = new \DateTime($tableauInfosExperience['end_at'], new \DateTimeZone('Europe/Paris'));
        $tableauInfosExperience['begin_at'] = $begin_at->format('Y-m-d');
        $tableauInfosExperience['end_at'] = $end_at->format('Y-m-d');
        return $tableauInfosExperience;
    }
    #endregion

    #region --> Récupération et affichage des projets associés à une expérience
    /**
     * @param $id
     * @return array
     */
    public function getExperienceHasProjects($id)
    {
        $tableauProjets = $this->projet->getprojectExp($id);
        return $tableauProjets;
    }
    #endregion

    #region --> Modification de l'expérience
    public function updateExperience($id, $name, $contrat, $entreprise, $ville, $dep_id, $begin_at, $end_at, $logo)
    {
        var_dump('controller');
        $experienceUpdated = $this->experience->updateExperience($id, $name, $contrat, $entreprise, $ville, $dep_id, $begin_at, $end_at, $logo);
        return $experienceUpdated;
    }
    #endregion
#endregion


#region /******************************* METHOD : formatage des dates ***************************************************/
    /**
     * @param $tableauExperiences
     * @return array
     */
    public function formatageDates($tableauExperiences)
    {
        $tabExp = [];
        foreach ($tableauExperiences as $exp) {
            $dateD = new \DateTime($exp['begin_at'], new \DateTimeZone('Europe/Paris'));
            $dateF = new \DateTime($exp['end_at'], new \DateTimeZone('Europe/Paris'));
            $exp['begin_at'] = $dateD->format('d-m-Y');
            $exp['end_at'] = $dateF->format('d-m-Y');
            $tabExp[] = $exp;
        }
        return $tabExp;
    }
#endregion


#region /******************************* METHOD : gestion des images ****************************************************/
    /**
     * @param $tableauExperiences
     * @return array
     */
    public function gestionImage($tableauExperiences)
    {
        $tableauFinal = [];
        foreach ($tableauExperiences as $tableauTransitoire) {
            if (!empty($tableauTransitoire['logo'])) {
                $image = '/logos/'.$tableauTransitoire['logo'];
            }
            else {
                $image = '/noImage.png';
            }
            $tableauTransitoire['logo'] = $image;
            $tableauFinal[] = $tableauTransitoire;
        }
        return $tableauFinal;
    }
#endregion
}