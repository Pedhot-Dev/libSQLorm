<?php

declare(strict_types=1);

namespace PedhotDev\libSQLorm\Contract;

use Generator;

interface QueryBuilderInterface
{
    public function table(string $table): static;

    /** @param string|string[] $columns */
    public function select(string|array $columns): static;

    public function where(string $column, string $operator, mixed $value): static;

    public function orWhere(string $column, string $operator, mixed $value): static;

    /** @param array<int, mixed> $values */
    public function whereIn(string $column, array $values): static;

    public function whereNull(string $column): static;

    public function whereNotNull(string $column): static;

    public function join(string $table, string $first, string $operator, string $second): static;

    public function leftJoin(string $table, string $first, string $operator, string $second): static;

    public function orderBy(string $column, string $direction = 'ASC'): static;

    public function groupBy(string ...$columns): static;

    public function limit(int $limit): static;

    public function offset(int $offset): static;

    /** @param string[] $relations */
    public function with(array $relations): static;

    /** @return Generator<mixed, mixed, mixed, array<int, array<string, mixed>>> */
    public function get(): Generator;

    /** @return Generator<mixed, mixed, mixed, array<string, mixed>|null> */
    public function first(): Generator;

    /** @return Generator<mixed, mixed, mixed, int> */
    public function count(): Generator;

    /** @return Generator<mixed, mixed, mixed, int|float> */
    public function sum(string $column): Generator;

    /** @return Generator<mixed, mixed, mixed, float> */
    public function avg(string $column): Generator;

    /**
     * @param array<string, mixed> $data
     * @return Generator<mixed, mixed, mixed, bool>
     */
    public function insert(array $data): Generator;

    /**
     * @param array<string, mixed> $data
     * @return Generator<mixed, mixed, mixed, int>
     */
    public function update(array $data): Generator;

    /** @return Generator<mixed, mixed, mixed, int> */
    public function delete(): Generator;
}
