<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\StorageS3TestCase;

class ExistsTest extends StorageS3TestCase
{
    /** @test */
    public function file_exists()
    {
        $exists = StorageS3::key($this->file)->exists();

        $this->assertIsBool($exists);
        $this->assertTrue($exists);
    }

    /** @test */
    public function file_doesnt_exist()
    {
        $doesntExist = StorageS3::key("fake_file_{$this->file}")->exists();

        $this->assertIsBool($doesntExist);
        $this->assertFalse($doesntExist);
    }

    /** @test */
    public function file_missing()
    {
        $doesntExist = StorageS3::key("fake_file_{$this->file}")->missing();

        $this->assertIsBool($doesntExist);
        $this->assertTrue($doesntExist);
    }

    /** @test */
    public function directory_exists()
    {
        $exists = StorageS3::key('directory')->exists();

        $this->assertIsBool($exists);
        $this->assertTrue($exists);
    }

    /** @test */
    public function directory_doesnt_exist()
    {
        $doesntExist = StorageS3::key('fake_directory')->exists();

        $this->assertIsBool($doesntExist);
        $this->assertFalse($doesntExist);
    }

    /** @test */
    public function directory_missing()
    {
        $doesntExist = StorageS3::key('fake_directory')->missing();

        $this->assertIsBool($doesntExist);
        $this->assertTrue($doesntExist);
    }
}
