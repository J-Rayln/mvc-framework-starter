<?php

namespace JonathanRayln\UdemyClone\Routing\Exceptions;

use JonathanRayln\UdemyClone\Http\Request;
use JonathanRayln\UdemyClone\Http\Response;
use JonathanRayln\UdemyClone\Routing\Controller as BaseController;

class ExceptionViewer extends BaseController
{
    public function __construct(Request $request, Response $response)
    {
        $this->setLayout('blank-centered');
        parent::__construct($request, $response);
    }

    public function pageNotFound(): ExceptionViewer|BaseController
    {
        $this->response->setResponseCode(Response::NOT_FOUND);
        return $this->render('exceptions/general-error',
            'Page Not Found',
            [
                'code'    => Response::NOT_FOUND,
                'message' => 'The page you are looking for could not be found.'
            ]);
    }
}