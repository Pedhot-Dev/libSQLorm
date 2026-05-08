<?php

declare(strict_types=1);

namespace PedhotDev\libSQLorm\Database\Query;

use cooldogedev\libSQL\query\MySQLQuery;
use mysqli;
use mysqli_result;
use RuntimeException;

final class RawMySQLQuery extends MySQLQuery
{
    /** @param array<string, mixed> $bindings */
    public function __construct(
        private readonly string $sql,
        private readonly array $bindings = [],
        private readonly bool $statement = false,
    ) {}

    public function onRun(mysqli $connection): void
    {
        [$preparedSql, $values] = $this->prepareNamedBindings($this->sql, $this->bindings);
        $stmt = $connection->prepare($preparedSql);

        if ($stmt === false) {
            throw new RuntimeException('Failed to prepare MySQL statement: ' . $connection->error);
        }

        if ($values !== []) {
            $types = '';
            $refs = [];
            foreach ($values as $index => $value) {
                $types .= $this->detectBindType($value);
                $refs[$index] = $values[$index];
            }

            $params = [$types];
            foreach ($refs as $index => &$ref) {
                $params[] = &$ref;
            }

            if (!$stmt->bind_param(...$params)) {
                throw new RuntimeException('Failed to bind MySQL parameters: ' . $stmt->error);
            }
        }

        if (!$stmt->execute()) {
            throw new RuntimeException('Failed to execute MySQL statement: ' . $stmt->error);
        }

        if ($this->statement) {
            $this->setResult($stmt->affected_rows);
            $stmt->close();
            return;
        }

        $result = $stmt->get_result();
        if (!$result instanceof mysqli_result) {
            $this->setResult([]);
            $stmt->close();
            return;
        }

        $rows = [];
        while (($row = $result->fetch_assoc()) !== null) {
            $rows[] = $row;
        }

        $result->free();
        $stmt->close();
        $this->setResult($rows);
    }

    /**
     * @param array<string, mixed> $bindings
     * @return array{string, list<mixed>}
     */
    private function prepareNamedBindings(string $sql, array $bindings): array
    {
        $ordered = [];
        $normalized = [];

        foreach ($bindings as $name => $value) {
            $normalized[str_starts_with($name, ':') ? substr($name, 1) : $name] = $value;
        }

        $prepared = preg_replace_callback('/:([a-zA-Z_][a-zA-Z0-9_]*)/', function (array $matches) use (&$ordered, $normalized): string {
            $key = $matches[1];
            if (!array_key_exists($key, $normalized)) {
                throw new RuntimeException("Missing binding for parameter :$key");
            }
            $ordered[] = $normalized[$key];
            return '?';
        }, $sql);

        if ($prepared === null) {
            throw new RuntimeException('Failed to parse SQL parameter bindings');
        }

        return [$prepared, $ordered];
    }

    private function detectBindType(mixed $value): string
    {
        return match (true) {
            is_int($value) => 'i',
            is_float($value) => 'd',
            is_string($value) => 's',
            default => 'b',
        };
    }
}
