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

#[AsMapper(from: DinoRegion::class, to: DinoRegionDto::class)]
class DinoRegionToDtoMapper implements MapperInterface
{
    public function __construct(private MicroMapperInterface $microMapper)
    {
    }

    public function load(object $from, string $toClass, array $context): object
    {
        $dto = new $toClass();
        $dto->id = $from->id;

        return $dto;
    }

    public function populate(object $from, object $to, array $context): object
    {
        \assert($from instanceof DinoRegion);
        \assert($to instanceof DinoRegionDto);

        $to->name = $from->name;
        $to->climate = $from->climate;
        $shallowDinosaurDtos = [];
        foreach ($from->dinosaurs as $dino) {
            $shallowDinosaurDtos[] = $this->microMapper->map($dino, DinosaurDto::class, [
                MicroMapperInterface::MAX_DEPTH => 0,
            ]);
        }
        $to->dinosaursMappedShallow = $shallowDinosaurDtos;

        $to->dinosaursMultiMappedShallow = $this->microMapper->mapMultiple($from->dinosaurs, DinosaurDto::class, [
            MicroMapperInterface::MAX_DEPTH => 0,
        ]);

        $deepDinosaurDtos = [];
        foreach ($from->dinosaurs as $dino) {
            $deepDinosaurDtos[] = $this->microMapper->map($dino, DinosaurDto::class, [
                MicroMapperInterface::MAX_DEPTH => 1,
            ]);
        }
        $to->dinosaursMappedDeep = $deepDinosaurDtos;

        $to->dinosaursMultiMappedDeep = $this->microMapper->mapMultiple($from->dinosaurs, DinosaurDto::class, [
            MicroMapperInterface::MAX_DEPTH => 1,
        ]);

        return $to;
    }
}
