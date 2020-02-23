<?php
declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

final class UpdateTestSchema extends Command
{
    protected static $defaultName = 'app:update-test-schema';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $schemaFile = __DIR__ . '/../../tests/assets/schema.sql';

        // Prompt to override

        $this->createSchemaDump($schemaFile);
        $this->loadSchemaIntoTest($schemaFile);

        return 0;
    }

    protected function createSchemaDump(string $schemaFile): void
    {
        $process = new Process(['mysqldump', '-uroot', 'symfony', '--no-data', '--ignore-table=symfony.migration_versions']);

        // Run process
        // Check if successful
        // Save output to schema file
    }

    protected function loadSchemaIntoTest(string $schemaFile): void
    {
        $process = new Process(['mysql', '-uroot', 'symfony_test']);

        // Input schema file into process
        // Run process
        // Check if successful
    }
}
