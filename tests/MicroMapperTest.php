<?php

/*
 * This file is part of the SymfonyCasts MicroMapper package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfonycasts\MicroMapper\Tests;

use PHPUnit\Framework\TestCase;
use Symfonycasts\MicroMapper\MapperConfig;
use Symfonycasts\MicroMapper\MicroMapper;
use Symfonycasts\MicroMapper\MicroMapperInterface;
use Symfonycasts\MicroMapper\Tests\fixtures\DinoRegion;
use Symfonycasts\MicroMapper\Tests\fixtures\DinoRegionDto;
use Symfonycasts\MicroMapper\Tests\fixtures\DinoRegionToDtoMapper;
use Symfonycasts\MicroMapper\Tests\fixtures\Dinosaur;
use Symfonycasts\MicroMapper\Tests\fixtures\DinosaurDto;
use Symfonycasts\MicroMapper\Tests\fixtures\DinosaurToDtoMapper;

class MicroMapperTest extends TestCase
{
    // calls correct mapper
    // respects MAX_DEPTH (and only calls init)
    // throws on circular reference

    public function testMap()
    {
        $this->createMapper();
        $region = new DinoRegion();
        $region->id = 1;
        $region->name = 'North America';
        $region->climate = 'temperate';

        $dinosaur1 = new Dinosaur(3, 'Velociraptor', 'mongoliensis');
        $dinosaur1->region = $region;
        $dinosaur2 = new Dinosaur(4, 'Triceratops', 'horridus');
        $dinosaur2->region = $region;
        $region->dinosaurs = [$dinosaur1, $dinosaur2];

        $dto = $this->createMapper()->map($region, DinoRegionDto::class);
        $this->assertInstanceOf(DinoRegionDto::class, $dto);
        $this->assertSame(1, $dto->id);
        $this->assertSame('North America', $dto->name);
        $this->assertSame('temperate', $dto->climate);
        $this->assertCount(2, $dto->dinosaursMappedShallow);
        $this->assertCount(2, $dto->dinosaursMappedDeep);

        // id is mapped for both deep and shallow
        $this->assertSame(3, $dto->dinosaursMappedShallow[0]->id);
        $this->assertSame(3, $dto->dinosaursMappedDeep[0]->id);
        // further properties are only in the deep
        $this->assertNull($dto->dinosaursMappedShallow[0]->genus);
        $this->assertSame('Velociraptor', $dto->dinosaursMappedDeep[0]->genus);
        // the deep will have a region, but it will be shallow
        $this->assertSame($dto->dinosaursMappedDeep[0]->region->id, 1);
        $this->assertNull($dto->dinosaursMappedDeep[0]->region->name);
    }

    private function createMapper(): MicroMapperInterface
    {
        $microMapper = new MicroMapper([]);
        $microMapper->addMapperConfig(new MapperConfig(
            DinoRegion::class,
            DinoRegionDto::class,
            fn () => new DinoRegionToDtoMapper($microMapper)
        ));
        $microMapper->addMapperConfig(new MapperConfig(
            Dinosaur::class,
            DinosaurDto::class,
            fn () => new DinosaurToDtoMapper($microMapper)
        ));

        return $microMapper;
    }
}
