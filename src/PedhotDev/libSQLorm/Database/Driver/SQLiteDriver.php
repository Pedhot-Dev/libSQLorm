<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Database\Driver;

use Generator;
use PedhotDev\libSQLorm\Contract\ConnectionManagerInterface;
use PedhotDev\libSQLorm\Contract\DriverInterface;
use PedhotDev\libSQLorm\Database\Query\RawSQLiteQuery;

final readonly class SQLiteDriver implements DriverInterface
{
    public function __construct(private ConnectionManagerInterface $manager)
    {
    }

    public function executeStatement(string $sql, array $bindings = []): Generator
    {
        $affected = yield from $this->manager->submitAsync(new RawSQLiteQuery($sql, $bindings, true));
        return $affected;
    }

    public function lastInsertId(): Generator
    {
        $rows = yield from $this->executeQuery('SELECT last_insert_rowid() as id');
        return $rows[0]['id'] ?? 0;
    }

    public function executeQuery(string $sql, array $bindings = []): Generator
    {
        $rows = yield from $this->manager->submitAsync(new RawSQLiteQuery($sql, $bindings, false));
        return $rows;
    }

    public function getGrammarClass(): string
    {
        return SQLiteGrammar::class;
    }
}
