<?php
declare(strict_types=1);

namespace Tests\Acceptance\ApplicationState;

use Assert\Assert;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use RuntimeException;

final class DatabaseApplicationState implements ApplicationState
{
    private Connection $database;

    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    public function addProduct(string $name): void
    {
        $numRows = $this->getNumRows('products');
        $this->database->insert('products', [
            'code' => '00000' . ($numRows + 1),
            'coverFileName' => 'abc.png',
            'id' => ($numRows + 1),
        ]);
        $this->database->insert('product_translation', [
            'name' => $name,
            'code' => '00000' . ($numRows + 1),
            'locale' => 'en',
        ]);
    }

    private function getNumRows(string $tableName): int
    {
        $statement = $this->database->query('SELECT COUNT(*) AS num_rows FROM ' . $tableName);
        return (int) $statement->fetchColumn(0);
    }

    public function reset(): void
    {
        $filePaths = ['schema.sql'];
        $fullPaths = [];
        foreach ($filePaths as $filePath) {
            $fullPath = __DIR__ . '/../../../assets/' . $filePath;
            Assert::that($fullPath)->file();
            $fullPaths[] = $fullPath;
        }

        $params = $this->database->getParams();

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

    private function executeCommand(string $command): void
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

    public function getProduct(string $name): array
    {
        $statement = $this->database->prepare('
            SELECT p.*, t.name
            FROM products p 
            INNER JOIN product_translation t ON t.code = p.code AND t.locale = :locale
            WHERE t.name = :name'
        );
        $statement->execute(['name' => $name, 'locale' => 'en']);
        return $statement->fetch(FetchMode::ASSOCIATIVE);
    }
}
