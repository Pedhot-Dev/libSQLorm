<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Collection;

use ArrayIterator;
use Closure;
use IteratorAggregate;
use Traversable;

class Collection implements IteratorAggregate
{
    public function __construct(private array $items = [])
    {
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function map(Closure $callback): self
    {
        return new self(array_values(array_map($callback, $this->items)));
    }

    public function filter(Closure $callback): self
    {
        return new self(array_values(array_filter($this->items, $callback)));
    }

    public function reduce(Closure $callback, mixed $initial = null): mixed
    {
        return array_reduce($this->items, $callback, $initial);
    }

    public function first(): mixed
    {
        return $this->items[0] ?? null;
    }

    public function pluck(string $field): self
    {
        return new self(array_values(array_map(static fn(mixed $item): mixed => $item->{$field} ?? null, $this->items)));
    }

    public function chunk(int $size): self
    {
        return new self(array_chunk($this->items, $size));
    }

    public function sort(?Closure $comparator = null): self
    {
        $items = $this->items;
        $comparator === null ? sort($items) : usort($items, $comparator);
        return new self($items);
    }

    public function all(): array
    {
        return $this->items;
    }
}
