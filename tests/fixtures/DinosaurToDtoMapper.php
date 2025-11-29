<?php

/*
 * This file is part of the SymfonyCasts MicroMapper package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfonycasts\MicroMapper\Tests\fixtures;

use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;
use Symfonycasts\MicroMapper\MicroMapperInterface;

/** @implements MapperInterface<Dinosaur, DinosaurDto> */
#[AsMapper(from: Dinosaur::class, to: DinosaurDto::class)]
class DinosaurToDtoMapper implements MapperInterface
{
    public function __construct(private MicroMapperInterface $microMapper)
    {
    }

    public function load(object $from, array $context): object
    {
        $dto = new DinosaurDto();
        $dto->id = $from->id;

        return $dto;
    }

    public function populate(object $from, object $to, array $context): object
    {
        $to->genus = $from->genus;
        $to->species = $from->species;
        $to->region = $this->microMapper->map($from->region, DinoRegionDto::class, [
            MicroMapperInterface::MAX_DEPTH => 0,
        ]);

        return $to;
    }
}
