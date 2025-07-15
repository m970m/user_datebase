<?php
declare(strict_types=1);

namespace App\Router;

use App\Container\Container;

class Router
{
    public function __construct(private Container $container, private array $routes) {}

    public function handleRequest(string $requestUri, string $method, ?array $postData): void
    {
        header('Content-Type: application/json');

        try
        {
            $url = $this->parseUrl($requestUri);
            $response = $this->resolve($url, $method, $postData);
            http_response_code(200);
        } catch (\Exception $ex)
        {
            http_response_code(400);
            $response = [
                'status' => 'failed',
                'message' => 'bad request'
            ];
        }

        echo json_encode($response);
    }

    private function resolve(array $url, string $method, ?array $postData): array
    {
        $action = $this->routes[$method][$url[0]] ?? null;
        $param = $url[1] ?? null;
        if (!$action)
        {
            throw new \InvalidArgumentException("Invalid request arguments");
        }

        [$class, $method] = $action;
        $class = $this->container->get($class);
        if ($param)
        {
            return call_user_func([$class, $method], $param);
        }
        return call_user_func([$class, $method], $postData);
    }

    private function parseUrl(string $requestUri): array
    {
        $url = explode('/', trim($requestUri, '/'));
        if (empty($url))
        {
            throw new \InvalidArgumentException('Invalid request');
        }

        return $url;
    }
}