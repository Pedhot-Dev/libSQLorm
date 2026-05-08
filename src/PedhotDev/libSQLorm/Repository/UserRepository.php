<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Repository;

use Generator;
use PedhotDev\libSQLorm\Example\Model\User;

final class UserRepository extends Repository
{
    public function findRichUsers(): Generator
    {
        return yield from User::query()->where('coins', '>', 1000)->orderBy('coins', 'DESC')->getModels();
    }

    protected function modelClass(): string
    {
        return User::class;
    }
}
