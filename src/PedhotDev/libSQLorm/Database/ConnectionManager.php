<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Database;

use Closure;
use cooldogedev\libSQL\ConnectionPool;
use cooldogedev\libSQL\query\SQLQuery;
use Generator;
use PedhotDev\libSQLorm\Contract\ConnectionManagerInterface;
use SOFe\AwaitGenerator\Await;

final readonly class ConnectionManager implements ConnectionManagerInterface
{
    public function __construct(private ConnectionPool $pool)
    {
    }

    public function submitAsync(SQLQuery $query): Generator
    {
        return yield from Await::promise(function (Closure $resolve, Closure $reject) use ($query): void {
            $this->submit($query, static fn(mixed $result): mixed => $resolve($result), static fn(mixed $e): mixed => $reject($e));
        });
    }

    public function submit(SQLQuery $query, ?Closure $onSuccess = null, ?Closure $onFail = null): void
    {
        $this->pool->submit($query, $onSuccess, $onFail);
    }

    public function isConnected(): bool
    {
        return true;
    }
}
