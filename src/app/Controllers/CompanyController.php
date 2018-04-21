<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 28/08/2017
 * Time: 19:51.
 */

namespace Sufel\App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Utils\Validator;

/**
 * Class CompanyController.
 */
class CompanyController
{
    /**
     * @var CompanyApiInterface
     */
    private $api;
    /**
     * @var string
     */
    private $adminToken;

    /**
     * CompanyController constructor.
     *
     * @param CompanyApiInterface $api
     * @param string              $adminToken
     */
    public function __construct(CompanyApiInterface $api, $adminToken)
    {
        $this->api = $api;
        $this->adminToken = $adminToken;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createCompany($request, $response, $args)
    {
        $queryParams = $request->getQueryParams();
        if (!isset($queryParams['token'])) {
            return $response->withStatus(400);
        }

        if ($this->adminToken != $queryParams['token']) {
            return $response->withJson(['message' => 'invalid token'], 401);
        }

        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['ruc', 'nombre', 'password'])) {
            return $response->withJson(['message' => 'parametros incompletos'], 400);
        }

        $result = $this->api->createCompany($params['ruc'], $params['nombre'], $params['password']);

        return $response->withJson($result->getData(), $result->getStatusCode());
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function addDocument($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['xml', 'pdf'])) {
            return $response->withStatus(400);
        }

        $jwt = $request->getAttribute('jwt');

        $result = $this->api->addDocument($jwt->ruc, $params['xml'], $params['pdf']);

        return $response->withJson($result->getData(), $result->getStatusCode());
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function changePassword($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['old', 'new'])) {
            return $response->withStatus(400);
        }

        $jwt = $request->getAttribute('jwt');
        $result = $this->api->changePassword($jwt->ruc, $params['new'], $params['old']);

        return $response->withJson($result->getData(), $result->getStatusCode());
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function anularDocument($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['tipo', 'serie', 'correlativo'])) {
            return $response->withStatus(400);
        }
        $jwt = $request->getAttribute('jwt');
        $result = $this->api->anularDocument(
            $jwt->ruc,
            $params['tipo'],
            $params['serie'],
            $params['correlativo']);

        return $response->withJson($result->getData(), $result->getStatusCode());
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getInvoices($request, $response, $args)
    {
        $params = $request->getQueryParams();
        if (!Validator::existFields($params, ['start', 'end'])) {
            return $response->withStatus(400);
        }
        $init = new \DateTime($params['start']);
        $end = new \DateTime($params['end']);

        $jwt = $request->getAttribute('jwt');
        $result = $this->api->getInvoices($jwt->ruc, $init, $end);

        return $response->withJson($result->getData(), $result->getStatusCode());
    }
}
