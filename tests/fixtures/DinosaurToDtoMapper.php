<?php

namespace Symfonycasts\MicroMapper\Tests\fixtures;

use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;
use Symfonycasts\MicroMapper\MicroMapperInterface;

#[AsMapper(from: Dinosaur::class, to: DinosaurDto::class)]
class DinosaurToDtoMapper implements MapperInterface
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
        assert($from instanceof Dinosaur);
        assert($to instanceof DinosaurDto);

        $to->genus = $from->genus;
        $to->species = $from->species;
        $to->region = $this->microMapper->map($from->region, DinoRegionDto::class, [
            MicroMapperInterface::MAX_DEPTH => 0,
        ]);

        return $to;
    }
}
