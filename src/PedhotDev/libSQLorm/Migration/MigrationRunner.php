<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Migration;

use Generator;

final readonly class MigrationRunner
{
    public function __construct(private MigrationRepository $repository, private iterable $migrations)
    {
    }

    public function migrate(): Generator
    {
        yield from $this->repository->ensureTable();
        $ran = yield from $this->repository->allRan();
        $batch = yield from $this->repository->nextBatch();
        foreach ($this->migrations as $migrationClass) {
            if (in_array($migrationClass, $ran, true)) continue;
            $migration = new $migrationClass();
            yield from $migration->up();
            yield from $this->repository->log($migrationClass, $batch);
        }
    }

    public function rollback(): Generator
    {
        $ran = array_reverse(yield from $this->repository->allRan());
        foreach ($ran as $migrationClass) {
            if (!class_exists($migrationClass)) continue;
            $migration = new $migrationClass();
            yield from $migration->down();
            yield from $this->repository->delete($migrationClass);
        }
    }
}
