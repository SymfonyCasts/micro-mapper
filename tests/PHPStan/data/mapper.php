<?php

declare(strict_types=1);

/*
 * This file is part of the SymfonyCasts MicroMapper package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfonycasts\MicroMapper\Tests\fixtures\Dinosaur;
use Symfonycasts\MicroMapper\Tests\fixtures\DinosaurDto;
use Symfonycasts\MicroMapper\Tests\fixtures\DinosaurToDtoMapper;

use function PHPStan\Testing\assertType;

function doMapperInterfaceLoad(DinosaurToDtoMapper $dinosaurMapper, Dinosaur $dinosaur): void
{
    assertType(DinosaurDto::class, $dinosaurMapper->load($dinosaur, []));
}

function doMapperInterfacePopulate(DinosaurToDtoMapper $dinosaurMapper, Dinosaur $dinosaur, DinosaurDto $dto): void
{
    assertType(DinosaurDto::class, $dinosaurMapper->populate($dinosaur, $dto, []));
}
