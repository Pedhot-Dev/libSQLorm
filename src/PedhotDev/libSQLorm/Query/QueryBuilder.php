<?php

declare(strict_types=1);

namespace PedhotDev\libSQLorm\Query;

use Generator;
use PedhotDev\libSQLorm\Contract\DriverInterface;
use PedhotDev\libSQLorm\Contract\QueryBuilderInterface;

class QueryBuilder implements QueryBuilderInterface
{
    protected QueryState $state;
    /** @var array<string, mixed> */
    protected array $bindings = [];
    protected int $bindingCounter = 0;
    /** @var string[] */
    protected array $with = [];

    public function __construct(protected readonly DriverInterface $driver)
    {
        $this->state = new QueryState();
    }

    public function table(string $table): static
    {
        $this->state->table = $table;
        return $this;
    }

    public function where(string $column, string $operator, mixed $value): static
    {
        $key = $this->bind($value);
        $this->state->wheres[] = ['type' => 'AND', 'sql' => "$column $operator :$key", 'bindings' => [$key => $value]];
        return $this;
    }

    private function bind(mixed $value): string
    {
        $key = 'b' . (++$this->bindingCounter);
        $this->bindings[$key] = $value;
        return $key;
    }

    public function orWhere(string $column, string $operator, mixed $value): static
    {
        $key = $this->bind($value);
        $this->state->wheres[] = ['type' => 'OR', 'sql' => "$column $operator :$key", 'bindings' => [$key => $value]];
        return $this;
    }

    public function whereIn(string $column, array $values): static
    {
        $holders = [];
        $b = [];
        foreach ($values as $v) {
            $k = $this->bind($v);
            $holders[] = ':' . $k;
            $b[$k] = $v;
        }
        $this->state->wheres[] = ['type' => 'AND', 'sql' => $column . ' IN (' . implode(',', $holders) . ')', 'bindings' => $b];
        return $this;
    }

    public function whereNull(string $column): static
    {
        $this->state->wheres[] = ['type' => 'AND', 'sql' => "$column IS NULL", 'bindings' => []];
        return $this;
    }

    public function whereNotNull(string $column): static
    {
        $this->state->wheres[] = ['type' => 'AND', 'sql' => "$column IS NOT NULL", 'bindings' => []];
        return $this;
    }

    public function join(string $table, string $first, string $operator, string $second): static
    {
        $this->state->joins[] = "JOIN $table ON $first $operator $second";
        return $this;
    }

    public function leftJoin(string $table, string $first, string $operator, string $second): static
    {
        $this->state->joins[] = "LEFT JOIN $table ON $first $operator $second";
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $this->state->orderBy[] = "$column $direction";
        return $this;
    }

    public function groupBy(string ...$columns): static
    {
        $this->state->groupBy = [...$this->state->groupBy, ...$columns];
        return $this;
    }

    public function offset(int $offset): static
    {
        $this->state->offset = $offset;
        return $this;
    }

    public function with(array $relations): static
    {
        $this->with = $relations;
        return $this;
    }

    public function count(): Generator
    {
        $clone = clone $this;
        $clone->select('COUNT(*) as aggregate');
        $row = yield from $clone->first();
        return (int)($row['aggregate'] ?? 0);
    }

    public function select(string|array $columns): static
    {
        $this->state->selects = is_array($columns) ? $columns : [$columns];
        return $this;
    }

    public function first(): Generator
    {
        $this->limit(1);
        $rows = yield from $this->get();
        return $rows[0] ?? null;
    }

    public function limit(int $limit): static
    {
        $this->state->limit = $limit;
        return $this;
    }

    public function get(): Generator
    {
        return yield from $this->driver->executeQuery($this->toSelectSql(), $this->bindings);
    }

    protected function toSelectSql(): string
    {
        $sql = 'SELECT ' . implode(', ', $this->state->selects) . ' FROM ' . $this->state->table;
        if ($this->state->joins !== []) {
            $sql .= ' ' . implode(' ', $this->state->joins);
        }
        $sql .= $this->whereSql();
        if ($this->state->groupBy !== []) {
            $sql .= ' GROUP BY ' . implode(', ', $this->state->groupBy);
        }
        if ($this->state->orderBy !== []) {
            $sql .= ' ORDER BY ' . implode(', ', $this->state->orderBy);
        }
        if ($this->state->limit !== null) {
            $sql .= ' LIMIT ' . $this->state->limit;
        }
        if ($this->state->offset !== null) {
            $sql .= ' OFFSET ' . $this->state->offset;
        }
        return $sql;
    }

    protected function whereSql(): string
    {
        if ($this->state->wheres === []) {
            return '';
        }
        $first = true;
        $sql = ' WHERE';
        foreach ($this->state->wheres as $where) {
            $prefix = $first ? ' ' : ' ' . $where['type'] . ' ';
            $sql .= $prefix . $where['sql'];
            $first = false;
        }
        return $sql;
    }

    public function sum(string $column): Generator
    {
        $clone = clone $this;
        $clone->select("SUM($column) as aggregate");
        $row = yield from $clone->first();
        return (int)($row['aggregate'] ?? 0);
    }

    public function avg(string $column): Generator
    {
        $clone = clone $this;
        $clone->select("AVG($column) as aggregate");
        $row = yield from $clone->first();
        return (float)($row['aggregate'] ?? 0.0);
    }

    public function insert(array $data): Generator
    {
        $cols = array_keys($data);
        $pl = [];
        $b = [];
        foreach ($data as $k => $v) {
            $p = $this->bind($v);
            $pl[] = ':' . $p;
            $b[$p] = $v;
        }
        $sql = 'INSERT INTO ' . $this->state->table . ' (' . implode(',', $cols) . ') VALUES (' . implode(',', $pl) . ')';
        $affected = yield from $this->driver->executeStatement($sql, $b);
        return $affected > 0;
    }

    public function update(array $data): Generator
    {
        $sets = [];
        $b = [];
        foreach ($data as $k => $v) {
            $p = $this->bind($v);
            $sets[] = "$k = :$p";
            $b[$p] = $v;
        }
        $sql = 'UPDATE ' . $this->state->table . ' SET ' . implode(', ', $sets) . $this->whereSql();
        $bindings = [...$b, ...$this->bindings];
        return yield from $this->driver->executeStatement($sql, $bindings);
    }

    public function delete(): Generator
    {
        $sql = 'DELETE FROM ' . $this->state->table . $this->whereSql();
        return yield from $this->driver->executeStatement($sql, $this->bindings);
    }
}
