<?php

/*
 * This file is part of the SymfonyCasts MicroMapper package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfonycasts\MicroMapper;

/**
 * @author Ryan Weaver <ryan@symfonycasts.com>
 */
class MicroMapper implements MicroMapperInterface
{
    private array $objectHashes = [];

    private int $currentDepth = 0;
    private ?int $maxDepth = null;

    /**
     * @param MapperConfig[] $mapperConfigs
     */
    public function __construct(private array $mapperConfigs = [])
    {
    }

    public function addMapperConfig(MapperConfig $mapperConfig): void
    {
        $this->mapperConfigs[] = $mapperConfig;
    }

    public function map(object $from, string $toClass, array $context = []): object
    {
        ++$this->currentDepth;

        if ($this->currentDepth > 50) {
            throw new \Exception('Max depth reached');
        }

        // set the max depth if not already set
        // the max depth is recorded as MAX_DEPTH + the current depth
        $previousMaxDepth = $this->maxDepth;
        if (isset($context[self::MAX_DEPTH])
            && (null === $this->maxDepth || ($context[self::MAX_DEPTH] + $this->currentDepth) < $this->maxDepth)
        ) {
            $this->maxDepth = $context[self::MAX_DEPTH] + $this->currentDepth;
        }

        $shouldFullyPopulate = null === $this->maxDepth || $this->currentDepth < $this->maxDepth;

        // watch for circular references, but only if we're fully populating
        // if we are not fully populating, this is already the final depth/level
        // through the micro mapper.
        if (isset($this->objectHashes[spl_object_hash($from)]) && $shouldFullyPopulate) {
            throw new \Exception(\sprintf('Circular reference detected with micro mapper: %s. Try passing [MicroMapperInterface::MAX_DEPTH => 1] when mapping relationships.', implode(' -> ', array_merge($this->objectHashes, [$from::class]))));
        }

        $this->objectHashes[spl_object_hash($from)] = $from::class;

        foreach ($this->mapperConfigs as $mapperConfig) {
            if (!$mapperConfig->supports($from, $toClass)) {
                continue;
            }

            $toObject = $mapperConfig->getMapper()->load($from, $toClass, $context);

            // avoid fully populated objects if max depth is reached
            if (null === $this->maxDepth || $this->currentDepth < $this->maxDepth) {
                $toObject = $mapperConfig->getMapper()->populate($from, $toObject, $context);
            }

            unset($this->objectHashes[spl_object_hash($from)]);
            --$this->currentDepth;
            $this->maxDepth = $previousMaxDepth;

            return $toObject;
        }

        $this->objectHashes = [];
        $this->currentDepth = 0;
        $this->maxDepth = null;

        throw new \Exception(\sprintf('No mapper found for %s -> %s', $from::class, $toClass));
    }

    public function mapMultiple(iterable $fromIterable, string $toClass, array $context = []): array
    {
        $toObjects = [];

        foreach ($fromIterable as $from) {
            $toObjects[] = $this->map($from, $toClass, $context);
        }

        return $toObjects;
    }
}
