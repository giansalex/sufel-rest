<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 23:37.
 */

namespace Sufel\App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Utils\Validator;

/**
 * Class ClientProfileController.
 */
class ClientProfileController
{
    /**
     * @var ClientProfileApiInterface
     */
    private $api;

    /**
     * ClientProfileController constructor.
     *
     * @param ClientProfileApiInterface $api
     */
    public function __construct(ClientProfileApiInterface $api)
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
    public function changePassword($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['old', 'new'])) {
            return $response->withStatus(400);
        }
        $jwt = $request->getAttribute('jwt');

        $result = $this->api->changePassword($jwt->document,
            $params['old'],
            $params['new']);

        if ($result->getStatusCode() !== 200) {
            return $response->withJson($result->getData(), $result->getStatusCode());
        }

        return $response;
    }
}
