<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 26/08/2017
 * Time: 18:25.
 */

namespace Sufel\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RouterInterface;
use Sufel\App\Services\PathResolver;

/**
 * Class HomeController.
 */
class HomeController
{
    /**
     * @var PathResolver
     */
    private $pathResolver;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * HomeController constructor.
     *
     * @param PathResolver    $pathResolver
     * @param RouterInterface $router
     */
    public function __construct(PathResolver $pathResolver, RouterInterface $router)
    {
        $this->pathResolver = $pathResolver;
        $this->router = $router;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function home($request, $response, $args)
    {
        $swaggerUrl = $this->pathResolver->getBasePath().$this->router->pathFor('swagger');
        $body = <<<HTML
<h1>SUFEL API</h1>
<a href="http://petstore.swagger.io/?url=$swaggerUrl">Swagger Docs</a><br>
HTML;

        $response->getBody()->write($body);

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function swagger($request, $response, $args)
    {
        $filename = __DIR__.'/../../data/swagger.json';
        $jsonContent = file_get_contents($filename);
        $response->getBody()
                ->write(str_replace('sufel.net', $this->pathResolver->getFullBasePath(), $jsonContent));

        return $response->withHeader('Content-Type', 'application/json; charset=utf8');
    }
}
