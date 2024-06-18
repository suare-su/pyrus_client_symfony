<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests;

use SuareSu\PyrusClientSymfony\SuareSuPyrusClientSymfonyBundle;

/**
 * @internal
 */
class SuareSuPyrusClientSymfonyBundleTest extends BaseCase
{
    public function testGetPath(): void
    {
        $bundle = new SuareSuPyrusClientSymfonyBundle();
        $res = $bundle->getPath();

        $this->assertSame(\dirname(__DIR__), $res);
    }
}
