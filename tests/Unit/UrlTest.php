<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\StorageS3TestCase;

class UrlTest extends StorageS3TestCase
{
    /** @test */
    public function url_is_valid()
    {
        $url = StorageS3::key($this->file)->url();

        $this->assertNotNull($url);
        $this->assertIsString($url);
        $this->assertStringContainsString($this->file, $url);
    }

    /** @test */
    public function temp_url_is_valid()
    {
        $url = StorageS3::key($this->file)->urlTemp();

        $this->assertNotNull($url);
        $this->assertIsString($url);
        $this->assertStringContainsString($this->file, $url);
        $this->assertNotEquals(StorageS3::key($this->file)->url(), $url);
    }
}
