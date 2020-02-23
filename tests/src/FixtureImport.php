<?php

namespace App\Tests;

use RuntimeException;

final class FixtureImport
{
    /**
     * @param string[] $params
     * @param string[] $filePaths
     */
    public function importFiles(array $params, array $filePaths) : void
    {
        $fullPaths = [];
        foreach ($filePaths as $filePath) {
            $fullPaths[] = __DIR__ . '/../assets/' . $filePath;
        }

        $this->executeCommand(sprintf(
            'cat %s | MYSQL_PWD=%s mysql --host=%s --port=%d --user=%s %s',
            implode(' ', $fullPaths),
            $params['password'],
            $params['host'],
            $params['port'],
            $params['user'],
            $params['dbname']
        ));
    }

    private function executeCommand(string $command) : void
    {
        $stdOut = exec(
            $command,
            $output,
            $exitCode
        );

        if ($exitCode !== 0) {
            throw new RuntimeException($stdOut . "\n" . implode("\n", $output));
        }
    }
}
