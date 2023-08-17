<?php

namespace Symfonycasts\MicroMapper\Tests\fixtures;

class Dinosaur
{
    public function __construct(
        public ?int $id = null,
        public ?string $genus = null,
        public ?string $species = null,
        public ?DinoRegion $region = null,
    )
    {
    }
}
