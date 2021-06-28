<?php


namespace Sfneal\Helpers\Aws\S3\Tests;


class StorageS3TestCase extends TestCase
{
    /**
     * Retrieve an array of asset files.
     *
     * @return array
     */
    private static function files(): array
    {
        return array_diff(scandir(__DIR__.'/Assets'), ['.', '..']);
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

    /**
     * @var string
     */
    protected $file;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->file = self::randomFile();
        parent::setUp();
    }
}
