<?php

declare(strict_types=1);

namespace PedhotDev\libSQLorm\Contract;

use Generator;
use PedhotDev\libSQLorm\Collection\Collection;
use PedhotDev\libSQLorm\Model\Model;

/**
 * @template T of Model
 */
interface RepositoryInterface
{
    /**
     * @return Generator<mixed, mixed, mixed, T|null>
     */
    public function findById(mixed $id): Generator;

    /**
     * @return Generator<mixed, mixed, mixed, Collection<T>>
     */
    public function findAll(): Generator;

    /**
     * @return Generator<mixed, mixed, mixed, bool>
     */
    public function save(Model $model): Generator;

    /**
     * @return Generator<mixed, mixed, mixed, bool>
     */
    public function delete(Model $model): Generator;
}
