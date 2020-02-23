<?php
declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

final class UpdateTestSchema extends Command
{
    protected static $defaultName = 'app:update-test-schema';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $schemaFile = __DIR__ . '/../../tests/assets/schema.sql';

        $io = new SymfonyStyle($input, $output);
        if (file_exists($schemaFile)) {
            $response = $io->confirm('Schema file already exists. Overwrite?', false);
            if (!$response) {
                $io->error('Aborted');
                return 1;
            }
        }

        $this->createSchemaDump($schemaFile);
        $this->loadSchemaIntoTest($schemaFile);

        $io->success('Done');
        return 0;
    }

    protected function createSchemaDump(string $schemaFile): void
    {
        $process = new Process(['mysqldump', '-uroot', 'symfony', '--no-data', '--ignore-table=symfony.migration_versions']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        file_put_contents($schemaFile, $process->getOutput());
    }

    protected function loadSchemaIntoTest(string $schemaFile): void
    {
        $process = new Process(['mysql', '-uroot', 'symfony_test']);
        $stream = fopen($schemaFile, 'r');
        $process->setInput($stream);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
