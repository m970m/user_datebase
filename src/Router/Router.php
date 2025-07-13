<?php
declare(strict_types=1);

namespace App\Router;

use App\Controller\UserController;

class Router
{
    public function __construct(
        private UserController $userController
    ) {}

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
        if ($method === 'GET' && $url[0] === 'list-users')
        {
            return $this->userController->listUsers();
        }

        if ($method === 'DELETE' && $url[0] === 'delete-user' && isset($url[1]) && filter_var(($url[1]), FILTER_VALIDATE_INT))
        {
            return $this->userController->deleteUser((int) $url[1]);
        }

        if ($method === 'POST' && $url[0] === 'create-user' && $postData)
        {
            return $this->userController->createUser($postData);
        }

        throw new \InvalidArgumentException("Invalid request arguments");
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