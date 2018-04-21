<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 28/08/2017
 * Time: 19:50.
 */

namespace Sufel\App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Models\ApiResult;
use Sufel\App\Utils\Validator;
use Sufel\App\ViewModels\FilterViewModel;

/**
 * Class ClientController.
 */
class ClientController
{
    /**
     * @var ClientApiInterface
     */
    private $api;

    /**
     * ClientController constructor.
     *
     * @param ClientApiInterface $api
     */
    public function __construct(ClientApiInterface $api)
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
    public function getCompanies($request, $response, $args)
    {
        $jwt = $request->getAttribute('jwt');
        $document = $jwt->document;

        $result = $this->api->getCompanies($document);
        $docs = $result->getData();

        return $response->withJson($docs);
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
    public function getList($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['start', 'end'])) {
            return $response->withStatus(400);
        }
        $init = new \DateTime($params['start']);
        $end = new \DateTime($params['end']);

        $jwt = $request->getAttribute('jwt');
        $document = $jwt->document;

        $filter = new FilterViewModel();
        $filter
            ->setClient($document)
            ->setEmisor(isset($params['emisor']) ? $params['emisor'] : '')
            ->setTipoDoc(isset($params['tipoDoc']) ? $params['tipoDoc'] : '')
            ->setSerie(isset($params['serie']) ? $params['serie'] : '')
            ->setCorrelativo(isset($params['correlativo']) ? $params['correlativo'] : '')
            ->setFecInicio($init)
            ->setFecFin($end);

        $result = $this->api->getList($filter);
        $docs = $result->getData();

        return $response->withJson($docs);
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
    public function getDocument($request, $response, $args)
    {
        $id = $args['id'];
        $type = $args['type'];
        $jwt = $request->getAttribute('jwt');
        $result = $this->api->getDocument($jwt->document, $id, $type);

        return $this->setFileResponse($response, $result);
    }

    private function setFileResponse(Response $response, ApiResult $result)
    {
        if ($result->getStatusCode() != 200) {
            return $response->withStatus($result->getStatusCode());
        }

        if (!empty($result->getHeaders())) {
            foreach ($result->getHeaders() as $key => $value) {
                $response = $response->withHeader($key, $value);
            }
        }

        $response->getBody()->write($result['file']);

        return $response;
    }
}
