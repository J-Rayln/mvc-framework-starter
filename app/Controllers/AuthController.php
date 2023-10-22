<?php

namespace JonathanRayln\UdemyClone\Controllers;

use JonathanRayln\UdemyClone\Models\User;
use JonathanRayln\UdemyClone\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    public function register(): self
    {
        $user = new User();

        if ($this->request->isPost()) {
            $user->loadData($this->request->getBody());

            if ($user->validate() && $user->save()) {
                $this->response->redirectTo('login');
            }
        }

        return $this->setLayout('blank-centered')
            ->render('auth/register',
                'Create an Account',
                [
                    'model'  => $user,
                    'errors' => $user->errors
                ]);
    }

    public function login(): self
    {
        return $this->setLayout('blank-centered')->render('auth/login', 'Sign In');
    }
}