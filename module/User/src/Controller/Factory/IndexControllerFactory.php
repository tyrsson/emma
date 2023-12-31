<?php
declare(strict_types=1);

namespace User\Controller\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use User\Controller\IndexController;
use User\Db\TableGateway;
use Webinertia\Utils\Debug;
use Laminas\Form\FormElementManager;


final class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): IndexController
    {
        // here in this context $requestedName is User\Controller\IndexController
        return new $requestedName(
            $container->get(FormElementManager::class),
            $container->get(TableGateway::class)
        );
    }
}




