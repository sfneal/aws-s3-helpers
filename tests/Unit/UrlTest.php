<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\TestCase;
use Sfneal\Helpers\Aws\S3\Tests\Unit\Traits\RandomFile;

class UrlTest extends TestCase
{
    use RandomFile;

    /** @test */
    public function url_is_valid()
    {
        $file = self::randomFile();
        $url = StorageS3::key($file)->url();

        $this->assertNotNull($url);
        $this->assertIsString($url);

        // todo: add response testing
    }
}
