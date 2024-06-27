<?php

/*
 * This file is part of the SymfonyCasts MicroMapper package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfonycasts\MicroMapper\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfonycasts\MicroMapper\AsMapper;

/**
 * @author Ryan Weaver <ryan@symfonycasts.com>
 */
class MicroMapperExtension extends Extension implements ConfigurationInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../config'));
        $loader->load('services.php');

        $container->registerAttributeForAutoconfiguration(AsMapper::class, static function (ChildDefinition $definition, AsMapper $attribute) {
            $definition->addTag('micro_mapper.mapper', [
                'from' => $attribute->getFrom(),
                'to' => $attribute->getTo(),
            ]);
        });
    }

    public function getConfiguration(array $config, ContainerBuilder $container): ?ConfigurationInterface
    {
        return $this;
    }

    public function getAlias(): string
    {
        return 'symfonycasts_micro_mapper';
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('symfonycasts_micro_mapper');
        $rootNode = $treeBuilder->getRootNode();
        \assert($rootNode instanceof ArrayNodeDefinition);

        // no configuration options yet

        return $treeBuilder;
    }
}
