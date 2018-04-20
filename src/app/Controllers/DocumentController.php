<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/11/2017
 * Time: 12:42 PM.
 */

namespace Sufel\App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Repository\DocumentRepository;

/**
 * Class DocumentController.
 */
class DocumentController
{
    /**
     * @var DocumentRepository
     */
    private $repository;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * CompanyController constructor.
     *
     * @param ContainerInterface $container
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get(DocumentRepository::class);
        $this->rootDir = $container->get('settings')['upload_dir'];
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

        $doc = $this->repository->get($id);
        if ($doc === null) {
            return $response->withStatus(404);
        }
        if ($type == 'info') {
            return $response->withJson($doc);
        }

        $name = $doc['filename'];
        $pathZip = $this->rootDir.DIRECTORY_SEPARATOR.$doc['emisor'].DIRECTORY_SEPARATOR.$name.'.zip';
        $zip = new \ZipArchive();
        $zip->open($pathZip);

        $result = [];
        if ($type == 'xml') {
            $result['file'] = $zip->getFromName($name.'.xml');
            $result['type'] = 'text/xml';
        } else {
            $result['file'] = $zip->getFromName($name.'.pdf');
            $result['type'] = 'application/pdf';
        }
        $zip->close();

        $response->getBody()->write($result['file']);

        return $response
            ->withHeader('Content-Type', $result['type'])
            ->withHeader('Content-Disposition', 'attachment')
            ->withHeader('Content-Length', strlen($result['file']))
            ->withoutHeader('Pragma')
            ->withoutHeader('Expires')
            ->withoutHeader('Cache-Control');
    }
}
