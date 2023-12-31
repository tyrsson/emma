<?php

declare(strict_types=1);

namespace User\Form\Fieldset\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use User\Form\Fieldset\EditUserFieldset;

class EditUserFieldsetFactory implements FactoryInterface
{
    /** @inheritDoc */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): EditUserFieldset
    {
        return new $requestedName(options: $options);
    }
}
