<?php namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\BaseController;

class UsersController extends BaseController {

    public function getAll($request, $response, $args) {

        $pdo = $this->container->get('db');

        $sql = "SELECT users.id, users.username,
                AES_DECRYPT(users.password, 'Papiro') AS password, users.rol
                FROM users";

        $query = $pdo->query($sql);

        $payload = $query->fetchAll();

        $response->getBody()->write(json_encode($payload));

        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(200);
    }
}
