<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Migration;

use Generator;
use PedhotDev\libSQLorm\Contract\DriverInterface;

final readonly class MigrationRepository
{
    public function __construct(private DriverInterface $driver)
    {
    }

    public function ensureTable(): Generator
    {
        yield from $this->driver->executeStatement('CREATE TABLE IF NOT EXISTS migrations (migration TEXT PRIMARY KEY, batch INTEGER NOT NULL)');
    }

    public function log(string $migration, int $batch): Generator
    {
        yield from $this->driver->executeStatement('INSERT INTO migrations (migration, batch) VALUES (:migration, :batch)', ['migration' => $migration, 'batch' => $batch]);
    }

    public function delete(string $migration): Generator
    {
        yield from $this->driver->executeStatement('DELETE FROM migrations WHERE migration = :migration', ['migration' => $migration]);
    }

    public function allRan(): Generator
    {
        $rows = yield from $this->driver->executeQuery('SELECT migration FROM migrations ORDER BY migration ASC');
        return array_map(static fn(array $row): string => (string)$row['migration'], $rows);
    }

    public function nextBatch(): Generator
    {
        $rows = yield from $this->driver->executeQuery('SELECT MAX(batch) as batch FROM migrations');
        return ((int)($rows[0]['batch'] ?? 0)) + 1;
    }
}
