<?php

declare(strict_types=1);

namespace PedhotDev\libSQLorm\Contract;

use Closure;
use cooldogedev\libSQL\query\SQLQuery;
use Generator;

interface ConnectionManagerInterface
{
    /**
     * Submit a query callback-style (fire and forget with handlers).
     */
    public function submit(SQLQuery $query, ?Closure $onSuccess = null, ?Closure $onFail = null): void;

    /**
     * Submit a query and return a Generator (await-generator coroutine).
     * Use: $result = yield from $manager->submitAsync($query);
     *
     * @return Generator<mixed, mixed, mixed, mixed>
     */
    public function submitAsync(SQLQuery $query): Generator;

    /**
     * Whether the connection pool is initialized and alive.
     */
    public function isConnected(): bool;
}
