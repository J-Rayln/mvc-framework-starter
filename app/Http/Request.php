<?php

namespace JonathanRayln\UdemyClone\Http;

class Request
{
    public function getMethod(): string
    {
        return isset($_SERVER['_method']) ? strtoupper($_SERVER['_method']) : $_SERVER['REQUEST_METHOD'];
    }

    public function getUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
        return $uri === '/' ? $uri : rtrim($uri, '/');
    }

    public function isGet(): bool
    {
        return $this->getMethod() === 'GET';
    }

    public function isPost(): bool
    {
        return $this->getMethod() === 'POST';
    }

    public function isPatch(): bool
    {
        return $this->getMethod() === 'PATCH';
    }

    public function isPut(): bool
    {
        return $this->getMethod() === 'PUT';
    }

    public function isDelete(): bool
    {
        return $this->getMethod() === 'DELETE';
    }

    /**
     * Returns sanitized input.
     *
     * @return array
     */
    public function getBody(): array
    {
        $body = [];
        if ($this->getMethod() === 'GET') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        $body = [];
        if ($this->getMethod() === 'POST') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }
}