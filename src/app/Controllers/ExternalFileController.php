<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 22:40.
 */

namespace Sufel\App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Models\ApiResult;

/**
 * Class ExternalFileController.
 */
class ExternalFileController
{
    /**
     * @var ExternalFileApiInterface
     */
    private $api;

    /**
     * ExternalFileController constructor.
     *
     * @param ExternalFileApiInterface $api
     */
    public function __construct(ExternalFileApiInterface $api)
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
    public function download($request, $response, $args)
    {
        $hash = $args['hash'];
        $type = $args['type'];
        if (!in_array($type, ['xml', 'pdf'])) {
            return $response->withStatus(404);
        }
        $result = $this->api->download($hash, $type);

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
