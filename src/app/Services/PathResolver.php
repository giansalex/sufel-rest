<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 21/04/2018
 * Time: 11:39.
 */

namespace Sufel\App\Services;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;

/**
 * Class PathResolver.
 */
class PathResolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * LinkGenerator constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return string
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getFullBasePath()
    {
        $uri = $this->getUri();
        $url = $uri->getHost();
        if ($uri->getPort() && $uri->getPort() !== 80) {
            $url .= ':'.$uri->getPort();
        }
        /* @var $uri \Slim\Http\Uri */
        $url .= $uri->getBasePath();

        return $url;
    }

    /**
     * @return string Port with domain
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getBasePath()
    {
        $uri = $this->getUri();
        $url = $uri->getScheme().'://'.$uri->getHost();
        if ($uri->getPort() && $uri->getPort() !== 80) {
            $url .= ':'.$uri->getPort();
        }

        return $url;
    }

    /**
     * @return \Psr\Http\Message\UriInterface
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getUri()
    {
        /** @var $request Request */
        $request = $this->container->get('request');

        return $request->getUri();
    }
}
