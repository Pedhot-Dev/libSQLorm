<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Async;

use Generator;
use SOFe\AwaitGenerator\Await;

final class Async
{
    private function __construct()
    {
    }

    public static function concurrent(array $tasks): Generator
    {
        return yield from self::awaitAll($tasks);
    }

    public static function awaitAll(array $tasks): Generator
    {
        return yield from Await::all($tasks);
    }

    public static function race(array $tasks): Generator
    {
        return yield from Await::safeRace($tasks);
    }
}
