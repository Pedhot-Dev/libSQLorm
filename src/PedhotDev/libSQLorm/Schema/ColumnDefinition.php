<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Schema;
final class ColumnDefinition
{
    public function __construct(public string $name, public string $type, public bool $primary = false, public bool $nullable = false, public mixed $default = null)
    {
    }

    public function primary(): self
    {
        $this->primary = true;
        return $this;
    }

    public function nullable(): self
    {
        $this->nullable = true;
        return $this;
    }

    public function default(mixed $value): self
    {
        $this->default = $value;
        return $this;
    }
}
