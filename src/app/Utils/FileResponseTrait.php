<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 21/04/2018
 * Time: 12:28.
 */

namespace Sufel\App\Utils;

use Slim\Http\Response;
use Sufel\App\Models\ApiResult;

/**
 * Trait FileResponseTrait.
 */
trait FileResponseTrait
{
    /**
     * @param Response  $response
     * @param ApiResult $result
     *
     * @return Response|static
     */
    protected function setFileResponse(Response $response, ApiResult $result)
    {
        if ($result->getStatusCode() != 200) {
            return $response->withStatus($result->getStatusCode());
        }

        if (!empty($result->getHeaders())) {
            foreach ($result->getHeaders() as $key => $value) {
                $response = $response->withHeader($key, $value);
            }
        }

        $data = $result->getData();

        if (isset($data['file'])) {
            $response->getBody()->write($data['file']);

            return $response;
        }

        return $response->withJson($data);
    }
}
