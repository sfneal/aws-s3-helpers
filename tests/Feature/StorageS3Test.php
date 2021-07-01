<?php


namespace Sfneal\Helpers\Aws\S3\Tests\Feature;


use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\StorageS3TestCase;
use Sfneal\Helpers\Aws\S3\Utils\S3;

class StorageS3Test extends StorageS3TestCase
{
    /** @test */
    public function methods_exists()
    {
        $methods = [
            'key',
            'disk',
        ];
        foreach ($methods as $method) {
            $this->assertTrue(method_exists(StorageS3::class, $method));
        }
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function key_method_return_s3_instance(string $file)
    {
        $this->assertInstanceOf(
            S3::class,
            StorageS3::key($file)
        );
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function disk_method_return_s3_instance(string $file)
    {
        $this->assertInstanceOf(
            S3::class,
            StorageS3::disk(config('filesystems.cloud', 's3'), $file)
        );
    }
}
