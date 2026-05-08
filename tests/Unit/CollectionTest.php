<?php

declare(strict_types=1);

namespace Tests\Unit;

use PedhotDev\libSQLorm\Collection\Collection;
use Tests\Tests;

final class CollectionTest extends Tests
{
    public function testCollectionPipelines(): void
    {
        $result = (new Collection([1, 2, 3, 4]))
            ->filter(static fn (int $n): bool => $n % 2 === 0)
            ->map(static fn (int $n): int => $n * 10)
            ->all();

        self::assertSame([20, 40], $result);
    }

    public function testReduceAndFirst(): void
    {
        $collection = new Collection([2, 3, 5]);
        self::assertSame(2, $collection->first());
        self::assertSame(10, $collection->reduce(static fn (int $carry, int $n): int => $carry + $n, 0));
    }
}
