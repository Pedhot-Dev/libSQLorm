<?php

declare(strict_types=1);

namespace Tests\Unit;

use PedhotDev\libSQLorm\Example\Model\User;
use PedhotDev\libSQLorm\Metadata\ModelMetadataFactory;
use Tests\Tests;

final class MetadataFactoryTest extends Tests
{
    public function testReadsTableAndColumnsFromAttributes(): void
    {
        $factory = new ModelMetadataFactory();
        $metadata = $factory->get(User::class);

        self::assertSame('users', $metadata->table);
        self::assertArrayHasKey('xuid', $metadata->columns);
        self::assertSame('xuid', $metadata->columns['xuid']);
        self::assertSame('guild_id', $metadata->columns['guildId']);
    }
}
