<?php

/*
 * This file is part of the SymfonyCasts MicroMapper package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfonycasts\MicroMapper\MicroMapper;
use Symfonycasts\MicroMapper\MicroMapperInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('symfonycasts.micro_mapper', MicroMapper::class)
            ->args([
                abstract_arg('mapper configs array'),
            ])
        ->alias(MicroMapperInterface::class, 'symfonycasts.micro_mapper')
    ;
};
