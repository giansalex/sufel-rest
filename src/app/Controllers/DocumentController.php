<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/11/2017
 * Time: 12:42 PM.
 */

namespace Sufel\App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Models\ApiResult;

/**
 * Class DocumentController.
 */
class DocumentController
{
    /**
     * @var DocumentApiInterface
     */
    private $api;

    /**
     * DocumentController constructor.
     *
     * @param DocumentApiInterface $api
     */
    public function __construct(DocumentApiInterface $api)
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
    public function getDocument($request, $response, $args)
    {
        $type = $args['type'];
        if (!in_array($type, ['info', 'xml', 'pdf'])) {
            return $response->withStatus(404);
        }

        $jwt = $request->getAttribute('jwt');
        $id = $jwt->doc;

        $result = $this->api->getDocument($id, $type);

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
