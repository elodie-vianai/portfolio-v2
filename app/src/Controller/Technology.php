<?php

namespace Portfolio\Controller;

use Portfolio\Portfolio\Controller;
use Portfolio\Model\Technology as TechnologyModel;
use Portfolio\Portfolio\Validator;
use Portfolio\Portfolio\Flash;
use Slim\Http\Request;
use Slim\Http\Response;

class Technology extends Controller
{
#region /******************************* METHOD : crud *****************************************************************/
    /**
     * CRUD of all administrator's technologies.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function crud (Request $request, Response $response) {
        $technology_model   = new TechnologyModel($this->container);
        $technologies = $technology_model->getAll();
        return $this->render($response, 'AdminZone/Technologies/CRUD_technologies.html.twig',
            ['technologies'=>$technologies]);
    }
#endregion


#region /******************************* METHOD : add a new technology **************************************************/
    /**
     * Add a new technology into the database
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function add (Request $request, Response $response) {
        // récupération du routeur pour pouvoir rediriger
        $router = $this->container->get('router');

        $errors = '';

        // initialisation du POST + préparation pour récupérer les données
        $_POST = $request->getParsedBody();

        if (isset($_POST)) {
            // doc Slim ==> $_POST
            $params = $request->getParams();
            $result = [];

            $validator = new Validator($params);

            $validator->addRules([
                'name' => [
                    'required' => 'Le nom de la technologie est obligatoire'
                ]
            ]);

            // vérification de la validité du formulaire
            if ($validator->check()) {
                $technology_model = new TechnologyModel($this->container);
                $technology_model->add($params);
                return $response->withRedirect($router->pathFor('CRUD_technologies'));
            } else {
                $errors = $validator->getErrors();
            }

            Flash::set('params', $params);
            Flash::set('errors', $errors);
        }
        return $this->render($response, 'AdminZone/Technologies/form.html.twig');
    }
#endregion


#region /******************************* METHOD : update data of a technology *******************************************/
    /**
     * Update the name and the image_path of a registered technology.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function update(Request $request, Response $response, $args) {
        // récupération du routeur pour pouvoir rediriger
        $router = $this->container->get('router');

        // initialisation du POST + préparation pour récupérer les données
        $_POST = $request->getParsedBody();

        $technology_model = new TechnologyModel($this->container);
       // récupération des informations liées au compte (dont le mot de passe, qui avait effacé de la session)
           $technology = $technology_model->getOne($args['id']);

           $technology['update'] = true;

        if (isset($_POST)) {
            // doc Slim ==> $_POST
            $params = $_POST;

            $result = [];

            $validator = new Validator($params);

            $validator->addRules([
                'name' => [
                    'required' => 'Le nom de la technologie est obligatoire'
                ]
            ]);
            // vérification de la validité du formulaire
            if ($validator->check()) {
                $technology_model->update($params);
                return $response->withRedirect($router->pathFor('CRUD_technologies'));
            } else {
                $errors = $validator->getErrors();
            }

            Flash::set('params', $params);
            Flash::set('errors', $errors);
        }

        return $this->render($response, 'AdminZone/Technologies/form.html.twig', ['technology'=>$technology]);
    }
#endregion


#region /******************************* METHOD : delete a technology ***************************************************/
    /**
     * Delete a technology , based on his id.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function delete(Request $request, Response $response, $args) {
        // récupération du routeur pour pouvoir rediriger
        $router = $this->container->get('router');

        $technology_model = new TechnologyModel($this->container);
        $technology_model->delete($args['id']);

        return $response->withRedirect($router->pathFor('CRUD_technologies'));

    }
#endregion
}