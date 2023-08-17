<?php

namespace Symfonycasts\MicroMapper;

/**
 * Attribute added to all "mapper" classes.
 *
 * Those classes should also implement MapperInterface.
 *
 * @author Ryan Weaver <ryan@symfonycasts.com>
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class AsMapper
{
    public function __construct(
        private string $from,
        private string $to,
    )
    {
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }
}
