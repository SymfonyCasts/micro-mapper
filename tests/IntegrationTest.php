<?php

namespace Symfonycasts\MicroMapper\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfonycasts\MicroMapper\MicroMapperInterface;
use Symfonycasts\MicroMapper\Tests\fixtures\DinoRegion;
use Symfonycasts\MicroMapper\Tests\fixtures\DinoRegionDto;
use Symfonycasts\MicroMapper\Tests\fixtures\Dinosaur;

class IntegrationTest extends KernelTestCase
{
    public function testBundleIntegration()
    {
        $region = new DinoRegion();
        $region->id = 1;
        $region->name = 'North America';
        $region->climate = 'temperate';
        $dinosaur1 = new Dinosaur(3, 'Velociraptor', 'mongoliensis');
        $dinosaur1->region = $region;
        $region->dinosaurs = [$dinosaur1];

        $microMapper = self::getContainer()->get('public.micro_mapper');
        assert($microMapper instanceof MicroMapperInterface);
        $dto = $microMapper->map($region, DinoRegionDto::class);
        $this->assertInstanceOf(DinoRegionDto::class, $dto);
        $this->assertSame(1, $dto->id);
        $this->assertCount(1, $dto->dinosaursMappedShallow);
        $this->assertSame(3, $dto->dinosaursMappedShallow[0]->id);
    }
}
