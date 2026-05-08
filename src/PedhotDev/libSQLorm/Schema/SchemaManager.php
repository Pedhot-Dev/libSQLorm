<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Schema;

use Closure;
use Generator;
use PedhotDev\libSQLorm\Contract\DriverInterface;
use PedhotDev\libSQLorm\Contract\SchemaManagerInterface;

final readonly class SchemaManager implements SchemaManagerInterface
{
    public function __construct(private DriverInterface $driver)
    {
    }

    public function create(string $table, Closure $callback): Generator
    {
        $blueprint = new Blueprint($table);
        $callback($blueprint);
        yield from $this->driver->executeStatement($blueprint->toCreateSql());
    }

    public function drop(string $table): Generator
    {
        yield from $this->driver->executeStatement('DROP TABLE ' . $table);
    }

    public function dropIfExists(string $table): Generator
    {
        yield from $this->driver->executeStatement('DROP TABLE IF EXISTS ' . $table);
    }

    public function table(string $table, Closure $callback): Generator
    {
        $blueprint = new Blueprint($table);
        $callback($blueprint);
        yield from $this->driver->executeStatement($blueprint->toCreateSql());
    }

    public function hasTable(string $table): Generator
    {
        $rows = yield from $this->driver->executeQuery('SELECT name FROM sqlite_master WHERE type = :type AND name = :name LIMIT 1', ['type' => 'table', 'name' => $table]);
        return $rows !== [];
    }
}
