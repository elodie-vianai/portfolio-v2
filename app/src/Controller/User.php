<?php

namespace Portfolio\Controller;

use Portfolio\Portfolio\Controller;
use Portfolio\Model\User as UserModel;
use Portfolio\Portfolio\Flash;
use Portfolio\Portfolio\Validator;
use Slim\Http\Request;
use Slim\Http\Response;

class User extends Controller
{
#region /******************************* METHOD : get the CRUD **********************************************************/
    /**
     * Get data for the CRUD.
     *
     * @param Request $request
     * @param Response $response
     * @return response
     */
    public function crud(Request $request, Response $response)
{
    $user_model = new UserModel($this->container);
    $users = $user_model->getAll();
    return $this->render($response, 'AdminZone/Users/CRUD_users.html.twig', ['users' => $users]);
}
#endregion


#region /******************************* METHOD : see user's account data ***********************************************/
    /**
     * Get user's account data.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getmyaccount(Request $request, Response $response) {
        $user_model = new UserModel($this->container);
        $user = $user_model->getUser($_SESSION['user']['id']);
        return $this->render($response, 'UsersZone/Account/myaccount.html.twig', ['user'=>$user]);

    }
#endregion


#region /******************************* METHOD : update user's data ****************************************************/
    /**
     * Update the user's username, email and password.
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

        $user_model = new UserModel($this->container);
        // récupération des informations liées au compte (dont le mot de passe, qui avait effacé de la session)
        if ($_SESSION['user']['roles'] == 'admin'){
            $user = $user_model->getUser($args['id']);
        } else {
            $user = $user_model->getUser($_SESSION['user']['id']);
        }

        if (isset($_POST)) {
            // doc Slim ==> $_POST
            $params = $request->getParams();
            $result = [];

            $validator = new Validator($params);

            $validator->addRules([
                'username' => [
                    'required' => 'Le nom d\'utilisateur est obligatoire'
                ],
                'password' => [
                    'required' => 'Le mot de passe est obligatoire'
                ],
                'email' => [
                    'required' => 'L\'adresse mail est obligatoire'
                ]
            ]);

            // vérification de la validité du formulaire
            if ($validator->check()) {
                $errors = '';
                if ($_SESSION['user']['roles'] == 'admin') {
                    if ($params['roles'] == 'admin') {
                        $user_model->update($params);
                        $_SESSION['user']['username'] = $params['username'];
                        $_SESSION['user']['email'] = $params['email'];
                        return $response->withRedirect($router->pathFor('CRUD_utilisateurs'));
                    } else {
                        $user_model->update($params);
                        return $response->withRedirect($router->pathFor('CRUD_utilisateurs'));
                    }
                } else {
                    $r = $user_model->update($params);
                    $_SESSION['user']['username'] = $params['username'];
                    $_SESSION['user']['email'] = $params['email'];
                    return $response->withRedirect('/user/moncompte');
                }
            } else {
                $errors = $validator->getErrors();
            }

            Flash::set('params', $params);
            Flash::set('errors', $errors);
        }
        if ($_SESSION['user']['roles'] == 'admin') {
            return $this->render($response, 'AdminZone/Users/form.html.twig', ['user' => $user]);
        } else {
            return $this->render($response, 'UsersZone/Account/updateMyAccount.html.twig', ['user' => $user]);
        }
    }
#endregion


#region /******************************* METHOD : delete a user's account ***********************************************/
    /**
     * Delete a user's account into the database, based on his id.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function delete(Request $request, Response $response, $args) {
        // récupération du routeur pour pouvoir rediriger
        $router = $this->container->get('router');

        $user_model = new UserModel($this->container);

        if ($_SESSION['user']['roles'] == 'admin') {
            if ($_SESSION['user']['id'] == $args['id']) {
                var_dump('vous ne pouvez pas supprimer votre compte administrateur depuis ce compte');
                return $response->withRedirect($router->pathFor('CRUD_utilisateurs'));
            } else {
                $user_model->delete($args['id']);
                // destruction de la session

                return $response->withRedirect($router->pathFor('CRUD_utilisateurs'));
            }
        } else {
            $user_model->delete($_SESSION['user']['id']);
            // destruction de la session
            session_destroy();

            return $response->withRedirect($router->pathFor('publicHomepage'));
        }
    }
#endregion
}