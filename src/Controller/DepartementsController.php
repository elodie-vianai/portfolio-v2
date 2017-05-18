<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 21/04/2017
 * Time: 15:51
 */

namespace Portfolio\Controller;

use Portfolio\Models\DepartementsModel;

class DepartementsController
{
    #region /******************************* ATTRIBUTES *********************************************************************/
    private $departement;
#endregion


#region /******************************* CONSTRUCTOR ********************************************************************/
    /**
     * DepartementsController constructor.
     */
    public function __construct()
    {
        $this->departement = new DepartementsModel('mysql:host=localhost;dbname=portfolio');
    }
#endregion



#region /******************************* METHODS : Récupérer tous les départements **************************************/
    /**
     * @return array
     */
    public function displayAllDep()
    {
        $tableauDepartements = $this->departement->getAllDep();
        return $tableauDepartements;
    }
#endregion


}