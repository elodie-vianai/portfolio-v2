<?php

namespace Portfolio\Controller;

use Portfolio\Portfolio\Controller;
use Portfolio\Portfolio\Flash;
use Portfolio\Model\Project as ProjectModel;
use Portfolio\Model\Skill as SkillModel;
use Portfolio\Model\Experience as ExperienceModel;
use Portfolio\Portfolio\Validator;
use Slim\Http\Request;
use Slim\Http\Response;

class Project extends Controller
{
#region /******************************* METHOD : project detail ********************************************************/
    /**
     * Get all data of a project for a detail on it.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function projectDetail(Request $request, Response $response, $args)
    {
        $project_model = new ProjectModel($this->container);
        $project = $project_model->getOne($args['id']);
        $skill_model = new SkillModel($this->container);
        $skills = $skill_model->getTechnoProject($args['id']);
        $experience_model = new ExperienceModel($this->container);
        $experience = $experience_model->getExperienceProject($project['id']);
        if (!empty($experience)){
            $experience['realisation'] = 'Réalisation professionnelle';
        }
        else {
            $experience['realisation'] = 'Réalisation personnelle';
        }
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
        } else {
            $user = '';
        }
//        var_dump($experience);die;
        return $this->render($response, 'UsersZone/projectDetail.html.twig',
            ['project' => $project, 'skills' => $skills, 'experience'=>$experience, 'user' => $user]);
    }
    #endregion


#region /******************************* METHOD : crud ******************************************************************/
    /**
     * CRUD of all administrator's projects.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function crud(Request $request, Response $response)
    {
        $project_model = new ProjectModel($this->container);
        $array_projects = $project_model->getAll();
        $skill_model = new SkillModel($this->container);
        $projects = [];
        foreach ($array_projects as $project) {
            $skills = $skill_model->getTechnoProject($project['id']);
            $project['skills'] = $skills;
            $projects[] = $project;
        }
        //var_dump($projects);die;
        return $this->render($response, 'AdminZone/Projects/CRUD_projects.html.twig',
            ['projets' => $projects]);
    }
#endregion


#region /******************************* METHOD : add a new project *****************************************************/
    /**
     * Add a new project into the database.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function add(Request $request, Response $response)
    {
        $project_model = new ProjectModel($this->container);
        $projects = $project_model->getAll();

        $skill_model = new SkillModel($this->container);
        $skills = $skill_model->getAll();

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
                    'required' => 'Le nom du projet est obligatoire'
                ],
                'year' => [
                    'required' => 'La date de réalisation du projet est obligatoire',
                ],
            ]);

            // vérification de la validité du formulaire
            if ($validator->check()) {
                $project_model = new ProjectModel($this->container);
                $project_model->add($params);
                return $response->withRedirect($router->pathFor('CRUD_projects'));
            } else {
                $errors = $validator->getErrors();
            }

            Flash::set('params', $params);
            Flash::set('errors', $errors);
        }
        return $this->render($response, 'AdminZone/Projects/form.html.twig',
            ['skills' => $skills]);
    }
#endregion


#region /******************************* METHOD : update data of a project **********************************************/
    /**
     * Update data of a registered project.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function update(Request $request, Response $response, $args)
    {
        // récupération du routeur pour pouvoir rediriger
        $router = $this->container->get('router');

        // initialisation du POST + préparation pour récupérer les données
        $_POST = $request->getParsedBody();

        $project_model = new ProjectModel($this->container);
        $skill_model = new SkillModel($this->container);

        $project = $project_model->getOne($args['id']);
        $skills = $skill_model->getAll();
        $project['technologies'] = $skill_model->getTechnoProject($project['id']);


        $project['update'] = true;

        if (isset($_POST)) {
            // doc Slim ==> $_POST
            $params = $_POST;

            $result = [];

            $validator = new Validator($params);

            $validator->addRules([
                'name' => [
                    'required' => 'Le nom du projet est obligatoire'
                ],
                'year' => [
                    'required' => 'La date de réalisation du projet est obligatoire',
                ],
            ]);
            // vérification de la validité du formulaire
            if ($validator->check()) {
                $project_model->update($params);
                return $response->withRedirect($router->pathFor('CRUD_projects'));
            } else {
                $errors = $validator->getErrors();
            }

            Flash::set('params', $params);
            Flash::set('errors', $errors);
        }
        return $this->render($response, 'AdminZone/Projects/form.html.twig',
            ['project' => $project, 'skills' => $skills]);
    }
#endregion


#region /******************************* METHOD : delete a project ******************************************************/
    /**
     * Delete a project , based on his id.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function delete(Request $request, Response $response, $args)
    {
        // récupération du routeur pour pouvoir rediriger
        $router = $this->container->get('router');

        $project_model = new ProjectModel($this->container);
        $project_model->delete($args['id']);

        return $response->withRedirect($router->pathFor('CRUD_projects'));

    }
#endregion

}