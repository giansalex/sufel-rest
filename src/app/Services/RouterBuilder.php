<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 20/04/2018
 * Time: 18:56.
 */

namespace Sufel\App\Services;

use Slim\Interfaces\RouterInterface;
use Sufel\App\Service\RouterBuilderInterface;

/**
 * Class RouterBuilder.
 */
class RouterBuilder implements RouterBuilderInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * RouterBuilder constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param string $name
     * @param array  $args
     *
     * @return string
     */
    public function getFullPath($name, array $args)
    {
        return $this->router->pathFor($name, $args);
    }
}
