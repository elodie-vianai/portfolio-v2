<?php

namespace Portfolio\Controller;

use Portfolio\Portfolio\Controller;
use Slim\Http\Request;
use Slim\Http\Response;

class Contact extends Controller
{

#region /******************************* METHOD : index *****************************************************************/
    /**
     * Page contact.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index (Request $request, Response $response) {

        return $this->render($response, 'UsersZone/contact.html.twig');
    }
#endregion
}