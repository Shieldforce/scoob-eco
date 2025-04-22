<?php

namespace ScoobEco\Core\Http;

class Request
{
    public string $method;
    public string $uri;
    public array $params;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = strtok($_SERVER['REQUEST_URI'], '?');
        $this->params = $_REQUEST;
    }
}