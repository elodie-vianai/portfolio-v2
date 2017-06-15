<?php
/**
 * Traitement des vues
 */

namespace Portfolio\Portfolio;

use Slim\Http\Response;


class Controller
{
#region --------- ATTRIBUTES ---------
    // Conteneur d'injection de dépendances
    protected $container;

    // Contient des infos globales (user...)
    protected $global_data = [];
#endregion


#region --------- CONSTRUCTOR ---------
    /**
     * Stocke le container Slim dans le controller
     *
     * @param $container
     */
    public function __construct ($container)
    {
        // Je stocke le conteneur d'injection de dépendances de Slim dans mon controller
        $this->container = $container;
    }
#endregion


#region --------- METHOD : render ---------
    /**
     * Rend une vue avec des infos globales
     *
     * @param Response $response
     * @param $filename
     * @param array $data
     *
     * @return Response
     */
    public function render (Response $response, $filename, $data = [])
    {
        // si c'est l'utilisateur ou l'administrateur qui sont connectés, on passe le render à la vue
        if (isset ($_SESSION['user'])) {
            $this->global_data['user'] = $_SESSION['user'];
        }
        elseif (isset($_SESSION['admin'])) {
            $this->global_data['admin'] = $_SESSION['admin'];
        }

        // Fusionne les global_data avec les data du controller fils
        $data = array_replace_recursive($this->global_data, $data);

        return $this->container->view->render($response, $filename, $data);
    }
#endregion
}
