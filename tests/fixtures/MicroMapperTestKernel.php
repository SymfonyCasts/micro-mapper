<?php

namespace Symfonycasts\MicroMapper\Tests\fixtures;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfonycasts\MicroMapper\MicroMapperInterface;
use Symfonycasts\MicroMapper\SymfonycastsMicroMapperBundle;

class MicroMapperTestKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new SymfonycastsMicroMapperBundle(),
        ];
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->loadFromExtension('framework', [
            'secret' => 'foo',
            'test' => true,
            'http_method_override' => true,
            'handle_all_throwables' => true,
            'php_errors' => [
                'log' => true,
            ],
        ]);
        $container->register(DinoRegionToDtoMapper::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);
        $container->register(DinosaurToDtoMapper::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);
        $container->setAlias('public.micro_mapper', new Alias(MicroMapperInterface::class, true));
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir().'/cache'.spl_object_hash($this);
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir().'/logs'.spl_object_hash($this);
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }
}
