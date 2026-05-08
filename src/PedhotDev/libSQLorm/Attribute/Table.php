<?php

declare(strict_types=1);

namespace PedhotDev\libSQLorm\Attribute;

use Attribute;

/**
 * Maps a Model class to a specific database table name.
 *
 * Usage:
 *   #[Table("users")]
 *   class User extends Model {}
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class Table
{
    public function __construct(
        public readonly string $name,
    )
    {
    }
}
