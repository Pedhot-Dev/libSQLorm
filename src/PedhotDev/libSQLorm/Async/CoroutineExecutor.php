<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Async;

use Generator;
use SOFe\AwaitGenerator\Await;

final class CoroutineExecutor
{
    public function run(Generator $coroutine, ?callable $onComplete = null): void
    {
        Await::g2c($coroutine, $onComplete);
    }
}
