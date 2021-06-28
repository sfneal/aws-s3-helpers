<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Feature;

use Sfneal\Helpers\Aws\S3\Tests\TestCase;

class ConfigTest extends TestCase
{
    /** @test */
    public function s3_filesystem_is_configured()
    {
        $keys = [
            'driver',
            'key',
            'secret',
            'region',
            'bucket',
            'root',
        ];

        $this->assertIsArray(config('filesystems.disks.s3'));

        foreach ($keys as $key) {
            $this->assertNotNull(config("filesystems.disks.s3.{$key}"));
            $this->assertIsString(config("filesystems.disks.s3.{$key}"));
        }
    }
}
