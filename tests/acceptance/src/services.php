<?php

namespace Tests\Acceptance;

use DI;
use DI\ContainerBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Tests\Acceptance\ApplicationState\ApplicationState;
use Tests\Acceptance\ApplicationState\DatabaseApplicationState;
use Tests\Acceptance\Context\ResetApplicationState;

$defs = [];
$defs['app.host'] = '127.0.0.1:8000';

$defs[ResetApplicationState::class] = DI\autowire(ResetApplicationState::class);
$defs[ApplicationState::class] = DI\autowire(DatabaseApplicationState::class);
$defs[HttpBrowser::class] = DI\factory(static function (ContainerInterface $container): HttpBrowser {
    $httpBrowser = new HttpBrowser(
        HttpClient::create([
            'verify_peer' => false, // SSL certs
        ])
    );
    $httpBrowser->followRedirects(false);
    $httpBrowser->setServerParameter('HTTP_HOST', DI\get('app.host')->resolve($container));
    $httpBrowser->setServerParameter('HTTPS', 'On');

    return $httpBrowser;
});

$defs[Connection::class] = DI\factory(static function (ContainerInterface $container): Connection {
    return DriverManager::getConnection([
        'dbname' => 'symfony_test',
        'user' => 'root',
        'password' => '',
        'host' => '127.0.0.1',
        'port' => '3306',
        'driver' => 'pdo_mysql',
    ]);

});

return (new ContainerBuilder())
    ->addDefinitions($defs)
    ->build();
