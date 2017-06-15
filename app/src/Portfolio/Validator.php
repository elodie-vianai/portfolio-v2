<?php

namespace Portfolio\Portfolio;


/**
 * Class Validator
 *
 * Valide les champs d'un formulaire
 */
class Validator
{
#region --------- ATTRIBUTES ---------
    protected $params = [];
    protected $rules  = [];
    protected $errors = [];
#endregion

#region --------- CONSTRUCTOR ---------
    /**
     * Constructeur de la classe Validator
     *
     * @param array $p_params Paramètres envoyés ($_POST) par le formulaire
     */
    public function __construct (array $p_params)
    {
        $this->params = $p_params;
    }
#endregion


#region --------- METHOD : ajout d'une règle de validation et ses messages ---------
    /**
     * Ajoute les règles de validation et les messages d'erreurs correspondants
     *
     * @param array $p_rules Règles et messages d'erreurs
     */
    public function addRules (array $p_rules)
    {
        $this->rules = $p_rules;
    }
#endregion

#region --------- METHOD : exécution des règles de validation ---------
    /**
     * Exécute les règles de validation pour chaque paramètre et remplit un tableau d'erreurs si il y en a
     *
     * @return bool Indique s'il y a des erreurs ou non
     * @throws \Exception
     */
    public function check ()
    {
        // Pour chaque élément du tableau contenant les règles de validation
        foreach ($this->rules as $paramName => $rules) {
            // Si le paramètre correspondant à la règle existe ...
            if (isset ($this->params[$paramName])) {
                // Execution des règles de validation sur chaque paramètre
                foreach ($this->rules[$paramName] as $rule => $errorMessage) {
                    switch ($rule) {
                        case 'required':
                            if (empty($this->params[$paramName]) || (is_array($this->params[$paramName]) && empty($this->params[$paramName][0]))) {
                                $this->errors[$paramName] = $errorMessage;
                            }
                            break;
                        case 'confirmationMdp':
                            if ($this->params['mdp'] !== $this->params['mdpC']) {
                                $this->errors[$paramName] = $errorMessage;
                            }
                            break;

                        default :
                            throw new \Exception('La règle ' . $rule . ' n\'existe pas');

                    }
                }
            }
            else {
                // ... sinon une erreur est envoyée
                throw new \Exception('Le paramètre ' . $paramName . ' n\'a pas été trouvé');
            }
        }

        // Renvoie un booléen : si tableau d'erreur ne contient rien alors true sinon false
        return count($this->errors) === 0;
    }
#endregion

#region --------- METHOD : tableau d'erreurs ---------
    /**
     * Retourne un tableau d'erreurs
     *
     * @return array Tableau contenant les messages d'erreur
     */
    public function getErrors ()
    {
        return $this->errors;
    }
#endregion
}
