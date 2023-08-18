<?php

/*
 * This file is part of the SymfonyCasts MicroMapper package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
