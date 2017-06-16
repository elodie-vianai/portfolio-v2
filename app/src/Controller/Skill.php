<?php

namespace Portfolio\Controller;

use Portfolio\Portfolio\Controller;
use Portfolio\Model\Skill as SkillModel;
use Portfolio\Portfolio\Validator;
use Portfolio\Portfolio\Flash;
use Slim\Http\Request;
use Slim\Http\Response;

class Skill extends Controller
{
#region /******************************* METHOD : index *****************************************************************/
    /**
     * Public ans user's page skills.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index (Request $request, Response $response) {
        $skill_model    = new SkillModel($this->container);
        $skills   = $skill_model->getAll();
        return $this->render($response, 'UsersZone/skills.html.twig', ['skills'=>$skills]);
    }
#endregion


#region /******************************* METHOD : crud *****************************************************************/
    /**
     * CRUD of all administrator's technologies.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function crud (Request $request, Response $response) {
        $skill_model   = new SkillModel($this->container);
        $skills = $skill_model->getAll();
        //var_dump($skills);die;
        return $this->render($response, 'AdminZone/Skills/CRUD_skills.html.twig',
            ['skills'=>$skills]);
    }
#endregion


#region /******************************* METHOD : add a new skill **************************************************/
    /**
     * Add a new skill into the database
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
                    'required' => 'Le nom de la compétence est obligatoire'
                ],
                'category' => [
                    'required' => 'La catégorie de la compétence est obligatoire'
                ]
            ]);

            // vérification de la validité du formulaire
            if ($validator->check()) {
                $skill_model = new SkillModel($this->container);
                $skill_model->add($params);
                return $response->withRedirect($router->pathFor('CRUD_skills'));
            } else {
                $errors = $validator->getErrors();
            }

            Flash::set('params', $params);
            Flash::set('errors', $errors);
        }
        return $this->render($response, 'AdminZone/Skills/form.html.twig');
    }
#endregion


#region /******************************* METHOD : update data of a skill ************************************************/
    /**
     * Update the name, the level, the category and the image_path of a registered skill.
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

        $skill_model = new SkillModel($this->container);
       // récupération des informations liées au compte (dont le mot de passe, qui avait effacé de la session)
           $skill = $skill_model->getOne($args['id']);

           $skill['update'] = true;

        if (isset($_POST)) {
            // doc Slim ==> $_POST
            $params = $_POST;

            $result = [];

            $validator = new Validator($params);

            $validator->addRules([
                'name' => [
                    'required' => 'Le nom de la technologie est obligatoire'
                ],
                'category' => [
                'required' => 'La catégorie de la compétence est obligatoire'
            ]
            ]);
            // vérification de la validité du formulaire
            if ($validator->check()) {
                $skill_model->update($params);
                return $response->withRedirect($router->pathFor('CRUD_skills'));
            } else {
                $errors = $validator->getErrors();
            }

            Flash::set('params', $params);
            Flash::set('errors', $errors);
        }

        return $this->render($response, 'AdminZone/Skills/form.html.twig', ['skill'=>$skill]);
    }
#endregion


#region /******************************* METHOD : delete a skill ***************************************************/
    /**
     * Delete a skill , based on his id.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function delete(Request $request, Response $response, $args) {
        // récupération du routeur pour pouvoir rediriger
        $router = $this->container->get('router');

        $skill_model = new SkillModel($this->container);
        $skill_model->delete($args['id']);

        return $response->withRedirect($router->pathFor('CRUD_skills'));

    }
#endregion
}