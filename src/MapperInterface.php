<?php

/*
 * This file is part of the SymfonyCasts MicroMapper package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfonycasts\MicroMapper;

/**
 * Interface needed for each individual mapper.
 *
 * Also add #[AsMapper(from: Foo:class, to: Bar:class)] to each mapper class.
 *
 * @template TFrom of object
 * @template TTo of object
 *
 * @author Ryan Weaver <ryan@symfonycasts.com>
 */
interface MapperInterface
{
    /**
     * @param TFrom $from
     * @param class-string<TTo> $toClass
     * @param array $context
     * @return TTo
     */
    public function load(object $from, string $toClass, array $context): object;

    /**
     * @param TFrom $from
     * @param TTo $to
     * @param array $context
     * @return TTo
     */
    public function populate(object $from, object $to, array $context): object;
}
