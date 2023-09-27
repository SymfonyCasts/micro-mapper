<?php

declare(strict_types=1);

/*
 * This file is part of the SymfonyCasts MicroMapper package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfonycasts\MicroMapper\Tests\PHPStan;

use PHPStan\Testing\TypeInferenceTestCase;

final class MicroMapperTest extends TypeInferenceTestCase
{
    /** @return array<string, mixed[]> */
    public static function dataFileAsserts(): iterable
    {
        yield from self::gatherAssertTypes(__DIR__.'/data/micro_mapper.php');
        yield from self::gatherAssertTypes(__DIR__.'/data/mapper.php');
    }

    /**
     * @dataProvider dataFileAsserts
     */
    public function testFileAsserts(
        string $assertType,
        string $file,
        mixed ...$args,
    ): void {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }
}
