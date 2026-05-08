<?php

declare(strict_types=1);

namespace Tests\Unit;

use PedhotDev\libSQLorm\Query\QueryBuilder;
use Tests\Support\FakeDriver;
use Tests\Tests;

final class QueryBuilderTest extends Tests
{
    public function testSelectQueryGenerationAndBindings(): void
    {
        $driver = new FakeDriver();
        $driver->queryResult = [['id' => 1, 'coins' => 1200]];

        $builder = (new QueryBuilder($driver))
            ->table('users')
            ->select(['id', 'coins'])
            ->where('coins', '>', 1000)
            ->orderBy('coins', 'DESC')
            ->limit(10)
            ->offset(5);

        $rows = $this->await($builder->get());

        self::assertCount(1, $rows);
        self::assertCount(1, $driver->queries);
        self::assertStringContainsString('SELECT id, coins FROM users', $driver->queries[0]['sql']);
        self::assertStringContainsString('WHERE coins > :b1', $driver->queries[0]['sql']);
        self::assertStringContainsString('ORDER BY coins DESC', $driver->queries[0]['sql']);
        self::assertStringContainsString('LIMIT 10', $driver->queries[0]['sql']);
        self::assertStringContainsString('OFFSET 5', $driver->queries[0]['sql']);
        self::assertSame(['b1' => 1000], $driver->queries[0]['bindings']);
    }

    public function testInsertAndUpdateStatements(): void
    {
        $driver = new FakeDriver();
        $builder = (new QueryBuilder($driver))->table('users');

        $inserted = $this->await($builder->insert(['xuid' => 'abc', 'coins' => 0]));
        self::assertTrue($inserted);
        self::assertStringContainsString('INSERT INTO users', $driver->statements[0]['sql']);

        $updated = $this->await($builder->where('xuid', '=', 'abc')->update(['coins' => 500]));
        self::assertSame(1, $updated);
        self::assertStringContainsString('UPDATE users SET coins = :', $driver->statements[1]['sql']);
        self::assertStringContainsString('WHERE xuid = :', $driver->statements[1]['sql']);
    }
}
