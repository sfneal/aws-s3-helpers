<?php

namespace Sfneal\Helpers\Aws\S3\Tests;

abstract class StorageS3TestCase extends TestCase
{
    /**
     * Retrieve an array of asset files.
     *
     * @return array
     */
    public function fileProvider(): array
    {
        // Map files into arrays to be passed to test methods
        return array_map(
            function (string $file) {
                return [$file];
            },
            array_diff(scandir(__DIR__.'/Assets'), ['.', '..'])
        );
    }
}
