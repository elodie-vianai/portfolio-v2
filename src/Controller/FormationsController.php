<?php

/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 13/04/2017
 * Time: 10:00
 */
namespace Portfolio\Controller;

use Portfolio\Models\FormationsModel;


// ENREGISTREMENT DE L'AUTOLOAD
spl_autoload('FormationsModel', '.php');

class FormationsController
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



#region /******************************* METHOD : affichage de toutes les formations ************************************/
    /**
     * @return array
     */
    public function displayAllFormations()
    {
        $tableauFormations = $this->formation->getAllFormations();
        $tableauFormations = $this->gestionDMention($tableauFormations);
        $tableauFormations = $this->gestionDate($tableauFormations);
        $tableauFormations = $this->gestionImage($tableauFormations);
        return $tableauFormations;
    }
#endregion


#region /******************************* METHOD : gestion de la date en fonction du type de formation *******************/
    /**
     * @param $tableauFormations
     * @return array
     * @internal param $tabDataF
     */
    public function gestionDate($tableauFormations)
    {
        $formations = [];
        foreach ($tableauFormations as $formation) {
            $dateD = date_parse($formation['begin_at']);
            $dateD = $dateD['year'];
            $dateF = date_parse($formation['end_at']);
            $dateF = $dateF['year'];
            $mention = $formation['type'];
            if ($mention == 'Training') {
                $formation['date'] = $dateD . ' - ' . $dateF;
            }
            else {
                $formation['date'] = $dateF;
            }
            $formations[] = $formation;
        }
        return $formations;
    }
#endregion


#region /******************************* METHOD : affichage de la mention s'il y a **************************************/
    /**
     * @param $tabF1
     * @return array
     */
    public function gestionDMention($tabFormations)
    {
        $tabMentions = [];
        foreach ($tabFormations as $formation) {
            $mention = $formation['mention'];
            if ($mention == 'P') {
                $formation['mention'] = '';
            } else {
                $formation['mention'] = '(mention ' . $mention . ')';
            }
            $tabMentions[] = $formation;
        }
        return $tabMentions;
    }
#endregion


#region /******************************* METHOD : gestion du chemin des images ******************************************/
    /**
     * @param $tableauFormations
     * @return array
     */
    public function gestionImage($tableauFormations)
    {
        $tableauFinal = [];
        foreach ($tableauFormations as $tableauTransitoire) {
            if (!empty($tableauTransitoire['logo'])) {
                $image = 'logos/'.$tableauTransitoire['logo'];
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
