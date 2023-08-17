<?php

namespace Symfonycasts\MicroMapper\Tests\fixtures;

class DinoRegionDto
{
    public ?int $id = null;
    public ?string $name = null;
    public ?string $climate = null;
    /**
     * @var array DinosaurDto[]
     */
    public array $dinosaursMappedShallow = [];
    /**
     * @var array DinosaurDto[]
     */
    public array $dinosaursMappedDeep = [];
}
