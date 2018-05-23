<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 27/08/2017
 * Time: 14:11.
 */

namespace Sufel\App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Utils\Validator;
use Sufel\App\ViewModels\DocumentLogin;

/**
 * Class SecureController.
 */
class SecureController
{
    /**
     * @var SecureApiInterface
     */
    private $api;

    /**
     * SecureController constructor.
     *
     * @param SecureApiInterface $api
     */
    public function __construct(SecureApiInterface $api)
    {
        $this->api = $api;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function client($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['emisor', 'tipo', 'documento', 'fecha', 'total'])) {
            return $response->withStatus(400);
        }

        $login = new DocumentLogin();
        $login->setEmisor($params['emisor'])
            ->setTipo($params['tipo'])
            ->setCorrelativo($params['documento'])
            ->setFecha(new \DateTime($params['fecha']))
            ->setTotal(floatval($params['total']));

        $result = $this->api->client($login);

        return $response->withJson($result->getData(), $result->getStatusCode());
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function company($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['ruc', 'password'])) {
            return $response->withStatus(400);
        }
        $result = $this->api->company($params['ruc'], $params['password']);

        return $response->withJson($result->getData(), $result->getStatusCode());
    }
}
