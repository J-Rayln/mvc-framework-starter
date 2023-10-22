<?php

namespace JonathanRayln\UdemyClone\Controllers;

use JonathanRayln\UdemyClone\Models\User;
use JonathanRayln\UdemyClone\Routing\Controller as BaseController;

class SiteController extends BaseController
{
    public function index()
    {
        $user = new User();
        $result = $user->findAll();
        $result = User::findOne(['id' => 6]);
        echo '<pre>';
        print_r($result);
        echo '</pre>';
        return $this->setLayout('instructor')->render('index', 'title');
    }

    public function about()
    {
        return $this->render('index', 'About Us');
    }

    public function params($param = '', $param2 = '')
    {
        if (empty($param))
            echo 'missing params';

        var_dump('the param is ' . $param . ' ' . $param2);

        $two = $_GET['id'] ?? '';

        echo '<pre>';
        print_r($two);
        echo '</pre>';
    }
}