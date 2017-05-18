<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 13/04/2017
 * Time: 17:11
 */

namespace Portfolio\Controller\Admin;

use Portfolio\Models\FormationsModel;


// ENREGISTREMENT DE L'AUTOLOAD
spl_autoload('FormationsModel', '.php');

class FormationsControllerAdmin
{
#region /******************************* ATTRIBUTES *********************************************************************/
    private $formation;
#endregion


#region /******************************* CONSTRUCTOR ********************************************************************/
    /**
     * FormationsControllerAdmin constructor.
     */
    public function __construct()
    {
        $this->formation = new FormationsModel('mysql:host=localhost;dbname=portfolio');
    }
#endregion



#region /******************************* METHOD : CRUD *****************************************************************/
    /**
     * @return array
     */
    public function displayAllFormations()
    {
        $tableauFormations = $this->formation->getAllFormations();
        $tabForm = [];
        foreach ($tableauFormations as $formation) {
            if ($formation['mention'] == 'P') {
                $formation['mention'] = ' ';
            }
            $tabForm[] = $formation;
        }
        $tableauFormations = $this->gestionImage($tabForm);
        $tableauFormations = $this->formatageDates($tableauFormations);
        return $tableauFormations;
    }
#endregion


#region /******************************* METHOD : FORMATAGE DES DATES ***************************************************/
    /**
     * @param $tableauFormations
     * @return array
     */
   public function formatageDates($tableauFormations)
    {
        $tabForm = [];
        foreach ($tableauFormations as $form) {
            $dateD = new \DateTime($form['begin_at'], new \DateTimeZone('Europe/Paris'));
            $dateF = new \DateTime($form['end_at'], new \DateTimeZone('Europe/Paris'));
            $form['begin_at'] = $dateD->format('d-m-Y');
            $form['end_at'] = $dateF->format('d-m-Y');
            $tabForm[] = $form;
        }
        return $tabForm;
    }
#endregion


#region /******************************* METHOD : GESTION DES IMAGES ****************************************************/
    /**
     * @param $tableauFormations
     * @return array
     */
    public function gestionImage($tableauFormations)
    {
        $tableauFinal = [];
        foreach ($tableauFormations as $tableauTransitoire) {
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


#region /******************************* METHOD : AJOUTER UNE NOUVELLE FORMATION ****************************************/
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
    public function addFormation($name, $type, $etablissement, $ville, $begin_at, $end_at, $logo, $mention, $dep)
    {
        if ((!isset($name)) OR (!isset($type)) OR (!isset($begin_at)) OR (!isset($etablissement)) OR (!isset($ville)) OR (!isset($dep))) {
            if (!isset($name)) {
                $msgError = 'Erreur : veuillez rentrer le nom de la formation !';
                return $msgError;
            } elseif (!isset($type)) {
                $msgError = 'Erreur : veuillez rentrer le type de formation suivie !';
                return $msgError;
            } elseif (!isset($begin_at)) {
                $msgError = 'Erreur : veuillez rentrer une date de début pour la formation !';
                return $msgError;
            } elseif (!isset($etablissement)) {
                $msgError = 'Erreur : veuillez rentrer un établissmeent pour la formation !';
                return $msgError;
            } elseif (!isset($ville)) {
                $msgError = 'Erreur : veuillez rentrer une ville pour l\'établissement de formation !';
                return $msgError;
            } elseif (!isset($dep)) {
                $msgError = 'Erreur : veuillez rentrer un département pour l\'établissement de formation !';
                return $msgError;
            }
        }
        else {
            $results = $this->formation->addFormation($name, $type, $etablissement, $ville, $begin_at, $end_at, $logo, $mention, $dep);
            return $results;
        }
    }
#endregion


#region /******************************* METHOD : SUPPRIMER UNE FORMATION ***********************************************/
    /**
     * @param $id
     * @return string
     */
    public function deleteFormation($id)
    {
        if (isset($id)) {
            $this->formation->deleteFormation($id);
        }
        else {
            $msgError = 'Erreur : il semblerait qu\'il y ait un problème !';
            return $msgError;
        }
    }
#endregion


#region /******************************* METHOD : MODIFIER UNE FORMATION ************************************************/
    #region -> Récupération d'une formation précise
    /**
     * @param $id
     * @return mixed
     */
    public function getFormation($id)
    {
        $tableauInfosFormation = $this->formation->getFormation($id);
        $begin_at = new \DateTime($tableauInfosFormation['begin_at'], new \DateTimeZone('Europe/Paris'));
        $end_at = new \DateTime($tableauInfosFormation['end_at'], new \DateTimeZone('Europe/Paris'));
        $tableauInfosFormation['begin_at'] = $begin_at->format('Y-m-d');
        $tableauInfosFormation['end_at'] = $end_at->format('Y-m-d');
        return $tableauInfosFormation;
    }
    #endregion

    #region -> Modification de la formation
    /**
     * @param $id
     * @param $name
     * @param $type
     * @param $etablissement
     * @param $ville
     * @param $begin_at
     * @param $end_at
     * @param $logo
     * @param $mention
     * @param $dep
     */
    public function updateFormation($id, $name, $type, $etablissement, $ville, $begin_at, $end_at, $logo, $mention, $dep)
    {
        $formationUpdated = $this->formation->updateFormation($id, $name, $type, $etablissement, $ville, $begin_at,
                $end_at, $logo, $mention, $dep);
            return $formationUpdated;
        //}
    }
    #endregion
#endregion
}