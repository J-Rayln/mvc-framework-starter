<?php

namespace JonathanRayln\UdemyClone\Http\Middleware;

interface MiddlewareInterface
{
    /**
     * @return mixed
     */
    public function handle();
}