<?php

// DIC configuration

use Peru\Http\ClientInterface;
use Peru\Sunat\UserValidator;
use Sufel\App\Controllers\ClientController;
use Sufel\App\Controllers\ClientProfileController;
use Sufel\App\Controllers\ClientSecureController;
use Sufel\App\Controllers\CompanyController;
use Sufel\App\Controllers\DocumentController;
use Sufel\App\Controllers\ExternalFileController;
use Sufel\App\Controllers\SecureController;
use Sufel\App\Models\DocumentConverter;
use Sufel\App\Repository\ClienteRepository;
use Sufel\App\Repository\ClientProfileRepository;
use Sufel\App\Repository\CompanyRepository;
use Sufel\App\Repository\DbConnection;
use Sufel\App\Repository\DocumentFilterRepository;
use Sufel\App\Repository\DocumentRepository;
use Sufel\App\Repository\FileRepository;
use Sufel\App\Repository\Query\QueryJoiner;
use Sufel\App\Service\AuthClient;
use Sufel\App\Service\ClientProfile;
use Sufel\App\Service\CryptoService;
use Sufel\App\Service\LinkGenerator;
use Sufel\App\Service\RouterBuilderInterface;
use Sufel\App\Service\TokenServiceInterface;
use Sufel\App\Service\UserValidateInterface;
use Sufel\App\Services\PathResolver;
use Sufel\App\Utils\PdoErrorLogger;

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Katzgrau\KLogger\Logger($settings['path'], $settings['level'], ['extension' => 'log']);

    return $logger;
};

$container[ClientInterface::class] = function () {
    return new \Peru\Http\ContextClient();
};

$container[UserValidator::class] = function ($c) {
    return new UserValidator($c->get(ClientInterface::class));
};

$container[UserValidateInterface::class] = function ($c) {
    return new \Sufel\App\Service\UserValidatorAdapter($c->get(UserValidator::class));
};

$container[CryptoService::class] = function ($c) {
    return new CryptoService($c->get('settings')['jwt']['secret']);
};

$container[RouterBuilderInterface::class] = function ($c) {
    return new \Sufel\App\Services\RouterBuilder($c->get('router'));
};

$container[LinkGenerator::class] = function ($c) {
    return new LinkGenerator($c->get(RouterBuilderInterface::class), $c->get(CryptoService::class));
};

$container[PathResolver::class] = function ($c) {
    return new PathResolver($c->get('request'));
};

$container[PdoErrorLogger::class] = function ($c) {
    return new PdoErrorLogger($c->get('logger'));
};

$container[DbConnection::class] = function ($c) {
    return new DbConnection($c->get('settings')['db'], $c->get('logger'), $c->get(PdoErrorLogger::class));
};

$container[CompanyRepository::class] = function ($c) {
    return new CompanyRepository($c->get(DbConnection::class));
};

$container[DocumentRepository::class] = function ($c) {
    return new DocumentRepository($c->get(DbConnection::class));
};

$container[ClienteRepository::class] = function ($c) {
    return new ClienteRepository($c->get(DbConnection::class));
};

$container[ClientProfileRepository::class] = function ($c) {
    return new ClientProfileRepository($c->get(DbConnection::class));
};

$container[FileRepository::class] = function ($c) {
    return new FileRepository($c->get('settings')['upload_dir']);
};

$container[QueryJoiner::class] = function () {
    return new QueryJoiner();
};

$container[DocumentConverter::class] = function () {
    return new DocumentConverter();
};

$container[TokenServiceInterface::class] = function () {
    return new \Sufel\App\Services\TokenProvider();
};

$container[DocumentFilterRepository::class] = function ($c) {
    $joiner = $c->get(QueryJoiner::class);
    $repo = new DocumentFilterRepository(
        $c->get(DbConnection::class),
        $joiner
    );
    $repo->setBuilders([
        new \Sufel\App\Repository\Query\DocumentBuilder($joiner),
        new \Sufel\App\Repository\Query\DefaultBuilder($joiner),
    ]);

    return $repo;
};

$container[AuthClient::class] = function ($c) {
    return new AuthClient(
        $c->get(ClienteRepository::class),
        $c->get(ClientProfileRepository::class),
        $c->get(UserValidateInterface::class)
    );
};

$container[ClientProfile::class] = function ($c) {
    return new ClientProfile($c->get(ClienteRepository::class), $c->get(ClientProfileRepository::class));
};

$container[ClientController::class] = function ($c) {
    $api = new \Sufel\App\Controllers\ClientApi(
        $c->get(ClienteRepository::class),
        $c->get(DocumentFilterRepository::class),
        $c->get(FileRepository::class),
        $c->get(DocumentRepository::class),
        $c->get(DocumentConverter::class)
    );

    return new ClientController($api);
};

$container[ClientProfileController::class] = function ($c) {
    $api = new \Sufel\App\Controllers\ClientProfileApi($c->get(ClientProfile::class));

    return new ClientProfileController($api);
};

$container[ClientSecureController::class] = function ($c) {
    $api = new \Sufel\App\Controllers\ClientSecureApi(
        $c->get('settings')['jwt']['secret'],
        $c->get(AuthClient::class),
        $c->get(TokenServiceInterface::class)
    );

    return new ClientSecureController($api);
};

$container[CompanyController::class] = function ($c) {
    $api = new \Sufel\App\Controllers\CompanyApi(
        $c->get(CompanyRepository::class),
        $c->get(DocumentRepository::class),
        $c->get(LinkGenerator::class),
        $c->get(FileRepository::class)
    );

    return new CompanyController($api, $c->get('settings')['token_admin']);
};

$container[DocumentController::class] = function ($c) {
    $api = new \Sufel\App\Controllers\DocumentApi(
        $c->get(DocumentRepository::class),
        $c->get(FileRepository::class),
        $c->get(DocumentConverter::class)
    );

    return new DocumentController($api);
};

$container[ExternalFileController::class] = function ($c) {
    $api = new \Sufel\App\Controllers\ExternalFileApi(
        $c->get(CryptoService::class),
        $c->get(DocumentRepository::class),
        $c->get(FileRepository::class),
        $c->get(DocumentConverter::class)
    );

    return new ExternalFileController($api);
};

$container[SecureController::class] = function ($c) {
    $api = new \Sufel\App\Controllers\SecureApi(
        $c->get('settings')['jwt']['secret'],
        $c->get(DocumentRepository::class),
        $c->get(CompanyRepository::class),
        $c->get(TokenServiceInterface::class)
    );

    return new SecureController($api);
};
