<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 22:58.
 */

namespace Sufel\App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Utils\Validator;
use Sufel\App\ViewModels\ClientRegister;

/**
 * Class ClientSecureController.
 */
class ClientSecureController
{
    /**
     * @var ClientSecureApiInterface
     */
    private $api;

    /**
     * ClientSecureController constructor.
     *
     * @param ClientSecureApiInterface $api
     */
    public function __construct(ClientSecureApiInterface $api)
    {
        $this->api = $api;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function login($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['documento', 'password'])) {
            return $response->withStatus(400);
        }
        $result = $this->api->login($params['documento'], $params['password']);

        return $response->withJson($result->getData(), $result->getStatusCode());
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function register($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['documento', 'nombres', 'usuario_sol', 'password', 'repeat_password'])) {
            return $response->withStatus(400);
        }

        $client = new ClientRegister();
        $client->setDocumento($params['documento'])
            ->setNombres($params['nombres'])
            ->setUserSol($params['usuario_sol'])
            ->setPassword($params['password'])
            ->setRepeatPassword($params['repeat_password']);

        $result = $this->api->register($client);

        return $response->withJson($result->getData(), $result->getStatusCode());
    }
}
