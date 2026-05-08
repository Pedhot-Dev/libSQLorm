<?php

declare(strict_types=1);

namespace PedhotDev\libSQLorm\Contract;

use PedhotDev\libSQLorm\Model\Model;

/**
 * @template T of Model
 */
interface HydratorInterface
{
    /**
     * Hydrate a single database row into a model instance.
     *
     * @param array<string, mixed> $row
     * @param class-string<T> $modelClass
     * @return T
     */
    public function hydrate(array $row, string $modelClass): Model;

    /**
     * Hydrate multiple rows into model instances.
     *
     * @param array<int, array<string, mixed>> $rows
     * @param class-string<T> $modelClass
     * @return T[]
     */
    public function hydrateMany(array $rows, string $modelClass): array;

    /**
     * Dehydrate a model instance into a plain array for persistence.
     *
     * @param T $model
     * @return array<string, mixed>
     */
    public function dehydrate(Model $model): array;
}
