<?php

declare(strict_types=1);

namespace PedhotDev\libSQLorm\Contract;

use Closure;
use Generator;

interface SchemaManagerInterface
{
    /**
     * Create a table using a Blueprint callback.
     *
     * @return Generator<mixed, mixed, mixed, void>
     */
    public function create(string $table, Closure $callback): Generator;

    /**
     * Drop a table if it exists.
     *
     * @return Generator<mixed, mixed, mixed, void>
     */
    public function drop(string $table): Generator;

    /**
     * Drop a table if it exists (silently).
     *
     * @return Generator<mixed, mixed, mixed, void>
     */
    public function dropIfExists(string $table): Generator;

    /**
     * Modify an existing table using a Blueprint callback.
     *
     * @return Generator<mixed, mixed, mixed, void>
     */
    public function table(string $table, Closure $callback): Generator;

    /**
     * Check whether a table exists.
     *
     * @return Generator<mixed, mixed, mixed, bool>
     */
    public function hasTable(string $table): Generator;
}
