<?php
/**
 * Created by PhpStorm.
 * User: Elodie Vianai
 * Date: 12/04/2017
 * Time: 10:59
 */

namespace Portfolio\Models;


abstract class Database
{
#region /************************************************** ATTRIBUTES **************************************************/
    private static $db;
#endregion


#region /************************************************ CONSTRUCTOR ***************************************************/
    /**
     * Database constructor.
     */
    public function __construct() {}
#endregion


#region /****************************************** EXÉCUTER UNE REQUETE ************************************************/
    /**
     * @param $sql
     * @param null $params
     * @return \PDOStatement
     */
    protected function executerRequete($sql, $params = null) {
        if ($params == null) {
            $resultat = self::getDb()->query($sql);     // exécution directe
        }
        else {
            $resultat = self::getDb()->prepare($sql);       // requête préparée
            $resultat->execute($params);
        }
        return $resultat;
   }
#endregion


#region /********************************** CONNEXION A LA BASE DE DONNÉES **********************************************/
    /**
     * @return \PDO
     */
    private static  function getDb() {
        if (self::$db == null) {
            self::$db = new \PDO('mysql:host=localhost;dbname=portfolio;charset=UTF8', 'root', '',
                array(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION));
        }
        return self::$db;
    }
#endregion


}