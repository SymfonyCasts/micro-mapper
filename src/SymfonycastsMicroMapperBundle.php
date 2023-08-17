<?php

namespace Symfonycasts\MicroMapper;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
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
}
