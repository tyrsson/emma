<?php

declare(strict_types=1);

namespace User\Db\Factory;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\TableGateway as Gateway;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use User\Db\TableGateway;
use User\Db\UserModel;

final class TableGatewayFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): TableGateway
    {
        return new $requestedName(
            new Gateway(
                'user',
                $container->get(AdapterInterface::class),
                null,
                new HydratingResultSet(
                    new ReflectionHydrator(),
                    $container->get(UserModel::class)
                )
            )
        );
    }
}