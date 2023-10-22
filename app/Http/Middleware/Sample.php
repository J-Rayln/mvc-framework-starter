<?php

namespace JonathanRayln\UdemyClone\Http\Middleware;

class Sample implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function handle()
    {
        echo '<pre>';
        var_dump('you hit the sample middleware');
        echo '</pre>';
        exit;
    }
}