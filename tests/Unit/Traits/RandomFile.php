<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit\Traits;

trait RandomFile
{
    /**
     * Retrieve an array of asset files.
     *
     * @return array
     */
    private static function files(): array
    {
        return array_diff(scandir(__DIR__.'/../../Assets'), ['.', '..']);
    }

    /**
     * Retrieve a random asset file.
     *
     * @return string
     */
    public static function randomFile(): string
    {
        $files = self::files();
        $file = array_rand($files);

        return $files[$file];
    }
}
