<?php

declare(strict_types=1);

namespace PedhotDev\libSQLorm\Attribute;

use Attribute;

/**
 * Marks a property as a mapped database column.
 *
 * Usage:
 *   #[Column]
 *   public string $xuid;
 *
 *   #[Column(name: "user_name")]
 *   public string $name;
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Column
{
    public function __construct(
        /** Override the column name. If null, uses the property name. */
        public readonly ?string $name = null,
    )
    {
    }
}
