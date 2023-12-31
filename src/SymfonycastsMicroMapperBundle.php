<?php

/*
 * This file is part of the SymfonyCasts MicroMapper package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfonycasts\MicroMapper;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfonycasts\MicroMapper\Bundle\DependencyInjection\MicroMapperCompilerPass;
use Symfonycasts\MicroMapper\Bundle\DependencyInjection\MicroMapperExtension;

/**
 * @author Ryan Weaver <ryan@symfonycasts.com>
 */
class SymfonycastsMicroMapperBundle extends Bundle
{
    protected function createContainerExtension(): ?ExtensionInterface
    {
        return new MicroMapperExtension();
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new MicroMapperCompilerPass());
    }
}
