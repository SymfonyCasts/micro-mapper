<?php

namespace Symfonycasts\MicroMapper;

/**
 * Maps one object to another using the configured mappers.
 */
interface MicroMapperInterface
{
    public const MAX_DEPTH = 'max_depth';

    public function map(object $from, string $toClass, array $context = []): object;
}
