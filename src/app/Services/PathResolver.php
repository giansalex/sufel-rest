<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 21/04/2018
 * Time: 11:39.
 */

namespace Sufel\App\Services;

use Slim\Http\Request;

/**
 * Class PathResolver.
 */
class PathResolver
{
    /**
     * @var Request
     */
    private $request;

    /**
     * PathResolver constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getFullBasePath()
    {
        $uri = $this->request->getUri();
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
     */
    public function getBasePath()
    {
        $uri = $this->request->getUri();
        $url = $uri->getScheme().'://'.$uri->getHost();
        if ($uri->getPort() && $uri->getPort() !== 80) {
            $url .= ':'.$uri->getPort();
        }

        return $url;
    }
}
