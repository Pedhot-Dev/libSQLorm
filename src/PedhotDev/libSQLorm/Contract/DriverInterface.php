<?php

declare(strict_types=1);

namespace PedhotDev\libSQLorm\Contract;

use Generator;

interface DriverInterface
{
    /**
     * Execute a raw SQL query and return results as an array.
     *
     * @param array<string, mixed> $bindings
     * @return Generator<mixed, mixed, mixed, array<int, array<string, mixed>>>
     */
    public function executeQuery(string $sql, array $bindings = []): Generator;

    /**
     * Execute a raw SQL statement (INSERT/UPDATE/DELETE) and return affected rows.
     *
     * @param array<string, mixed> $bindings
     * @return Generator<mixed, mixed, mixed, int>
     */
    public function executeStatement(string $sql, array $bindings = []): Generator;

    /**
     * Return the last inserted ID from the most recent INSERT.
     *
     * @return Generator<mixed, mixed, mixed, int|string>
     */
    public function lastInsertId(): Generator;

    /**
     * Return the grammar class name for this driver.
     */
    public function getGrammarClass(): string;
}
