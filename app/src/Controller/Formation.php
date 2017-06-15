<?php

namespace Portfolio\Controller;

use Portfolio\Portfolio\Controller;
use Portfolio\Model\Formation as FormationModel;
use Portfolio\Model\Department as DepartmentModel;
use Portfolio\Portfolio\Validator;
use Portfolio\Portfolio\Flash;
use Slim\Http\Request;
use Slim\Http\Response;

class Formation extends Controller
{
#region /******************************* METHOD : index *****************************************************************/
    /**
     * Public ans user's page education.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index (Request $request, Response $response) {
        $formation_model    = new FormationModel($this->container);
        $formations   = $formation_model->getAll();
        return $this->render($response, 'UsersZone/formation.html.twig', ['formations'=>$formations]);
    }
#endregion


#region /******************************* METHOD : crud *****************************************************************/
    /**
     * CRUD of all administrator's education.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function crud (Request $request, Response $response) {
        $formation_model   = new FormationModel($this->container);
        $formations = $formation_model->getAll();
        //var_dump($formations);die;
        return $this->render($response, 'AdminZone/Formations/CRUD_formations.html.twig',
            ['formations'=>$formations]);
    }
#endregion


#region /******************************* METHOD : add a new education ***************************************************/
    /**
     * Add
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function add (Request $request, Response $response) {
        $department_model = new DepartmentModel($this->container);
        $departments = $department_model->getAll();

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
                'begin_at' => [
                    'required' => 'La date de début de la formation est obligatoire'
                ],
                'name' => [
                    'required' => 'Le nom de la formation est obligatoire'
                ],
                'type' => [
                    'required' => 'Le type de la formation est obligatoire',
                ],
                'etablissement' => [
                    'required' => 'Le nom de l\'établissement est obligatoire'
                ],
                'ville' => [
                    'required' => 'Le nom de la ville est obligatoire'
                ],
                'dep_id' => [
                    'required' => 'Le département est obligatoire'
                ]
            ]);

            // vérification de la validité du formulaire
            if ($validator->check()) {
                if (empty($_POST['end_at'])) {
                    $dtz = new \DateTimeZone('Europe/Paris');
                    $datetime = new \DateTime();
                    $datetime->setTimezone($dtz);
                    $_POST['end_at'] = $datetime->format('Y-m-d');
                }
                $formation_model = new FormationModel($this->container);
                $formation_model->add($params);
                return $response->withRedirect($router->pathFor('CRUD_formations'));
            } else {
                $errors = $validator->getErrors();
            }

            Flash::set('params', $params);
            Flash::set('errors', $errors);
        }
        return $this->render($response, 'AdminZone/Formations/form.html.twig',
                ['departements'=>$departments]);
    }
#endregion


#region /******************************* METHOD : update data of an education *******************************************/
    /**
     * Update data of a registered education.
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

        $formation_model    = new FormationModel($this->container);
        $departement_model  = new DepartmentModel($this->container);

        $formation      = $formation_model->getOne($args['id']);
        $departements   = $departement_model->getAll();


        $formation['update'] = true;

        if (isset($_POST)) {
            // doc Slim ==> $_POST
            $params = $_POST;

            $result = [];

            $validator = new Validator($params);

            $validator->addRules([
                'begin_at' => [
                    'required' => 'La date de début de la formation est obligatoire'
                ],
                'name' => [
                    'required' => 'Le nom de la formation est obligatoire'
                ],
                'type' => [
                    'required' => 'Le type de la formation est obligatoire',
                ],
                'etablissement' => [
                    'required' => 'Le nom de l\'établissement est obligatoire'
                ],
                'ville' => [
                    'required' => 'Le nom de la ville est obligatoire'
                ],
                'dep_id' => [
                    'required' => 'Le département est obligatoire'
                ]
            ]);
            // vérification de la validité du formulaire
            if ($validator->check()) {
                $formation_model->update($params);
                return $response->withRedirect($router->pathFor('CRUD_formations'));
            } else {
                $errors = $validator->getErrors();
            }

            Flash::set('params', $params);
            Flash::set('errors', $errors);
        }

        return $this->render($response, 'AdminZone/Formations/form.html.twig', ['formation'=>$formation, 'departements'=>$departements]);
    }
#endregion


#region /******************************* METHOD : delete an education ***************************************************/
    /**
     * Delete an education , based on his id.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function delete(Request $request, Response $response, $args) {
        // récupération du routeur pour pouvoir rediriger
        $router = $this->container->get('router');

        $formation_model = new FormationModel($this->container);
        $formation_model->delete($args['id']);

        return $response->withRedirect($router->pathFor('CRUD_formations'));

    }
#endregion
}