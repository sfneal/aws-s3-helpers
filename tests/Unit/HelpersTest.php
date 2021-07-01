<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\StorageS3TestCase;

class HelpersTest extends StorageS3TestCase
{
    /** @test */
    public function file_url_exists()
    {
        $url = s3FileURL($this->file);

        $this->assertNotNull($url);
        $this->assertIsString($url);
        $this->assertStringContainsString($this->file, $url);
    }

    /** @test */
    public function file_url_temp_exists()
    {
        $url = s3FileUrlTemp($this->file);

        $this->assertNotNull($url);
        $this->assertIsString($url);
        $this->assertStringContainsString($this->file, $url);
        $this->assertNotEquals(StorageS3::key($this->file)->url(), $url);
    }

    /** @test */
    public function file_exists()
    {
        $exists = s3Exists($this->file);

        $this->assertIsBool($exists);
        $this->assertTrue($exists, "The file '{$this->file}' doesn't exist.");
    }

    /** @test */
    public function file_doesnt_exist()
    {
        $doesntExist = s3Exists("fake_file_{$this->file}");

        $this->assertIsBool($doesntExist);
        $this->assertFalse($doesntExist);
    }

    /** @test */
    public function directory_exists()
    {
        $exists = s3Exists('directory');

        $this->assertIsBool($exists);
        $this->assertTrue($exists);
    }

    /** @test */
    public function directory_doesnt_exist()
    {
        $doesntExist = s3Exists('fake_directory');

        $this->assertIsBool($doesntExist);
        $this->assertFalse($doesntExist);
    }
}
