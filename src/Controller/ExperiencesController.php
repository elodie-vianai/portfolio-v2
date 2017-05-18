<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 11/04/2017
 * Time: 16:03
 */

namespace Portfolio\Controller;

use Portfolio\Models\ExperiencesModel;

// ENREGISTREMENT DE L'AUTOLOAD
spl_autoload('ExperiencesModel', '.php');


class ExperiencesController
{
#region /******************************* ATTRIBUTES *********************************************************************/
    private $experience;
#endregion


#region /******************************* CONSTRUCTOR ********************************************************************/
    /**
     * ExperiencesControllerAdmin constructor.
     */
    public function __construct()
    {
        $this->experience = new ExperiencesModel('mysql:host=localhost;dbname=portfolio');
    }
#endregion



#region /******************************* METHOD : affichage de toutes les expériences ***********************************/
    /**
     * @return array
     */
    public function formatageExperiences()
    {
        $tableauExperiences = $this->experience->getAllExperiences();
        $tableauExperiences = $this->dureeContrat($tableauExperiences);
        $tableauExperiences = $this->gestionImage($tableauExperiences);
        return $tableauExperiences;
    }
#endregion


#region /******************************* METHOD : calcul de la durée du contrat *****************************************/
    /**
     * @param $tableauExperiences
     * @return array
     */
    public function dureeContrat($tableauExperiences)
    {
        $tabExpDuree = [];
        foreach ($tableauExperiences as $exp) {
            $dateD = new \DateTime($exp['begin_at'], new \DateTimeZone('Europe/Paris'));
            $dateF = new \DateTime($exp['end_at'], new \DateTimeZone('Europe/Paris'));
            $duree = date_diff($dateD, $dateF)/*->format('%y an(s) et %m mois')*/;
            if ($duree->y == '0') {
                $duree = $duree->format('%m mois');
            }
            elseif ($duree->y == '1') {
                $duree = $duree->format('%y an et %m mois');
            }
            else {
                $duree = $duree->format('%y ans et %m mois');
            }
            $exp['duree'] = $duree;
            $exp['begin_at'] = $dateD->format('m-Y');
            $exp['end_at'] = $dateF->format('m-Y');
            $tabExpDuree[] = $exp;
        }
        return $tabExpDuree;

    }
#endregion


#region /******************************* METHOD : gestion du chemin des images ******************************************/
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