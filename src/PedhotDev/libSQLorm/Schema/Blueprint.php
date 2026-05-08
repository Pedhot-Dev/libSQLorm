<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Schema;
final class Blueprint
{
    private array $columns = [];

    public function __construct(private readonly string $table)
    {
    }

    public function integer(string $name): ColumnDefinition
    {
        return $this->push(new ColumnDefinition($name, 'INTEGER'));
    }

    private function push(ColumnDefinition $column): ColumnDefinition
    {
        $this->columns[] = $column;
        return $column;
    }

    public function bigInteger(string $name): ColumnDefinition
    {
        return $this->push(new ColumnDefinition($name, 'INTEGER'));
    }

    public function timestamps(): void
    {
        $this->string('created_at')->nullable();
        $this->string('updated_at')->nullable();
    }

    public function string(string $name): ColumnDefinition
    {
        return $this->push(new ColumnDefinition($name, 'TEXT'));
    }

    public function toCreateSql(): string
    {
        $parts = [];
        foreach ($this->columns as $column) {
            $line = $column->name . ' ' . $column->type;
            if ($column->primary) {
                $line .= ' PRIMARY KEY';
            }
            if (!$column->nullable) {
                $line .= ' NOT NULL';
            }
            if ($column->default !== null) {
                $line .= is_string($column->default) ? " DEFAULT '" . str_replace("'", "''", $column->default) . "'" : ' DEFAULT ' . $column->default;
            }
            $parts[] = $line;
        }
        return 'CREATE TABLE ' . $this->table . ' (' . implode(', ', $parts) . ')';
    }
}
