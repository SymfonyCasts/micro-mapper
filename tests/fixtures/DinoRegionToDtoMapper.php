<?php

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

    public function init(object $from, string $toClass, array $context): object
    {
        $dto = new $toClass();
        $dto->id = $from->id;

        return $dto;
    }

    public function populate(object $from, object $to, array $context): object
    {
        assert($from instanceof DinoRegion);
        assert($to instanceof DinoRegionDto);

        $to->name = $from->name;
        $to->climate = $from->climate;
        $shallowDinosaurDtos = [];
        foreach ($from->dinosaurs as $dino) {
            $shallowDinosaurDtos[] = $this->microMapper->map($dino, DinosaurDto::class, [
                MicroMapperInterface::MAX_DEPTH => 0,
            ]);
        }
        $to->dinosaursMappedShallow = $shallowDinosaurDtos;

        $deepDinosaurDtos = [];
        foreach ($from->dinosaurs as $dino) {
            $deepDinosaurDtos[] = $this->microMapper->map($dino, DinosaurDto::class, [
                MicroMapperInterface::MAX_DEPTH => 1,
            ]);
        }
        $to->dinosaursMappedDeep = $deepDinosaurDtos;

        return $to;
    }
}
