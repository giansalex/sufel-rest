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
use Sufel\App\Utils\FileResponseTrait;

/**
 * Class DocumentController.
 */
class DocumentController
{
    use FileResponseTrait;

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
}
