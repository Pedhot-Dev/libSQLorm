<?php

declare(strict_types=1);

namespace Tests;

use Generator;
use PHPUnit\Framework\TestCase;
use SOFe\AwaitGenerator\Await;

abstract class libSQLormTestCase extends TestCase
{
    protected function await(Generator $generator): mixed
    {
        $resolved = null;
        Await::g2c($generator, static function (mixed $value) use (&$resolved): void {
            $resolved = $value;
        });

        return $resolved;
    }
}
