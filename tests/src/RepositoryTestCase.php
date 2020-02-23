<?php
declare(strict_types=1);

namespace App\Tests;

use App\Kernel;
use App\Tests\FixtureImport;
use Doctrine\DBAL\Driver\Connection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RepositoryTestCase extends TestCase
{
    protected ContainerInterface $container;
    protected Connection $database;
    private FixtureImport $fixtures;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $kernel = new Kernel('test', true);
        $kernel->boot();
        $this->container = $kernel->getContainer();

        $this->database = $this->container->get('doctrine')->getConnection();
        $this->fixtures = new FixtureImport();
    }

    protected function setUp(): void
    {
        $this->fixtures->importFiles(
            $this->database->getParams(),
            ['schema.sql']
        );
    }
}
