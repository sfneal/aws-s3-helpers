<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\TestCase;
use Sfneal\Helpers\Aws\S3\Tests\Unit\Traits\RandomFile;

class UrlTest extends TestCase
{
    use RandomFile;

    /**
     * @var string
     */
    private $file;

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

    /** @test */
    public function url_is_valid()
    {
        $url = StorageS3::key($this->file)->url();

        $this->assertNotNull($url);
        $this->assertIsString($url);
    }
}
