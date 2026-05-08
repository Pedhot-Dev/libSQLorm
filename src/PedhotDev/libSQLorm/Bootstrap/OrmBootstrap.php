<?php

declare(strict_types=1);

namespace PedhotDev\libSQLorm\Bootstrap;

use PedhotDev\libSQLorm\Model\Model;
use PedhotDev\libSQLorm\Model\ModelContext;
use PedhotDev\libSQLorm\Schema\Schema;
use PedhotDev\libSQLorm\Schema\SchemaManager;
use PedhotDev\NepotismFree\Contract\ContainerInterface;

final readonly class OrmBootstrap
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function boot(): void
    {
        $context = ModelContext::fromContainer($this->container);
        Model::setContext($context, $this->container->get(\PedhotDev\libSQLorm\Metadata\ModelMetadataFactory::class));
        Schema::setManager($this->container->get(SchemaManager::class));
    }
}
