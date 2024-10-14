<?php

/*
 * This file is part of the SymfonyCasts MicroMapper package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfonycasts\MicroMapper;

/**
 * Wrapper around an individual mapper.
 */
class MapperConfig
{
    public function __construct(
        private string $fromClass,
        private string $toClass,
        private \Closure $mapper,
    ) {
    }

    public function supports(object $fromObject, string $targetClass): bool
    {
        return $fromObject instanceof $this->fromClass && $this->toClass === $targetClass;
    }

    public function getMapper(): MapperInterface
    {
        return ($this->mapper)();
    }
}
