<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Example\Usage;

use Generator;
use PedhotDev\libSQLorm\Async\Async;
use PedhotDev\libSQLorm\Example\Model\User;
use PedhotDev\libSQLorm\Repository\UserRepository;

final class OrmUsageExample
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    public function run(): Generator
    {
        $user = yield from User::find('xuid');
        if ($user !== null) {
            $user->coins += 1000;
            yield from $user->save();
        }
        $topUsers = yield from User::query()->where('coins', '>', 1000)->orderBy('coins', 'DESC')->limit(10)->getModels();
        $withGuild = yield from User::query()->with(['guild'])->firstModel();
        $richUsers = yield from $this->repository->findRichUsers();
        yield from Async::concurrent(['persistTop' => (function () use ($topUsers): Generator {
            foreach ($topUsers as $user) {
                yield from $user->save();
            }
            return null;
        })(), 'persistRich' => (function () use ($richUsers): Generator {
            foreach ($richUsers as $user) {
                yield from $user->save();
            }
            return null;
        })(),]);
        if ($withGuild !== null) {
            yield from $withGuild->save();
        }
    }
}
