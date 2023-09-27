<?php

declare(strict_types=1);

/*
 * This file is part of the SymfonyCasts MicroMapper package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfonycasts\MicroMapper\MapperInterface;
use Symfonycasts\MicroMapper\Tests\fixtures\DinosaurDto;

use function PHPStan\Testing\assertType;

function doMapperInterfaceLoad(MapperInterface $microMapper): void
{
    assertType(DinosaurDto::class, $microMapper->load(new \stdClass(), DinosaurDto::class));
}

function doMapperInterfacePopulate(MapperInterface $microMapper, DinosaurDto $dto): void
{
    assertType(DinosaurDto::class, $microMapper->populate(new \stdClass(), $dto));
}
