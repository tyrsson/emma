<?php

declare(strict_types=1);

namespace User\Form\Fieldset\Factory;

use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use User\Form\Fieldset\LoginFieldset;

final class LoginFieldsetFactory implements FactoryInterface
{
    /** @param string $requestedName */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): LoginFieldset
    {
        return new LoginFieldset(
            $container->get(AuthenticationService::class),
            'login-data',
            []
        );
    }
}