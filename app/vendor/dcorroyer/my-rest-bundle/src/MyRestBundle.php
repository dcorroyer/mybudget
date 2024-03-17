<?php

declare(strict_types=1);

namespace My\RestBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use My\RestBundle\DependencyInjection\MyRestBundleExtension;

class MyRestBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if ($this->extension === null) {
            return new MyRestBundleExtension();
        }

        return $this->extension;
    }

    /**
     * @throws ParameterNotFoundException
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }
}
