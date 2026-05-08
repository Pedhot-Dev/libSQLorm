<?php

declare(strict_types=1);

namespace Tests\Support;

use Generator;
use PedhotDev\libSQLorm\Contract\DriverInterface;

final class FakeDriver implements DriverInterface
{
    /** @var list<array{sql:string, bindings:array<string,mixed>}> */
    public array $queries = [];
    /** @var list<array{sql:string, bindings:array<string,mixed>}> */
    public array $statements = [];
    /** @var list<array<string,mixed>> */
    public array $queryResult = [];
    public int $statementResult = 1;

    public function executeQuery(string $sql, array $bindings = []): Generator
    {
        $this->queries[] = ['sql' => $sql, 'bindings' => $bindings];
        yield from (function (): Generator {
            return;
            yield;
        })();
        return $this->queryResult;
    }

    public function executeStatement(string $sql, array $bindings = []): Generator
    {
        $this->statements[] = ['sql' => $sql, 'bindings' => $bindings];
        yield from (function (): Generator {
            return;
            yield;
        })();
        return $this->statementResult;
    }

    public function lastInsertId(): Generator
    {
        yield from (function (): Generator {
            return;
            yield;
        })();
        return 1;
    }

    public function getGrammarClass(): string
    {
        return \PedhotDev\libSQLorm\Database\Driver\SQLiteGrammar::class;
    }
}
