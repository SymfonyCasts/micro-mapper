<?php

declare(strict_types=1);

/*
 * This file is part of the SymfonyCasts MicroMapper package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfonycasts\MicroMapper\MicroMapper;
use Symfonycasts\MicroMapper\MicroMapperInterface;
use Symfonycasts\MicroMapper\Tests\fixtures\DinosaurDto;

use function PHPStan\Testing\assertType;

function doMicroMapperInterfaceMap(MicroMapperInterface $microMapper): void
{
    assertType(DinosaurDto::class, $microMapper->map(new \stdClass(), DinosaurDto::class));
}

function doMicroMapperImplementationMap(MicroMapper $microMapper): void
{
    assertType(DinosaurDto::class, $microMapper->map(new \stdClass(), DinosaurDto::class));
}
