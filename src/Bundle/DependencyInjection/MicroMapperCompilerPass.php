<?php

/*
 * This file is part of the SymfonyCasts MicroMapper package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfonycasts\MicroMapper\Bundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfonycasts\MicroMapper\MapperConfig;

/**
 * @author Ryan Weaver <ryan@symfonycasts.com>
 */
class MicroMapperCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $mapperConfigDefinitions = [];
        foreach ($container->findTaggedServiceIds('micro_mapper.mapper') as $id => $tags) {
            foreach ($tags as $tag) {
                $mapperConfigDefinitions[] = new Definition(MapperConfig::class, [
                    $tag['from'],
                    $tag['to'],
                    new ServiceClosureArgument(new Reference($id)),
                ]);
            }
        }
        $microMapperDefinition = $container->findDefinition('symfonycasts.micro_mapper');
        $microMapperDefinition->setArgument(0, $mapperConfigDefinitions);
    }
}
