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
 * @author Ryan Weaver <ryan@symfonycasts.com>
 *
 * @template TFrom of object
 * @template TTo of object
 */
interface MapperInterface
{
    /**
     * Load the "to object" and return it.
     *
     * This method should load (e.g. from the database) or instantiate the "to object".
     * Avoid populating any properties except for an identifier.
     *
     * @param TFrom        $from
     * @param array<mixed> $context
     *
     * @return TTo
     */
    public function load(object $from, array $context): object;

    /**
     * Populate the data onto the "to object" from the "from object".
     *
     * Receives the "to object" returned from load().
     *
     * @param TFrom        $from
     * @param TTo          $to
     * @param array<mixed> $context
     *
     * @return TTo
     */
    public function populate(object $from, object $to, array $context): object;
}
