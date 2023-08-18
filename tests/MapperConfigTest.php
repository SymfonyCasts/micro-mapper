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
use Symfonycasts\MicroMapper\MapperInterface;
use Symfonycasts\MicroMapper\Tests\fixtures\DinoRegion;
use Symfonycasts\MicroMapper\Tests\fixtures\DinoRegionDto;

class MapperConfigTest extends TestCase
{
    public function testSupports()
    {
        $config = new MapperConfig(
            fromClass: DinoRegion::class,
            toClass: DinoRegionDto::class,
            mapper: fn () => $this->createMock(MapperInterface::class),
        );

        $this->assertTrue($config->supports(new DinoRegion(), DinoRegionDto::class));
        $this->assertFalse($config->supports(new \stdClass(), DinoRegionDto::class));
        $this->assertFalse($config->supports(new DinoRegion(), \stdClass::class));
        $this->assertFalse($config->supports(new DinoRegionDto(), DinoRegion::class));
    }

    public function testGetMapper()
    {
        $mockMapper = $this->createMock(MapperInterface::class);
        $config = new MapperConfig(
            fromClass: DinoRegion::class,
            toClass: DinoRegionDto::class,
            mapper: fn () => $mockMapper,
        );

        $this->assertSame($mockMapper, $config->getMapper());
    }
}
