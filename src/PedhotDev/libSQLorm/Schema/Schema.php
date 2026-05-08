<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Schema;

use Generator;
use RuntimeException;

final class Schema
{
    private static ?SchemaManager $manager = null;

    public static function setManager(SchemaManager $manager): void
    {
        self::$manager = $manager;
    }

    public static function create(string $table, \Closure $callback): Generator
    {
        return yield from self::manager()->create($table, $callback);
    }

    private static function manager(): SchemaManager
    {
        if (self::$manager === null) {
            throw new RuntimeException('Schema manager not initialized');
        }
        return self::$manager;
    }

    public static function drop(string $table): Generator
    {
        return yield from self::manager()->drop($table);
    }

    public static function dropIfExists(string $table): Generator
    {
        return yield from self::manager()->dropIfExists($table);
    }
}
