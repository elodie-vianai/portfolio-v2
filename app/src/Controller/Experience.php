<?php

namespace Portfolio\Controller;

use Portfolio\Model\Department;
use Portfolio\Portfolio\Controller;
use Portfolio\Model\Experience as ExperienceModel;
use Portfolio\Model\Project as ProjectModel;
use Portfolio\Model\Department as DepartmentModel;
use Portfolio\Portfolio\Validator;
use Portfolio\Portfolio\Flash;
use Slim\Http\Request;
use Slim\Http\Response;

class Experience extends Controller
{
#region /******************************* METHOD : index *****************************************************************/
    /**
     * Public ans user's page experiences.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index (Request $request, Response $response) {
        $experience_model   = new ExperienceModel($this->container);
        $experiences = $experience_model->getAll();
        $project_model      = new ProjectModel($this->container);
        $projects     = $project_model->getAll();
        return $this->render($response, 'UsersZone/experiences.html.twig',
            ['experiences'=>$experiences, 'projects'=>$projects]);
    }
#endregion


#region /******************************* METHOD : crud *****************************************************************/
    /**
     * CRUD of all administrator's experiences.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function crud (Request $request, Response $response) {
        $experience_model   = new ExperienceModel($this->container);
        $array_experiences = $experience_model->getAll();
        //récupération de tous les projets en fonction des expériences
        $project_model = new ProjectModel($this->container);
        $experiences = [];
        foreach ($array_experiences as $experience) {
            $projects = $project_model->getprojectExperience($experience['id']);
            $experience['projets'] = $projects;
            $experiences[] = $experience;
        }//var_dump($experiences);die;
        return $this->render($response, 'AdminZone/Experiences/CRUD_experiences.html.twig',
            ['experiences'=>$experiences]);
    }
#endregion


#region /******************************* METHOD : add a new experience **************************************************/
    /**
     * Add
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function add (Request $request, Response $response) {
        $experience_model   = new ExperienceModel($this->container);
        $department_model   = new DepartmentModel($this->container);
        $project_model      = new ProjectModel($this->container);

        $departments = $department_model->getAll();
        $projects = $project_model->getAll();

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
                    'required' => 'La date de début de l\'expérience est obligatoire'
                ],
                'name' => [
                    'required' => 'Le nom de l\'expérience est obligatoire'
                ],
                'contrat' => [
                    'required' => 'Le contrat de l\'expérience est obligatoire',
                ],
                'entreprise' => [
                    'required' => 'Le nom de l\'entreprise est obligatoire'
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
                $experience_model->add($params);
                return $response->withRedirect($router->pathFor('CRUD_experiences'));
            } else {
                $errors = $validator->getErrors();
            }

            Flash::set('params', $params);
            Flash::set('errors', $errors);
        }
        return $this->render($response, 'AdminZone/Experiences/form.html.twig',
            ['departements'=>$departments, 'projects'=>$projects]);
    }
#endregion


#region /******************************* METHOD : update data of an experience ******************************************/
    /**
     * Update data of a registered experience.
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

        $experience_model   = new ExperienceModel($this->container);
        $departement_model  = new DepartmentModel($this->container);
        $project_model      = new ProjectModel($this->container);

        $experience                 = $experience_model->getOne($args['id']);
        $departements               = $departement_model->getAll();
        $projects                   = $project_model->getAll();
        $experience['projects']     = $project_model->getprojectExperience($experience['id']);


        $experience['update'] = true;

        if (isset($_POST)) {
            // doc Slim ==> $_POST
            $params = $_POST;

            $result = [];

            $validator = new Validator($params);

            $validator->addRules([
                'begin_at' => [
                    'required' => 'La date de début de l\'expérience est obligatoire'
                ],
                'name' => [
                    'required' => 'Le nom de l\'expérience est obligatoire'
                ],
                'contrat' => [
                    'required' => 'Le contrat de l\'expérience est obligatoire',
                ],
                'entreprise' => [
                    'required' => 'Le nom de l\'entreprise est obligatoire'
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
                $experience_model->update($params);
                return $response->withRedirect($router->pathFor('CRUD_experiences'));
            } else {
                $errors = $validator->getErrors();
            }

            Flash::set('params', $params);
            Flash::set('errors', $errors);
        }
        return $this->render($response, 'AdminZone/Experiences/form.html.twig',
            ['experience'=>$experience, 'departements'=>$departements, 'projects'=>$projects]);
    }
#endregion


#region /******************************* METHOD : delete an experience **************************************************/
    /**
     * Delete an experience , based on his id.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function delete(Request $request, Response $response, $args) {
        // récupération du routeur pour pouvoir rediriger
        $router = $this->container->get('router');

        $experience_model = new ExperienceModel($this->container);
        $experience_model->delete($args['id']);

        return $response->withRedirect($router->pathFor('CRUD_experiences'));

    }
#endregion

}