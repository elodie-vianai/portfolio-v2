<?php

namespace Portfolio\Portfolio;


class Model
{

#region --------- ATTRIBUTES ---------
    // Conteneur d'injection de dépendance
    protected $container;
    protected $db;
#endregion

#region --------- CONSTRUCTOR ---------
    /**
     * Stocke le container Slim dans le model
     *
     * @param $container
     */
    public function __construct ($container)
    {
        // Stockage du conteneur d'injection de dépendance de Slim dans ce model
        $this->container = $container;
        $this->db        = $this->container->get('db');
    }
#endregion

#region --------- METHOD findAllWithFields ---------
    /**
     * Récupère dans la bdd les occurences d'une table donné avec les champs donnés en paramètres
     *
     * @param array $fields Tableau contenant les champs que l'on souhaite séléctionner
     * @param array $orderBy
     *
     * @throws \Exception
     *
     * @return mixed
     */
    /*public function findAllWithFields ($fields = [], $orderBy = [])
    {
        // Vérification que le model est bien relié à une table
        if (empty($this->table)) {
            throw new \Exception('Table not define in model');
        }

        // Si aucun champs n'est défini, sélection de tous les champs
        if (empty($fields)) {
            $fields = '*';
        }
        // ... sinon rassemblement des éléments du tableau $fields en une chaine de caractères
        else {
            $fields = implode(', ', $fields);
        }

        $orderBySql = '';
        if (!empty($orderBy)) {
            $orderBySql = 'ORDER BY ' . implode(', ', $orderBy);
        }

        // Requête d'extraction de tous les praticiens
        $sql = 'SELECT ' . $fields . '
                FROM ' . $this->table . '
                ' . $orderBySql;

        // Execution de la requête
        $result = $this->db->query($sql);

        return $result->fetchAll();
    }*/
}